<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$category_id = $_GET['category'] ?? null;
$categories = getCategories();
$products = getProductsByCategory($category_id);
$cartCount = isLoggedIn() ? getCartCount(getUserId()) : 0;
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - FreshMart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">
                <span class="logo-icon">🥬</span>
                FreshMart
            </a>
            <ul class="nav-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="products.php" class="active">Products</a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="cart.php" class="nav-cart">
                        🛒 Cart <span class="cart-badge"><?php echo $cartCount; ?></span>
                    </a></li>
                    <li><a href="orders.php">My Orders</a></li>
                    <li><a href="feedback.php">Feedback</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <section class="section" style="padding-top: 2rem;">
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>

        <div class="section-header">
            <h2><?php echo $category_id ? $categories[array_search($category_id, array_column($categories, 'category_id'))]['category_name'] ?? 'Products' : 'All Products'; ?></h2>
            <p>Fresh groceries at your fingertips</p>
        </div>

        <div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; justify-content: center;">
            <a href="products.php" class="btn-outline <?php echo !$category_id ? 'active' : ''; ?>" style="<?php echo !$category_id ? 'background: var(--primary); color: white;' : ''; ?>">All</a>
            <?php foreach ($categories as $cat): ?>
            <a href="products.php?category=<?php echo $cat['category_id']; ?>" class="btn-outline" style="<?php echo $category_id == $cat['category_id'] ? 'background: var(--primary); color: white;' : ''; ?>">
                <?php echo $cat['category_name']; ?>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($products)): ?>
            <div class="empty-state">
                <div class="icon">📦</div>
                <h3>No products found</h3>
                <p>Check back later for new items!</p>
            </div>
        <?php else: ?>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?php echo $product['image_url']; ?>" class="product-img" alt="<?php echo $product['product_name']; ?>">
                <div class="product-info">
                    <span class="cat-label"><?php echo $product['category_name']; ?></span>
                    <h3><?php echo $product['product_name']; ?></h3>
                    <p class="desc"><?php echo $product['description']; ?></p>
                    <div class="price-row">
                        <span class="price">RM <?php echo number_format($product['price'], 2); ?></span>
                        <span class="unit">/<?php echo $product['unit']; ?></span>
                    </div>
                    <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.5rem;">
                        Stock: <?php echo $product['stock_quantity']; ?> <?php echo $product['unit']; ?>
                    </p>
                    <?php if (isLoggedIn()): ?>
                    <?php if ($product['stock_quantity'] > 0): ?>
                    <form class="add-to-cart-form" action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                    <?php else: ?>
                    <button class="btn-outline" style="width: 100%; margin-top: 1rem; opacity: 0.6; cursor: not-allowed;" disabled>Out of Stock</button>
                    <?php endif; ?>
                    <?php else: ?>
                    <a href="login.php" class="btn-outline" style="display:block; text-align:center; margin-top:1rem;">Login to Order</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>🥬 FreshMart</h4>
                <p>Your trusted online grocery store.</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 FreshMart. All rights reserved.</p>
        </div>
    </footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>
