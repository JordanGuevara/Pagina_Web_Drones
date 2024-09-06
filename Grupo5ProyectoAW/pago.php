<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total = $_POST['total'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Pago con Tarjeta</h1>
        <form action="confirmacion.php" method="POST">
            <div class="form-group">
                <label for="card_number">Número de Tarjeta</label>
                <input type="text" name="card_number" id="card_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="expiration_date">Fecha de Expiración</label>
                <input type="text" name="expiration_date" id="expiration_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" name="cvv" id="cvv" class="form-control" required>
            </div>
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <button type="submit" class="btn btn-success">Confirmar Pago</button>
        </form>
    </div>
</body>
</html>
