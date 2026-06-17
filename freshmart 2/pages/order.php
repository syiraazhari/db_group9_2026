<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = getUserId();
$cartItems = getCartItems($user_id);

if (empty($cartItems)) {
    setFlash('error', 'Your cart is empty!');
    redirect('cart.php');
}

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

if (isset($_POST['place_order'])) {
    $pickup_date = $_POST['pickup_date'];
    $pickup_time = $_POST['pickup_time'];
    $notes = $_POST['notes'] ?? '';

    try {
        $order_id = createOrder($user_id, $pickup_date, $pickup_time, $notes);
        setFlash('success', "Order #$order_id placed successfully! Pickup and pay at store.");
        redirect('orders.php');
    } catch (Exception $e) {
        setFlash('error', 'Failed to place order: ' . $e->getMessage());
        redirect('order.php');
    }
}

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - FreshMart</title>
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
                <li><a href="cart.php">🛒 Cart</a></li>
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
            <h2>Ready to Order?</h2>
            <p>Fill in your pickup details and we'll prepare your order</p>
        </div>

        <div class="cart-container">
            <div class="order-form">
                <h3 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Pickup Details</h3>
                <form method="POST" action="order.php">
                    <div class="form-group">
                        <label for="pickup_date">Pickup Date *</label>
                        <input type="date" name="pickup_date" id="pickup_date" required 
                               min="<?php echo date('Y-m-d'); ?>" 
                               max="<?php echo date('Y-m-d', strtotime('+7 days')); ?>">
                    </div>
                    <div class="form-group">
                        <label for="pickup_time">Pickup Time *</label>
                        <select name="pickup_time" id="pickup_time" required>
                            <option value="">Select Time</option>
                            <option value="08:00">8:00 AM</option>
                            <option value="09:00">9:00 AM</option>
                            <option value="10:00">10:00 AM</option>
                            <option value="11:00">11:00 AM</option>
                            <option value="12:00">12:00 PM</option>
                            <option value="13:00">1:00 PM</option>
                            <option value="14:00">2:00 PM</option>
                            <option value="15:00">3:00 PM</option>
                            <option value="16:00">4:00 PM</option>
                            <option value="17:00">5:00 PM</option>
                            <option value="18:00">6:00 PM</option>
                            <option value="19:00">7:00 PM</option>
                            <option value="20:00">8:00 PM</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Special Instructions (Optional)</label>
                        <textarea name="notes" id="notes" placeholder="Any special requests for your order..."></textarea>
                    </div>
                    <div style="background: var(--bg-warm); padding: 1rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
                        <p style="font-size: 0.9rem; color: var(--text-light);">
                            📍 <strong>Pickup Location:</strong><br>
                            FreshMart Store<br>
                            123 Jalan Fresh, Taman Suria<br>
                            Johor Bahru, Malaysia
                        </p>
                    </div>
                    <button type="submit" name="place_order" class="btn-primary" style="width: 100%;">
                        Place Order (Pay at Store)
                    </button>
                </form>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                <?php foreach ($cartItems as $item): ?>
                <div class="summary-row">
                    <span><?php echo $item['product_name']; ?> x<?php echo $item['quantity']; ?></span>
                    <span>RM <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </div>
                <?php endforeach; ?>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>RM <?php echo number_format($total, 2); ?></span>
                </div>
                <div style="margin-top: 1rem; padding: 1rem; background: #fef3c7; border-radius: var(--radius); text-align: center;">
                    <p style="font-size: 0.9rem; color: #92400e;">
                        💰 Payment will be made when you pickup at the store
                    </p>
                </div>
            </div>
        </div>
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
