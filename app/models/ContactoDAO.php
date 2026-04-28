<?php
require_once __DIR__ . '/../../config/database.php';

class ContactoDAO
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::connect();
    }

    // Garda unha mensaxe de contacto na base de datos
    public function gardarMensaxe(string $nome, string $email, string $mensaxe): bool
    {
        try {
            $sql = "INSERT INTO contacto (nome, email, mensaxe, data_creacion) 
                    VALUES (:nome, :email, :mensaxe, NOW())";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mensaxe', $mensaxe);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error ao gardar mensaxe: " . $e->getMessage();
            return false;
        }
    }
}
