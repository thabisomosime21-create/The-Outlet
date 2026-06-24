<?php
include 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('/login.php');
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Check if product exists
$query = "SELECT * FROM products WHERE product_id = $product_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die('Product not found.');
}

// Check if already in wishlist
$check = "SELECT * FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
$check_result = $conn->query($check);

if ($check_result->num_rows > 0) {
    redirect('/wishlist.php');
}

// Add to wishlist
$insert = "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)";
if ($conn->query($insert)) {
    redirect('/wishlist.php');
} else {
    die('Failed to add to wishlist.');
}
?>
