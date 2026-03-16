<?php
class Database {
    public static function connect() {
        $ruta_db = __DIR__ . "/../sql/a_dorgita.db";
        $conexion = new PDO("sqlite:" . $ruta_db);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    }
}