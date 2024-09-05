<?php
include 'conexion.php';

$identifier = $_POST['identifier'];
$password = md5($_POST['password']);
$sql = "SELECT * FROM usuario WHERE correo = :identifier OR nombre = :identifier LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['identifier' => $identifier]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user['password'] === $password) {
    header('Location: ../index.html');
    exit;
} else {
    echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
}
?>

