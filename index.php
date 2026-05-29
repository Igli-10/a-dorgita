<?php



require_once 'config/database.php';
require_once 'config/init-db.php';

// Inicia a sesión e aplica auto-login se o usuario marcou "Lembrarme"
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-login mediante cookie "Recuérdame"
if (!isset($_SESSION['usuario']) && isset($_COOKIE['usuario_login'])) {
    require_once 'app/models/UsuarioDAO.php';
    $usuarioDAO = new UsuarioDAO();
    $usuario = $usuarioDAO->obterPorEmail($_COOKIE['usuario_login']);
    if ($usuario) {
        $_SESSION['usuario'] = [
            "id" => $usuario->getId(),
            "nome" => $usuario->getNome(),
            "email" => $usuario->getEmail(),
            "rol" => $usuario->getRol(),
            "foto_perfil" => $usuario->getFotoPerfil()
        ];
    }
}

$controller = 'producto';

if(!isset($_REQUEST['c']))
{
    require_once "app/controllers/$controller" . "Controller.php";
    $controller = ucwords($controller) . 'Controller';
    $controller = new $controller;
    $controller->index();    
}
else
{
    $controller = strtolower($_REQUEST['c']);
    $accion = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'index';
    
    require_once "app/controllers/$controller" . "Controller.php";
    $controller = ucwords($controller) . 'Controller';
    $controller = new $controller;
    
    call_user_func( array( $controller, $accion ) );
}