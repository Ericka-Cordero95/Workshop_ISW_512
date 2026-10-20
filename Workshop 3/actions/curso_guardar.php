<?php
session_start();
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';
require_once __DIR__ . '/../utils/cursos.php';

// La acción también se protege: sin sesión no se puede guardar nada
require_login();

// Esta acción solo se alcanza desde el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/pages/cursos.php');
}

$id          = $_POST['id'] ?? '';
$nombre      = $_POST['nombre'] ?? null;
$codigo      = $_POST['codigo'] ?? null;
$carreraId   = $_POST['carrera_id'] ?? null;
$creditos    = $_POST['creditos'] ?? null;
$descripcion = $_POST['descripcion'] ?? null;

if (
    !is_string($id) || !is_string($nombre) || !is_string($codigo)
    || !is_string($carreraId) || !is_string($creditos) || !is_string($descripcion)
) {
    redirect('/pages/curso_form.php?msg=invalid_input');
}

// Con id es una edición; sin id es un curso nuevo
$editando = ($id !== '');
if ($editando) {
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if ($id === false) {
        redirect('/pages/cursos.php?msg=course_not_found');
    }
}

// Adónde volver si hay un error (el formulario de creación o el de edición)
$volver = $editando ? '/pages/curso_form.php?id=' . $id . '&msg=' : '/pages/curso_form.php?msg=';

// Validación en el servidor
$nombre = trim($nombre);
if (strlen($nombre) < 3 || strlen($nombre) > 100) {
    redirect($volver . 'invalid_name');
}

$codigo = trim($codigo);
if (!preg_match('/^[A-Za-z0-9-]{2,20}$/', $codigo)) {
    redirect($volver . 'invalid_code');
}

$carreraId = filter_var($carreraId, FILTER_VALIDATE_INT);
if ($carreraId === false) {
    redirect($volver . 'invalid_career');
}

$creditos = filter_var($creditos, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]]);
if ($creditos === false) {
    redirect($volver . 'invalid_credits');
}

$descripcion = trim($descripcion);
if ($descripcion === '' || strlen($descripcion) > 1000) {
    redirect($volver . 'invalid_description');
}

try {
    // La carrera enviada debe existir en la tabla carreras
    if (findCarrera($conn, $carreraId) === false) {
        redirect($volver . 'invalid_career');
    }

    if ($editando) {
        // El curso a editar debe existir
        if (findCurso($conn, $id) === false) {
            redirect('/pages/cursos.php?msg=course_not_found');
        }

        $statement = $conn->prepare(
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
        $mensaje = 'course_updated';
    } else {
        $statement = $conn->prepare(
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
        $mensaje = 'course_created';
    }
} catch (PDOException $exception) {
    // 23000 = violación de una restricción; aquí, el código UNIQUE ya existe
    if ($exception->getCode() === '23000') {
        redirect($volver . 'code_taken');
    }
    error_log('Error al guardar el curso: ' . $exception->getMessage());
    redirect($volver . 'server_error');
}

// Al terminar, se vuelve al listado de cursos
redirect('/pages/cursos.php?msg=' . $mensaje);