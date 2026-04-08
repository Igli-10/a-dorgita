<?php
require_once __DIR__ . '/../models/ProductoDAO.php';
require_once __DIR__ . '/../models/entidades/Producto.php';

class ProductoController
{
    private $model;

    //Inicializa o modelo DAO para interactuar ca base de datos
    public function __construct()
    {
        //Comprobo se a sesión está activa e senon iníciase
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->model = new ProductoDAO();
    }

    //Acción por defecto que e listar os productos
    public function index()
    {
        //Capturo o mensaxe da búsqueda da lupa
        $mensaxe = $_GET["q"] ?? null;
        //Datos base da paxinación na portada
        $pagina = isset($_GET["pagina"]) ? max(1, (int)$_GET["pagina"]) : 1;
        $limite = 9;
        $offset = ($pagina - 1) * $limite;

        //Comprobo se existen os parámetros na URL e senon asígnolles valor null
        $id_cat = $_REQUEST['cat'] ?? null;
        $max_prezo = $_REQUEST['max_prezo'] ?? null;

        //Se o usuario usa a lupa, priorizo a búsqueda
        if ($mensaxe && !empty(trim($mensaxe))) {
            //Conta e trae só os produtos da páxina actual para a búsqueda
            $totalRegistros = $this->model->contarBusqueda($mensaxe);
            $productos = $this->model->buscarPaginadoCatalogo($mensaxe, $limite, $offset);
        } else {
            //Se non hai búsqueda, aplico filtros con paxinación
            $totalRegistros = $this->model->contarFiltradosCatalogo($id_cat, $max_prezo);
            $productos = $this->model->filtrarPaginadoCatalogo($id_cat, $max_prezo, $limite, $offset);
        }

        //Número total de botóns de páxina para a vista
        $totalPaginas = max(1, (int)ceil($totalRegistros / $limite));

        //Obteño os IDs de favoritos do usuario autenticado para marcar os corazóns na vista
        $idsFavoritos = [];
        if (isset($_SESSION['usuario'])) {
            $idsFavoritos = $this->model->obterIdsFavoritos((int)$_SESSION['usuario']['id']);
        }

        //Se se pide unha páxina fora de rango, recárgase coa última válida
        if ($pagina > $totalPaginas) {
            $pagina = $totalPaginas;
            $offset = ($pagina - 1) * $limite;

            if ($mensaxe && !empty(trim($mensaxe))) {
                $productos = $this->model->buscarPaginadoCatalogo($mensaxe, $limite, $offset);
            } else {
                $productos = $this->model->filtrarPaginadoCatalogo($id_cat, $max_prezo, $limite, $offset);
            }
        }


        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/inicio.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    // Alterna o estado de favorito dun produto para o usuario autenticado
    public function toggleFavorito()
    {
        // Só os usuarios autenticados poden usar favoritos
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?c=usuario&a=login");
            exit;
        }

        $id_prod = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $id_user = (int)$_SESSION['usuario']['id'];
        $accion  = $_GET['accion'] ?? 'engadir';

        // Valido que o ID do produto sexa un enteiro positivo
        if ($id_prod && $id_prod > 0) {
            if ($accion === 'engadir') {
                $this->model->engadirFavorito($id_user, $id_prod);
                $_SESSION['mensaje']      = "Produto engadido a favoritos.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $this->model->quitarFavorito($id_user, $id_prod);
                $_SESSION['mensaje']      = "Produto eliminado de favoritos.";
                $_SESSION['tipo_mensaje'] = "danger";
            }
        }

        // Volvo á páxina anterior
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?c=producto&a=index';
        header("Location: " . $referer);
        exit;
    }

    //Acción pa mostrar os detalles dun producto
    public function obter()
    {
        if (isset($_REQUEST["id"])) { // Verifico que o ID veña na URL

            $id_prod = $_REQUEST["id"];
            $prod = $this->model->obter($id_prod);

            // Cargo as reseñas e se o usuario pode comentar
            $resenas = $this->model->obterResenas($id_prod);
            $podeComentar = false;
            if (isset($_SESSION['usuario'])) {
                $id_user = (int)$_SESSION['usuario']['id'];
                // Aquí comprobo eu se este usuario xa mercou este produto para activar o formulario de reseña
                $podeComentar = $this->model->usuarioMercouProducto($id_user, $id_prod);
            }

            require_once __DIR__ . '/../../includes/header.php';
            require_once __DIR__ . '/../../views/produto.php';
            require_once __DIR__ . '/../../includes/footer.php';
        }
    }

    // Acción que devolve JSON para o JavaScript
    public function suxerir()
    {
        // Capturo o termo da URL ou baleiro se non existe
        $mensaxe = $_GET['q'] ?? '';

        //O JSON vai ser a resposta
        header('Content-Type: application/json');

        //Se o término non está baleiro, chamo ao modelo e se está baleiro, envío un array vacío
        echo json_encode(!empty(trim($mensaxe)) ? $this->model->suxerir($mensaxe) : []);
        exit;
    }

    public function engadirResena()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['usuario'])) {
            $id_prod = $_POST['id_producto'];
            $id_user = $_SESSION['usuario']['id'];
            $puntos = $_POST['puntuacion'];
            $comentario = htmlspecialchars($_POST['comentario']);

            if ($this->model->usuarioMercouProducto($id_user, $id_prod)) {
                $this->model->gardarResena($id_prod, $id_user, $puntos, $comentario);
                $_SESSION['mensaje'] = "Grazas pola túa valoración!";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                // Se non o mercou, aviso eu claramente para que saiba por que non se publica
                $_SESSION['mensaje'] = "Só podes valorar produtos que xa mercaches.";
                $_SESSION['tipo_mensaje'] = "danger";
            }

            header("Location: index.php?c=producto&a=obter&id=" . $id_prod);
            exit;
        }
    }
}
