<?php
session_start();
require_once __DIR__ . '/../utils/database.php';

// Un usuario con sesión activa no necesita registrarse
if (isset($_SESSION['user_id'])) {
    header('Location: /pages/perfil.php');
    exit;
}

// Provincias para llenar el dropdown
try {
    $provincias = $conn->query('SELECT id, nombre FROM provincias ORDER BY id')->fetchAll();
} catch (PDOException $exception) {
    error_log('Error al cargar provincias: ' . $exception->getMessage());
    die('No se pudieron cargar las provincias.');
}

$alertas = [
    'invalid_input'     => 'Complete todos los campos.',
    'invalid_username'  => 'El usuario debe tener entre 3 y 50 caracteres.',
    'password_short'    => 'La contraseña debe tener al menos 6 caracteres.',
    'password_mismatch' => 'Las contraseñas no coinciden.',
    'invalid_province'  => 'Seleccione una provincia válida.',
    'username_taken'    => 'Ese nombre de usuario ya existe. Elija otro.',
    'server_error'      => 'Ocurrió un error en el servidor. Intente de nuevo.',
];
$clave = $_GET['msg'] ?? '';
$alerta = (is_string($clave) && isset($alertas[$clave])) ? $alertas[$clave] : null;

$pageTitle = 'Registro';
require_once __DIR__ . '/common/header.php';
?>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            <h4>Registro de usuario</h4>
          </div>
          <div class="card-body">
            <?php if ($alerta): ?>
              <div class="alert alert-danger"><?php echo $alerta; ?></div>
            <?php endif; ?>
            <form action="/actions/registro.php" method="POST">
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
                <!-- trae las provincias de la tabla -->
                <select class="form-control" id="provincia_id" name="provincia_id" required>
                  <option value="">-- Seleccione una provincia --</option>
                  <!--genera un option por cada una. Si se agrega una provincia nueva, se verá. -->
                  <?php foreach ($provincias as $provincia): ?> 
                    <option value="<?php echo (int) $provincia['id']; ?>">
                        <!--Texto seguro, para evitar codigo HTML O JAVASCRIPT.-->
                      <?php echo htmlspecialchars($provincia['nombre']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
            </form>
            <p class="text-center mt-3 mb-0">¿Ya tiene cuenta? <a href="/index.php">Inicie sesión</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php require_once __DIR__ . '/common/footer.php'; ?>