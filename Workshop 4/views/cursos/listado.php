  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Cursos</h2>
      <a href="/index.php?c=cursos&a=formulario" class="btn btn-primary">Crear Curso</a>
    </div>

    <?php if ($alerta): ?>
      <div class="alert alert-<?php echo e($alerta[0]); ?>" role="alert"><?php echo e($alerta[1]); ?></div>
    <?php endif; ?>

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
            <td><?php echo e($curso['nombre']); ?></td>
            <td><?php echo e($curso['codigo']); ?></td>
            <td><?php echo (int) $curso['carrera_id']; ?></td>
            <td><?php echo (int) $curso['creditos']; ?></td>
            <td><?php echo e($curso['descripcion']); ?></td>
            <td class="text-nowrap">
              <a href="/index.php?c=cursos&a=formulario&id=<?php echo (int) $curso['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
              <!-- Eliminar se envía por POST; el id va en un campo oculto -->
              <form action="/index.php?c=cursos&a=eliminar" method="POST" class="d-inline"
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