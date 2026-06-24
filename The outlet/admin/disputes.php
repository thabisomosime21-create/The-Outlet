<?php
include '../config.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !has_role('admin')) {
    header("Location: ../login.php");
    exit();
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? sanitize($conn, $_GET['status']) : '';

// Build query
$query = "SELECT d.*, o.order_id, o.amount, p.label_name, p.brand, 
          u1.name as raised_by_name, u2.name as buyer_name, u3.name as seller_name 
          FROM disputes d 
          JOIN orders o ON d.order_id = o.order_id 
          JOIN products p ON o.product_id = p.product_id 
          JOIN users u1 ON d.raised_by = u1.user_id 
          JOIN users u2 ON o.buyer_id = u2.user_id 
          JOIN users u3 ON o.seller_id = u3.user_id 
          WHERE 1=1";

if ($status_filter) {
    $query .= " AND d.status = '$status_filter'";
}

$query .= " ORDER BY d.created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disputes - The Outlet Admin</title>
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

    <!-- Disputes Section -->
    <section class="admin-section">
        <div class="container">
            <div class="section-header">
                <h1>Manage Disputes</h1>
                <form method="GET" action="disputes.php" class="filter-form">
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="open" <?php echo $status_filter == 'open' ? 'selected' : ''; ?>>Open</option>
                        <option value="investigating" <?php echo $status_filter == 'investigating' ? 'selected' : ''; ?>>Investigating</option>
                        <option value="resolved" <?php echo $status_filter == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                        <option value="closed" <?php echo $status_filter == 'closed' ? 'selected' : ''; ?>>Closed</option>
                    </select>
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </form>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Dispute ID</th>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Raised By</th>
                        <th>Buyer</th>
                        <th>Seller</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($dispute = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $dispute['dispute_id']; ?></td>
                            <td>#<?php echo $dispute['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($dispute['brand'] . ' - ' . $dispute['label_name']); ?></td>
                            <td><?php echo htmlspecialchars($dispute['raised_by_name']); ?></td>
                            <td><?php echo htmlspecialchars($dispute['buyer_name']); ?></td>
                            <td><?php echo htmlspecialchars($dispute['seller_name']); ?></td>
                            <td><?php echo format_price($dispute['amount']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $dispute['status']; ?>">
                                    <?php echo ucfirst($dispute['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($dispute['created_at'])); ?></td>
                            <td>
                                <a href="resolve_dispute.php?id=<?php echo $dispute['dispute_id']; ?>" class="btn btn-sm btn-secondary">Resolve</a>
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
