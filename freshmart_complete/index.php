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
            <div class="hero-images">
                <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=200" class="hero-img" alt="Vegetables">
                <img src="https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=200" class="hero-img" alt="Fruits">
                <img src="https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=200" class="hero-img" alt="Meat">
                <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=200" class="hero-img" alt="Bread">
            </div>
            <h1>Fresh Groceries.<br>Online. On Time.</h1>
            <p>Welcome to our online grocery store! Every vegetable, fruit, and dairy product is hand-picked for freshness. Order online, pickup in-store.</p>
            <a href="pages/products.php" class="btn-primary">Shop Fresh Today</a>
        </div>
    </section>

    <section class="section">
        <div class="section-header">
            <h2>Shop by Category</h2>
            <p>Fresh picks from every category</p>
        </div>
        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
            <a href="pages/products.php?category=<?php echo $cat['category_id']; ?>" class="category-card">
                <img src="<?php echo $cat['image_url']; ?>" alt="<?php echo $cat['category_name']; ?>">
                <div class="cat-info">
                    <h3><?php echo $cat['category_name']; ?></h3>
                    <p><?php echo $cat['description']; ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section" style="background: var(--bg-warm); border-radius: var(--radius-lg); margin: 2rem auto;">
        <div class="section-header">
            <h2>Fresh Picks</h2>
            <p>Handpicked fresh items for you</p>
        </div>
        <div class="products-grid">
            <?php foreach ($featured as $product): ?>
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
                    <?php if (isLoggedIn()): ?>
                    <form class="add-to-cart-form" action="pages/cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                    <?php else: ?>
                    <a href="pages/login.php" class="btn-outline" style="display:block; text-align:center; margin-top:1rem;">Login to Order</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section">
        <div class="info-cards">
            <div class="info-card">
                <div class="icon">🛒</div>
                <h3>Easy Ordering</h3>
                <p>Browse, add to cart, and checkout in minutes</p>
            </div>
            <div class="info-card">
                <div class="icon">📅</div>
                <h3>Schedule Pickup</h3>
                <p>Choose your preferred pickup date and time</p>
            </div>
            <div class="info-card">
                <div class="icon">💰</div>
                <h3>Pay In-Store</h3>
                <p>Pay when you collect your items at our store</p>
            </div>
            <div class="info-card">
                <div class="icon">⭐</div>
                <h3>Fresh Quality</h3>
                <p>Handpicked fresh produce every single day</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>🥬 FreshMart</h4>
                <p>Your trusted online grocery store for fresh produce and daily essentials.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="index.php">Home</a><br>
                <a href="pages/products.php">Products</a><br>
                <a href="pages/cart.php">Cart</a><br>
                <a href="pages/feedback.php">Feedback</a>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <p>📍 123 Jalan Fresh, Taman Suria</p>
                <p>📞 03-1234 5678</p>
                <p>✉️ hello@freshmart.com</p>
            </div>
            <div class="footer-section">
                <h4>Hours</h4>
                <p>Mon - Fri: 8AM - 8PM</p>
                <p>Sat - Sun: 9AM - 6PM</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 FreshMart. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
