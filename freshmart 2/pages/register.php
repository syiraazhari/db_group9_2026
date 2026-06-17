<?php
require_once '../includes/db.php';

if (isLoggedIn()) {
    $role = getUserRole();
    if ($role == 'admin') redirect('../admin/dashboard.php');
    if ($role == 'staff') redirect('../staff/dashboard.php');
    if ($role == 'manager') redirect('../manager/dashboard.php');
    redirect('../index.php');
}

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = $_POST['address'];

    if ($password !== $confirm_password) {
        $error = 'Passwords do not match!';
    } else {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email already registered!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, password, address, role) VALUES (?, ?, ?, ?, ?, 'customer')");
            $stmt->execute([$full_name, $email, $phone, $hashed_password, $address]);
            $success = 'Registration successful! Please login.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FreshMart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background: var(--bg-warm); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="auth-container">
        <div style="text-align: center; margin-bottom: 2rem;">
            <span style="font-size: 3rem;">🥬</span>
            <h2>Create Account</h2>
            <p style="color: var(--text-light);">Join FreshMart today</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" required placeholder="John Doe">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required placeholder="your@email.com">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" name="phone" id="phone" required placeholder="012-3456789">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea name="address" id="address" rows="2" placeholder="Your delivery address..."></textarea>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required placeholder="••••••••">
            </div>
            <button type="submit" name="register" class="btn-primary" style="width: 100%;">Register</button>
        </form>

        <div class="auth-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
