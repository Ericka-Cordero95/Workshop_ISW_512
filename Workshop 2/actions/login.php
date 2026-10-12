<?php
session_start();//activa la el sistema de sesiones.
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';

//consulta preparada.
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

// Validar que lleguen textos, no vacíos y con largo razonable
if (
    !is_string($username)
    || !is_string($password)
    || trim($username) === ''
    || strlen($username) > 50
    || $password === ''
) {
    redirect('/index.php?msg=invalid_input');
}

try {
    // Buscar el usuario (consulta preparada, evita inyección SQL)
    $statement = $conn->prepare(
        'SELECT id, username, password FROM usuarios WHERE username = :username LIMIT 1'
    );
    $statement->execute(['username' => trim($username)]);
    $user = $statement->fetch();
} catch (PDOException $exception) {
    error_log('Error de autenticación: ' . $exception->getMessage());
    redirect('/index.php?msg=server_error');
}

// Usuario inexistente o contraseña incorrecta: el mismo mensaje en ambos casos
if ($user === false || !password_verify($password, $user['password'])) {
    redirect('/index.php?msg=invalid_credentials');
}

// Credenciales correctas: se crea la sesión
session_regenerate_id(true); // nuevo id de sesión (previene fijación de sesión)
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['username'] = $user['username'];

redirect('/pages/perfil.php');