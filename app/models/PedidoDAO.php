<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/entidades/Producto.php';
require_once __DIR__ . '/entidades/Pedido.php';

class PedidoDAO
{
    private $conexion;

    //Establecese a conexión a base de datos
    public function __construct()
    {
        try {
            $this->conexion = Database::connect();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function crearPedido($id_usuario, $total, $carro)
    {
        try {
            //Inicio unha transacción para que se falla un paso non se garda nada
            $this->conexion->beginTransaction();

            //Preparo a consulta para a táboa de pedidos
            $stmtPedido = $this->conexion->prepare("INSERT INTO pedidos (id_usuario, total) VALUES (?, ?)");
            $stmtPedido->execute(array($id_usuario, $total));

            //Recupero o ID xenerado para este pedido
            $id_pedido = $this->conexion->lastInsertID();

            //Preparo a consulta para os detalle dos pedidos
            $stmtDetalle = $this->conexion->prepare("INSERT INTO detalles_pedido (id_pedido, id_producto, cantidade, prezo_unitario) VALUES (?, ?, ?, ?)");

            // Consulta para descontar o stock no produto: só resta se hai unidades suficientes
            $stmtStock = $this->conexion->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?");

            //Percorro cada produto do carro para gardalo na base de datos
            foreach ($carro as $id_producto => $item) {

                // Gardo o detalle
                $stmtDetalle->execute(array(
                    $id_pedido,
                    $id_producto,
                    $item["cantidade"],
                    $item["precio"]
                ));

                // Desconto o stock
                $stmtStock->execute(array(
                    $item["cantidade"],
                    $id_producto,
                    $item["cantidade"]
                ));

                // Verifico se o UPDATE afectou a algunha fila
                if ($stmtStock->rowCount() == 0) {
                    throw new Exception("Non hai stock abondo para o produto: " . $item["nome"]);
                }
            }

            //Se hasta aqui non houbo errores, confirmo os cambios
            $this->conexion->commit();

            //Devolovo o num do pedido pa mostralo na vista de confirmacion
            return $id_pedido;
        } catch (PDOException $e) {
            //Se hai algun erro, desfago todo o proceso
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollback();
            }
            die("Erro o procesar o pedido " . $e->getMessage());
        }
    }

    //Método para obter un pedido a traves do seu id
    public function obter($id)
    {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM pedidos WHERE id = ?");
            $stmt->execute(array($id));

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Pedido");
            return $stmt->fetch();
        } catch (PDOException $e) {
            die("Erro o ver detalles do pedido " . $e->getMessage());
        }
    }

