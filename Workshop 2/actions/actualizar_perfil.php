<?php
session_start();
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';

// La acción también se protege: sin sesión no se puede modificar nada
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/pages/perfil.php');
}

$username    = $_POST['username'] ?? null;
$provinciaId = $_POST['provincia_id'] ?? null;
$password    = $_POST['password'] ?? '';
$confirm     = $_POST['password_confirm'] ?? '';

if (!is_string($username) || !is_string($provinciaId) || !is_string($password) || !is_string($confirm)) {
    redirect('/pages/perfil.php?msg=invalid_input');
}

$username = trim($username);

if (strlen($username) < 3 || strlen($username) > 50) {
    redirect('/pages/perfil.php?msg=invalid_username');
}

$provinciaId = filter_var($provinciaId, FILTER_VALIDATE_INT);
if ($provinciaId === false) {
    redirect('/pages/perfil.php?msg=invalid_province');
}

// La contraseña es opcional: solo se valida y se cambia si el usuario escribió una nueva
$cambiaPassword = ($password !== '' || $confirm !== '');
if ($cambiaPassword) {
    if (strlen($password) < 6) {
        redirect('/pages/perfil.php?msg=password_short');
    }
    if ($password !== $confirm) {
        redirect('/pages/perfil.php?msg=password_mismatch');
    }
}

try {
    // La provincia enviada debe existir
    $statement = $conn->prepare('SELECT id FROM provincias WHERE id = :id');
    $statement->execute(['id' => $provinciaId]);
    if ($statement->fetch() === false) {
        redirect('/pages/perfil.php?msg=invalid_province');
    }

    // El usuario a modificar sale de la SESIÓN, nunca del formulario
    if ($cambiaPassword) {
        $statement = $conn->prepare(
            'UPDATE usuarios SET username = :username, provincia_id = :provincia_id, password = :password WHERE id = :id'
        );
        $statement->execute([
            'username'     => $username,
            'provincia_id' => $provinciaId,
            'password'     => password_hash($password, PASSWORD_DEFAULT),
            'id'           => $_SESSION['user_id'],
        ]);
    } else {
        $statement = $conn->prepare(
            'UPDATE usuarios SET username = :username, provincia_id = :provincia_id WHERE id = :id'
        );
        $statement->execute([
            'username'     => $username,
            'provincia_id' => $provinciaId,
            'id'           => $_SESSION['user_id'],
        ]);
    }
} catch (PDOException $exception) {
    if ($exception->getCode() === '23000') {
        redirect('/pages/perfil.php?msg=username_taken');
    }
    error_log('Error al actualizar perfil: ' . $exception->getMessage());
    redirect('/pages/perfil.php?msg=server_error');
}

// Mantener la sesión al día con el nuevo nombre de usuario
$_SESSION['username'] = $username;

redirect('/pages/perfil.php?msg=updated');