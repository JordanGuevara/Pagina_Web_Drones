<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$password = md5($_POST['password']);
$repassword = md5($_POST['repassword']);

if ($password !== $repassword) {
    echo "<p style='color:red;'>Las contraseñas no coinciden. Por favor, inténtalo de nuevo.</p>";
    exit;
}

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
    'password' => $password
]);

echo "<p style='color:green;'>Registro exitoso. Ahora puedes iniciar sesión.</p>";
?>

