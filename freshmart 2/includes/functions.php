<?php
// =====================================================
// FreshMart - Helper Functions
// File: includes/functions.php
// =====================================================
require_once 'db.php';

// ==================== CUSTOMER FUNCTIONS ====================

function getCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories WHERE status='active' ORDER BY category_name");
    return $stmt->fetchAll();
}

function getProductsByCategory($category_id = null) {
    global $pdo;
    if ($category_id) {
        $stmt = $pdo->prepare("SELECT p.*, c.category_name FROM products p 
                              JOIN categories c ON p.category_id = c.category_id 
                              WHERE p.category_id = ? AND p.status = 'available'");
        $stmt->execute([$category_id]);
    } else {
        $stmt = $pdo->query("SELECT p.*, c.category_name FROM products p 
                            JOIN categories c ON p.category_id = c.category_id 
                            WHERE p.status = 'available' ORDER BY p.category_id");
    }
    return $stmt->fetchAll();
}

function getProductById($product_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, c.category_name FROM products p 
                          JOIN categories c ON p.category_id = c.category_id 
                          WHERE p.product_id = ?");
    $stmt->execute([$product_id]);
    return $stmt->fetch();
}

function getCartItems($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT c.*, p.product_name, p.price, p.image_url, p.unit, p.stock_quantity 
                          FROM cart c 
                          JOIN products p ON c.product_id = p.product_id 
                          WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function getCartCount($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT SUM(quantity) as count FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch();
    return $result['count'] ?? 0;
}

function addToCart($user_id, $product_id, $quantity) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        $newQty = $existing['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
        $stmt->execute([$newQty, $existing['cart_id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
    }
    return true;
}

function removeFromCart($cart_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = ?");
    return $stmt->execute([$cart_id]);
}

function updateCartQuantity($cart_id, $quantity) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
    return $stmt->execute([$quantity, $cart_id]);
}

function createOrder($user_id, $pickup_date, $pickup_time, $notes = '') {
    global $pdo;
    try {
        $pdo->beginTransaction();
        $cartItems = getCartItems($user_id);
        if (empty($cartItems)) throw new Exception("Cart is empty");

        $total = 0;
        foreach ($cartItems as $item) $total += $item['price'] * $item['quantity'];

        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, pickup_date, pickup_time, notes) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $total, $pickup_date, $pickup_time, $notes]);
        $order_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) 
                              VALUES (?, ?, ?, ?, ?)");
        foreach ($cartItems as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price'], $subtotal]);
            $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?")
                ->execute([$item['quantity'], $item['product_id']]);
        }

        $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);
        $pdo->commit();
        return $order_id;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function getUserOrders($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function getOrderDetails($order_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT oi.*, p.product_name, p.image_url FROM order_items oi 
                          JOIN products p ON oi.product_id = p.product_id 
                          WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    return $stmt->fetchAll();
}

function addFeedback($user_id, $order_id, $rating, $comment) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO feedback (user_id, order_id, rating, comment) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$user_id, $order_id, $rating, $comment]);
}

function getAllFeedback() {
    global $pdo;
    $stmt = $pdo->query("SELECT f.*, u.full_name FROM feedback f 
                        JOIN users u ON f.user_id = u.user_id 
                        ORDER BY f.created_at DESC");
    return $stmt->fetchAll();
}

// ==================== ADMIN FUNCTIONS ====================

function getAllUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

function getAllOrders() {
    global $pdo;
    $stmt = $pdo->query("SELECT o.*, u.full_name, u.email FROM orders o 
                        JOIN users u ON o.user_id = u.user_id 
                        ORDER BY o.created_at DESC");
    return $stmt->fetchAll();
}

function getAllProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT p.*, c.category_name FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.category_id 
                        ORDER BY p.product_id DESC");
    return $stmt->fetchAll();
}

function updateOrderStatus($order_id, $status) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    return $stmt->execute([$status, $order_id]);
}

function updatePaymentStatus($order_id, $status) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE orders SET payment_status = ? WHERE order_id = ?");
    return $stmt->execute([$status, $order_id]);
}

function addProduct($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO products (category_id, product_name, description, price, stock_quantity, unit, image_url) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$data['category_id'], $data['product_name'], $data['description'], 
                          $data['price'], $data['stock_quantity'], $data['unit'], $data['image_url']]);
}

function updateProduct($product_id, $data) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE products SET category_id=?, product_name=?, description=?, 
                          price=?, stock_quantity=?, unit=?, image_url=?, status=? WHERE product_id=?");
    return $stmt->execute([$data['category_id'], $data['product_name'], $data['description'], 
                          $data['price'], $data['stock_quantity'], $data['unit'], $data['image_url'], 
                          $data['status'], $product_id]);
}

function deleteProduct($product_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    return $stmt->execute([$product_id]);
}

// ==================== STAFF FUNCTIONS ====================

function getPendingOrders() {
    global $pdo;
    $stmt = $pdo->query("SELECT o.*, u.full_name, u.phone FROM orders o 
                        JOIN users u ON o.user_id = u.user_id 
                        WHERE o.status IN ('pending', 'processing', 'ready') 
                        ORDER BY o.pickup_date, o.pickup_time");
    return $stmt->fetchAll();
}

function getStaffTasks($staff_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM staff_tasks WHERE staff_id = ? ORDER BY assigned_at DESC");
    $stmt->execute([$staff_id]);
    return $stmt->fetchAll();
}

function addInventoryLog($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO inventory_logs (product_id, staff_id, action_type, quantity_changed, previous_stock, new_stock, notes) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$data['product_id'], $data['staff_id'], $data['action_type'], 
                          $data['quantity_changed'], $data['previous_stock'], $data['new_stock'], $data['notes']]);
}

// ==================== MANAGER FUNCTIONS ====================

function getSalesReport($start_date, $end_date) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_orders, SUM(total_amount) as total_revenue 
                          FROM orders WHERE status='completed' AND DATE(created_at) BETWEEN ? AND ?");
    $stmt->execute([$start_date, $end_date]);
    return $stmt->fetch();
}

function getTopProducts($limit = 5) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.product_name, SUM(oi.quantity) as total_sold, SUM(oi.subtotal) as total_revenue 
                          FROM order_items oi 
                          JOIN products p ON oi.product_id = p.product_id 
                          GROUP BY oi.product_id ORDER BY total_sold DESC LIMIT " . (int)$limit);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getLowStockProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM products WHERE stock_quantity < 20 AND status='available'");
    return $stmt->fetchAll();
}
?>