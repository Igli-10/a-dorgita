<?php
require_once __DIR__ . '/../models/ProductoDAO.php';
require_once __DIR__ . '/../models/entidades/Producto.php';

class ProductoController
{
    private $model;
    
    //Inicializa o modelo DAO para interactuar ca base de datos
    public function __construct()
    {
        $this->model = new ProductoDAO();
    }

    //Acción por defecto que e listar os productos
    public function index()
    {
        $productos = $this->model->listar();

        include 'includes/header.php';
      
        require_once __DIR__ . '/../../views/inicio.php';

        include 'includes/footer.php';
    }

    //Acción pa mostrar os detalles dun producto
    public function obter(){
        if(isset($_REQUEST["id"])){ // Verificamos que o ID veña na URL
            $prod=$this->model->obter($_REQUEST["id"]);

            include 'includes/header.php';
            require_once __DIR__ . '/../../views/produto.php';
            include 'includes/footer.php';
        }
    }
}
