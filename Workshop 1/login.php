<?php
//Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "workshop1");
if ($conn->connect_error) {
    die("Error de conexión");
}

//Datos que vienen del formulario de index.php
$username = $_POST['username'];
$password = $_POST['password'];

//Buscar el usuario en la tabla
$stmt = $conn->prepare("SELECT password FROM usuarios WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

//Verificar usuario y contraseña
if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        header("Location: bienvenido.php");
        exit;
    }
}

//Usuario no existe o contraseña incorrecta.
header("Location: index.php?error=1");
exit;