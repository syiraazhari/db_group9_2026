<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireRole('manager');

$stmt = $pdo->query("SELECT * FROM users WHERE role = 'staff' ORDER BY created_at DESC");
$staff = $stmt->fetchAll();

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff - FreshMart Manager</title>
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
                <li><a href="orders.php">📦 Orders</a></li>
                <li><a href="users.php">👤 Users</a></li>
                <li><a href="staff.php" class="active">🛠️ Staff</a></li>
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

            <h1 style="margin-bottom: 2rem; color: var(--primary-dark);">Manage Staff</h1>

            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staff as $s): ?>
                        <tr>
                            <td><?php echo $s['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($s['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($s['email']); ?></td>
                            <td><?php echo htmlspecialchars($s['phone'] ?? '-'); ?></td>
                            <td><?php echo date('d M Y', strtotime($s['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($staff)): ?>
                        <tr><td colspan="5" style="text-align: center;">No staff members found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>