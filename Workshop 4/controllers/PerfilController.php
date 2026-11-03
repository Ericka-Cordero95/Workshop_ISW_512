<?php
require_once BASE_PATH . '/models/Usuario.php';
require_once BASE_PATH . '/models/Provincia.php';

// Controlador del perfil del usuario con sesión
class PerfilController extends Controller
{
    // Muestra el perfil con el formulario de actualización
    public function ver()
    {
        // Sin sesión válida no se puede ver esta página
        $this->requerirLogin();

        try {
            // Datos del usuario de la sesión, con el nombre de su provincia
            $usuario = (new Usuario($this->conn))->buscarPorId($_SESSION['user_id']);
            $provincias = (new Provincia($this->conn))->todas();
        } catch (PDOException $exception) {
            error_log('Error al cargar el perfil: ' . $exception->getMessage());
            die('No se pudo cargar el perfil.');
        }

        // Si la sesión apunta a un usuario que ya no existe, se cierra
        if ($usuario === false) {
            session_unset();
            session_destroy();
            $this->redirigir('/index.php?c=auth&a=mostrarLogin&msg=no_session');
        }

        $this->vista('perfil/ver', [
            'tituloPagina'  => 'Perfil',
            'paginaActiva'  => 'perfil',
            'mostrarMenu'   => true,
            'usuario'       => $usuario,
            'provincias'    => $provincias,
        ]);
    }

    // Procesa el formulario de actualización del perfil
    public function actualizar()
    {
        // La acción también se protege: sin sesión no se puede modificar nada
        $this->requerirLogin();

        if (!$this->esPost()) {
            $this->redirigir('/index.php?c=perfil&a=ver');
        }

        $volver = '/index.php?c=perfil&a=ver&msg=';

        $username    = $_POST['username'] ?? null;
        $provinciaId = $_POST['provincia_id'] ?? null;
        $password    = $_POST['password'] ?? '';
        $confirm     = $_POST['password_confirm'] ?? '';

        if (!is_string($username) || !is_string($provinciaId) || !is_string($password) || !is_string($confirm)) {
            $this->redirigir($volver . 'invalid_input');
        }

        $username = trim($username);

        if (strlen($username) < 3 || strlen($username) > 50) {
            $this->redirigir($volver . 'invalid_username');
        }

        $provinciaId = filter_var($provinciaId, FILTER_VALIDATE_INT);
        if ($provinciaId === false) {
            $this->redirigir($volver . 'invalid_province');
        }

        // La contraseña es opcional: solo se valida y se cambia si el usuario escribió una nueva
        $cambiaPassword = ($password !== '' || $confirm !== '');
        if ($cambiaPassword) {
            if (strlen($password) < 6) {
                $this->redirigir($volver . 'password_short');
            }
            if ($password !== $confirm) {
                $this->redirigir($volver . 'password_mismatch');
            }
        }

        try {
            // La provincia enviada debe existir
            if ((new Provincia($this->conn))->buscar($provinciaId) === false) {
                $this->redirigir($volver . 'invalid_province');
            }

            // El usuario a modificar sale de la SESIÓN, nunca del formulario
            $hash = $cambiaPassword ? password_hash($password, PASSWORD_DEFAULT) : null;
            (new Usuario($this->conn))->actualizar($_SESSION['user_id'], $username, $provinciaId, $hash);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $this->redirigir($volver . 'username_taken');
            }
            error_log('Error al actualizar perfil: ' . $exception->getMessage());
            $this->redirigir($volver . 'server_error');
        }

        // Mantener la sesión al día con el nuevo nombre de usuario
        $_SESSION['username'] = $username;

        $this->redirigir($volver . 'updated');
    }
}