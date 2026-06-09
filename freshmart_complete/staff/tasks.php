<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireRole('staff');

$staff_id = getUserId();
$tasks = getStaffTasks($staff_id);
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks - FreshMart Staff</title>
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
                <li><a href="dashboard.php">📋 Orders</a></li>
                <li><a href="inventory.php">📦 Inventory</a></li>
                <li><a href="tasks.php" class="active">✅ My Tasks</a></li>
                <li><a href="../pages/logout.php">🚪 Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?>">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>

            <h1 style="margin-bottom: 2rem; color: var(--primary-dark);">My Tasks</h1>

            <div class="orders-list">
                <?php foreach ($tasks as $task): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <h4>Task #<?php echo $task['task_id']; ?></h4>
                            <p style="color: var(--text-light); font-size: 0.9rem;">
                                Assigned: <?php echo date('d M Y, h:i A', strtotime($task['assigned_at'])); ?>
                            </p>
                        </div>
                        <span class="status-badge status-<?php echo $task['status']; ?>">
                            <?php echo ucfirst($task['status']); ?>
                        </span>
                    </div>
                    <p><?php echo htmlspecialchars($task['task_description']); ?></p>
                    <?php if ($task['order_id']): ?>
                    <p style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--text-light);">
                        Related Order: #<?php echo $task['order_id']; ?>
                    </p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
