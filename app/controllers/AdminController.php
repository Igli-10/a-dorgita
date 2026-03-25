<?php
require_once __DIR__ . "/../models/PedidoDAO.php";
require_once __DIR__ . "/../models/ProductoDAO.php";
require_once __DIR__ . "/../models/CategoriaDAO.php";
require_once __DIR__ . "/../models/UsuarioDAO.php";

class AdminController
{
    private $pedidoDAO;
    private $productoDAO;
    private $categoriaDAO;
    private $usuarioDAO;

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
        $this->categoriaDAO = new CategoriaDAO();
        $this->usuarioDAO = new UsuarioDAO();
    }

    // Método para listar produtos no panel de administración
    public function productos()
    {
        $mensaxe = $_GET["busca"] ?? null;
        $pagina = isset($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;
        $limite = 10;
        $offset = ($pagina - 1) * $limite;

        if ($mensaxe && !empty(trim($mensaxe))) {
            $totalRegistros = $this->productoDAO->contarBusqueda($mensaxe);
            $productos = $this->productoDAO->buscarPaginado($mensaxe, $limite, $offset);
        } else {
            $totalRegistros = $this->productoDAO->contarProductos();
            $productos = $this->productoDAO->listarPaginado($limite, $offset);
        }

        $totalPaginas = ceil($totalRegistros / $limite);

        $categorias = $this->productoDAO->obterCategorias();

        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/admin/produtos.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    //Método para ver todos os pedidos 
    public function pedidos()
    {
        //Comprobo se o admin recibiu algo na barra de búsqueda
        $mensaxe = $_GET["busca"] ?? null;

        //Se recibe algo, uso o metodo de filtrar e senon cargo todo
        if ($mensaxe) {
            $pedidos_base = $this->pedidoDAO->buscarPedidos($mensaxe);
        } else {
            $pedidos_base = $this->pedidoDAO->obterTodos();
        }

        $pedidos_completos = [];

        // Engado os detalles de cada pedido para que a vista poida amosalos no modal
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

    public function categorias()
    {
        // Obtén todas as categorías para a súa xestión dende o panel
        $categorias = $this->categoriaDAO->listar();

        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/admin/categorias.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    public function cambiarEstado()
    {
        //Recollo o ID e o estado do pedido dende o formulario
        $id = $_REQUEST["id"] ?? null;
        $estado = $_REQUEST["estado"] ?? null;

        //Se existen eses datos, actualizase o estado
        if ($id && $estado) {
            if ($this->pedidoDAO->actualizarEstado($id, $estado)) {
                $_SESSION['mensaje'] = "O estado do pedido actualizouse correctamente.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Houbo un erro ao actualizar o estado do pedido.";
                $_SESSION['tipo_mensaje'] = "danger";
            }
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
            if ($this->productoDAO->gardar($nome, $descripcion, $precio, $stock, $imagen, $id_categoria)) {
                $_SESSION['mensaje'] = "O produto gardouse con éxito.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Erro ao gardar o novo produto.";
                $_SESSION['tipo_mensaje'] = "danger";
            }

            // Refresco a páxina de produtos para que o novo artigo apareza na táboa
            header("Location: index.php?c=admin&a=productos");
            exit();
        }
    }

    public function gardarCategoria()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recollo os datos do formulario de alta de categoría
            $nome = $_POST["nome"];
            $descripcion = $_POST["descripcion"];

            // Creo a nova categoría e volvo ao panel
            if ($this->categoriaDAO->gardar($nome, $descripcion)) {
                $_SESSION['mensaje'] = "Nova categoría rexistrada con éxito.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Non se puido gardar a nova categoría.";
                $_SESSION['tipo_mensaje'] = "danger";
            }

            header("Location: index.php?c=admin&a=categorias");
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
            if ($this->productoDAO->actualizar($id, $nome, $descripcion, $precio, $stock, $imagen, $id_categoria)) {
                $_SESSION['mensaje'] = "A información do produto actualizouse correctamente.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Erro ao intentar modificar o produto.";
                $_SESSION['tipo_mensaje'] = "danger";
            }

            // Redirixo á vista de produtos para confirmar visualmente os cambios
            header("Location: index.php?c=admin&a=productos");
            exit();
        }
    }

    public function actualizarCategoria()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recollo os datos editados da categoría seleccionada
            $id = $_POST["id"];
            $nome = $_POST["nome"];
            $descripcion = $_POST["descripcion"];

            // Actualizo a categoría e regreso á pantalla principal do admin
            if ($this->categoriaDAO->actualizar($id, $nome, $descripcion)) {
                $_SESSION['mensaje'] = "Os datos da categoría modificáronse correctamente.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Produciuse un erro ao actualizar a categoría.";
                $_SESSION['tipo_mensaje'] = "danger";
            }

            header("Location: index.php?c=admin&a=categorias");
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
            if ($this->productoDAO->borrar($id)) {
                $_SESSION['mensaje'] = "O produto eliminouse do catálogo definitivamente.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Houbo un problema ao intentar borrar o produto.";
                $_SESSION['tipo_mensaje'] = "danger";
            }

            // Refresco a lista de produtos para que desapareza da táboa
            header("Location: index.php?c=admin&a=productos");
            exit();
        }
    }

    public function borrarCategoria()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Collo o identificador da categoría a eliminar
            $id = $_POST["id"];

            // Borro a categoría e redirixo ao panel
            if ($this->categoriaDAO->borrar($id)) {
                $_SESSION['mensaje'] = "A categoría foi eliminada correctamente.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Houbo un erro ao eliminar a categoría.";
                $_SESSION['tipo_mensaje'] = "danger";
            }

            header("Location: index.php?c=admin&a=categorias");
            exit();
        }
    }

    // Método para cargar a interface principal da xestión de usuarios no panel
    public function usuarios()
    {
        // Comprobo se o admin escribiu algo na barra de búsqueda
        $mensaxe = $_GET["busca"] ?? null;

        // Se hai busca, filtro os usuarios; senon, amoso a lista completa
        if ($mensaxe && !empty(trim($mensaxe))) {
            $usuarios = $this->usuarioDAO->buscar($mensaxe);
        } else {
            $usuarios = $this->usuarioDAO->listar();
        }

        // Cargo as vistas
        require_once __DIR__ . "/../../includes/header.php";
        require_once __DIR__ . "/../../views/admin/usuarios.php";
        require_once __DIR__ . "/../../includes/footer.php";
    }

    // Método para alternar os privilexios dunha conta dende o panel de control
    public function cambiarRolAdmin()
    {
        // Comprobo que a petición veña do formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST["id"];
            $rol = $_POST["rol"];

            // Actualizo a base de datos
            if ($this->usuarioDAO->actualizarRol($id, $rol)) {
                $_SESSION['mensaje'] = "Os permisos do usuario foron actualizados.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Erro ao intentar cambiar o rol do usuario.";
                $_SESSION['tipo_mensaje'] = "danger";
            }

            header("Location: index.php?c=admin&a=usuarios");
            exit();
        }
    }

    // Método para borrar usuarios dende o panel
    public function borrarUsuarioAdmin()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST["id"];

            //Evito que un admin se borre a si mesmo sen querer
            if ($id == $_SESSION["usuario"]["id"]) {
                $_SESSION['mensaje'] = "Acción denegada: non podes eliminar a túa propia conta de administrador.";
                $_SESSION['tipo_mensaje'] = "danger";
                header("Location: index.php?c=admin&a=usuarios");
                exit();
            }

            // Solicito o borrado 
            if ($this->usuarioDAO->borrar($id)) {
                $_SESSION['mensaje'] = "O usuario eliminouse correctamente da base de datos.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Non se puido borrar o usuario.";
                $_SESSION['tipo_mensaje'] = "danger";
            }

            header("Location: index.php?c=admin&a=usuarios");
            exit();
        }
    }

    public function panelControl()
    {
        $totalUsuarios = $this->usuarioDAO->contarUsuarios();
        $totalProductos = $this->productoDAO->contarProductos();
        $productosAgotados = $this->productoDAO->contarProductosAgotados();
        $totalPedidos = $this->pedidoDAO->contarPedidos();
        $pedidosPendientes = $this->pedidoDAO->contarPedidosPendientes();
        $ingresosTotales = $this->pedidoDAO->calcularIngresos();

        require_once __DIR__ . "/../../includes/header.php";
        require_once __DIR__ . "/../../views/admin/panel_control.php";
        require_once __DIR__ . "/../../includes/footer.php";
    }
}