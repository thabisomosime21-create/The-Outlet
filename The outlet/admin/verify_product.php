<?php
include '../config.php';

// Check if user is logged in and is admin or moderator
if (!is_logged_in() || (!has_role('admin') && !has_role('moderator'))) {
    header("Location: ../login.php");
    exit();
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get product details
$query = "SELECT p.*, u.name as seller_name, u.email as seller_email 
          FROM products p 
          JOIN users u ON p.seller_id = u.user_id 
          WHERE p.product_id = $product_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die('Product not found.');
}

$product = $result->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth_status = sanitize($conn, $_POST['auth_status']);
    $rejection_reason = sanitize($conn, $_POST['rejection_reason']);

    // Update product auth status
    $update = "UPDATE products SET auth_status = '$auth_status' WHERE product_id = $product_id";

    if ($conn->query($update)) {
        $success = 'Product verification status updated!';
    } else {
        $error = 'Failed to update product.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Product - The Outlet Admin</title>
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
                <li><a href="manage_products.php" class="active">Products</a></li>
                <li><a href="disputes.php">Disputes</a></li>
                <li><a href="../index.php">View Site</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Verify Product Section -->
    <section class="admin-section">
        <div class="container">
            <h1>Verify Product</h1>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <p><a href="manage_products.php">Return to products list</a></p>
            <?php else: ?>

            <div class="product-verification">
                <div class="product-details">
                    <h2>Product Details</h2>
                    <div class="product-info-grid">
                        <div class="info-item">
                            <strong>Brand:</strong> <?php echo htmlspecialchars($product['brand']); ?>
                        </div>
                        <div class="info-item">
                            <strong>Label Name:</strong> <?php echo htmlspecialchars($product['label_name']); ?>
                        </div>
                        <div class="info-item">
                            <strong>Size:</strong> <?php echo htmlspecialchars($product['size']); ?>
                        </div>
                        <div class="info-item">
                            <strong>Condition:</strong> <?php echo ucfirst(str_replace('_', ' ', $product['condition'])); ?>
                        </div>
                        <div class="info-item">
                            <strong>Price:</strong> <?php echo format_price($product['price']); ?>
                        </div>
                        <div class="info-item">
                            <strong>Seller:</strong> <?php echo htmlspecialchars($product['seller_name']); ?>
                        </div>
                        <div class="info-item">
                            <strong>Current Status:</strong> <span class="auth-badge <?php echo $product['auth_status']; ?>"><?php echo ucfirst($product['auth_status']); ?></span>
                        </div>
                    </div>

                    <?php if ($product['image']): ?>
                        <div class="product-image-display">
                            <h3>Product Image</h3>
                            <img src="../uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['label_name']); ?>">
                        </div>
                    <?php endif; ?>

                    <?php if ($product['auth_document']): ?>
                        <div class="auth-document-display">
                            <h3>Authentication Document</h3>
                            <a href="../uploads/<?php echo $product['auth_document']; ?>" target="_blank" class="btn btn-secondary">View Document</a>
                        </div>
                    <?php endif; ?>

                    <?php if ($product['description']): ?>
                        <div class="product-description">
                            <h3>Description</h3>
                            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="verification-form">
                    <h2>Verification Decision</h2>
                    <form method="POST" action="verify_product.php?id=<?php echo $product_id; ?>">
                        <div class="form-group">
                            <label for="auth_status">Verification Status</label>
                            <select id="auth_status" name="auth_status" required>
                                <option value="">Select Status</option>
                                <option value="verified">Verified (Authentic)</option>
                                <option value="rejected">Rejected (Not Authentic)</option>
                                <option value="pending">Pending (Need More Info)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="rejection_reason">Reason (if rejected)</label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="4" placeholder="Explain why this product was rejected..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Status</button>
                        <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
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
