<?php
include '../config.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !has_role('admin')) {
    header("Location: ../login.php");
    exit();
}

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get user details
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die('User not found.');
}

$user = $result->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $role = sanitize($conn, $_POST['role']);
    $status = sanitize($conn, $_POST['status']);

    // Update user
    $update = "UPDATE users SET name = '$name', email = '$email', role = '$role', status = '$status' WHERE user_id = $user_id";

    if ($conn->query($update)) {
        $success = 'User updated successfully!';
    } else {
        $error = 'Failed to update user.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - The Outlet Admin</title>
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
                <li><a href="manage_users.php" class="active">Users</a></li>
                <li><a href="manage_products.php">Products</a></li>
                <li><a href="disputes.php">Disputes</a></li>
                <li><a href="../index.php">View Site</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Edit User Section -->
    <section class="admin-section">
        <div class="container">
            <h1>Edit User</h1>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <p><a href="manage_users.php">Return to users list</a></p>
            <?php else: ?>

            <form method="POST" action="edit_user.php?id=<?php echo $user_id; ?>" class="admin-form">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="buyer" <?php echo $user['role'] == 'buyer' ? 'selected' : ''; ?>>Buyer</option>
                        <option value="seller" <?php echo $user['role'] == 'seller' ? 'selected' : ''; ?>>Seller</option>
                        <option value="moderator" <?php echo $user['role'] == 'moderator' ? 'selected' : ''; ?>>Moderator</option>
                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="active" <?php echo $user['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="suspended" <?php echo $user['status'] == 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
            </form>
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
