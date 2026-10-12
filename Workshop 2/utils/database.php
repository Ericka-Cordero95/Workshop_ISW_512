<?php
// Conexión a la base de datos con PDO (igual que en el week3)
$dsn = 'mysql:host=localhost;dbname=workshop1;charset=utf8mb4';
$dbUser = 'root';
$dbPassword = '';

try {
    $conn = new PDO($dsn, $dbUser, $dbPassword, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,//hace que los errores de SQL lancen excepciones que podemos capturar con try/catch.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $exception) {
    error_log('Error de conexión: ' . $exception->getMessage());
    die('No se pudo conectar a la base de datos.');
}