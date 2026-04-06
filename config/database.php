<?php
class Database {
    public static function connect() {
        $host = "localhost";
        $user = "root";
        $password = "";
        $database = "a_dorgita";
        
        try {
            $conexion = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $user, $password);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>