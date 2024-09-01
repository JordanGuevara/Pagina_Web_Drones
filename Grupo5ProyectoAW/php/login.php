<?php
include 'conexion.php';

$identifier = $_POST['identifier'];
$password = $_POST['password'];
$sql = "SELECT * FROM usuario WHERE correo = :identifier OR nombre = :identifier LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['identifier' => $identifier]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    header('Location: ../index.html');
    exit;
} else {
    echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
}
?>

