  <div class="container mt-5">
    <h1 class="text-center">Welcome to My Website</h1>
    <p class="text-center">This is a simple homepage created with PHP and Bootstrap.</p>
  </div>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            <h4>Login</h4>
          </div>
          <div class="card-body">
            <?php if ($alerta): ?>
              <div class="alert alert-<?php echo e($alerta[0]); ?>"><?php echo e($alerta[1]); ?></div>
            <?php endif; ?>
            <form action="/index.php?c=auth&a=login" method="POST">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <p class="text-center mt-3 mb-0">¿No tiene cuenta? <a href="/index.php?c=auth&a=mostrarRegistro">Regístrese aquí</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>