<?php
//borra la cookie del navegador, Solo funciona por POST,
session_start();

// Solo se permite cerrar sesión desde el botón (método POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit;
}

// Vaciar los datos de la sesión y borrar la cookie de sesión del navegador
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

header('Location: /index.php?msg=logout');
exit;