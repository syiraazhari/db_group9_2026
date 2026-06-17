<?php
session_start();
require_once '../includes/db.php';

echo "<h2>Debug Info</h2>";
echo "<p>Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET') . "</p>";
echo "<p>Session user_role: " . ($_SESSION['user_role'] ?? 'NOT SET') . "</p>";
echo "<p>isLoggedIn(): " . (isLoggedIn() ? 'YES' : 'NO') . "</p>";

$stmt = $pdo->query("SELECT user_id, full_name, email, role FROM users WHERE email = 'admin@freshmart.com'");
$admin = $stmt->fetch();
echo "<p>Admin in DB: " . ($admin ? 'FOUND' : 'NOT FOUND') . "</p>";
if ($admin) {
    echo "<p>Admin role: " . $admin['role'] . "</p>";
}

echo "<hr><a href='auto_login.php'>Auto Login as Admin</a>";
echo "<br><a href='dashboard.php'>Go to Dashboard</a>";
?>