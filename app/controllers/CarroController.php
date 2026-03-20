<?php
require_once __DIR__ . "/../models/ProductoDAO.php";
require_once __DIR__ . '/../models/PedidoDAO.php';

class CarroController
{
    private $productoDAO;

    public function __construct()
    {
        //Comprobamos se a sesión esta activa e senon inciamola
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
        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/carro.php';
        require_once __DIR__ . '/../../includes/footer.php';
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

                $stock_real = $producto->getStock();
                $cantidade_no_carro = isset($_SESSION["carro"][$id]) ? $_SESSION["carro"][$id]["cantidade"] : 0;
                $total_final = $cantidade_no_carro + $cantidade_a_engadir;

                // VALIDACIÓN DE STOCK: Comprobamos que a suma non supere o stock real
                if ($total_final <= $stock_real) {
                    //Se o produto xa estaba no carro ou é novo, actualizamos/creamos
                    $_SESSION["carro"][$id] = [
                        "id"        => $producto->getId(),
                        "nome"      => $producto->getNome(),
                        "precio"    => $producto->getPrecio(),
                        "imagen"    => $producto->getImagen(),
                        "stock"     => $stock_real,
                        "cantidade" => $total_final
                    ];
                } else {
                    // Se a cantidade solicitada supera o stock, limitamos ao máximo posible
                    $_SESSION["carro"][$id]["cantidade"] = $stock_real;
                    $_SESSION['erro_stock'] = "Non se poden engadir máis unidades. Stock máximo alcanzado.";
                }
            }
        }

        // Se non é AJAX, rediriximos. Se é AJAX, devolvemos o fragmento para actualizar o carrito
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            // Se hai erro, volvemos á ficha do produto para que se vexa a mensaxe
            $url = isset($_SESSION['erro_stock']) ? "index.php?c=producto&a=obter&id=$id" : "index.php?c=producto&a=index";
            header("Location: $url");
            exit;
        } else {
            $this->get_fragment();
            exit;
        }
    }

    public function eliminar()
    {
        //Recollemos o ID do produto que o usuario quere eliminar
        $id = $_REQUEST["id"] ?? null;

        //Se o id e válido e o produto está na sesión. Eliminamolo
        if ($id && isset($_SESSION["carro"][$id])) {
            unset($_SESSION["carro"][$id]);
        }

        //Se é AJAX, devolvemos o fragmento sen recargar. Se non, rediriximos
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->get_fragment();
            exit;
        } else {
            header("Location: index.php?c=carro&a=index");
            exit;
        }
    }

    public function restar($id = null)
    {
        //Recollemos o ID do produto que queremos restarlle unha unidad
        $id = $id ?? ($_REQUEST["id"] ?? null);

        //Se o produto xa existe no noso carro
        if ($id && isset($_SESSION["carro"][$id])) {

            //Restamoslle 1
            $_SESSION["carro"][$id]["cantidade"]--;

            //Se a cantidade o restarlle e igual ou menor que 0 eliminamolo
            if ($_SESSION["carro"][$id]["cantidade"] <= 0) {
                unset($_SESSION["carro"][$id]);
            }
        }

        //Se é AJAX, devolvemos o fragmento sen recargar. Se non, rediriximos
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->get_fragment();
            exit;
        } else {
            header("Location: index.php?c=carro&a=index");
            exit;
        }
    }

    public function sumar($id = null, $redirect = true)
    {
        //Recollemos da URL o id senon se pasa por parámetro
        $id = $id ?? ($_REQUEST["id"] ?? null);

        //Comprobamos que existe
        if ($id && isset($_SESSION["carro"][$id])) {

            //Comprobamos que a cantidad non supere o máximo de stock
            if ($_SESSION["carro"][$id]["cantidade"] < $_SESSION["carro"][$id]["stock"]) {

                //Engadimos 1
                $_SESSION["carro"][$id]["cantidade"]++;
            } else {
                $_SESSION['erro_stock'] = "Non hai máis unidades en stock.";
            }
        }

        //Se é redirect normal, usamos header. Se é AJAX, devolvemos o fragmento
        if ($redirect) {
            header("Location: /a-dorgita/index.php?c=carro&a=index");
            exit;
        } elseif (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->get_fragment();
            exit;
        }
    }

    public function calcularTotal()
    {
        $total = 0;

        //Recorremos cada produto gardado na sesión
        foreach ($_SESSION["carro"] as $item) {
            //Multiplicamos o precio de cada produto pola cantidade
            $total += $item["precio"] * $item["cantidade"];
        }

        return $total;
    }

    public function get_fragment()
    {
        // Recuperamos os datos necesarios da sesión
        $carro = $_SESSION["carro"];
        $total = $this->calcularTotal();

        //Cargamos só o fragmento, sen header nin footer
        require_once __DIR__ . '/../../views/partials/carro_lateral.php';
    }

    //Método que mete o pedido na base de datos
    public function finalizar()
    {
        // Verifico que o usuario está logueado
        if (!isset($_SESSION["usuario"])) {
            $_SESSION['mensaxe_aviso'] = "Debes iniciar sesión para poder completar o teu pedido.";
            header("Location: index.php?c=usuario&a=login");
            exit;
        }

        $carro = $_SESSION["carro"] ?? [];

        // Se o carro está baleiro, rediriximos ao inicio
        if (empty($carro)) {
            header("Location: index.php?c=producto&a=index");
            exit;
        }

        $pedidoDAO = new PedidoDAO();

        $id_usuario = $_SESSION["usuario"]["id"];
        $total = $this->calcularTotal();

        // Gardo o pedido na base de datos
        $id_pedido = $pedidoDAO->crearPedido($id_usuario, $total, $carro);

        if (!$id_pedido) {
            die("Erro crítico ao procesar a compra.");
        }

        // Se se gardou, baleirase o carro e amosamos confirmación
        $_SESSION["carro"] = [];

        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/confirmacion_pedido.php';
        require_once __DIR__ . '/../../includes/footer.php';
        exit;
    }
}
