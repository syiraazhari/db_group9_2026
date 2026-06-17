<?php
require_once '../includes/db.php';

// Auto login as admin
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute(['admin@freshmart.com']);
$user = $stmt->fetch();

if ($user) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['user_role'] = $user['role'];
    
    echo "Login successful! Role: " . $user['role'];
    echo "<br><a href='dashboard.php'>Go to Dashboard</a>";
} else {
    echo "Admin not found in database!";
}
?>