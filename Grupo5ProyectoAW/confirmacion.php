<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el pago aquí

    // Vaciar el carrito
    $_SESSION['cart'] = [];

    echo "<h1>¡Gracias por tu compra!</h1>";
    echo "<p>Tu pago ha sido procesado exitosamente.</p>";
    echo "<a href='catalogo.php' class='btn btn-primary'>Volver al Catálogo</a>";
}
?>
