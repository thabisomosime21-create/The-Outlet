<?php
include 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('/login.php');
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$user_id = $_SESSION['user_id'];

// Get order details
$query = "SELECT o.*, e.escrow_id 
          FROM orders o 
          JOIN escrow_transactions e ON o.order_id = e.order_id 
          WHERE o.order_id = $order_id AND o.buyer_id = $user_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die('Order not found.');
}

$order = $result->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Update order status
        $order_update = "UPDATE orders SET status = 'completed' WHERE order_id = $order_id";
        if (!$conn->query($order_update)) {
            throw new Exception('Failed to update order status.');
        }

        // Release escrow
        $escrow_update = "UPDATE escrow_transactions SET status = 'released', released_at = NOW() WHERE escrow_id = {$order['escrow_id']}";
        if (!$conn->query($escrow_update)) {
            throw new Exception('Failed to release escrow.');
        }

        // Commit transaction
        $conn->commit();
        $success = 'Payment released to seller! Order completed.';
    } catch (Exception $e) {
        $conn->rollback();
        $error = 'Failed to release payment: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Receipt - The Outlet</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php">The Outlet</a>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <?php if (has_role('seller') || has_role('admin')): ?>
                    <li><a href="sell.php">Sell</a></li>
                <?php endif; ?>
                <li><a href="wishlist.php">Wishlist</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Escrow Release Section -->
    <section class="escrow-section">
        <div class="container">
            <div class="escrow-box">
                <h1>Confirm Receipt & Authenticity</h1>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                    <p><a href="dashboard.php">Return to dashboard</a></p>
                <?php else: ?>

                <div class="confirmation-info">
                    <h2>Order #<?php echo $order_id; ?></h2>
                    <p>Status: <strong><?php echo ucfirst($order['status']); ?></strong></p>
                    <p>Amount: <strong><?php echo format_price($order['amount']); ?></strong></p>
                </div>

                <div class="warning-box">
                    <h3>⚠️ Important</h3>
                    <p>By confirming receipt, you acknowledge that:</p>
                    <ul>
                        <li>You have received the item</li>
                        <li>The item is authentic</li>
                        <li>The item matches the description</li>
                        <li>Payment will be released to the seller</li>
                        <li>This action cannot be undone</li>
                    </ul>
                </div>

                <p>If there are any issues with the item, please <a href="raise_dispute.php?order_id=<?php echo $order_id; ?>">raise a dispute</a> instead.</p>

                <form method="POST" action="escrow_release.php?order_id=<?php echo $order_id; ?>">
                    <button type="submit" class="btn btn-success btn-large">Confirm Receipt & Release Payment</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 The Outlet - Authentic Streetwear & Designer Marketplace</p>
        </div>
    </footer>
    <script src="js/main.js"></script>
</body>
</html>
