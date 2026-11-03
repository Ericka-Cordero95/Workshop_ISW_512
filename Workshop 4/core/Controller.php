<?php
// Clase base de todos los controladores
abstract class Controller
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::conectar();
    }

    // Muestra una vista dentro del diseño común (encabezado, menú opcional y pie)
    protected function vista($vista, array $datos = [])
    {
        $datos['alerta'] = Mensajes::desdeUrl();
        extract($datos);

        require BASE_PATH . '/views/layout/header.php';
        if (!empty($mostrarMenu)) {
            require BASE_PATH . '/views/layout/navbar.php';
        }
        require BASE_PATH . '/views/' . $vista . '.php';
        require BASE_PATH . '/views/layout/footer.php';
    }

    // Redirige a otra dirección y detiene el script
    protected function redirigir($url)
    {
        header('Location: ' . $url);
        exit;
    }

    // Indica si la petición actual es POST
    protected function esPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    // Protege una acción: si no hay sesión válida, vuelve al login
    protected function requerirLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirigir('/index.php?c=auth&a=mostrarLogin&msg=no_session');
        }
    }
}