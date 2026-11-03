  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            <h4>Registro de usuario</h4>
          </div>
          <div class="card-body">
            <?php if ($alerta): ?>
              <div class="alert alert-<?php echo e($alerta[0]); ?>"><?php echo e($alerta[1]); ?></div>
            <?php endif; ?>
            <form action="/index.php?c=auth&a=registro" method="POST">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" minlength="3" maxlength="50" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" minlength="6" required>
              </div>
              <div class="form-group">
                <label for="password_confirm">Confirmar password</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="6" required>
              </div>
              <div class="form-group">
                <label for="provincia_id">Provincia</label>
                <select class="form-control" id="provincia_id" name="provincia_id" required>
                  <option value="">-- Seleccione una provincia --</option>
                  <?php foreach ($provincias as $provincia): ?>
                    <option value="<?php echo (int) $provincia['id']; ?>">
                      <?php echo e($provincia['nombre']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
            </form>
            <p class="text-center mt-3 mb-0">¿Ya tiene cuenta? <a href="/index.php?c=auth&a=mostrarLogin">Inicie sesión</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>