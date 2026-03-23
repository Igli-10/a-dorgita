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

            // Consulta para descontar o stock: só resta se o stock actual é maior ou igual á cantidade pedida
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

                // Verificamos se o UPDATE afectou a algunha fila
                if ($stmtStock->rowCount() == 0) {
                    throw new Exception("Non hai stock abondo para o produto: " . $item["nome"]);
                }
            }

            //Se hasta aqui non houbo errores, confirmo os cambios
            $this->conexion->commit();

            //Devolovo o num do pedido pa mostralo na vista de confirmacion
            return $id_pedido;
        } catch (PDOException $e) {
            //Se hai algun erro, desfacemos todo o proceso
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
                SELECT dp.cantidade, dp.prezo_unitario, p.nome 
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

    public function obterTodos(){
        try{
            //Uso INNER JOIN para conectar o nome do usuario que fixo o pedido
            $stmt=$this->conexion->prepare("SELECT p.*, p.data_pedido as data, u.nome as nome_usuario
                                            FROM pedidos p
                                            INNER JOIN usuarios u ON p.id_usuario=u.id
                                            ORDER BY p.data_pedido DESC"  
            );

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);   
        }catch(PDOException $e){
            return [];
        }
    }

    public function actualizarEstado($id_pedido,$novo_estado){
        try{
            $stmt=$this->conexion->prepare("UPDATE pedidos SET estado = ? WHERE id=?");
            return $stmt->execute(array($novo_estado, $id_pedido));
        }catch (PDOException $e) {
            return false;
        }
    }
}
