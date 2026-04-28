<?php
/**
 * Script de inicialización automática da base de datos
 * Execútase só se a BD non existe
 */

$host = "localhost";
$user = "root";
$password = "";
$database = "a_dorgita";

try {
    // Conectar sen especificar a BD para comprobar se existe
    $conexion = new PDO("mysql:host=$host;charset=utf8mb4", $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Comprobar se a base de datos existe
    $result = $conexion->query("SHOW DATABASES LIKE '$database'");
    
    if ($result->rowCount() == 0) {
        // A BD non existe, creala automaticamente
        $sqlFile = __DIR__ . '/../sql/a_dorgita.sql';
        
        if (file_exists($sqlFile)) {
            $sqlContent = file_get_contents($sqlFile);
            $statements = array_filter(array_map('trim', explode(';', $sqlContent)));
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    $conexion->exec($statement);
                }
            }
        }
    }
} catch (PDOException $e) {
    // Se hai erro, simplemente continuar (probablemente MySQL non está correndo)
    // O erro mostrarase cando se intente conectar a BD normalmente
}
