<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
requireRole('admin');

// Handle add product
if (isset($_POST['add_product'])) {
    $data = [
        'category_id' => $_POST['category_id'],
        'product_name' => $_POST['product_name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'stock_quantity' => $_POST['stock_quantity'],
        'unit' => $_POST['unit'],
        'image_url' => $_POST['image_url']
    ];
    addProduct($data);
    setFlash('success', 'Product added successfully!');
    redirect('products.php');
}

// Handle update product
if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $data = [
        'category_id' => $_POST['category_id'],
        'product_name' => $_POST['product_name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'stock_quantity' => $_POST['stock_quantity'],
        'unit' => $_POST['unit'],
        'image_url' => $_POST['image_url'],
        'status' => $_POST['status']
    ];
    updateProduct($product_id, $data);
    setFlash('success', 'Product updated successfully!');
    redirect('products.php');
}

// Handle delete product
if (isset($_GET['delete'])) {
    deleteProduct($_GET['delete']);
    setFlash('success', 'Product deleted successfully!');
    redirect('products.php');
}

$products = getAllProducts();
$categories = getCategories();
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - FreshMart Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="admin-dashboard">
        <aside class="sidebar">
            <a href="dashboard.php" class="logo">
                <span class="logo-icon">🥬</span>
                FreshMart Admin
            </a>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="products.php" class="active">📦 Manage Products</a></li>
                <li><a href="orders.php">📋 Manage Orders</a></li>
                <li><a href="users.php">👥 Manage Users</a></li>
                <li><a href="../pages/feedback.php">⭐ Feedback</a></li>
                <li><a href="../pages/logout.php">🚪 Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <?php if ($flash): ?>
                <div class="alert alert-<?php echo $flash['type']; ?>">
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>

            <h1 style="margin-bottom: 2rem; color: var(--primary-dark);">Manage Products</h1>

            <!-- Add Product Form -->
            <div class="order-form" style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem;">Add New Product</h3>
                <form method="POST" action="products.php">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="product_name" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" required>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['category_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Price (RM)</label>
                            <input type="number" step="0.01" name="price" required>
                        </div>
                        <div class="form-group">
                            <label>Stock Quantity</label>
                            <input type="number" name="stock_quantity" required>
                        </div>
                        <div class="form-group">
                            <label>Unit</label>
                            <input type="text" name="unit" placeholder="kg, pcs, bottle" required>
                        </div>
                        <div class="form-group">
                            <label>Image URL</label>
                            <input type="url" name="image_url" placeholder="https://..." required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="2" required></textarea>
                    </div>
                    <button type="submit" name="add_product" class="btn-primary">Add Product</button>
                </form>
            </div>

            <!-- Products Table -->
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td><img src="<?php echo $product['image_url']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo $product['category_name']; ?></td>
                            <td>RM <?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['stock_quantity']; ?></td>
                            <td><span class="status-badge status-<?php echo $product['status'] == 'available' ? 'completed' : 'cancelled'; ?>"><?php echo ucfirst($product['status']); ?></span></td>
                            <td>
                                <a href="products.php?edit=<?php echo $product['product_id']; ?>" class="btn-secondary" style="padding: 0.3rem 0.75rem; font-size: 0.85rem;">Edit</a>
                                <a href="products.php?delete=<?php echo $product['product_id']; ?>" class="btn-danger" style="padding: 0.3rem 0.75rem; font-size: 0.85rem;" onclick="return confirm('Delete this product?')">Delete</a>
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
