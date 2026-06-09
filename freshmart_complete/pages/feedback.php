<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = getUserId();

// Handle feedback submission
if (isset($_POST['submit_feedback'])) {
    $order_id = $_POST['order_id'] ?? null;
    $order_id = $order_id ? intval($order_id) : null;
    $rating = intval($_POST['rating']);
    $comment = $_POST['comment'];

    addFeedback($user_id, $order_id, $rating, $comment);
    setFlash('success', 'Thank you for your feedback!');
    redirect('feedback.php');
}

$allFeedback = getAllFeedback();
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - FreshMart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">
                <span class="logo-icon">🥬</span>
                FreshMart
            </a>
            <ul class="nav-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="cart.php">🛒 Cart</a></li>
                <li><a href="orders.php">My Orders</a></li>
                <li><a href="feedback.php" class="active">Feedback</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="section" style="padding-top: 2rem;">
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>

        <div class="section-header">
            <h2>We're always finding ways to improve.</h2>
            <p>Let us know what you think or what you'd love to see more of.</p>
        </div>

        <div class="feedback-form">
            <form method="POST" action="feedback.php">
                <div class="form-group">
                    <label>How would you rate our service overall?</label>
                    <div class="rating-stars">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                    <input type="hidden" name="rating" id="rating" value="0" required>
                </div>
                <div class="form-group">
                    <label for="comment">Please share the reason for your rating</label>
                    <textarea name="comment" id="comment" rows="4" placeholder="Tell us about your experience..." required></textarea>
                </div>
                <?php if (isset($_GET['order'])): ?>
                <input type="hidden" name="order_id" value="<?php echo intval($_GET['order']); ?>">
                <?php endif; ?>
                <button type="submit" name="submit_feedback" class="btn-primary" style="width: 100%;">
                    Submit Feedback
                </button>
            </form>
        </div>

        <div class="section-header" style="margin-top: 4rem;">
            <h2>What Our Customers Say</h2>
        </div>
        <div class="feedback-list">
            <?php foreach ($allFeedback as $fb): ?>
            <div class="feedback-card">
                <div class="feedback-header">
                    <span class="name"><?php echo htmlspecialchars($fb['full_name']); ?></span>
                    <span class="date"><?php echo date('d M Y', strtotime($fb['created_at'])); ?></span>
                </div>
                <div class="rating-stars" style="font-size: 1.2rem; margin-bottom: 0.5rem;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?php echo $i <= $fb['rating'] ? 'active' : ''; ?>">★</span>
                    <?php endfor; ?>
                </div>
                <p><?php echo htmlspecialchars($fb['comment']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>🥬 FreshMart</h4>
                <p>Your trusted online grocery store.</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 FreshMart. All rights reserved.</p>
        </div>
    </footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>
