<?php
require_once BASE_PATH . '/models/Curso.php';
require_once BASE_PATH . '/models/Carrera.php';

// Controlador de cursos: listar, crear, editar y eliminar
class CursosController extends Controller
{
    // Muestra el listado de cursos
    public function listar()
    {
        $this->requerirLogin();

        try {
            $cursos = (new Curso($this->conn))->todos();
        } catch (PDOException $exception) {
            error_log('Error al listar cursos: ' . $exception->getMessage());
            die('No se pudieron cargar los cursos.');
        }

        $this->vista('cursos/listado', [
            'tituloPagina' => 'Cursos',
            'paginaActiva' => 'cursos',
            'mostrarMenu'  => true,
            'cursos'       => $cursos,
        ]);
    }

    // Muestra el formulario: vacío para crear, o cargado si llega ?id= para editar
    public function formulario()
    {
        $this->requerirLogin();

        $listado = '/index.php?c=cursos&a=listar&msg=';

        // Si llega ?id= se edita ese curso; si no, se crea uno nuevo
        $id = null;
        if (isset($_GET['id'])) {
            $id = is_string($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : false;
            if ($id === false) {
                $this->redirigir($listado . 'course_not_found');
            }
        }

        $curso = null;
        try {
            if ($id !== null) {
                $curso = (new Curso($this->conn))->buscar($id);
                if ($curso === false) {
                    $this->redirigir($listado . 'course_not_found');
                }
            }
            $carreras = (new Carrera($this->conn))->todas();
        } catch (PDOException $exception) {
            error_log('Error al cargar el formulario de curso: ' . $exception->getMessage());
            die('No se pudo cargar el formulario.');
        }

        $editando = ($curso !== null);

        $this->vista('cursos/formulario', [
            'tituloPagina' => $editando ? 'Editar Curso' : 'Crear Curso',
            'paginaActiva' => 'cursos',
            'mostrarMenu'  => true,
            'curso'        => $curso,
            'editando'     => $editando,
            'carreras'     => $carreras,
        ]);
    }

    // Procesa el formulario: crea o actualiza según el campo oculto id
    public function guardar()
    {
        // La acción también se protege: sin sesión no se puede guardar nada
        $this->requerirLogin();

        // Esta acción solo se alcanza desde el formulario (POST)
        if (!$this->esPost()) {
            $this->redirigir('/index.php?c=cursos&a=listar');
        }

        $id          = $_POST['id'] ?? '';
        $nombre      = $_POST['nombre'] ?? null;
        $codigo      = $_POST['codigo'] ?? null;
        $carreraId   = $_POST['carrera_id'] ?? null;
        $creditos    = $_POST['creditos'] ?? null;
        $descripcion = $_POST['descripcion'] ?? null;

        if (
            !is_string($id) || !is_string($nombre) || !is_string($codigo)
            || !is_string($carreraId) || !is_string($creditos) || !is_string($descripcion)
        ) {
            $this->redirigir('/index.php?c=cursos&a=formulario&msg=invalid_input');
        }

        // Con id es una edición; sin id es un curso nuevo
        $editando = ($id !== '');
        if ($editando) {
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if ($id === false) {
                $this->redirigir('/index.php?c=cursos&a=listar&msg=course_not_found');
            }
        }

        // Adónde volver si hay un error (el formulario de creación o el de edición)
        $volver = $editando
            ? '/index.php?c=cursos&a=formulario&id=' . $id . '&msg='
            : '/index.php?c=cursos&a=formulario&msg=';

        // Validación en el servidor
        $nombre = trim($nombre);
        if (strlen($nombre) < 3 || strlen($nombre) > 100) {
            $this->redirigir($volver . 'invalid_name');
        }

        $codigo = trim($codigo);
        if (!preg_match('/^[A-Za-z0-9-]{2,20}$/', $codigo)) {
            $this->redirigir($volver . 'invalid_code');
        }

        $carreraId = filter_var($carreraId, FILTER_VALIDATE_INT);
        if ($carreraId === false) {
            $this->redirigir($volver . 'invalid_career');
        }

        $creditos = filter_var($creditos, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]]);
        if ($creditos === false) {
            $this->redirigir($volver . 'invalid_credits');
        }

        $descripcion = trim($descripcion);
        if ($descripcion === '' || strlen($descripcion) > 1000) {
            $this->redirigir($volver . 'invalid_description');
        }

        try {
            $cursos = new Curso($this->conn);

            // La carrera enviada debe existir en la tabla carreras
            if ((new Carrera($this->conn))->buscar($carreraId) === false) {
                $this->redirigir($volver . 'invalid_career');
            }

            if ($editando) {
                // El curso a editar debe existir
                if ($cursos->buscar($id) === false) {
                    $this->redirigir('/index.php?c=cursos&a=listar&msg=course_not_found');
                }
                $cursos->actualizar($id, $nombre, $codigo, $carreraId, $creditos, $descripcion);
                $mensaje = 'course_updated';
            } else {
                $cursos->crear($nombre, $codigo, $carreraId, $creditos, $descripcion);
                $mensaje = 'course_created';
            }
        } catch (PDOException $exception) {
            // 23000 = violación de una restricción; aquí, el código UNIQUE ya existe
            if ($exception->getCode() === '23000') {
                $this->redirigir($volver . 'code_taken');
            }
            error_log('Error al guardar el curso: ' . $exception->getMessage());
            $this->redirigir($volver . 'server_error');
        }

        // Al terminar, se vuelve al listado de cursos
        $this->redirigir('/index.php?c=cursos&a=listar&msg=' . $mensaje);
    }

    // Elimina un curso (solo por POST)
    public function eliminar()
    {
        // La acción también se protege: sin sesión no se puede eliminar nada
        $this->requerirLogin();

        // Eliminar solo se permite por POST (nunca por un enlace)
        if (!$this->esPost()) {
            $this->redirigir('/index.php?c=cursos&a=listar');
        }

        $listado = '/index.php?c=cursos&a=listar&msg=';

        $id = $_POST['id'] ?? null;
        $id = is_string($id) ? filter_var($id, FILTER_VALIDATE_INT) : false;
        if ($id === false) {
            $this->redirigir($listado . 'course_not_found');
        }

        try {
            $filas = (new Curso($this->conn))->eliminar($id);
        } catch (PDOException $exception) {
            error_log('Error al eliminar el curso: ' . $exception->getMessage());
            $this->redirigir($listado . 'server_error');
        }

        // Si no se borró ninguna fila, el curso no existía
        if ($filas === 0) {
            $this->redirigir($listado . 'course_not_found');
        }

        $this->redirigir($listado . 'course_deleted');
    }
}