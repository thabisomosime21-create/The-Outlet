<?php
include '../config.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !has_role('admin')) {
    header("Location: ../login.php");
    exit();
}

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Prevent deleting yourself
if ($user_id == $_SESSION['user_id']) {
    die('You cannot delete your own account.');
}

// Delete user
$delete = "DELETE FROM users WHERE user_id = $user_id";
if ($conn->query($delete)) {
    header("Location: manage_users.php");
    exit();
} else {
    die('Failed to delete user.');
}
?>
