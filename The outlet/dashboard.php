<?php
include 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('/login.php');
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Get user's orders (as buyer)
$buyer_orders_query = "SELECT o.*, p.label_name, p.brand, p.image, u.name as seller_name 
                       FROM orders o 
                       JOIN products p ON o.product_id = p.product_id 
                       JOIN users u ON o.seller_id = u.user_id 
                       WHERE o.buyer_id = $user_id 
                       ORDER BY o.created_at DESC";
$buyer_orders = $conn->query($buyer_orders_query);

// Get user's sales (as seller)
$seller_orders_query = "SELECT o.*, p.label_name, p.brand, p.image, u.name as buyer_name 
                       FROM orders o 
                       JOIN products p ON o.product_id = p.product_id 
                       JOIN users u ON o.buyer_id = u.user_id 
                       WHERE o.seller_id = $user_id 
                       ORDER BY o.created_at DESC";
$seller_orders = $conn->query($seller_orders_query);

// Get user's products (if seller)
$products_query = "SELECT * FROM products WHERE seller_id = $user_id ORDER BY created_at DESC";
$products = $conn->query($products_query);

// Get user's wishlist
$wishlist_query = "SELECT w.*, p.brand, p.label_name, p.price, p.image, p.size, p.condition 
                  FROM wishlist w 
                  JOIN products p ON w.product_id = p.product_id 
                  WHERE w.user_id = $user_id 
                  ORDER BY w.created_at DESC";
$wishlist = $conn->query($wishlist_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - The Outlet</title>
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
                <?php if ($user_role == 'seller' || $user_role == 'admin'): ?>
                    <li><a href="sell.php">Sell</a></li>
                <?php endif; ?>
                <li><a href="wishlist.php">Wishlist</a></li>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <?php if ($user_role == 'admin' || $user_role == 'moderator'): ?>
                    <li><a href="admin/index.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Dashboard Section -->
    <section class="dashboard-section">
        <div class="container">
            <div class="dashboard-header">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
                <p class="user-role">Role: <?php echo ucfirst($user_role); ?></p>
            </div>

            <!-- Dashboard Tabs -->
            <div class="dashboard-tabs">
                <button class="tab-btn active" onclick="showTab('purchases')">My Purchases</button>
                <?php if ($user_role == 'seller' || $user_role == 'admin'): ?>
                    <button class="tab-btn" onclick="showTab('sales')">My Sales</button>
                    <button class="tab-btn" onclick="showTab('listings')">My Listings</button>
                <?php endif; ?>
                <button class="tab-btn" onclick="showTab('wishlist')">Wishlist</button>
            </div>

            <!-- Purchases Tab -->
            <div id="purchases" class="tab-content active">
                <h2>My Purchases</h2>
                <?php if ($buyer_orders->num_rows > 0): ?>
                    <div class="orders-list">
                        <?php while ($order = $buyer_orders->fetch_assoc()): ?>
                            <div class="order-card">
                                <div class="order-info">
                                    <h3><?php echo htmlspecialchars($order['brand'] . ' - ' . $order['label_name']); ?></h3>
                                    <p>Seller: <?php echo htmlspecialchars($order['seller_name']); ?></p>
                                    <p>Amount: <?php echo format_price($order['amount']); ?></p>
                                    <p>Status: <span class="status <?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></p>
                                    <p>Date: <?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
                                </div>
                                <div class="order-actions">
                                    <?php if ($order['status'] == 'shipped'): ?>
                                        <a href="escrow_release.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-success">Confirm Receipt</a>
                                        <a href="#" class="btn btn-danger" onclick="openDispute(<?php echo $order['order_id']; ?>)">Raise Dispute</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>No purchases yet.</p>
                <?php endif; ?>
            </div>

            <?php if ($user_role == 'seller' || $user_role == 'admin'): ?>
            <!-- Sales Tab -->
            <div id="sales" class="tab-content">
                <h2>My Sales</h2>
                <?php if ($seller_orders->num_rows > 0): ?>
                    <div class="orders-list">
                        <?php while ($order = $seller_orders->fetch_assoc()): ?>
                            <div class="order-card">
                                <div class="order-info">
                                    <h3><?php echo htmlspecialchars($order['brand'] . ' - ' . $order['label_name']); ?></h3>
                                    <p>Buyer: <?php echo htmlspecialchars($order['buyer_name']); ?></p>
                                    <p>Amount: <?php echo format_price($order['amount']); ?></p>
                                    <p>Status: <span class="status <?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></p>
                                    <p>Date: <?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
                                </div>
                                <div class="order-actions">
                                    <?php if ($order['status'] == 'pending'): ?>
                                        <a href="mark_shipped.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-primary">Mark as Shipped</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>No sales yet.</p>
                <?php endif; ?>
            </div>

            <!-- Listings Tab -->
            <div id="listings" class="tab-content">
                <h2>My Listings</h2>
                <a href="sell.php" class="btn btn-primary">Add New Listing</a>
                <?php if ($products->num_rows > 0): ?>
                    <div class="products-list">
                        <?php while ($product = $products->fetch_assoc()): ?>
                            <div class="product-card-small">
                                <div class="product-image">
                                    <?php if ($product['image']): ?>
                                        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['label_name']); ?>">
                                    <?php else: ?>
                                        <div class="placeholder-image">No Image</div>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($product['brand'] . ' - ' . $product['label_name']); ?></h3>
                                    <p>Price: <?php echo format_price($product['price']); ?></p>
                                    <p>Status: <span class="auth-badge <?php echo $product['auth_status']; ?>"><?php echo ucfirst($product['auth_status']); ?></span></p>
                                    <div class="product-actions">
                                        <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-secondary">Edit</a>
                                        <a href="delete_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>No listings yet.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Wishlist Tab -->
            <div id="wishlist" class="tab-content">
                <h2>My Wishlist</h2>
                <?php if ($wishlist->num_rows > 0): ?>
                    <div class="products-list">
                        <?php while ($item = $wishlist->fetch_assoc()): ?>
                            <div class="product-card-small">
                                <div class="product-image">
                                    <?php if ($item['image']): ?>
                                        <img src="uploads/<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['label_name']); ?>">
                                    <?php else: ?>
                                        <div class="placeholder-image">No Image</div>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($item['brand'] . ' - ' . $item['label_name']); ?></h3>
                                    <p>Price: <?php echo format_price($item['price']); ?></p>
                                    <p>Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                    <div class="product-actions">
                                        <a href="product.php?id=<?php echo $item['product_id']; ?>" class="btn btn-primary">View</a>
                                        <a href="remove_wishlist.php?id=<?php echo $item['product_id']; ?>" class="btn btn-danger">Remove</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>Your wishlist is empty.</p>
                <?php endif; ?>
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

    <script>
        function showTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabId).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }

        function openDispute(orderId) {
            const description = prompt('Please describe the issue:');
            if (description) {
                window.location.href = 'raise_dispute.php?order_id=' + orderId + '&description=' + encodeURIComponent(description);
            }
        }
    </script>
    <script src="js/main.js"></script>
</body>
</html>
