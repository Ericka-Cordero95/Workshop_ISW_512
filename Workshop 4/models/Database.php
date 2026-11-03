<?php
// Conexión a la base de datos con PDO (una sola conexión por petición)
class Database
{
    private static $conexion = null;

    public static function conectar()
    {
        if (self::$conexion === null) {
            $dsn = 'mysql:host=localhost;dbname=workshop1;charset=utf8mb4';
            $usuario = 'root';
            $clave = '';

            try {
                self::$conexion = new PDO($dsn, $usuario, $clave, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $exception) {
                error_log('Error de conexión: ' . $exception->getMessage());
                die('No se pudo conectar a la base de datos.');
            }
        }
        return self::$conexion;
    }
}