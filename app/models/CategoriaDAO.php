<?php
require_once __DIR__ . "/../../config/database.php";

class CategoriaDAO
{
    private $conexion;

    // Abre a conexión coa táboa de categorías
    public function __construct()
    {
        try {
            $this->conexion = Database::connect();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // Devolve todas as categorías ordenadas alfabeticamente
    public function listar()
    {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM categorias ORDER BY nome ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Inserta unha nova categoría na base de datos
    public function gardar($nome, $descripcion)
    {
        try {
            $stmt = $this->conexion->prepare("INSERT INTO categorias (nome, descripcion) VALUES (?, ?)");
            return $stmt->execute(array($nome, $descripcion));
        } catch (PDOException $e) {
            return false;
        }
    }

    // Actualiza o nome e a descrición dunha categoría existente
    public function actualizar($id, $nome, $descripcion)
    {
        try {
            $stmt = $this->conexion->prepare("UPDATE categorias SET nome =?, descripcion=? WHERE id=?");
            return $stmt->execute(array($nome, $descripcion, $id));
        } catch (PDOException $e) {
            return false;
        }
    }

    // Elimina unha categoría polo seu identificador
    public function borrar($id)
    {
        try {
            $stmt = $this->conexion->prepare("DELETE FROM categorias WHERE id=?");
            return $stmt->execute(array($id));
        } catch (PDOException $e) {
            return false;
        }
    }
}
