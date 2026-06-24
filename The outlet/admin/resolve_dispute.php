<?php
include '../config.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !has_role('admin')) {
    header("Location: ../login.php");
    exit();
}

$dispute_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get dispute details
$query = "SELECT d.*, o.order_id, o.amount, o.buyer_id, o.seller_id, p.label_name, p.brand, 
          u1.name as raised_by_name, u2.name as buyer_name, u3.name as seller_name 
          FROM disputes d 
          JOIN orders o ON d.order_id = o.order_id 
          JOIN products p ON o.product_id = p.product_id 
          JOIN users u1 ON d.raised_by = u1.user_id 
          JOIN users u2 ON o.buyer_id = u2.user_id 
          JOIN users u3 ON o.seller_id = u3.user_id 
          WHERE d.dispute_id = $dispute_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die('Dispute not found.');
}

$dispute = $result->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = sanitize($conn, $_POST['status']);
    $resolution = sanitize($conn, $_POST['resolution']);
    $action = sanitize($conn, $_POST['action']);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update dispute
        $update_dispute = "UPDATE disputes SET status = '$status', resolution = '$resolution', resolved_by = {$_SESSION['user_id']} WHERE dispute_id = $dispute_id";
        if (!$conn->query($update_dispute)) {
            throw new Exception('Failed to update dispute.');
        }

        // Handle escrow based on resolution
        if ($action == 'refund_buyer') {
            // Refund buyer - release escrow back to buyer (simulated by marking as refunded)
            $escrow_update = "UPDATE escrow_transactions SET status = 'refunded' WHERE order_id = {$dispute['order_id']}";
            if (!$conn->query($escrow_update)) {
                throw new Exception('Failed to refund buyer.');
            }

            // Update order status
            $order_update = "UPDATE orders SET status = 'cancelled' WHERE order_id = {$dispute['order_id']}";
            if (!$conn->query($order_update)) {
                throw new Exception('Failed to update order status.');
            }
        } elseif ($action == 'release_seller') {
            // Release to seller
            $escrow_update = "UPDATE escrow_transactions SET status = 'released', released_at = NOW() WHERE order_id = {$dispute['order_id']}";
            if (!$conn->query($escrow_update)) {
                throw new Exception('Failed to release to seller.');
            }

            // Update order status
            $order_update = "UPDATE orders SET status = 'completed' WHERE order_id = {$dispute['order_id']}";
            if (!$conn->query($order_update)) {
                throw new Exception('Failed to update order status.');
            }
        }

        // Commit transaction
        $conn->commit();
        $success = 'Dispute resolved successfully!';
    } catch (Exception $e) {
        $conn->rollback();
        $error = 'Failed to resolve dispute: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolve Dispute - The Outlet Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="admin-navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php">The Outlet Admin</a>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="manage_users.php">Users</a></li>
                <li><a href="manage_products.php">Products</a></li>
                <li><a href="disputes.php" class="active">Disputes</a></li>
                <li><a href="../index.php">View Site</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Resolve Dispute Section -->
    <section class="admin-section">
        <div class="container">
            <h1>Resolve Dispute #<?php echo $dispute_id; ?></h1>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <p><a href="disputes.php">Return to disputes list</a></p>
            <?php else: ?>

            <div class="dispute-details">
                <h2>Dispute Information</h2>
                <div class="dispute-info-grid">
                    <div class="info-item">
                        <strong>Order ID:</strong> #<?php echo $dispute['order_id']; ?>
                    </div>
                    <div class="info-item">
                        <strong>Product:</strong> <?php echo htmlspecialchars($dispute['brand'] . ' - ' . $dispute['label_name']); ?>
                    </div>
                    <div class="info-item">
                        <strong>Amount:</strong> <?php echo format_price($dispute['amount']); ?>
                    </div>
                    <div class="info-item">
                        <strong>Raised By:</strong> <?php echo htmlspecialchars($dispute['raised_by_name']); ?>
                    </div>
                    <div class="info-item">
                        <strong>Buyer:</strong> <?php echo htmlspecialchars($dispute['buyer_name']); ?>
                    </div>
                    <div class="info-item">
                        <strong>Seller:</strong> <?php echo htmlspecialchars($dispute['seller_name']); ?>
                    </div>
                    <div class="info-item">
                        <strong>Current Status:</strong> <span class="status-badge <?php echo $dispute['status']; ?>"><?php echo ucfirst($dispute['status']); ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($dispute['created_at'])); ?>
                    </div>
                </div>

                <div class="dispute-description">
                    <h3>Dispute Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($dispute['description'])); ?></p>
                </div>

                <?php if ($dispute['resolution']): ?>
                    <div class="dispute-resolution">
                        <h3>Current Resolution</h3>
                        <p><?php echo nl2br(htmlspecialchars($dispute['resolution'])); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="resolution-form">
                <h2>Resolution Decision</h2>
                <form method="POST" action="resolve_dispute.php?id=<?php echo $dispute_id; ?>">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="investigating">Investigating</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="action">Escrow Action</label>
                        <select id="action" name="action" required>
                            <option value="">Select Action</option>
                            <option value="refund_buyer">Refund Buyer (Return payment to buyer)</option>
                            <option value="release_seller">Release to Seller (Pay seller)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="resolution">Resolution Details</label>
                        <textarea id="resolution" name="resolution" rows="4" required placeholder="Describe your resolution decision..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Resolve Dispute</button>
                    <a href="disputes.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 The Outlet - Admin Panel</p>
        </div>
    </footer>
</body>
</html>
