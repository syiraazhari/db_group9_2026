<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = getUserId();
$orders = getUserOrders($user_id);
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - FreshMart</title>
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
                <li><a href="orders.php" class="active">My Orders</a></li>
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
            <h2>My Orders</h2>
            <p>Track your orders and pickup status</p>
        </div>

        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <div class="icon">📦</div>
                <h3>No orders yet</h3>
                <p>Start shopping to place your first order!</p>
                <a href="products.php" class="btn-primary" style="margin-top: 1.5rem;">Shop Now</a>
            </div>
        <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): 
                $orderItems = getOrderDetails($order['order_id']);
                $statusClass = 'status-' . $order['status'];
                $paymentClass = 'payment-' . $order['payment_status'];
            ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <h4>Order #<?php echo $order['order_id']; ?></h4>
                        <p style="color: var(--text-light); font-size: 0.9rem;">
                            <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?>
                        </p>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <span class="status-badge <?php echo $statusClass; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                        <span class="status-badge <?php echo $paymentClass; ?>">
                            <?php echo $order['payment_status'] == 'paid' ? 'Paid' : 'Unpaid'; ?>
                        </span>
                    </div>
                </div>

                <div class="order-items">
                    <?php foreach ($orderItems as $item): ?>
                    <div class="order-item">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['product_name']; ?>">
                            <div>
                                <p style="font-weight: 600;"><?php echo $item['product_name']; ?></p>
                                <p style="font-size: 0.85rem; color: var(--text-light);">
                                    <?php echo $item['quantity']; ?> x RM <?php echo number_format($item['unit_price'], 2); ?>
                                </p>
                            </div>
                        </div>
                        <span style="font-weight: 700; color: var(--primary);">
                            RM <?php echo number_format($item['subtotal'], 2); ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="border-top: 1px solid var(--border); margin-top: 1rem; padding-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="font-size: 0.9rem; color: var(--text-light);">
                            📅 Pickup: <?php echo date('d M Y', strtotime($order['pickup_date'])); ?> at 
                            <?php echo date('h:i A', strtotime($order['pickup_time'])); ?>
                        </p>
                        <?php if ($order['notes']): ?>
                        <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 0.3rem;">
                            📝 <?php echo $order['notes']; ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 1.3rem; font-weight: 700; color: var(--primary);">
                            Total: RM <?php echo number_format($order['total_amount'], 2); ?>
                        </p>
                        <?php if ($order['status'] == 'completed' && $order['payment_status'] == 'paid'): ?>
                        <a href="feedback.php?order=<?php echo $order['order_id']; ?>" class="btn-secondary" style="margin-top: 0.5rem; display: inline-block;">
                            Rate Order
                        </a>
                        <?php endif; ?>
                    </div>
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
