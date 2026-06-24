<?php
include 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('/login.php');
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$user_id = $_SESSION['user_id'];

// Verify this order belongs to this seller
$query = "SELECT * FROM orders WHERE order_id = $order_id AND seller_id = $user_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die('Order not found.');
}

// Update order status
$update = "UPDATE orders SET status = 'shipped' WHERE order_id = $order_id";
if ($conn->query($update)) {
    redirect('/dashboard.php');
} else {
    die('Failed to update order status.');
}
?>
