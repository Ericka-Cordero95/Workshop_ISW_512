<?php
session_start();

// Si ya hay una sesión activa, redirige a perfil.
if (isset($_SESSION['user_id'])) {
    header('Location: /pages/perfil.php');
    exit;
}

//Los mensajes ahora vienen de un arreglo, y se elige uno según ?msg= en la URL
// Mensajes que se pueden mostrar según el valor de ?msg= en la URL
$alertas = [
    'invalid_input'       => ['danger',  'Ingrese un usuario y una contraseña válidos.'],
    'invalid_credentials' => ['danger',  'Credenciales inválidas'],
    'server_error'        => ['danger',  'Ocurrió un error en el servidor. Intente de nuevo.'],
    'no_session'          => ['warning', 'Debe iniciar sesión para acceder al perfil.'],
    'registered'          => ['success', 'Usuario registrado correctamente. Ya puede iniciar sesión.'],
    'logout'              => ['info',    'Sesión cerrada correctamente.'],
];
$clave = $_GET['msg'] ?? '';
$alerta = (is_string($clave) && isset($alertas[$clave])) ? $alertas[$clave] : null;

$pageTitle = 'Login';
//para que las rutas funcionen sin importar desde dónde se ejecute el archivo.
require_once __DIR__ . '/pages/common/header.php';
?>
<body>
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
              <div class="alert alert-<?php echo $alerta[0]; ?>"><?php echo $alerta[1]; ?></div>
            <?php endif; ?>
            <form action="/actions/login.php" method="POST">
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
            <p class="text-center mt-3 mb-0">¿No tiene cuenta? <a href="/pages/registro.php">Regístrese aquí</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php require_once __DIR__ . '/pages/common/footer.php'; ?>