<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireRole('staff');

$staff_id = getUserId();
$products = getAllProducts();
$flash = getFlash();

// Handle inventory update
if (isset($_POST['update_stock'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    $action = $_POST['action_type'];

    $product = getProductById($product_id);
    $prev_stock = $product['stock_quantity'];

    if ($action == 'restock') {
        $new_stock = $prev_stock + $quantity;
    } else {
        $new_stock = max(0, $prev_stock - $quantity);
    }

    $logData = [
        'product_id' => $product_id,
        'staff_id' => $staff_id,
        'action_type' => $action,
        'quantity_changed' => $quantity,
        'previous_stock' => $prev_stock,
        'new_stock' => $new_stock,
        'notes' => $_POST['notes'] ?? ''
    ];

    addInventoryLog($logData);

    $stmt = $pdo->prepare("UPDATE products SET stock_quantity = ? WHERE product_id = ?");
    $stmt->execute([$new_stock, $product_id]);

    setFlash('success', 'Inventory updated successfully!');
    redirect('inventory.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - FreshMart Staff</title>
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
                <li><a href="inventory.php" class="active">📦 Inventory</a></li>
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

            <h1 style="margin-bottom: 2rem; color: var(--primary-dark);">Inventory Management</h1>

            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Current Stock</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Update Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td>
                                <img src="<?php echo $product['image_url']; ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px; vertical-align: middle; margin-right: 0.5rem;">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </td>
                            <td><?php echo $product['stock_quantity']; ?></td>
                            <td><?php echo $product['unit']; ?></td>
                            <td><span class="status-badge status-<?php echo $product['status'] == 'available' ? 'completed' : 'cancelled'; ?>"><?php echo ucfirst($product['status']); ?></span></td>
                            <td>
                                <form method="POST" style="display: flex; gap: 0.5rem;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <input type="number" name="quantity" value="10" min="1" style="width: 60px; padding: 0.3rem; border: 1px solid var(--border); border-radius: 4px;">
                                    <select name="action_type" style="padding: 0.3rem; border-radius: 4px;">
                                        <option value="restock">Restock</option>
                                        <option value="sold">Sold</option>
                                        <option value="damaged">Damaged</option>
                                    </select>
                                    <button type="submit" name="update_stock" class="btn-success" style="padding: 0.3rem 0.75rem; font-size: 0.8rem;">Update</button>
                                </form>
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
