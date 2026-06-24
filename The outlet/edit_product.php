<?php
include 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    redirect('/login.php');
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Get product details
$query = "SELECT * FROM products WHERE product_id = $product_id AND seller_id = $user_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die('Product not found.');
}

$product = $result->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand = sanitize($conn, $_POST['brand']);
    $label_name = sanitize($conn, $_POST['label_name']);
    $size = sanitize($conn, $_POST['size']);
    $condition = sanitize($conn, $_POST['condition']);
    $price = floatval($_POST['price']);
    $description = sanitize($conn, $_POST['description']);

    // Handle image upload
    $image = $product['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = 'uploads/';
        $image = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image;
        
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $error = 'Failed to upload image.';
        }
    }

    if (empty($error)) {
        $update = "UPDATE products SET brand = '$brand', label_name = '$label_name', size = '$size', condition = '$condition', price = '$price', image = '$image', description = '$description' WHERE product_id = $product_id";

        if ($conn->query($update)) {
            $success = 'Product updated successfully!';
        } else {
            $error = 'Failed to update product.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - The Outlet</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php">The Outlet</a>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="sell.php">Sell</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Edit Product Section -->
    <section class="sell-section">
        <div class="container">
            <div class="sell-box">
                <h2>Edit Product</h2>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                    <p><a href="dashboard.php">Return to dashboard</a></p>
                <?php else: ?>

                <form method="POST" action="edit_product.php?id=<?php echo $product_id; ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="brand">Brand *</label>
                        <select id="brand" name="brand" required>
                            <option value="">Select Brand</option>
                            <option value="Nike" <?php echo $product['brand'] == 'Nike' ? 'selected' : ''; ?>>Nike</option>
                            <option value="Adidas" <?php echo $product['brand'] == 'Adidas' ? 'selected' : ''; ?>>Adidas</option>
                            <option value="Gucci" <?php echo $product['brand'] == 'Gucci' ? 'selected' : ''; ?>>Gucci</option>
                            <option value="Louis Vuitton" <?php echo $product['brand'] == 'Louis Vuitton' ? 'selected' : ''; ?>>Louis Vuitton</option>
                            <option value="Off-White" <?php echo $product['brand'] == 'Off-White' ? 'selected' : ''; ?>>Off-White</option>
                            <option value="Supreme" <?php echo $product['brand'] == 'Supreme' ? 'selected' : ''; ?>>Supreme</option>
                            <option value="Essentials" <?php echo $product['brand'] == 'Essentials' ? 'selected' : ''; ?>>Essentials</option>
                            <option value="Balenciaga" <?php echo $product['brand'] == 'Balenciaga' ? 'selected' : ''; ?>>Balenciaga</option>
                            <option value="Fear of God" <?php echo $product['brand'] == 'Fear of God' ? 'selected' : ''; ?>>Fear of God</option>
                            <option value="Yeezy" <?php echo $product['brand'] == 'Yeezy' ? 'selected' : ''; ?>>Yeezy</option>
                            <option value="Jordan" <?php echo $product['brand'] == 'Jordan' ? 'selected' : ''; ?>>Jordan</option>
                            <option value="Other" <?php echo $product['brand'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="label_name">Label/Collection Name *</label>
                        <input type="text" id="label_name" name="label_name" value="<?php echo htmlspecialchars($product['label_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="size">Size *</label>
                        <select id="size" name="size" required>
                            <option value="">Select Size</option>
                            <option value="US 6" <?php echo $product['size'] == 'US 6' ? 'selected' : ''; ?>>US 6</option>
                            <option value="US 7" <?php echo $product['size'] == 'US 7' ? 'selected' : ''; ?>>US 7</option>
                            <option value="US 8" <?php echo $product['size'] == 'US 8' ? 'selected' : ''; ?>>US 8</option>
                            <option value="US 9" <?php echo $product['size'] == 'US 9' ? 'selected' : ''; ?>>US 9</option>
                            <option value="US 10" <?php echo $product['size'] == 'US 10' ? 'selected' : ''; ?>>US 10</option>
                            <option value="US 11" <?php echo $product['size'] == 'US 11' ? 'selected' : ''; ?>>US 11</option>
                            <option value="US 12" <?php echo $product['size'] == 'US 12' ? 'selected' : ''; ?>>US 12</option>
                            <option value="EU 40" <?php echo $product['size'] == 'EU 40' ? 'selected' : ''; ?>>EU 40</option>
                            <option value="EU 41" <?php echo $product['size'] == 'EU 41' ? 'selected' : ''; ?>>EU 41</option>
                            <option value="EU 42" <?php echo $product['size'] == 'EU 42' ? 'selected' : ''; ?>>EU 42</option>
                            <option value="EU 43" <?php echo $product['size'] == 'EU 43' ? 'selected' : ''; ?>>EU 43</option>
                            <option value="S" <?php echo $product['size'] == 'S' ? 'selected' : ''; ?>>S</option>
                            <option value="M" <?php echo $product['size'] == 'M' ? 'selected' : ''; ?>>M</option>
                            <option value="L" <?php echo $product['size'] == 'L' ? 'selected' : ''; ?>>L</option>
                            <option value="XL" <?php echo $product['size'] == 'XL' ? 'selected' : ''; ?>>XL</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="condition">Condition *</label>
                        <select id="condition" name="condition" required>
                            <option value="">Select Condition</option>
                            <option value="new_with_tags" <?php echo $product['condition'] == 'new_with_tags' ? 'selected' : ''; ?>>New with tags</option>
                            <option value="like_new" <?php echo $product['condition'] == 'like_new' ? 'selected' : ''; ?>>Like new</option>
                            <option value="very_good" <?php echo $product['condition'] == 'very_good' ? 'selected' : ''; ?>>Very good</option>
                            <option value="good" <?php echo $product['condition'] == 'good' ? 'selected' : ''; ?>>Good</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="price">Price (ZAR) *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo $product['price']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <?php if ($product['image']): ?>
                            <p>Current: <img src="uploads/<?php echo $product['image']; ?>" style="max-height: 100px;"></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Update Product</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 The Outlet - Authentic Streetwear & Designer Marketplace</p>
        </div>
    </footer>
    <script src="js/main.js"></script>
</body>
</html>
