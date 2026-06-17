<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireRole('manager');

$orders = getAllOrders();
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - FreshMart Manager</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-dashboard">
        <aside class="sidebar">
            <a href="dashboard.php" class="logo">
                <span class="logo-icon">🥬</span>
                FreshMart Manager
            </a>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="products.php">🥦 Products</a></li>
                <li><a href="categories.php">📁 Categories</a></li>
                <li><a href="orders.php" class="active">📦 Orders</a></li>
                <li><a href="users.php">👤 Users</a></li>
                <li><a href="staff.php">🛠️ Staff</a></li>
                <li><a href="feedback.php">💬 Feedback</a></li>
                <li><a href="../pages/logout.php">🚪 Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?>">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>

            <h1 style="margin-bottom: 2rem; color: var(--primary-dark);">Manage Orders</h1>

            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Pickup Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                            <td>RM <?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                            <td><span class="status-badge payment-<?php echo $order['payment_status']; ?>"><?php echo ucfirst($order['payment_status']); ?></span></td>
                            <td><?php echo date('d M Y', strtotime($order['pickup_date'])); ?></td>
                            <td>
                                <a href="order_view.php?id=<?php echo $order['order_id']; ?>" class="btn-success" style="padding: 0.3rem 0.7rem; font-size: 0.85rem;">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>