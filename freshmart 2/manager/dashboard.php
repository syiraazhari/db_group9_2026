<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireRole('manager');

$manager_id = getUserId();
$allOrders = getAllOrders();
$products = getAllProducts();
$lowStock = getLowStockProducts();
$topProducts = getTopProducts(5);

// Calculate stats
$totalRevenue = array_sum(array_column(array_filter($allOrders, fn($o) => $o['status'] == 'completed'), 'total_amount'));
$totalOrders = count($allOrders);
$completedOrders = count(array_filter($allOrders, fn($o) => $o['status'] == 'completed'));
$pendingOrders = count(array_filter($allOrders, fn($o) => $o['status'] == 'pending'));

// Date range for report
$start_date = $_GET['start'] ?? date('Y-m-d', strtotime('-7 days'));
$end_date = $_GET['end'] ?? date('Y-m-d');
$salesReport = getSalesReport($start_date, $end_date);

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - FreshMart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-dashboard">
        <aside class="sidebar">
            <a href="dashboard.php" class="logo">
                <span class="logo-icon">🥬</span>
                FreshMart Manager
            </a>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
                <li><a href="products.php">🥦 Products</a></li>
                <li><a href="categories.php">📁 Categories</a></li>
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

            <h1 style="margin-bottom: 2rem; color: var(--primary-dark);">Manager Dashboard</h1>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Revenue</h3>
                    <div class="value">RM <?php echo number_format($totalRevenue, 2); ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <div class="value"><?php echo $totalOrders; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Completed</h3>
                    <div class="value"><?php echo $completedOrders; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Pending</h3>
                    <div class="value"><?php echo $pendingOrders; ?></div>
                </div>
            </div>

            <!-- Sales Report Form -->
            <div class="order-form" style="margin: 2rem 0;">
                <h3 style="margin-bottom: 1rem;">Sales Report</h3>
                <form method="GET" action="dashboard.php" style="display: flex; gap: 1rem; align-items: flex-end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Start Date</label>
                        <input type="date" name="start" value="<?php echo $start_date; ?>">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>End Date</label>
                        <input type="date" name="end" value="<?php echo $end_date; ?>">
                    </div>
                    <button type="submit" class="btn-primary">Generate Report</button>
                </form>
                <div style="margin-top: 1rem; padding: 1rem; background: var(--bg-warm); border-radius: var(--radius);">
                    <p><strong>Period:</strong> <?php echo date('d M Y', strtotime($start_date)); ?> - <?php echo date('d M Y', strtotime($end_date)); ?></p>
                    <p><strong>Orders:</strong> <?php echo $salesReport['total_orders'] ?? 0; ?> | <strong>Revenue:</strong> RM <?php echo number_format($salesReport['total_revenue'] ?? 0, 2); ?></p>
                </div>
            </div>

            <!-- Top Products Chart -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
                <div class="order-form">
                    <h3 style="margin-bottom: 1rem;">Top Selling Products</h3>
                    <canvas id="topProductsChart" height="200"></canvas>
                </div>
                <div class="order-form">
                    <h3 style="margin-bottom: 1rem;">Low Stock Alert</h3>
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr><th>Product</th><th>Stock</th></tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lowStock)): ?>
                                <tr>
                                    <td colspan="2" style="text-align: center; color: #888;">No low stock products</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($lowStock as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                        <td><span class="status-badge status-cancelled"><?php echo $product['stock_quantity']; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const ctx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($topProducts, 'product_name')); ?>,
                datasets: [{
                    label: 'Units Sold',
                    data: <?php echo json_encode(array_column($topProducts, 'total_sold')); ?>,
                    backgroundColor: '#2d5a27',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>