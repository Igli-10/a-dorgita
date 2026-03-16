<?php
require_once 'config/database.php';

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