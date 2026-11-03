  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            <h4>Perfil</h4>
          </div>
          <div class="card-body">
            <!-- Datos enviados por el controlador -->
            <p class="text-center">
              Sesión activa como <strong><?php echo e($usuario['username']); ?></strong>
              (id <?php echo (int) $usuario['id']; ?>)
            </p>
            <p class="text-center text-muted">
              Provincia actual: <?php echo $usuario['provincia'] !== null ? e($usuario['provincia']) : 'sin asignar'; ?>
            </p>

            <?php if ($alerta): ?>
              <div class="alert alert-<?php echo e($alerta[0]); ?>"><?php echo e($alerta[1]); ?></div>
            <?php endif; ?>

            <!-- Actualizar datos -->
            <form action="/index.php?c=perfil&a=actualizar" method="POST">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                       value="<?php echo e($usuario['username']); ?>" minlength="3" maxlength="50" required>
              </div>
              <div class="form-group">
                <label for="provincia_id">Provincia</label>
                <select class="form-control" id="provincia_id" name="provincia_id" required>
                  <option value="">-- Seleccione una provincia --</option>
                  <?php foreach ($provincias as $provincia): ?>
                    <option value="<?php echo (int) $provincia['id']; ?>"
                      <?php echo ((int) $provincia['id'] === (int) $usuario['provincia_id']) ? 'selected' : ''; ?>>
                      <?php echo e($provincia['nombre']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <hr>
              <p class="text-muted small">Deje los campos de contraseña vacíos si no desea cambiarla.</p>
              <div class="form-group">
                <label for="password">Nueva contraseña</label>
                <input type="password" class="form-control" id="password" name="password" minlength="6">
              </div>
              <div class="form-group">
                <label for="password_confirm">Confirmar nueva contraseña</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="6">
              </div>
              <button type="submit" class="btn btn-primary btn-block">Guardar cambios</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>