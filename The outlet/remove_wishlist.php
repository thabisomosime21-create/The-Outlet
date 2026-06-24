<?php
include 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('/login.php');
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Remove from wishlist
$delete = "DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
if ($conn->query($delete)) {
    redirect('/wishlist.php');
} else {
    die('Failed to remove from wishlist.');
}
?>
