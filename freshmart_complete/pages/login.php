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

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] == 'admin') {
            redirect('../admin/dashboard.php');
        } elseif ($user['role'] == 'staff') {
            redirect('../staff/dashboard.php');
        } elseif ($user['role'] == 'manager') {
            redirect('../manager/dashboard.php');
        } else {
            redirect('../index.php');
        }
    } else {
        $error = 'Invalid email or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FreshMart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background: var(--bg-warm); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="auth-container">
        <div style="text-align: center; margin-bottom: 2rem;">
            <span style="font-size: 3rem;">🥬</span>
            <h2>Welcome Back!</h2>
            <p style="color: var(--text-light);">Login to continue shopping</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required placeholder="your@email.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>
            <button type="submit" name="login" class="btn-primary" style="width: 100%;">Login</button>
        </form>

        <div class="auth-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>

        <div style="margin-top: 2rem; padding: 1rem; background: var(--bg-warm); border-radius: var(--radius); font-size: 0.85rem; color: var(--text-light);">
            <p><strong>Demo Accounts:</strong></p>
            <p>Customer: ahmad@email.com / password</p>
            <p>Admin: admin@freshmart.com / password</p>
            <p>Staff: staff@freshmart.com / password</p>
            <p>Manager: manager@freshmart.com / password</p>
        </div>
    </div>
</body>
</html>
