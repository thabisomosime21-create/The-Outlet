<?php
include 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('/login.php');
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Verify this product belongs to this seller
$query = "SELECT * FROM products WHERE product_id = $product_id AND seller_id = $user_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die('Product not found.');
}

// Delete product
$delete = "DELETE FROM products WHERE product_id = $product_id";
if ($conn->query($delete)) {
    redirect('/dashboard.php');
} else {
    die('Failed to delete product.');
}
?>
