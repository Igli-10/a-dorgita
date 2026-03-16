<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/entidades/Producto.php';

class ProductoDAO {
    private $pdo;
    

    //Establecese a conexión a base de datos
    public function __construct() {
        try {
            $this->pdo = Database::connect();     
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    //Metodo para obter os productos da táboa 
    public function listar() {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM productos");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_CLASS, 'Producto');
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    //Método para obter un producto polo seu id
    public function obter($id) { 
        try {
            $stm = $this->pdo->prepare("SELECT * FROM productos WHERE id = ?");
            $stm->execute(array($id));
            return $stm->fetchObject("Producto"); // Devolvemos o resultado como un obxecto da clase Producto
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}