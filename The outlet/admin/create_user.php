<?php
include '../config.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !has_role('admin')) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $password = sanitize($conn, $_POST['password']);
    $role = sanitize($conn, $_POST['role']);
    $status = sanitize($conn, $_POST['status']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check if email already exists
        $check_email = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($check_email);

        if ($result->num_rows > 0) {
            $error = 'Email already registered.';
        } else {
            // Hash password
            $hashed_password = md5($password);

            // Insert user
            $query = "INSERT INTO users (name, email, password, role, status) VALUES ('$name', '$email', '$hashed_password', '$role', '$status')";

            if ($conn->query($query)) {
                $success = 'User created successfully!';
            } else {
                $error = 'Failed to create user.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - The Outlet Admin</title>
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

    <!-- Create User Section -->
    <section class="admin-section">
        <div class="container">
            <h1>Create New User</h1>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <p><a href="manage_users.php">Return to users list</a></p>
            <?php else: ?>

            <form method="POST" action="create_user.php" class="admin-form">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="buyer">Buyer</option>
                        <option value="seller">Seller</option>
                        <option value="moderator">Moderator</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Create User</button>
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
