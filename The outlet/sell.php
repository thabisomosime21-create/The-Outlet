<?php
include 'config.php';

// Check if user is logged in and is a seller
if (!is_logged_in()) {
    redirect('/login.php');
}

if (!has_role('seller') && !has_role('admin')) {
    redirect('/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand = sanitize($conn, $_POST['brand']);
    $label_name = sanitize($conn, $_POST['label_name']);
    $size = sanitize($conn, $_POST['size']);
    $condition = sanitize($conn, $_POST['condition']);
    $price = floatval($_POST['price']);
    $description = sanitize($conn, $_POST['description']);
    $seller_id = $_SESSION['user_id'];

    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = 'uploads/';
        $image = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Image uploaded successfully
        } else {
            $error = 'Failed to upload image.';
        }
    }

    // Handle auth document upload
    $auth_document = '';
    if (isset($_FILES['auth_document']) && $_FILES['auth_document']['error'] == 0) {
        $target_dir = 'uploads/';
        $auth_document = 'auth_' . time() . '_' . basename($_FILES['auth_document']['name']);
        $target_file = $target_dir . $auth_document;
        
        if (move_uploaded_file($_FILES['auth_document']['tmp_name'], $target_file)) {
            // Document uploaded successfully
        } else {
            $error = 'Failed to upload authentication document.';
        }
    }

    if (empty($error)) {
        $query = "INSERT INTO products (seller_id, brand, label_name, size, condition, price, image, auth_document, description) 
                  VALUES ('$seller_id', '$brand', '$label_name', '$size', '$condition', '$price', '$image', '$auth_document', '$description')";

        if ($conn->query($query)) {
            $success = 'Product listed successfully! Waiting for admin verification.';
        } else {
            $error = 'Failed to list product. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell - The Outlet</title>
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
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="sell.php" class="active">Sell</a></li>
                <li><a href="wishlist.php">Wishlist</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <?php if (has_role('admin') || has_role('moderator')): ?>
                    <li><a href="admin/index.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Sell Section -->
    <section class="sell-section">
        <div class="container">
            <div class="sell-box">
                <h2>List Your Item</h2>
                <p>Sell authentic designer and streetwear clothing</p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                    <p><a href="dashboard.php">View your listings</a></p>
                <?php else: ?>

                <form method="POST" action="sell.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="brand">Brand *</label>
                        <select id="brand" name="brand" required>
                            <option value="">Select Brand</option>
                            <option value="Nike">Nike</option>
                            <option value="Adidas">Adidas</option>
                            <option value="Gucci">Gucci</option>
                            <option value="Louis Vuitton">Louis Vuitton</option>
                            <option value="Off-White">Off-White</option>
                            <option value="Supreme">Supreme</option>
                            <option value="Essentials">Essentials</option>
                            <option value="Balenciaga">Balenciaga</option>
                            <option value="Fear of God">Fear of God</option>
                            <option value="Yeezy">Yeezy</option>
                            <option value="Jordan">Jordan</option>
                            <option value="Palm Angels">Palm Angels</option>
                            <option value="Vlone">Vlone</option>
                            <option value="Dior">Dior</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="label_name">Label/Collection Name *</label>
                        <input type="text" id="label_name" name="label_name" placeholder="e.g., Yeezy Boost 350 V2, Essentials Hoodie" required>
                    </div>

                    <div class="form-group">
                        <label for="size">Size *</label>
                        <select id="size" name="size" required>
                            <option value="">Select Size</option>
                            <optgroup label="Shoe Sizes (US)">
                                <option value="US 6">US 6</option>
                                <option value="US 7">US 7</option>
                                <option value="US 8">US 8</option>
                                <option value="US 9">US 9</option>
                                <option value="US 10">US 10</option>
                                <option value="US 11">US 11</option>
                                <option value="US 12">US 12</option>
                                <option value="US 13">US 13</option>
                            </optgroup>
                            <optgroup label="Shoe Sizes (EU)">
                                <option value="EU 39">EU 39</option>
                                <option value="EU 40">EU 40</option>
                                <option value="EU 41">EU 41</option>
                                <option value="EU 42">EU 42</option>
                                <option value="EU 43">EU 43</option>
                                <option value="EU 44">EU 44</option>
                                <option value="EU 45">EU 45</option>
                            </optgroup>
                            <optgroup label="Clothing Sizes">
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="condition">Condition *</label>
                        <select id="condition" name="condition" required>
                            <option value="">Select Condition</option>
                            <option value="new_with_tags">New with tags</option>
                            <option value="like_new">Like new</option>
                            <option value="very_good">Very good</option>
                            <option value="good">Good</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="price">Price (ZAR) *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="image">Product Image *</label>
                        <input type="file" id="image" name="image" accept="image/*" required>
                    </div>

                    <div class="form-group">
                        <label for="auth_document">Authentication Document (Receipt/Auth Card)</label>
                        <input type="file" id="auth_document" name="auth_document" accept="image/*">
                        <small>Upload proof of authenticity (receipt, authenticity card, etc.)</small>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Describe your item, include any defects or special features"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">List Item</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>The Outlet</h3>
                    <p>Authentic Streetwear & Designer Marketplace in South Africa</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="contact.php">Contact Admin</a></li>
                        <li><a href="contact.php">Report an Issue</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 The Outlet - Authentic Streetwear & Designer Marketplace</p>
            </div>
        </div>
    </footer>
    <script src="js/main.js"></script>
</body>
</html>
