<?php
require_once BASE_PATH . '/models/Usuario.php';
require_once BASE_PATH . '/models/Provincia.php';

// Controlador de autenticación: login, registro y logout
class AuthController extends Controller
{
    // Muestra el formulario de login
    public function mostrarLogin()
    {
        // Si ya hay una sesión activa, no tiene sentido mostrar el login
        if (isset($_SESSION['user_id'])) {
            $this->redirigir('/index.php?c=perfil&a=ver');
        }

        $this->vista('auth/login', ['tituloPagina' => 'Login']);
    }

    // Procesa el formulario de login
    public function login()
    {
        if (!$this->esPost()) {
            $this->redirigir('/index.php?c=auth&a=mostrarLogin');
        }

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
            $this->redirigir('/index.php?c=auth&a=mostrarLogin&msg=invalid_input');
        }

        try {
            $usuarios = new Usuario($this->conn);
            $user = $usuarios->buscarPorUsername(trim($username));
        } catch (PDOException $exception) {
            error_log('Error de autenticación: ' . $exception->getMessage());
            $this->redirigir('/index.php?c=auth&a=mostrarLogin&msg=server_error');
        }

        // Usuario inexistente o contraseña incorrecta: el mismo mensaje en ambos casos
        if ($user === false || !password_verify($password, $user['password'])) {
            $this->redirigir('/index.php?c=auth&a=mostrarLogin&msg=invalid_credentials');
        }

        // Credenciales correctas: se crea la sesión
        session_regenerate_id(true); // nuevo id de sesión (previene fijación de sesión)
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];

        $this->redirigir('/index.php?c=perfil&a=ver');
    }

    // Cierra la sesión (solo por POST)
    public function logout()
    {
        if (!$this->esPost()) {
            $this->redirigir('/index.php?c=auth&a=mostrarLogin');
        }

        // Vaciar los datos de la sesión y borrar la cookie de sesión del navegador
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();

        $this->redirigir('/index.php?c=auth&a=mostrarLogin&msg=logout');
    }

    // Muestra el formulario de registro
    public function mostrarRegistro()
    {
        // Un usuario con sesión activa no necesita registrarse
        if (isset($_SESSION['user_id'])) {
            $this->redirigir('/index.php?c=perfil&a=ver');
        }

        try {
            $provincias = (new Provincia($this->conn))->todas();
        } catch (PDOException $exception) {
            error_log('Error al cargar provincias: ' . $exception->getMessage());
            die('No se pudieron cargar las provincias.');
        }

        $this->vista('auth/registro', [
            'tituloPagina' => 'Registro',
            'provincias'   => $provincias,
        ]);
    }

    // Procesa el formulario de registro
    public function registro()
    {
        if (!$this->esPost()) {
            $this->redirigir('/index.php?c=auth&a=mostrarRegistro');
        }

        $volver = '/index.php?c=auth&a=mostrarRegistro&msg=';

        $username    = $_POST['username'] ?? null;
        $password    = $_POST['password'] ?? null;
        $confirm     = $_POST['password_confirm'] ?? null;
        $provinciaId = $_POST['provincia_id'] ?? null;

        if (!is_string($username) || !is_string($password) || !is_string($confirm) || !is_string($provinciaId)) {
            $this->redirigir($volver . 'invalid_input');
        }

        $username = trim($username);

        if (strlen($username) < 3 || strlen($username) > 50) {
            $this->redirigir($volver . 'invalid_username');
        }
        if (strlen($password) < 6) {
            $this->redirigir($volver . 'password_short');
        }
        if ($password !== $confirm) {
            $this->redirigir($volver . 'password_mismatch');
        }

        $provinciaId = filter_var($provinciaId, FILTER_VALIDATE_INT);
        if ($provinciaId === false) {
            $this->redirigir($volver . 'invalid_province');
        }

        try {
            // La provincia enviada debe existir en la tabla provincias
            if ((new Provincia($this->conn))->buscar($provinciaId) === false) {
                $this->redirigir($volver . 'invalid_province');
            }

            // Guardar el usuario con la contraseña cifrada y la provincia asociada
            (new Usuario($this->conn))->crear($username, password_hash($password, PASSWORD_DEFAULT), $provinciaId);
        } catch (PDOException $exception) {
            // 23000 = violación de una restricción; aquí, el username UNIQUE ya existe
            if ($exception->getCode() === '23000') {
                $this->redirigir($volver . 'username_taken');
            }
            error_log('Error al registrar usuario: ' . $exception->getMessage());
            $this->redirigir($volver . 'server_error');
        }

        $this->redirigir('/index.php?c=auth&a=mostrarLogin&msg=registered');
    }
}