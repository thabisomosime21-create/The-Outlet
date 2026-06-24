<?php
include 'config.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['brand'] . ' - ' . $product['label_name']); ?> - The Outlet</title>
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
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (is_logged_in()): ?>
                    <?php if (has_role('seller') || has_role('admin')): ?>
                        <li><a href="sell.php">Sell</a></li>
                    <?php endif; ?>
                    <li><a href="wishlist.php">Wishlist</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <?php if (has_role('admin') || has_role('moderator')): ?>
                        <li><a href="admin/index.php">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Product Detail Section -->
    <section class="product-detail-section">
        <div class="container">
            <div class="product-detail">
                <div class="product-image-large">
                    <?php if ($product['image']): ?>
                        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['label_name']); ?>">
                    <?php else: ?>
                        <div class="placeholder-image-large">No Image</div>
                    <?php endif; ?>
                    <span class="auth-badge-large <?php echo $product['auth_status']; ?>">
                        <?php echo ucfirst($product['auth_status']); ?>
                    </span>
                </div>

                <div class="product-info-large">
                    <h1><?php echo htmlspecialchars($product['brand']); ?></h1>
                    <h2><?php echo htmlspecialchars($product['label_name']); ?></h2>
                    
                    <div class="product-meta">
                        <p><strong>Size:</strong> <?php echo htmlspecialchars($product['size']); ?></p>
                        <p><strong>Condition:</strong> <?php echo ucfirst(str_replace('_', ' ', $product['condition'])); ?></p>
                        <p><strong>Seller:</strong> <?php echo htmlspecialchars($product['seller_name']); ?></p>
                        <p><strong>Listed:</strong> <?php echo date('M d, Y', strtotime($product['created_at'])); ?></p>
                    </div>

                    <div class="product-price-large">
                        <?php echo format_price($product['price']); ?>
                    </div>

                    <?php if ($product['description']): ?>
                        <div class="product-description">
                            <h3>Description</h3>
                            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="product-actions-large">
                        <?php if (is_logged_in()): ?>
                            <a href="purchase.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary btn-large">Buy Now</a>
                            <a href="add_wishlist.php?id=<?php echo $product['product_id']; ?>" class="btn btn-secondary btn-large">Add to Wishlist</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary btn-large">Login to Buy</a>
                        <?php endif; ?>
                    </div>

                    <?php if ($product['auth_document']): ?>
                        <div class="auth-info">
                            <h3>Authentication Document</h3>
                            <p>This seller has provided proof of authenticity.</p>
                            <?php if (is_admin()): ?>
                                <a href="uploads/<?php echo $product['auth_document']; ?>" target="_blank" class="btn btn-secondary">View Document</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>The Outlet</h3>
                    <p>Authentic Streetwear & Designer Marketplace in South Africa</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="contact.php">Contact Admin</a></li>
                        <li><a href="contact.php">Report an Issue</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 The Outlet - Authentic Streetwear & Designer Marketplace</p>
            </div>
        </div>
    </footer>
    <script src="js/main.js"></script>
</body>
</html>
