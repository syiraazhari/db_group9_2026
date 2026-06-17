<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$categories = getCategories();
$products = getProductsByCategory();
$cartCount = isLoggedIn() ? getCartCount(getUserId()) : 0;
$featured = array_slice($products, 0, 6);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshMart - Online Grocery Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <span class="logo-icon">🥬</span>
                FreshMart
            </a>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="pages/products.php">Products</a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="pages/cart.php" class="nav-cart">
                        🛒 Cart <span class="cart-badge"><?php echo $cartCount; ?></span>
                    </a></li>
                    <li><a href="pages/orders.php">My Orders</a></li>
                    <li><a href="pages/feedback.php">Feedback</a></li>
                    <li><a href="pages/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="pages/login.php">Login</a></li>
                    <li><a href="pages/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1>Fresh Groceries.<br>Online. On Time.</h1>
            <p>Welcome to our online grocery store!</p>
            <a href="pages/products.php" class="btn-primary">Shop Fresh Today</a>
        </div>
    </section>
</body>
</html>