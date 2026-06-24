<?php
include '../config.php';

// Check if user is logged in and is admin or moderator
if (!is_logged_in() || (!has_role('admin') && !has_role('moderator'))) {
    header("Location: ../login.php");
    exit();
}

// Get filter parameters
$status_filter = isset($_GET['auth_status']) ? sanitize($conn, $_GET['auth_status']) : '';

// Build query
$query = "SELECT p.*, u.name as seller_name FROM products p JOIN users u ON p.seller_id = u.user_id WHERE 1=1";

if ($status_filter) {
    $query .= " AND p.auth_status = '$status_filter'";
}

$query .= " ORDER BY p.created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - The Outlet Admin</title>
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

    <!-- Manage Products Section -->
    <section class="admin-section">
        <div class="container">
            <div class="section-header">
                <h1>Manage Products</h1>
                <form method="GET" action="manage_products.php" class="filter-form">
                    <select name="auth_status">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="verified" <?php echo $status_filter == 'verified' ? 'selected' : ''; ?>>Verified</option>
                        <option value="rejected" <?php echo $status_filter == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </form>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Brand</th>
                        <th>Label Name</th>
                        <th>Seller</th>
                        <th>Price</th>
                        <th>Auth Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td>
                                <?php if ($product['image']): ?>
                                    <img src="../uploads/<?php echo $product['image']; ?>" alt="" style="max-width: 50px; max-height: 50px;">
                                <?php else: ?>
                                    <span>No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($product['brand']); ?></td>
                            <td><?php echo htmlspecialchars($product['label_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['seller_name']); ?></td>
                            <td><?php echo format_price($product['price']); ?></td>
                            <td>
                                <span class="auth-badge <?php echo $product['auth_status']; ?>">
                                    <?php echo ucfirst($product['auth_status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="verify_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-secondary">Verify</a>
                                <?php if (has_role('admin')): ?>
                                    <a href="../product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-info">View</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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
