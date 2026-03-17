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
        //Comprobamos se existen os parámetros na URL e senon asignamoslle valor null
        $id_cat = $_REQUEST['cat'] ?? null;
        $max_prezo = $_REQUEST['max_prezo'] ?? null;

        //Filtra en función dos valores
        $productos = $this->model->filtrar($id_cat, $max_prezo);

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
}