<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/entidades/Producto.php';

class ProductoDAO {
    private $pdo;
    
    public function __construct() {
        try {
            $this->pdo = Database::connect();     
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function listar() {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM productos");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_CLASS, 'Producto');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function obtener($id) { 
        try {
            $stm = $this->pdo->prepare("SELECT * FROM productos WHERE id = ?");
            $stm->execute(array($id));
            return $stm->fetchObject("Producto");
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}