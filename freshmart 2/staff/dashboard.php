<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireRole('staff');

$staff_id = getUserId();
$pendingOrders = getPendingOrders();
$tasks = getStaffTasks($staff_id);
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - FreshMart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-dashboard">
        <aside class="sidebar">
            <a href="dashboard.php" class="logo">
                <span class="logo-icon">🥬</span>
                FreshMart Staff
            </a>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php" class="active">📋 Orders</a></li>
                <li><a href="inventory.php">📦 Inventory</a></li>
                <li><a href="tasks.php">✅ My Tasks</a></li>
                <li><a href="../pages/logout.php">🚪 Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?>">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>

            <h1 style="margin-bottom: 2rem; color: var(--primary-dark);">Staff Dashboard</h1>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Pending Orders</h3>
                    <div class="value"><?php echo count($pendingOrders); ?></div>
                </div>
                <div class="stat-card">
                    <h3>My Tasks</h3>
                    <div class="value"><?php echo count($tasks); ?></div>
                </div>
            </div>

            <h2 style="margin: 2rem 0 1rem; color: var(--primary-dark);">Pending Orders</h2>
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Pickup</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingOrders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                            <td><?php echo $order['phone']; ?></td>
                            <td><?php echo date('d M Y', strtotime($order['pickup_date'])); ?><br><?php echo date('h:i A', strtotime($order['pickup_time'])); ?></td>
                            <td>RM <?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
