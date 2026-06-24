<?php
include 'config.php';

// Get filter parameters
$brand_filter = isset($_GET['brand']) ? sanitize($conn, $_GET['brand']) : '';
$label_filter = isset($_GET['label']) ? sanitize($conn, $_GET['label']) : '';
$size_filter = isset($_GET['size']) ? sanitize($conn, $_GET['size']) : '';
$condition_filter = isset($_GET['condition']) ? sanitize($conn, $_GET['condition']) : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 10000;
$auth_filter = isset($_GET['auth_status']) ? sanitize($conn, $_GET['auth_status']) : '';

// Build query
$query = "SELECT p.*, u.name as seller_name FROM products p JOIN users u ON p.seller_id = u.user_id WHERE 1=1";

if ($brand_filter) {
    $query .= " AND p.brand = '$brand_filter'";
}
if ($label_filter) {
    $query .= " AND p.label_name LIKE '%$label_filter%'";
}
if ($size_filter) {
    $query .= " AND p.size = '$size_filter'";
}
if ($condition_filter) {
    $query .= " AND p.condition = '$condition_filter'";
}
if ($auth_filter) {
    $query .= " AND p.auth_status = '$auth_filter'";
}
$query .= " AND p.price BETWEEN $min_price AND $max_price";
$query .= " ORDER BY p.created_at DESC";

$result = $conn->query($query);

// Get unique brands for filter
$brands_query = "SELECT DISTINCT brand FROM products ORDER BY brand";
$brands_result = $conn->query($brands_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Outlet - Authentic Streetwear & Designer Marketplace</title>
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
                <li><a href="index.php" class="active">Home</a></li>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Authentic Streetwear & Designer Marketplace</h1>
            <p>Buy and sell the latest label clothing in South Africa</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container main-content">
        <!-- Sidebar Filters -->
        <aside class="filters">
            <h3>Filter Products</h3>
            <form method="GET" action="index.php">
                <div class="filter-group">
                    <label>Brand</label>
                    <select name="brand">
                        <option value="">All Brands</option>
                        <?php while ($brand = $brands_result->fetch_assoc()): ?>
                            <option value="<?php echo $brand['brand']; ?>" <?php echo $brand_filter == $brand['brand'] ? 'selected' : ''; ?>>
                                <?php echo $brand['brand']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Label Name</label>
                    <input type="text" name="label" placeholder="Search label..." value="<?php echo htmlspecialchars($label_filter); ?>">
                </div>

                <div class="filter-group">
                    <label>Size</label>
                    <select name="size">
                        <option value="">All Sizes</option>
                        <option value="US 7" <?php echo $size_filter == 'US 7' ? 'selected' : ''; ?>>US 7</option>
                        <option value="US 8" <?php echo $size_filter == 'US 8' ? 'selected' : ''; ?>>US 8</option>
                        <option value="US 9" <?php echo $size_filter == 'US 9' ? 'selected' : ''; ?>>US 9</option>
                        <option value="US 10" <?php echo $size_filter == 'US 10' ? 'selected' : ''; ?>>US 10</option>
                        <option value="US 11" <?php echo $size_filter == 'US 11' ? 'selected' : ''; ?>>US 11</option>
                        <option value="US 12" <?php echo $size_filter == 'US 12' ? 'selected' : ''; ?>>US 12</option>
                        <option value="EU 40" <?php echo $size_filter == 'EU 40' ? 'selected' : ''; ?>>EU 40</option>
                        <option value="EU 41" <?php echo $size_filter == 'EU 41' ? 'selected' : ''; ?>>EU 41</option>
                        <option value="EU 42" <?php echo $size_filter == 'EU 42' ? 'selected' : ''; ?>>EU 42</option>
                        <option value="EU 43" <?php echo $size_filter == 'EU 43' ? 'selected' : ''; ?>>EU 43</option>
                        <option value="S" <?php echo $size_filter == 'S' ? 'selected' : ''; ?>>S</option>
                        <option value="M" <?php echo $size_filter == 'M' ? 'selected' : ''; ?>>M</option>
                        <option value="L" <?php echo $size_filter == 'L' ? 'selected' : ''; ?>>L</option>
                        <option value="XL" <?php echo $size_filter == 'XL' ? 'selected' : ''; ?>>XL</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Condition</label>
                    <select name="condition">
                        <option value="">All Conditions</option>
                        <option value="new_with_tags" <?php echo $condition_filter == 'new_with_tags' ? 'selected' : ''; ?>>New with tags</option>
                        <option value="like_new" <?php echo $condition_filter == 'like_new' ? 'selected' : ''; ?>>Like new</option>
                        <option value="very_good" <?php echo $condition_filter == 'very_good' ? 'selected' : ''; ?>>Very good</option>
                        <option value="good" <?php echo $condition_filter == 'good' ? 'selected' : ''; ?>>Good</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Price Range (ZAR)</label>
                    <div class="price-range">
                        <input type="number" name="min_price" placeholder="Min" value="<?php echo $min_price; ?>">
                        <span>to</span>
                        <input type="number" name="max_price" placeholder="Max" value="<?php echo $max_price; ?>">
                    </div>
                </div>

                <div class="filter-group">
                    <label>Authentication Status</label>
                    <select name="auth_status">
                        <option value="">All</option>
                        <option value="verified" <?php echo $auth_filter == 'verified' ? 'selected' : ''; ?>>Verified</option>
                        <option value="pending" <?php echo $auth_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="index.php" class="btn btn-secondary">Clear Filters</a>
            </form>
        </aside>

        <!-- Product Grid -->
        <main class="product-grid">
            <h2>Latest Label Clothing</h2>
            <?php if ($result->num_rows > 0): ?>
                <div class="products">
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if ($product['image']): ?>
                                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['label_name']); ?>">
                                <?php else: ?>
                                    <div class="placeholder-image">No Image</div>
                                <?php endif; ?>
                                <span class="auth-badge <?php echo $product['auth_status']; ?>">
                                    <?php echo ucfirst($product['auth_status']); ?>
                                </span>
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['brand']); ?></h3>
                                <p class="label-name"><?php echo htmlspecialchars($product['label_name']); ?></p>
                                <p class="size">Size: <?php echo htmlspecialchars($product['size']); ?></p>
                                <p class="condition"><?php echo ucfirst(str_replace('_', ' ', $product['condition'])); ?></p>
                                <p class="price"><?php echo format_price($product['price']); ?></p>
                                <p class="seller">Sold by: <?php echo htmlspecialchars($product['seller_name']); ?></p>
                                <div class="product-actions">
                                    <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary">View Details</a>
                                    <?php if (is_logged_in()): ?>
                                        <a href="purchase.php?id=<?php echo $product['product_id']; ?>" class="btn btn-secondary">Buy Now</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="no-products">No products found matching your filters.</p>
            <?php endif; ?>
        </main>
    </div>

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
                <div class="footer-section">
                    <h3>Connect</h3>
                    <ul>
                        <li><a href="https://github.com/thabisomosime21-create" target="_blank">GitHub Profile</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 The Outlet - Authentic Streetwear & Designer Marketplace | <a href="device-preview.php" style="color:#ffd700;">Device Preview</a></p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
