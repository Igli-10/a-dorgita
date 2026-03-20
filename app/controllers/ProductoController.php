<?php
require_once __DIR__ . '/../models/ProductoDAO.php';
require_once __DIR__ . '/../models/entidades/Producto.php';

class ProductoController
{
    private $model;

    //Inicializa o modelo DAO para interactuar ca base de datos
    public function __construct()
    {
        //Comprobamos se a sesión está activa e senon iniciámola
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->model = new ProductoDAO();
    }

    //Acción por defecto que e listar os productos
    public function index()
    {
        //Capturamos o mensaxe da búsqueda da lupa
        $mensaxe = $_GET["q"] ?? null;

        //Comprobamos se existen os parámetros na URL e senon asignamoslle valor null
        $id_cat = $_REQUEST['cat'] ?? null;
        $max_prezo = $_REQUEST['max_prezo'] ?? null;

        //Se o usuario usa a lupa, priorizamos a búsqueda
        if ($mensaxe && !empty(trim($mensaxe))) {
            $productos = $this->model->buscar($mensaxe);
        } else {
            //Se non hai búsqueda, listamos todos ou según os filtros se hai
            $productos = $this->model->filtrar($id_cat, $max_prezo);
        }


        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/inicio.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    //Acción pa mostrar os detalles dun producto
    public function obter()
    {
        if (isset($_REQUEST["id"])) { // Verificamos que o ID veña na URL

            $prod = $this->model->obter($_REQUEST["id"]);

            require_once __DIR__ . '/../../includes/header.php';
            require_once __DIR__ . '/../../views/produto.php';
            require_once __DIR__ . '/../../includes/footer.php';
        }
    }

    // Acción que devolve JSON para o JavaScript
    public function suxerir()
    {
        // Capturamos o termo da URL ou baleiro se non existe
        $mensaxe = $_GET['q'] ?? '';

        //O JSON vai ser a resposta
        header('Content-Type: application/json');

        //Se o término non está baleiro, chamamaos o modelo e se está baleiro, enviamos un array vacío
        echo json_encode(!empty(trim($mensaxe)) ? $this->model->suxerir($mensaxe) : []);
        exit;
    }
}
