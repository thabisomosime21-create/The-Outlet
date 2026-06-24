<?php
include 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('/login.php');
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$user_id = $_SESSION['user_id'];
$description = isset($_GET['description']) ? sanitize($conn, $_GET['description']) : '';

// Verify this order belongs to this user (buyer or seller)
$query = "SELECT * FROM orders WHERE order_id = $order_id AND (buyer_id = $user_id OR seller_id = $user_id)";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die('Order not found.');
}

// Check if dispute already exists
$check = "SELECT * FROM disputes WHERE order_id = $order_id";
$check_result = $conn->query($check);

if ($check_result->num_rows > 0) {
    die('Dispute already raised for this order.');
}

// Create dispute
$insert = "INSERT INTO disputes (order_id, raised_by, description, status) VALUES ($order_id, $user_id, '$description', 'open')";
if ($conn->query($insert)) {
    redirect('/dashboard.php');
} else {
    die('Failed to raise dispute.');
}
?>
