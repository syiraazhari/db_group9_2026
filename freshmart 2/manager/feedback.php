<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireRole('manager');

$feedback = getAllFeedback();
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback - FreshMart Manager</title>
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
                <li><a href="staff.php">🛠️ Staff</a></li>
                <li><a href="feedback.php" class="active">💬 Feedback</a></li>
                <li><a href="../pages/logout.php">🚪 Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?>">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>

            <h1 style="margin-bottom: 2rem; color: var(--primary-dark);">Customer Feedback</h1>

            <div class="feedback-list">
                <?php foreach ($feedback as $f): ?>
                <div class="feedback-card">
                    <div class="feedback-header">
                        <div>
                            <span class="name"><?php echo htmlspecialchars($f['full_name']); ?></span>
                            <div class="rating-stars" style="font-size: 1.2rem; margin-top: 0.3rem;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo $i <= $f['rating'] ? 'active' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <span class="date"><?php echo date('d M Y', strtotime($f['created_at'])); ?></span>
                    </div>
                    <p><?php echo htmlspecialchars($f['comment']); ?></p>
                </div>
                <?php endforeach; ?>
                <?php if (empty($feedback)): ?>
                <div class="empty-state">
                    <div class="icon">💬</div>
                    <h3>No Feedback Yet</h3>
                    <p>Customer feedback will appear here.</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>