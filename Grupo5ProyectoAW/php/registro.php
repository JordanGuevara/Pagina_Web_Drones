<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$password = md5($_POST['password']);
$repassword = md5($_POST['repassword']);

$response = [];

if ($password !== $repassword) {
    $response['status'] = 'error';
    $response['message'] = 'Las contraseñas no coinciden. Por favor, inténtalo de nuevo.';
    echo json_encode($response);
    exit;
}

$sql = "SELECT * FROM usuario WHERE correo = :correo LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['correo' => $correo]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $response['status'] = 'error';
    $response['message'] = 'El correo ya está registrado. Por favor, inicia sesión.';
    echo json_encode($response);
    exit;
}

$sql = "INSERT INTO usuario (nombre, correo, password) VALUES (:nombre, :correo, :password)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'nombre' => $nombre,
    'correo' => $correo,
    'password' => $password
]);

$response['status'] = 'success';
$response['message'] = 'Registro exitoso. Ahora puedes iniciar sesión.';
echo json_encode($response);
?>
