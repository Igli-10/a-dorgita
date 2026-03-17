<?php
require_once __DIR__ . "/../models/ProductoDAO.php";

class CarroController
{
    private $productoDAO;

    public function __construct()
    {
        //Comprobamos se a sessión ten un id asignado e senon inciamola
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //Se o usuario entra por primeira vez e non ten o array carro, creamolo baleiro
        if (!isset($_SESSION["carro"])) {
            $_SESSION["carro"] = [];
        }

        $this->productoDAO = new ProductoDAO();
    }

    public function index()
    {
        //Recuperamos os datos do carro desde a sesión 
        $carro = $_SESSION["carro"];

        //Calculamos o coste total dos produtos
        $total = $this->calcularTotal();

        //Cargamos as vistas
        include "includes/header.php";
        require_once __DIR__ . '/../../views/carro.php';
        include 'includes/footer.php';
    }

    public function engadir()
    {
        //Recollemos o id do produto que ven na URL
        $id = $_REQUEST["id"] ?? null;

        // Recollemos a cantidade do formulario (POST) ou da URL, por defecto 1
        $cantidade_a_engadir = isset($_REQUEST["cantidade"]) ? (int)$_REQUEST["cantidade"] : 1;

        if ($id) {
            //Buscamos o produto na base de datos
            $producto = $this->productoDAO->obter($id);

            //Comprobamos que existe e que teña stock
            if ($producto && $producto->getStock() > 0) {

                //Se o produto xa estaba no carro chamamos a sumar
                if (isset($_SESSION["carro"][$id])) {

                    $this->sumar($id);
                    return;

                    //Senon estaba no carro, rexistramolo
                } else {
                    $_SESSION["carro"][$id] = [
                        "nome" => $producto->getNome(),
                        "precio" => $producto->getPrecio(),
                        "imagen" => $producto->getImagen(),
                        'stock'     => $producto->getStock(),
                        'cantidade' => $cantidade_a_engadir
                    ];
                }
            }
        }
        //Recargamos a vista do carro
        header("Location: index.php?c=producto&a=index");
        exit;
    }

    public function eliminar()
    {
        //Recollemos o ID do produto que o usuario quere eliminar
        $id = $_REQUEST["id"] ?? null;

        //Se o id e válido e o produto está na sesión. Eliminamolo
        if ($id && isset($_SESSION["carro"][$id])) {
            unset($_SESSION["carro"][$id]);
        }

        //Recargamos a vista do carro
        header("Location: index.php?c=carro&a=index");
        exit;
    }

    public function restar()
    {
        //Recollemos o ID do produto que queremos restarlle unha unidad
        $id = $_REQUEST["id"] ?? null;

        //Se o produto xa existe no noso carro
        if ($id && isset($_SESSION["carro"][$id])) {

            //Restamoslle 1
            $_SESSION["carro"][$id]["cantidade"]--;

            //Se a cantidade o restarlle e igual ou menor que 0 eliminamolo
            if ($_SESSION["carro"][$id]["cantidade"] <= 0) {
                unset($_SESSION["carro"][$id]);
            }
        }
        //Recargamos a vista do carro
        header("Location: index.php?c=carro&a=index");
        exit;
    }

    public function sumar($id = null)
    {
        //Recollemos da URL o id senon se pasa por parámetro
        $id = $id ?? ($_REQUEST["id"] ?? null);

        //Comprobamos que existe
        if ($id && isset($_SESSION["carro"][$id])) {

            //Comprobamos que a cantidad non supere o máximo de stock
            if ($_SESSION["carro"][$id]["cantidade"] < $_SESSION["carro"][$id]["stock"]) {

                //Engadimos 1
                $_SESSION["carro"][$id]["cantidade"]++;
            }
        }

        header("Location: /a-dorgita/index.php?c=carro&a=index");
        exit;
    }

    public function calcularTotal()
    {
        $total = 0;

        //Recorremos cada produto guardado en la sesión
        foreach ($_SESSION["carro"] as $item) {
            //Multiplicamos o precio de cada produto pola cantidade
            $total += $item["precio"] * $item["cantidade"];
        }

        return $total;
    }
}
