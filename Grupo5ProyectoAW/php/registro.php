<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$password = $_POST['password'];
$repassword = $_POST['repassword'];

if ($password !== $repassword) {
    echo "<p style='color:red;'>Las contraseñas no coinciden. Por favor, inténtalo de nuevo.</p>";
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "SELECT * FROM usuario WHERE correo = :correo LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['correo' => $correo]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "<p style='color:red;'>El correo ya está registrado. Por favor, inicia sesión.</p>";
    exit;
}

$sql = "INSERT INTO usuario (nombre, correo, password) VALUES (:nombre, :correo, :password)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nombre' => $nombre,
    'correo' => $correo,
    'password' => $hashed_password
]);

echo "<p style='color:green;'>Registro exitoso. Ahora puedes iniciar sesión.</p>";
?>
