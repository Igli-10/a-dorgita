<?php
require_once __DIR__ . "/../models/PedidoDAO.php";
require_once __DIR__ . "/../models/ProductoDAO.php";

class AdminController
{
    private $pedidoDAO;
    private $productoDAO;

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
        $this->productoDAO = new ProductoDAO();
    }

    // Método para listar produtos no panel de administración
    public function productos()
    {
        $productos = $this->productoDAO->listar();
        $categorias = $this->productoDAO->obterCategorias();

        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/admin/produtos.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    // Alias para manter compatibilidade co nome en galego da acción
    public function produtos()
    {
        $this->productos();
    }

    //Método para ver todos os pedidos 
    public function pedidos()
    {
        $pedidos_base = $this->pedidoDAO->obterTodos();
        $pedidos_completos = [];

        foreach ($pedidos_base as $p) {
            $detalles = $this->pedidoDAO->obterDetalles($p["id"]);
            $pedidos_completos[] = [
                "pedido" => $p,
                "detalles" => $detalles
            ];
        }

        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/admin/pedidos.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    public function cambiarEstado()
    {
        //Recollo o ID e o estado do pedido dende o formulario
        $id = $_REQUEST["id"] ?? null;
        $estado = $_REQUEST["estado"] ?? null;

        //Se existen eses datos, actualizase o estado
        if ($id && $estado) {
            $this->pedidoDAO->actualizarEstado($id, $estado);
        }

        //Redirixo a lista de pedidos para ver os cambios aplicados
        header("Location: index.php?c=admin&a=pedidos");
        exit;
    }

    // Método para rexistrar un produto completamente novo na tenda
    public function gardarProducto()
    {
        // Verifico que a petición veña de enviar o formulario mediante o método POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Capturo todos os campos cubertos polo administrador na ventá modal
            $nome = $_POST["nome"];
            $id_categoria = $_POST["id_categoria"];
            $precio = $_POST["precio"];
            $stock = $_POST["stock"];
            $imagen = $_POST["imagen"];
            $descripcion = $_POST["descripcion"];

            // Gardo o novo produto na base de datos a través do DAO correspondente
            $this->productoDAO->gardar($nome, $descripcion, $precio, $stock, $imagen, $id_categoria);

            // Refresco a páxina de produtos para que o novo artigo apareza na táboa
            header("Location: index.php?c=admin&a=productos");
            exit();
        }
    }

    // Método para editar e gardar a información dun produto que xa existe
    public function actualizarProducto()
    {
        // Verifico que se enviase o formulario de edición
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Capturo o ID do produto (que viaxa oculto no formulario) e os demais datos modificados
            $id = $_POST["id"];
            $nome = $_POST["nome"];
            $id_categoria = $_POST["id_categoria"];
            $precio = $_POST["precio"];
            $stock = $_POST["stock"];
            $imagen = $_POST["imagen"];
            $descripcion = $_POST["descripcion"];

            // Sobrescribo a información antiga na base de datos cos novos datos
            $this->productoDAO->actualizar($id, $nome, $descripcion, $precio, $stock, $imagen, $id_categoria);

            // Redirixo á vista de produtos para confirmar visualmente os cambios
            header("Location: index.php?c=admin&a=productos");
            exit();
        }
    }

    // Método para eliminar un produto definitivamente do catálogo da tenda
    public function borrarProducto()
    {
        // Comprobo que a solicitude veña do botón de borrado (formulario por POST)
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Capturo o ID do produto que se desexa eliminar
            $id = $_POST["id"];

            // Executo a orde de borrado na base de datos
            $this->productoDAO->borrar($id);

            // Refresco a lista de produtos para que desapareza da táboa
            header("Location: index.php?c=admin&a=productos");
            exit();
        }
    }
}