    public function obterPedidosPorUsuario($id_usuario)
    {
        try {
            // Consulta para coller os pedidos do usuario, ordenados do máis recente ao máis antigo
            $stmt = $this->conexion->prepare("SELECT * FROM pedidos WHERE id_usuario= ? ORDER BY id DESC");
            $stmt->execute([$id_usuario]);

            $stmt->setFetchMode(PDO::FETCH_CLASS, "Pedido");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obterDetalles($id_pedido)
    {
        try {
            // Uso INNER JOIN para conectar as táboas e conseguir o nome real do produto
            $stmt = $this->conexion->prepare("
                SELECT dp.cantidade, dp.prezo_unitario, p.nome, p.imagen
                FROM detalles_pedido dp
                INNER JOIN productos p ON dp.id_producto = p.id
                WHERE dp.id_pedido = ?
            ");
            $stmt->execute([$id_pedido]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obterTodos()
    {
        try {
            //Uso INNER JOIN para conectar o nome do usuario que fixo o pedido
            $stmt = $this->conexion->prepare(
                "SELECT p.*, p.data_pedido as data, u.nome as nome_usuario
                                            FROM pedidos p
                                            INNER JOIN usuarios u ON p.id_usuario=u.id
                                            ORDER BY p.data_pedido DESC"
            );

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function actualizarEstado($id_pedido, $novo_estado)
    {
        try {
            // Inicio unha transacción para asegurar que se faga todo ou nada
            $this->conexion->beginTransaction();

            // Primeiro consulto o estado que ten o pedido actualmente
            $stmtEstado = $this->conexion->prepare("SELECT estado FROM pedidos WHERE id = ?");
            $stmtEstado->execute(array($id_pedido));
            $estadoActual = $stmtEstado->fetchColumn();

            if ($estadoActual === false) {
                $this->conexion->rollback();
                return false;
            }

            $estadoActual = strtolower($estadoActual);
            $novoEstadoNormalizado = strtolower($novo_estado);

            // Se non hai cambio real de estado, non fago máis operacións.
            if ($estadoActual === $novoEstadoNormalizado) {
                $this->conexion->commit();
                return true;
            }

            // Busco os produtos e cantidades que forman ese pedido
            $stmtDetalles = $this->conexion->prepare("SELECT id_producto, cantidade FROM detalles_pedido WHERE id_pedido = ?");
            $stmtDetalles->execute(array($id_pedido));
            $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

            // Se o pedido non estaba cancelado e agora si o vou cancelar, devolvo o stock
            if ($estadoActual !== "cancelado" && $novoEstadoNormalizado === "cancelado") {

                // Preparo a consulta para sumar as unidades de volta ao produto
                $stmtStock = $this->conexion->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");

                // Percorro os detalles e actualizo o stock un por un
                foreach ($detalles as $detalle) {
                    $stmtStock->execute(array($detalle["cantidade"], $detalle["id_producto"]));
                }
            }

            // Se estaba cancelado e o reactivo, hai que volver descontar stock.
            if ($estadoActual === "cancelado" && $novoEstadoNormalizado !== "cancelado") {
                $stmtStock = $this->conexion->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?");

                foreach ($detalles as $detalle) {
                    $cantidade = (int)$detalle["cantidade"];
                    $idProducto = (int)$detalle["id_producto"];

                    $stmtStock->execute(array($cantidade, $idProducto, $cantidade));

                    if ($stmtStock->rowCount() === 0) {
                        throw new Exception("Non hai stock abondo para reactivar o pedido");
                    }
                }
            }

            // Actualizo o estado do pedido á súa nova fase (enviado, cancelado, etc.)
            $stmtUpdate = $this->conexion->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
            $resultado = $stmtUpdate->execute(array($novo_estado, $id_pedido));

            // Se chego aquí sen fallos, confirmo os cambios na base de datos
            $this->conexion->commit();
            return $resultado;
        } catch (Exception $e) {
            // Se algo falla, desfacer calquera cambio feito durante a transacción
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollback();
            }
            return false;
        }
    }

    // Busca pedidos por ID ou polo nome do cliente desde o panel de administración
    public function buscarPedidos($mensaxe)
    {
        try {
            //Busco polo Id do pedido ou polo nome de usuario que o fixo
            $stmt = $this->conexion->prepare(" SELECT p.*, p.data_pedido AS data, u.nome AS nome_usuario 
                FROM pedidos p
                INNER JOIN usuarios u ON p.id_usuario = u.id
                WHERE p.id LIKE ? OR u.nome LIKE ?
                ORDER BY p.data_pedido DESC
            ");

            // Engado os comodíns % para buscar coincidencias parciais
            $busqueda = "%" . $mensaxe . "%";

            $stmt->execute(array($busqueda, $busqueda));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function contarPedidos()
    {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) AS total FROM pedidos");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado["total"];
    }

    public function contarPedidosPendientes()
    {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) AS total FROM pedidos WHERE estado = 'pendente'");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado["total"];
    }

    public function calcularIngresos()
    {
        $stmt = $this->conexion->prepare("SELECT SUM(total) as ingresos FROM pedidos WHERE estado != 'cancelado'");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado["ingresos"] ?? 0;
    }
}
