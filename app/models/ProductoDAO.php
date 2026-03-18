<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/entidades/Producto.php';

class ProductoDAO
{
    private $conexion;


    //Establecese a conexión a base de datos
    public function __construct()
    {
        try {
            $this->conexion = Database::connect();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    //Metodo para obter os productos da táboa 
    public function listar()
    {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM productos");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Producto');
        } catch (Exception $e) {
            die("Erro o listar os produtos" . $e->getMessage());
        }
    }

    //Método para obter un producto polo seu id
    public function obter($id)
    {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM productos WHERE id = ?");
            $stmt->execute(array($id));
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Producto'); // Recupera unha fila da base de datos e convírtea en obxecto produto
            return $stmt->fetch();
        } catch (Exception $e) {
            die("Erro o ver detalles do produto" . $e->getMessage());
        }
    }

    //Método para filtrar un producto por categoría e prezo
    public function filtrar($id_cat = null, $max_prezo = null)
    {
        try {
            $sql = "SELECT * FROM productos";
            $condicions = []; // Aquí gardaremos os anacos do WHERE 
            $params = [];     // Aquí gardaremos os valores para o execute

            // 1. Comprobamos se hai categoría
            if ($id_cat !== null && $id_cat !== '') {
                $condicions[] = "id_categoria = ?";
                $params[] = $id_cat;
            }

            // 2. Comprobamos se hai prezo
            if ($max_prezo !== null && $max_prezo !== '') {
                $condicions[] = "precio <= ?";
                $params[] = $max_prezo;
            }

            // 3. Só engadimos o WHERE se realmente hai algunha condición
            if (count($condicions) > 0) {
                // Implode xunta o array cun " AND " entre cada elemento
                $sql .= " WHERE " . implode(" AND ", $condicions);
            }

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_CLASS, "Producto");
        } catch (Exception $e) {
            die("Erro ao filtrar: " . $e->getMessage());
        }
    }

    public function descontarStock($id, $cantidade) {
    try {
        $stmt = $this->conexion->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?");
        return $stmt->execute([$cantidade, $id, $cantidade]);
    } catch (PDOException $e) {
        return false;
    }
}
}
