<?php
// Redirige a otra página y detiene el script
function redirect($url)//evita repetir header ... exit en cada validación
{
    header('Location: ' . $url);
    exit;
}

// Protege una pagina o accion: si no hay sesion valida y vuelve al login
function require_login()
{
    if (!isset($_SESSION['user_id'])) {
        redirect('/index.php?msg=no_session');
    }
}