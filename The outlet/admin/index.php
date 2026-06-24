<?php
session_start();
include '../config.php';

// Handle admin logout
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    unset($_SESSION['admin_authenticated']);
    header("Location: index.php");
    exit();
}

// Simple password protection for admin access
$admin_password = "admin123"; // Change this to your desired password

// Check if admin is authenticated via password
if (!isset($_SESSION['admin_authenticated']) || $_SESSION['admin_authenticated'] !== true) {
    // Check if password was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_password'])) {
        if ($_POST['admin_password'] === $admin_password) {
            $_SESSION['admin_authenticated'] = true;
            // Redirect to prevent form resubmission
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password";
        }
    }
    
    // Show login form if not authenticated
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - The Outlet</title>
        <link rel="stylesheet" href="../css/style.css">
        <style>
            .admin-login {
                max-width: 400px;
                margin: 100px auto;
                padding: 30px;
                background: #1a1a2e;
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0,0,0,0.3);
            }
            .admin-login h1 {
                text-align: center;
                color: #ffd700;
                margin-bottom: 20px;
            }
            .admin-login .form-group {
                margin-bottom: 20px;
            }
            .admin-login label {
                display: block;
                color: #fff;
                margin-bottom: 5px;
            }
            .admin-login input[type="password"] {
                width: 100%;
                padding: 10px;
                border: 1px solid #333;
                border-radius: 5px;
                background: #16213e;
                color: #fff;
            }
            .admin-login button {
                width: 100%;
                padding: 12px;
                background: #ffd700;
                color: #1a1a2e;
                border: none;
                border-radius: 5px;
                font-weight: bold;
                cursor: pointer;
            }
            .admin-login button:hover {
                background: #e6c200;
            }
            .admin-login .error {
                color: #ff6b6b;
                text-align: center;
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div class="admin-login">
            <h1>Admin Access</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="admin_password">Enter Admin Password:</label>
                    <input type="password" id="admin_password" name="admin_password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <p style="text-align: center; margin-top: 20px; color: #666;">
                <a href="../index.php" style="color: #ffd700;">Return to Site</a>
            </p>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Get dashboard statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$pending_disputes = $conn->query("SELECT COUNT(*) as count FROM disputes WHERE status = 'open'")->fetch_assoc()['count'];
$pending_verifications = $conn->query("SELECT COUNT(*) as count FROM products WHERE auth_status = 'pending'")->fetch_assoc()['count'];

// Get recent orders
$recent_orders = $conn->query("SELECT o.*, u1.name as buyer_name, u2.name as seller_name 
                               FROM orders o 
                               JOIN users u1 ON o.buyer_id = u1.user_id 
                               JOIN users u2 ON o.seller_id = u2.user_id 
                               ORDER BY o.created_at DESC LIMIT 5");

// Get recent products
$recent_products = $conn->query("SELECT p.*, u.name as seller_name 
                                  FROM products p 
                                  JOIN users u ON p.seller_id = u.user_id 
                                  ORDER BY p.created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The Outlet</title>
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
                <li><a href="index.php" class="active">Dashboard</a></li>
                <li><a href="manage_users.php">Users</a></li>
                <li><a href="manage_products.php">Products</a></li>
                <li><a href="disputes.php">Disputes</a></li>
                <li><a href="../index.php">View Site</a></li>
                <li><a href="?logout=1">Admin Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Admin Dashboard -->
    <section class="admin-dashboard">
        <div class="container">
            <h1>Admin Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> (<?php echo ucfirst($_SESSION['role']); ?>)</p>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p class="stat-number"><?php echo $total_users; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <p class="stat-number"><?php echo $total_products; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p class="stat-number"><?php echo $total_orders; ?></p>
                </div>
                <div class="stat-card warning">
                    <h3>Pending Disputes</h3>
                    <p class="stat-number"><?php echo $pending_disputes; ?></p>
                </div>
                <div class="stat-card info">
                    <h3>Pending Verifications</h3>
                    <p class="stat-number"><?php echo $pending_verifications; ?></p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="admin-section">
                <h2>Recent Orders</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Buyer</th>
                            <th>Seller</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $recent_orders->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $order['order_id']; ?></td>
                                <td><?php echo htmlspecialchars($order['buyer_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['seller_name']); ?></td>
                                <td><?php echo format_price($order['amount']); ?></td>
                                <td><span class="status <?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Recent Products -->
            <div class="admin-section">
                <h2>Recent Products</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Brand</th>
                            <th>Label Name</th>
                            <th>Seller</th>
                            <th>Price</th>
                            <th>Auth Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = $recent_products->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $product['product_id']; ?></td>
                                <td><?php echo htmlspecialchars($product['brand']); ?></td>
                                <td><?php echo htmlspecialchars($product['label_name']); ?></td>
                                <td><?php echo htmlspecialchars($product['seller_name']); ?></td>
                                <td><?php echo format_price($product['price']); ?></td>
                                <td><span class="auth-badge <?php echo $product['auth_status']; ?>"><?php echo ucfirst($product['auth_status']); ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
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
