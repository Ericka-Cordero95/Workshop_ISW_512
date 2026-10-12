<?php
session_start();
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';

// recibe los datos del formulario post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/pages/registro.php');
}
//valida en el servidor
$username    = $_POST['username'] ?? null;
$password    = $_POST['password'] ?? null;
$confirm     = $_POST['password_confirm'] ?? null;
$provinciaId = $_POST['provincia_id'] ?? null;

if (!is_string($username) || !is_string($password) || !is_string($confirm) || !is_string($provinciaId)) {
    redirect('/pages/registro.php?msg=invalid_input');
}

$username = trim($username);

if (strlen($username) < 3 || strlen($username) > 50) {
    redirect('/pages/registro.php?msg=invalid_username');
}
if (strlen($password) < 6) {
    redirect('/pages/registro.php?msg=password_short');
}
if ($password !== $confirm) {
    redirect('/pages/registro.php?msg=password_mismatch');
}

$provinciaId = filter_var($provinciaId, FILTER_VALIDATE_INT);
if ($provinciaId === false) {
    redirect('/pages/registro.php?msg=invalid_province');
}

try {
    // verifica que la provincia exista en la tabla provincias
    $statement = $conn->prepare('SELECT id FROM provincias WHERE id = :id');
    $statement->execute(['id' => $provinciaId]);
    if ($statement->fetch() === false) {
        redirect('/pages/registro.php?msg=invalid_province');
    }

    // Guardar el usuario con la contraseña cifrada y la provincia asociada
    $statement = $conn->prepare(
        'INSERT INTO usuarios (username, password, provincia_id)
         VALUES (:username, :password, :provincia_id)'
    );
    $statement->execute([
        'username'     => $username,
        'password'     => password_hash($password, PASSWORD_DEFAULT),
        'provincia_id' => $provinciaId,
    ]);
} catch (PDOException $exception) {
    // 23000 = Si el usuario ya existe, la restricción UNIQUE de la tabla lanza un error
    if ($exception->getCode() === '23000') {
        redirect('/pages/registro.php?msg=username_taken');
    }
    error_log('Error al registrar usuario: ' . $exception->getMessage());
    redirect('/pages/registro.php?msg=server_error');
}
//si todo bien, redirige al login con mensaje de éxito
redirect('/index.php?msg=registered');