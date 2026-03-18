<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/entidades/Producto.php';

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

            //Percorro cada produto do carro para gardalo na base de datos
            foreach ($carro as $id_producto => $item) {
                $stmtDetalle->execute(array(
                    $id_pedido,
                    $id_producto,
                    $item["cantidade"],
                    $item["precio"]
                ));
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

   
}
