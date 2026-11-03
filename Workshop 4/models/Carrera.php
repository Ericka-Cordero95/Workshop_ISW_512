<?php
// Modelo de carreras: consultas a la tabla carreras
class Carrera
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Todas las carreras (para el dropdown del formulario de cursos)
    public function todas()
    {
        return $this->conn->query('SELECT id, nombre FROM carreras ORDER BY id')->fetchAll();
    }

    // Una carrera por su id (false si no existe)
    public function buscar($id)
    {
        $statement = $this->conn->prepare('SELECT id, nombre FROM carreras WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }
}