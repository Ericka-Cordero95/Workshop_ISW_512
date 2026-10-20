<?php
session_start();
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';
require_once __DIR__ . '/../utils/messages.php';
require_once __DIR__ . '/../utils/cursos.php';

// Sin sesión válida no se puede ver esta página
require_login();

// Si llega ?id= se edita ese curso; si no, se crea uno nuevo
$id = null;
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($id === false) {
        redirect('/pages/cursos.php?msg=course_not_found');
    }
}

$curso = null;
try {
    if ($id !== null) {
        $curso = findCurso($conn, $id);
        if ($curso === false) {
            redirect('/pages/cursos.php?msg=course_not_found');
        }
    }
    $carreras = getCarreras($conn);
} catch (PDOException $exception) {
    error_log('Error al cargar el formulario de curso: ' . $exception->getMessage());
    die('No se pudo cargar el formulario.');
}

$editando = ($curso !== null);
$pageTitle = $editando ? 'Editar Curso' : 'Crear Curso';
$paginaActiva = 'cursos';
require_once __DIR__ . '/common/header.php';
?>
<body>
  <?php require_once __DIR__ . '/common/navbar.php'; ?>
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header text-center">
            <h4><?php echo $editando ? 'Editar Curso' : 'Crear Curso'; ?></h4>
          </div>
          <div class="card-body">
            <?php renderAlerta(); ?>
            <form action="/actions/curso_guardar.php" method="POST">
              <!-- Si hay id, la acción actualiza; si va vacío, crea un curso nuevo -->
              <input type="hidden" name="id" value="<?php echo $editando ? (int) $curso['id'] : ''; ?>">
              <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" minlength="3" maxlength="100"
                       value="<?php echo htmlspecialchars($curso['nombre'] ?? ''); ?>" required>
              </div>
              <div class="form-group">
                <label for="codigo">Código de Curso</label>
                <input type="text" class="form-control" id="codigo" name="codigo" minlength="2" maxlength="20"
                       pattern="[A-Za-z0-9\-]{2,20}" title="Letras, números y guiones (de 2 a 20 caracteres)"
                       value="<?php echo htmlspecialchars($curso['codigo'] ?? ''); ?>" required>
              </div>
              <div class="form-group">
                <label for="carrera_id">Carrera</label>
                <select class="form-control" id="carrera_id" name="carrera_id" required>
                  <option value="">-- Seleccione una carrera --</option>
                  <?php foreach ($carreras as $carrera): ?>
                    <option value="<?php echo (int) $carrera['id']; ?>"
                      <?php echo ((int) $carrera['id'] === (int) ($curso['carrera_id'] ?? 0)) ? 'selected' : ''; ?>>
                      <?php echo (int) $carrera['id']; ?> - <?php echo htmlspecialchars($carrera['nombre']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="creditos">Créditos</label>
                <input type="number" class="form-control" id="creditos" name="creditos" min="1" max="10"
                       value="<?php echo htmlspecialchars((string) ($curso['creditos'] ?? '')); ?>" required>
              </div>
              <div class="form-group">
                <label for="descripcion">Descripción del Curso</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" maxlength="1000" required><?php echo htmlspecialchars($curso['descripcion'] ?? ''); ?></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Guardar</button>
              <a href="/pages/cursos.php" class="btn btn-secondary">Cancelar</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php require_once __DIR__ . '/common/footer.php'; ?>