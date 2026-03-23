<?php
require_once __DIR__ . "/../models/PedidoDAO.php";

class AdminController
{
    private $pedidoDAO;

    public function __construct()
    {
        //Comprobo se a sesión esta activa e senon inicioa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //Se o usuario non é administrador ou non está logueado, refirixoo a tenda
        if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] !== "admin") {
            header("Location: index.php?c=producto&a=index");
            exit;
        }

        $this->pedidoDAO = new PedidoDAO();
    }

    //Método para ver todos os pedidos 
    public function pedidos()
    {
        $pedidos = $this->pedidoDAO->obterTodos();

        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/admin/pedidos.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    public function cambiarEstado(){
        //Recollo o ID e o estado do pedido dende o formulario
        $id=$_REQUEST["id"] ?? null;
        $estado=$_REQUEST["estado"] ?? null;

        //Se existen eses datos, actualizase o estado
        if($id && $estado){
            $this->pedidoDAO->actualizarEstado($id,$estado);
        }

        //Redirixo a lista de pedidos para ver os cambios aplicados
        header("Location: index.php?c=admin&a=pedidos");
        exit;
    }
}
