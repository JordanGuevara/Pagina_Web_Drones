<?php
// Iniciar la sesión
session_start();

// Configuración de la base de datos
$host = '4.203.136.126';
$dbname = 'backupPrueba';
$username = 'postgres';
$password = 'Edison23.'; 

// Configuración de la conexión con PDO
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

    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantities'] as $productId => $quantity) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId] = $quantity;
            }
        }
        header("Location: carrito.php");
        exit();
    }

    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
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

// Calcular totales
function calculateTotals($cartProducts) {
    $subtotal = 0;
    foreach ($cartProducts as $product) {
        $subtotal += $product['total_price'];
    }
    $tax = $subtotal * 0.05; // 5% de impuestos
    $shipping = ($subtotal > 0) ? 15 : 0; // Envío fijo de $15 si hay productos
    $total = $subtotal + $tax + $shipping;

    return [
        'subtotal' => $subtotal,
        'tax' => $tax,
        'shipping' => $shipping,
        'total' => $total
    ];
}

// Obtener todos los productos para mostrar en el catálogo
$allProducts = getAllProducts($pdo);

// Obtener productos en el carrito
$cartProducts = getCartProducts($pdo);

// Calcular totales
$totals = calculateTotals($cartProducts);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <style>
        body {
            padding-top: 70px;
            background-color: #f8f9fa;
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .cart-table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .footer {
            background-color: #343a40;
            color: #fff;
            padding: 30px 0;
            margin-top: 50px;
        }
        .footer h4 {
            margin-bottom: 20px;
        }
        .footer a {
            color: #fff;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Beast Complay</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"             aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Catálogo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Sobre Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="carrito.php">Carrito 
                        <span class="badge badge-pill badge-light">
                            <?php echo count($_SESSION['cart']); ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container">
        <h1 class="mb-4">Catálogo de Productos</h1>
        <div class="row">
            <?php foreach ($allProducts as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="mt-auto">
                                <h5>$<?php echo number_format($product['price'], 2); ?></h5>
                                <form method="POST" class="d-inline-block">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control mb-2" style="width: 80px;">
                                    <button type="submit" name="add_to_cart" class="btn btn-primary btn-block">Agregar al Carrito</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2 class="mt-5">Tu Carrito</h2>
        <?php if (empty($cartProducts)): ?>
            <p>El carrito está vacío.</p>
        <?php else: ?>
            <form method="POST">
                <table class="table table-bordered cart-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartProducts as $product): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                <td>
                                    <input type="number" name="quantities[<?php echo $product['id']; ?>]" value="<?php echo $product['quantity']; ?>" min="1" class="form-control" style="width: 80px;">
                                </td>
                                <td>$<?php echo number_format($product['total_price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-right mb-3">
                    <h5>Subtotal: $<?php echo number_format($totals['subtotal'], 2); ?></h5>
                    <h5>Impuestos (5%): $<?php echo number_format($totals['tax'], 2); ?></h5>
                    <h5>Envío: $<?php echo number_format($totals['shipping'], 2); ?></h5>
                    <h4>Total: $<?php echo number_format($totals['total'], 2); ?></h4>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" name="update_cart" class="btn btn-secondary">Actualizar Carrito</button>
                    <button type="submit" name="clear_cart" class="btn btn-danger">Vaciar Carrito</button>
                    <a href="#" class="btn btn-success">Proceder al Pago</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- Pie de Página -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4>Beast Complay</h4>
                    <p>Tu tienda de confianza para productos tecnológicos de alta calidad.</p>
                </div>
                <div class="col-md-4">
                    <h4>Síguenos</h4>
                    <p><a href="#">Facebook</a></p>
                    <p><a href="#">Instagram</a></p>
                    <p><a href="#">YouTube</a></p>
                </div>
                <div class="col-md-4">
                    <h4>Contacto</h4>
                    <p>Email: contacto@beastcomplay.com</p>
                    <p>Teléfono: +1 234 567 890</p>
                    <p>Dirección: 123 Calle Principal, Ciudad, País</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>
