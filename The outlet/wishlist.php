<?php
include 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('/login.php');
}

$user_id = $_SESSION['user_id'];

// Get user's wishlist
$query = "SELECT w.*, p.brand, p.label_name, p.price, p.image, p.size, p.condition, p.auth_status, u.name as seller_name 
          FROM wishlist w 
          JOIN products p ON w.product_id = p.product_id 
          JOIN users u ON p.seller_id = u.user_id 
          WHERE w.user_id = $user_id 
          ORDER BY w.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - The Outlet</title>
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
                <?php if (has_role('seller') || has_role('admin')): ?>
                    <li><a href="sell.php">Sell</a></li>
                <?php endif; ?>
                <li><a href="wishlist.php" class="active">Wishlist</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <?php if (has_role('admin') || has_role('moderator')): ?>
                    <li><a href="admin/index.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Wishlist Section -->
    <section class="wishlist-section">
        <div class="container">
            <h1>My Wishlist</h1>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="products">
                    <?php while ($item = $result->fetch_assoc()): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if ($item['image']): ?>
                                    <img src="uploads/<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['label_name']); ?>">
                                <?php else: ?>
                                    <div class="placeholder-image">No Image</div>
                                <?php endif; ?>
                                <span class="auth-badge <?php echo $item['auth_status']; ?>">
                                    <?php echo ucfirst($item['auth_status']); ?>
                                </span>
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($item['brand']); ?></h3>
                                <p class="label-name"><?php echo htmlspecialchars($item['label_name']); ?></p>
                                <p class="size">Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                <p class="condition"><?php echo ucfirst(str_replace('_', ' ', $item['condition'])); ?></p>
                                <p class="price"><?php echo format_price($item['price']); ?></p>
                                <p class="seller">Sold by: <?php echo htmlspecialchars($item['seller_name']); ?></p>
                                <div class="product-actions">
                                    <a href="product.php?id=<?php echo $item['product_id']; ?>" class="btn btn-primary">View Details</a>
                                    <a href="purchase.php?id=<?php echo $item['product_id']; ?>" class="btn btn-secondary">Buy Now</a>
                                    <a href="remove_wishlist.php?id=<?php echo $item['product_id']; ?>" class="btn btn-danger">Remove</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-wishlist">
                    <p>Your wishlist is empty.</p>
                    <a href="index.php" class="btn btn-primary">Browse Products</a>
                </div>
            <?php endif; ?>
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
