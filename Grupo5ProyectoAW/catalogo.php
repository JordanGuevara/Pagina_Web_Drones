<?php
session_start();

// Configuración de la base de datos
$host = '4.203.136.126';
$dbname = 'backupPrueba';
$username = 'postgres';
$password = 'Edison23.';

try {
    $dsn = "pgsql:host=$host;port=5432;dbname=$dbname;sslmode=disable";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Función para obtener todos los productos
function getAllProducts($pdo) {
    $stmt = $pdo->query("SELECT * FROM products");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener un producto por ID
function getProductById($pdo, $productId) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Inicializar el carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Manejo de acciones del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $productId = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        header("Location: carrito.php");
        exit();
    }
}

// Obtener productos del carrito
function getCartProducts($pdo) {
    $cartProducts = [];
    if (!empty($_SESSION['cart'])) {
        $ids = implode(',', array_keys($_SESSION['cart']));
        $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $product) {
            $productId = $product['id'];
            $product['quantity'] = $_SESSION['cart'][$productId];
            $product['total_price'] = $product['price'] * $product['quantity'];
            $cartProducts[] = $product;
        }
    }
    return $cartProducts;
}

// Obtener todos los productos para mostrar en el catálogo
$allProducts = getAllProducts($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Catálogo de Productos</h1>
        <div class="row">
            <?php foreach ($allProducts as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="number" name="quantity" value="1" min="1" class="form-control mb-2">
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Agregar al Carrito</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="carrito.php" class="btn btn-success">Ver Carrito</a>
    </div>
</body>
</html>
