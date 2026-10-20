<?php
session_start();
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php';

// Sin sesión válida no se puede ver esta página
//revisa si existe, si no te manda al login y no ejecuta el resto.
require_login();

try {
    // Datos del usuario de la sesión, con el nombre de su provincia
    $statement = $conn->prepare(
        'SELECT u.id, u.username, u.provincia_id, p.nombre AS provincia
         FROM usuarios u
         LEFT JOIN provincias p ON p.id = u.provincia_id
         WHERE u.id = :id'
    );
    $statement->execute(['id' => $_SESSION['user_id']]);
    $usuario = $statement->fetch();

    // Provincias para el dropdown
    $provincias = $conn->query('SELECT id, nombre FROM provincias ORDER BY id')->fetchAll();
} catch (PDOException $exception) {
    error_log('Error al cargar el perfil: ' . $exception->getMessage());
    die('No se pudo cargar el perfil.');
}

// Si la sesión apunta a un usuario que ya no existe, se cierra
if ($usuario === false) {
    session_unset();
    session_destroy();
    redirect('/index.php?msg=no_session');
}

$alertas = [
    'updated'           => ['success', 'Perfil actualizado correctamente.'],
    'invalid_input'     => ['danger',  'Complete los campos obligatorios.'],
    'invalid_username'  => ['danger',  'El usuario debe tener entre 3 y 50 caracteres.'],
    'password_short'    => ['danger',  'La nueva contraseña debe tener al menos 6 caracteres.'],
    'password_mismatch' => ['danger',  'Las contraseñas no coinciden.'],
    'invalid_province'  => ['danger',  'Seleccione una provincia válida.'],
    'username_taken'    => ['danger',  'Ese nombre de usuario ya está en uso.'],
    'server_error'      => ['danger',  'Ocurrió un error en el servidor. Intente de nuevo.'],
];
$clave = $_GET['msg'] ?? '';
$alerta = (is_string($clave) && isset($alertas[$clave])) ? $alertas[$clave] : null;

$pageTitle = 'Perfil';
$paginaActiva = 'perfil';
require_once __DIR__ . '/common/header.php';
?>
<body>
    <?php require_once __DIR__ . '/common/navbar.php'; ?>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            <h4>Perfil</h4>
          </div>
          <div class="card-body">
            <!-- Datos tomados de la sesión -->
            <p class="text-center">
              Sesión activa como <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
              (id <?php echo (int) $_SESSION['user_id']; ?>)
            </p>
            <p class="text-center text-muted">
              Provincia actual: <?php echo $usuario['provincia'] !== null ? htmlspecialchars($usuario['provincia']) : 'sin asignar'; ?>
            </p>

            <?php if ($alerta): ?>
              <div class="alert alert-<?php echo $alerta[0]; ?>"><?php echo $alerta[1]; ?></div>
            <?php endif; ?>

            <!-- Actualizar datos -->
            <form action="/actions/actualizar_perfil.php" method="POST">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                       value="<?php echo htmlspecialchars($usuario['username']); ?>" minlength="3" maxlength="50" required>
              </div>
              <div class="form-group">
                <label for="provincia_id">Provincia</label>
                <select class="form-control" id="provincia_id" name="provincia_id" required>
                  <option value="">-- Seleccione una provincia --</option>
                  <?php foreach ($provincias as $provincia): ?>
                    <option value="<?php echo (int) $provincia['id']; ?>"
                      <?php echo ((int) $provincia['id'] === (int) $usuario['provincia_id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($provincia['nombre']); ?>
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
<?php require_once __DIR__ . '/common/footer.php'; ?>