<?php
// Modelo de cursos: todas las consultas a la tabla cursos
class Curso
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Todos los cursos, ordenados por nombre
    public function todos()
    {
        return $this->conn->query(
            'SELECT id, nombre, codigo, carrera_id, creditos, descripcion
             FROM cursos
             ORDER BY nombre'
        )->fetchAll();
    }

    // Un curso por su id (false si no existe)
    public function buscar($id)
    {
        $statement = $this->conn->prepare(
            'SELECT id, nombre, codigo, carrera_id, creditos, descripcion
             FROM cursos
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }

    // Inserta un curso nuevo
    public function crear($nombre, $codigo, $carreraId, $creditos, $descripcion)
    {
        $statement = $this->conn->prepare(
            'INSERT INTO cursos (nombre, codigo, carrera_id, creditos, descripcion)
             VALUES (:nombre, :codigo, :carrera_id, :creditos, :descripcion)'
        );
        $statement->execute([
            'nombre'      => $nombre,
            'codigo'      => $codigo,
            'carrera_id'  => $carreraId,
            'creditos'    => $creditos,
            'descripcion' => $descripcion,
        ]);
    }

    // Actualiza un curso existente
    public function actualizar($id, $nombre, $codigo, $carreraId, $creditos, $descripcion)
    {
        $statement = $this->conn->prepare(
            'UPDATE cursos
             SET nombre = :nombre, codigo = :codigo, carrera_id = :carrera_id,
                 creditos = :creditos, descripcion = :descripcion
             WHERE id = :id'
        );
        $statement->execute([
            'nombre'      => $nombre,
            'codigo'      => $codigo,
            'carrera_id'  => $carreraId,
            'creditos'    => $creditos,
            'descripcion' => $descripcion,
            'id'          => $id,
        ]);
    }

    // Elimina un curso; devuelve cuántas filas se borraron (0 si no existía)
    public function eliminar($id)
    {
        $statement = $this->conn->prepare('DELETE FROM cursos WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount();
    }
}