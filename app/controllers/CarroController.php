<?php
require_once __DIR__ . "/../models/ProductoDAO.php";
require_once __DIR__ . '/../models/PedidoDAO.php';
require_once __DIR__ . '/../models/UsuarioDAO.php';

class CarroController
{
    private $productoDAO;
    private $usuarioDAO;

    public function __construct()
    {
        //Comprobo se a sesión esta activa e senon inícioa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //Se o usuario entra por primeira vez e non ten o array carro, créoo baleiro
        if (!isset($_SESSION["carro"])) {
            $_SESSION["carro"] = [];
        }

        $this->productoDAO = new ProductoDAO();
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function index()
    {
        //Recupero os datos do carro desde a sesión 
        $carro = $_SESSION["carro"];

        //Calculo o coste total dos produtos
        $total = $this->calcularTotal();

        //Cargo as vistas
        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/carro.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    public function engadir()
    {
        //Recollo o id do produto que ven na URL
        $id = $_REQUEST["id"] ?? null;

        // Recollo a cantidade do formulario (POST) ou da URL, por defecto 1
        $cantidade_a_engadir = isset($_REQUEST["cantidade"]) ? (int)$_REQUEST["cantidade"] : 1;

        if ($id) {
            //Busco o produto na base de datos
            $producto = $this->productoDAO->obter($id);

            //Comprobo que existe e que teña stock
            if ($producto && $producto->getStock() > 0) {

                $stock_real = $producto->getStock();
                $cantidade_no_carro = isset($_SESSION["carro"][$id]) ? $_SESSION["carro"][$id]["cantidade"] : 0;
                $total_final = $cantidade_no_carro + $cantidade_a_engadir;

                // VALIDACIÓN DE STOCK: Comprobo que a suma non supere o stock real
                if ($total_final <= $stock_real) {
                    //Se o produto xa estaba no carro ou é novo, actualizo/creo
                    $_SESSION["carro"][$id] = [
                        "id"        => $producto->getId(),
                        "nome"      => $producto->getNome(),
                        "precio"    => $producto->getPrecio(),
                        "imagen"    => $producto->getImagen(),
                        "stock"     => $stock_real,
                        "cantidade" => $total_final
                    ];
                } else {
                    // Se a cantidade solicitada supera o stock, limítoa ao máximo posible
                    $_SESSION["carro"][$id]["cantidade"] = $stock_real;
                    $_SESSION['erro_stock'] = "Non se poden engadir máis unidades. Stock máximo alcanzado.";
                }
            }
        }

        // Se non é AJAX, redirixo. Se é AJAX, devolvo o fragmento para actualizar o carrito
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            // Se hai erro, volvo á ficha do produto para que se vexa a mensaxe
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
        //Recollo o ID do produto que o usuario quere eliminar
        $id = $_REQUEST["id"] ?? null;

        //Se o id e válido e o produto está na sesión. Eliminamolo
        if ($id && isset($_SESSION["carro"][$id])) {
            unset($_SESSION["carro"][$id]);
        }

        //Se é AJAX, devolvo o fragmento sen recargar. Se non, redirixo
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
        //Recollo o ID do produto ao que quero restarlle unha unidade
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

        //Se é AJAX, devolvo o fragmento sen recargar. Se non, redirixo
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
        //Recollo da URL o id se non se pasa por parámetro
        $id = $id ?? ($_REQUEST["id"] ?? null);

        //Comprobo que existe
        if ($id && isset($_SESSION["carro"][$id])) {

            //Comprobo que a cantidade non supere o máximo de stock
            if ($_SESSION["carro"][$id]["cantidade"] < $_SESSION["carro"][$id]["stock"]) {

                //Engado 1
                $_SESSION["carro"][$id]["cantidade"]++;
            } else {
                $_SESSION['erro_stock'] = "Non hai máis unidades en stock.";
            }
        }

        //Se é redirect normal, uso header. Se é AJAX, devolvo o fragmento
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

        //Recorro cada produto gardado na sesión
        foreach ($_SESSION["carro"] as $item) {
            //Multiplico o precio de cada produto pola cantidade
            $total += $item["precio"] * $item["cantidade"];
        }

        return $total;
    }

    public function get_fragment()
    {
        // Recupero os datos necesarios da sesión
        $carro = $_SESSION["carro"];
        $total = $this->calcularTotal();

        //Cargo só o fragmento, sen header nin footer
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

        // Se o carro está baleiro, redirixo ao inicio
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

        // Se se gardou, baleiro o carro e amoso confirmación
        $_SESSION["carro"] = [];

        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/confirmacion_pedido.php';
        require_once __DIR__ . '/../../includes/footer.php';
        exit;
    }

    public function descargarFactura()
    {
        $id_pedido = $_GET["id"] ?? null;

        if (!isset($_SESSION["usuario"]) || !$id_pedido) {
            header("Location: index.php?c=usuario&a=login");
            exit;
        }

        $pedidoDAO = new PedidoDAO();
        $pedido = $pedidoDAO->obter($id_pedido);

        // Evita que un usuario descargue facturas doutro pedido
        if (!$pedido || (int)$pedido->getIdUsuario() !== (int)$_SESSION["usuario"]["id"]) {
            header("Location: index.php?c=usuario&a=perfil");
            exit;
        }

        $detalles = $pedidoDAO->obterDetalles($id_pedido);

        // Obter os datos do usuario
        $usuario = $this->usuarioDAO->obter($pedido->getIdUsuario());

        require_once __DIR__ . '/../../libs/fpdf/fpdf.php';

        $toLatin1 = function ($texto) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
            }

            return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
        };

        $pdf = new FPDF();
        $pdf->AddPage();

        // Logo
        $logoPath = __DIR__ . '/../../public/img/logo_favicon.png';
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 8, 18);
            $pdf->Ln(6);
        }

        // Paleta (verde + laranxa)
        $verdeR = 40;
        $verdeG = 122;
        $verdeB = 72;
        $laranxaR = 214;
        $laranxaG = 122;
        $laranxaB = 47;

        // Cabeceira 
        $pdf->SetFont('Times', 'B', 22);
        $pdf->SetTextColor($verdeR, $verdeG, $verdeB);
        $pdf->Cell(0, 12, $toLatin1('A Dorgita'), 0, 1, 'C');
        $pdf->SetFont('Times', 'B', 14);
        $pdf->SetTextColor(60, 60, 60);
        $pdf->Cell(0, 8, $toLatin1('FACTURA'), 0, 1, 'C');

        $pdf->SetDrawColor($verdeR, $verdeG, $verdeB);
        $pdf->SetLineWidth(0.6);
        $pdf->Line(15, $pdf->GetY() + 1, 195, $pdf->GetY() + 1);
        $pdf->Ln(6);

        // Datos básicos
        $pdf->SetFont('Times', '', 11);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->Cell(95, 8, $toLatin1('Pedido: #' . $id_pedido), 0, 0, 'L');
        $pdf->Cell(95, 8, $toLatin1('Data: ' . date('d/m/Y H:i')), 0, 1, 'R');
        $pdf->Ln(4);

        // Subliña cos datos do cliente
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(95, 7, $toLatin1('Cliente: ' . ($usuario ? $usuario->getNome() : 'Non dispoñible')), 0, 0, 'L');
        $pdf->Cell(95, 7, $toLatin1('Email: ' . ($usuario ? $usuario->getEmail() : '')), 0, 1, 'R');
        $pdf->Ln(2);

        // Táboa de produtos
        $pdf->SetFillColor(233, 242, 235);
        $pdf->SetDrawColor($verdeR, $verdeG, $verdeB);
        $pdf->SetFont('Times', 'B', 11);
        $pdf->Cell(95, 10, $toLatin1('Produto'), 1, 0, 'C', true);
        $pdf->Cell(25, 10, 'Cant.', 1, 0, 'C', true);
        $pdf->Cell(35, 10, 'Prezo Un.', 1, 0, 'C', true);
        $pdf->Cell(35, 10, 'Subtotal', 1, 1, 'C', true);

        $pdf->SetFont('Times', '', 11);
        $rowFill = false;
        foreach ($detalles as $d) {
            $subtotal = $d['cantidade'] * $d['prezo_unitario'];
            $pdf->SetFillColor(248, 250, 248);
            $pdf->Cell(95, 9, $toLatin1($d['nome']), 1, 0, 'L', $rowFill);
            $pdf->Cell(25, 9, $d['cantidade'], 1, 0, 'C', $rowFill);
            $pdf->Cell(35, 9, number_format($d['prezo_unitario'], 2) . ' EUR', 1, 0, 'C', $rowFill);
            $pdf->Cell(35, 9, number_format($subtotal, 2) . ' EUR', 1, 1, 'C', $rowFill);
            $rowFill = !$rowFill;
        }

        // Total final destacado
        $pdf->SetFont('Times', 'B', 13);
        $pdf->SetFillColor(255, 245, 235);
        $pdf->SetTextColor($laranxaR, $laranxaG, $laranxaB);
        $pdf->Cell(155, 12, 'TOTAL A PAGAR:', 1, 0, 'R', true);
        $pdf->Cell(35, 12, number_format($pedido->getTotal(), 2) . ' EUR', 1, 1, 'C', true);

        $pdf->Ln(6);
        $pdf->SetFont('Times', 'I', 10);
        $pdf->SetTextColor(90, 90, 90);
        $pdf->Cell(0, 8, $toLatin1('Grazas por confiar en A Dorgita.'), 0, 1, 'C');

        $pdf->Output('D', 'Factura_Dorgita_' . $id_pedido . '.pdf');
        exit;
    }
}
