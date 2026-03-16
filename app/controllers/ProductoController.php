<?php
require_once __DIR__ . '/../models/ProductoDAO.php';
require_once __DIR__ . '/../models/entidades/Producto.php';

class ProductoController
{
    private $model;

    public function __construct()
    {
        $this->model = new ProductoDAO();
    }

    public function index()
    {
        $productos = $this->model->listar();
        
        include 'includes/header.php';
      
        require_once __DIR__ . '/../../views/inicio.php';

        include 'includes/footer.php';
    }
}
