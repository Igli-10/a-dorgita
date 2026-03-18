<?php
require_once __DIR__ . "/../models/UsuarioDAO.php";

class UsuarioController
{
    private $usuarioDAO;

    public function __construct()
    {
        // Comprobamos se a sesión esta activa e senon inciamola
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->usuarioDAO = new UsuarioDAO();

        // Lóxica de Auto-Login mediante Cookie "Lembrarme"
        if (!isset($_SESSION['usuario']) && isset($_COOKIE['usuario_login'])) {
            $usuario = $this->usuarioDAO->obterPorEmail($_COOKIE['usuario_login']);
            if ($usuario) {
                $_SESSION['usuario'] = [
                    "id" => $usuario->getId(),
                    "nome" => $usuario->getNome(),
                    "email" => $usuario->getEmail(),
                    "rol" => $usuario->getRol()
                ];
            }
        }
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // Recolle os datos enviados dende o formulario
            $email = $_POST["email"] ?? "";
            $contrasinal = $_POST["contrasinal"] ?? "";

            // Comprobamos se o usuario marcou a casilla de lembrarme
            $lembrarme = isset($_POST["lembrarme"]);

            // Busca o usuario na base de datos utilizando o correo
            $usuario = $this->usuarioDAO->obterPorEmail($email);

            // Se o usuario existe e o contrasinal coincide co hash gardado na base de datos
            if ($usuario && password_verify($contrasinal, $usuario->getContrasinal())) {

                // Garda os datos do usuario na sesion actual
                $_SESSION["usuario"] = [
                    "id" => $usuario->getId(),
                    "nome" => $usuario->getNome(),
                    "email" => $usuario->getEmail(),
                    "rol" => $usuario->getRol()
                ];

                if ($lembrarme) {
                    // Gardamos o email durante 30 dias en segundos
                    setcookie("usuario_login", $email, time() + (30 * 24 * 60 * 60), "/");
                }

                // Redirixe ao usuario de volta á páxina principal da tenda
                header("Location: index.php?c=producto&a=index");
                exit;
            } else {
                // Se as credenciais non son correctas, define a mensaxe de erro
                $erro = "As credenciais son incorrectas.";
            }
        }

        // Carga de deseño dende o controlador
        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/login.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    public function rexistrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recolle os datos do formulario de rexistro
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $contrasinal = $_POST['contrasinal'] ?? '';
            $contrasinal2 = $_POST['contrasinal2'] ?? '';

            // Comproba que os dous contrasinais introducidos sexan idénticos
            if ($contrasinal === $contrasinal2) {
                // Cifra o contrasinal de forma segura antes de gardalo na base de datos
                $hash = password_hash($contrasinal, PASSWORD_DEFAULT);

                // Por defecto, asignamos o rol de 'cliente' a todos os novos rexistrados
                $rol = 'cliente';

                // Intenta crear o usuario a través do DAO
                $creado = $this->usuarioDAO->crear($nome, $email, $hash, $rol);

                if ($creado) {
                    // Se o rexistro é exitoso, redirixe ao usuario para que inicie sesión
                    header("Location: index.php?c=usuario&a=login");
                    exit;
                } else {
                    // Se o correo xa existe, mostra un erro
                    $erro = "Ese correo electrónico xa está rexistrado.";
                }
            } else {
                // Erro se o usuario non escribiu o mesmo contrasinal dúas veces
                $erro = "Os contrasinais non coinciden.";
            }
        }

        // Carga de deseño dende o controlador
        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/rexistro.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    public function perfil()
    {
        // Comproba se o usuario está autenticado
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?c=usuario&a=login");
            exit;
        }

        // Carga de deseño dende o controlador
        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/perfil.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    public function logout()
    {
        // Destrúe toda a información gardada na sesión (incluíndo o usuario)
        session_destroy();

        // Borramos a cookie de "Lembrarme"
        if (isset($_COOKIE['usuario_login'])) {
            setcookie("usuario_login", "", time() - 3600, "/");
        }

        // Redirixe ao catálogo de produtos público
        header("Location: index.php?c=producto&a=index");
        exit;
    }
}
