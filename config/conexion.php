<?php
try{
    $ruta_db=__DIR__ . "/../sql/a_dorgita.db";
    
    $conexion= new PDO("sqlite:". $ruta_db); // Uso PDO con SQLite para manter a portabilidade entre a empresa e casa.

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configuración para que nos avise de calquer error en SQL

    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //Configuración para que os resultados sexan arrays asociativos

    echo "Conexion establecida con éxito";

}catch(PDOException $e){
    die("Erro conectando á base de datos: ". $e->getMessage());
}


?>