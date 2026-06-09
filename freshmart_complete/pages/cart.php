<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = getUserId();

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($user_id, $product_id, $quantity);
    setFlash('success', 'Item added to cart!');
    redirect('cart.php');
}

// Handle update quantity
if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    if ($quantity > 0) {
        updateCartQuantity($cart_id, $quantity);
    } else {
        removeFromCart($cart_id);
    }
    setFlash('success', 'Cart updated!');
    redirect('cart.php');
}

// Handle remove item
if (isset($_GET['remove'])) {
    removeFromCart($_GET['remove']);
    setFlash('success', 'Item removed from cart!');
    redirect('cart.php');
}

$cartItems = getCartItems($user_id);
$cartCount = getCartCount($user_id);
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - FreshMart</title>
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
                <li><a href="products.php">Products</a></li>
                <li><a href="cart.php" class="nav-cart active">
                    🛒 Cart <span class="cart-badge"><?php echo $cartCount; ?></span>
                </a></li>
                <li><a href="orders.php">My Orders</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="logout.php">Logout</a></li>
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
            <h2>Shopping Cart</h2>
            <p>Review your items before checkout</p>
        </div>

        <?php if (empty($cartItems)): ?>
            <div class="empty-state">
                <div class="icon">🛒</div>
                <h3>Your cart is empty</h3>
                <p>Start shopping to add items!</p>
                <a href="products.php" class="btn-primary" style="margin-top: 1.5rem;">Browse Products</a>
            </div>
        <?php else: ?>
        <div class="cart-container">
            <div class="cart-items">
                <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['product_name']; ?>">
                    <div class="cart-item-details">
                        <h4><?php echo $item['product_name']; ?></h4>
                        <p class="item-price">RM <?php echo number_format($item['price'], 2); ?> / <?php echo $item['unit']; ?></p>
                        <form method="POST" style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock_quantity']; ?>" style="width: 60px; padding: 0.3rem; border: 1px solid var(--border); border-radius: var(--radius);">
                            <button type="submit" name="update_qty" class="btn-secondary" style="padding: 0.3rem 0.75rem;">Update</button>
                        </form>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 1.2rem; font-weight: 700; color: var(--primary);">
                            RM <?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </p>
                        <a href="cart.php?remove=<?php echo $item['cart_id']; ?>" class="btn-danger" style="margin-top: 0.5rem; display: inline-block; text-decoration: none;">
                            🗑️ Remove
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>RM <?php echo number_format($total, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Pickup Fee</span>
                    <span>Free</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>RM <?php echo number_format($total, 2); ?></span>
                </div>
                <p style="font-size: 0.85rem; color: var(--text-light); margin: 1rem 0; text-align: center;">
                    💡 Pay when you pickup at store
                </p>
                <a href="order.php" class="btn-primary" style="width: 100%; text-align: center;">
                    Proceed to Checkout
                </a>
            </div>
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
