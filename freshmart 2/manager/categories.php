<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireRole('manager');

$categories = getCategories();
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - FreshMart Manager</title>
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
                <li><a href="categories.php" class="active">📁 Categories</a></li>
                <li><a href="orders.php">📦 Orders</a></li>
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

            <h1 style="margin-bottom: 2rem; color: var(--primary-dark);">Manage Categories</h1>

            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?php echo $cat['category_id']; ?></td>
                            <td><?php echo htmlspecialchars($cat['category_name']); ?></td>
                            <td><span class="status-badge status-<?php echo $cat['status']; ?>"><?php echo ucfirst($cat['status']); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>