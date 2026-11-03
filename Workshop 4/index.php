<?php
// CONTROLADOR FRONTAL: todas las peticiones entran por este archivo.
// Lee ?c= (controlador) y ?a= (acción) y llama al método correspondiente.
session_start();
define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/core/helpers.php';
require_once BASE_PATH . '/core/Mensajes.php';
require_once BASE_PATH . '/models/Database.php';
require_once BASE_PATH . '/core/Controller.php';

// Lista blanca de rutas: controlador => [clase, acciones permitidas]
$rutas = [
    'auth'   => ['AuthController',   ['mostrarLogin', 'login', 'logout', 'mostrarRegistro', 'registro']],
    'perfil' => ['PerfilController', ['ver', 'actualizar']],
    'cursos' => ['CursosController', ['listar', 'formulario', 'guardar', 'eliminar']],
];

$c = $_GET['c'] ?? '';
$a = $_GET['a'] ?? '';

// Sin ruta (por ejemplo, al abrir el sitio): ir al perfil o al login según la sesión
if ($c === '' && $a === '') {
    $destino = isset($_SESSION['user_id'])
        ? '/index.php?c=perfil&a=ver'
        : '/index.php?c=auth&a=mostrarLogin';
    header('Location: ' . $destino);
    exit;
}

// Solo se aceptan controladores y acciones de la lista blanca
if (!is_string($c) || !is_string($a) || !isset($rutas[$c]) || !in_array($a, $rutas[$c][1], true)) {
    http_response_code(404);
    exit('Página no encontrada.');
}

$clase = $rutas[$c][0];
$archivo = BASE_PATH . '/controllers/' . $clase . '.php';
if (!file_exists($archivo)) {
    http_response_code(404);
    exit('Página no encontrada.');
}

require_once $archivo;
$controlador = new $clase();
$controlador->$a();