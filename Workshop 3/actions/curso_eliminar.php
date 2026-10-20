<?php
session_start();
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';

// La acción también se protege: sin sesión no se puede eliminar nada
require_login();

// Eliminar solo se permite por POST (nunca por un enlace)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/pages/cursos.php');
}

$id = $_POST['id'] ?? null;
$id = is_string($id) ? filter_var($id, FILTER_VALIDATE_INT) : false;
if ($id === false) {
    redirect('/pages/cursos.php?msg=course_not_found');
}

try {
    $statement = $conn->prepare('DELETE FROM cursos WHERE id = :id');
    $statement->execute(['id' => $id]);
} catch (PDOException $exception) {
    error_log('Error al eliminar el curso: ' . $exception->getMessage());
    redirect('/pages/cursos.php?msg=server_error');
}

// Si no se borró ninguna fila, el curso no existía
if ($statement->rowCount() === 0) {
    redirect('/pages/cursos.php?msg=course_not_found');
}

redirect('/pages/cursos.php?msg=course_deleted');