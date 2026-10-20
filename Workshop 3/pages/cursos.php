<?php
session_start();
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';
require_once __DIR__ . '/../utils/messages.php';
require_once __DIR__ . '/../utils/cursos.php';

// Sin sesión válida no se puede ver esta página
require_login();

try {
    $cursos = getCursos($conn);
} catch (PDOException $exception) {
    error_log('Error al cargar los cursos: ' . $exception->getMessage());
    die('No se pudieron cargar los cursos.');
}

$pageTitle = 'Cursos';
$paginaActiva = 'cursos';
require_once __DIR__ . '/common/header.php';
?>
<body>
  <?php require_once __DIR__ . '/common/navbar.php'; ?>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Cursos</h2>
      <a href="/pages/curso_form.php" class="btn btn-primary">Crear Curso</a>
    </div>

    <?php renderAlerta(); ?>

    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>Nombre</th>
          <th>Código de Curso</th>
          <th>Id de la Carrera</th>
          <th>Créditos</th>
          <th>Descripción del Curso</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($cursos) === 0): ?>
          <tr>
            <td colspan="6" class="text-center text-muted">No hay cursos registrados.</td>
          </tr>
        <?php endif; ?>
        <?php foreach ($cursos as $curso): ?>
          <tr>
            <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
            <td><?php echo htmlspecialchars($curso['codigo']); ?></td>
            <td><?php echo (int) $curso['carrera_id']; ?></td>
            <td><?php echo (int) $curso['creditos']; ?></td>
            <td><?php echo htmlspecialchars($curso['descripcion']); ?></td>
            <td class="text-nowrap">
              <a href="/pages/curso_form.php?id=<?php echo (int) $curso['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
              <!-- Eliminar se envía por POST; el id va en un campo oculto -->
              <form action="/actions/curso_eliminar.php" method="POST" class="d-inline"
                    onsubmit="return confirm('¿Seguro que desea eliminar este curso?');">
                <input type="hidden" name="id" value="<?php echo (int) $curso['id']; ?>">
                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php require_once __DIR__ . '/common/footer.php'; ?>