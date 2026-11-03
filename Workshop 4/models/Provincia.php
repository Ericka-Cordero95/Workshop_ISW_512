<?php
// Modelo de provincias: consultas a la tabla provincias
class Provincia
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Todas las provincias (para el dropdown)
    public function todas()
    {
        return $this->conn->query('SELECT id, nombre FROM provincias ORDER BY id')->fetchAll();
    }

    // Una provincia por su id (false si no existe)
    public function buscar($id)
    {
        $statement = $this->conn->prepare('SELECT id, nombre FROM provincias WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }
}