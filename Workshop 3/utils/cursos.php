<?php
// Funciones de acceso a datos de cursos y carreras (mismo estilo que utils/users.php de week4)

// Todos los cursos, ordenados por nombre
function getCursos(PDO $conn)
{
    return $conn->query(
        'SELECT id, nombre, codigo, carrera_id, creditos, descripcion
         FROM cursos
         ORDER BY nombre'
    )->fetchAll(PDO::FETCH_ASSOC);
}

// Un curso por su id (false si no existe)
function findCurso(PDO $conn, $id)
{
    $statement = $conn->prepare(
        'SELECT id, nombre, codigo, carrera_id, creditos, descripcion
         FROM cursos
         WHERE id = :id
         LIMIT 1'
    );
    $statement->execute(['id' => $id]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

// Todas las carreras (para el dropdown del formulario)
function getCarreras(PDO $conn)
{
    return $conn->query('SELECT id, nombre FROM carreras ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
}

// Una carrera por su id (false si no existe)
function findCarrera(PDO $conn, $id)
{
    $statement = $conn->prepare('SELECT id, nombre FROM carreras WHERE id = :id LIMIT 1');
    $statement->execute(['id' => $id]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}