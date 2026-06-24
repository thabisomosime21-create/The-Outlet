<?php
include 'config.php';

ensure_payments_table($conn);

// Check if user is logged in
if (!is_logged_in()) {
    redirect('/login.php');
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Get product details
$query = "SELECT p.*, u.name as seller_name, u.email as seller_email, u.user_id as seller_id 
          FROM products p 
          JOIN users u ON p.seller_id = u.user_id 
          WHERE p.product_id = $product_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die('Product not found.');
}

$product = $result->fetch_assoc();

// Check if user is trying to buy their own product
if ($product['seller_id'] == $user_id) {
    die('You cannot buy your own product.');
}

$error = '';
$success = '';
$payment_reference = '';

$cardholder_name = '';
$card_number = '';
$card_expiry = '';
$card_cvv = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cardholder_name = trim($_POST['cardholder_name'] ?? '');
    $card_number = preg_replace('/\D/', '', $_POST['card_number'] ?? '');
    $card_expiry = trim($_POST['card_expiry'] ?? '');
    $card_cvv = trim($_POST['card_cvv'] ?? '');

    if ($cardholder_name === '') {
        $error = 'Please enter the name on the card.';
    } elseif (!validate_card_number($card_number)) {
        $error = 'Please enter a valid card number.';
    } elseif (!validate_card_expiry($card_expiry)) {
        $error = 'Please enter a valid expiry date (MM/YY).';
    } elseif (!validate_cvv($card_cvv)) {
        $error = 'Please enter a valid CVV (3 or 4 digits).';
    } else {
        $amount = $product['price'];
        $seller_id = $product['seller_id'];
        $card_brand = get_card_brand($card_number);
        $card_last_four = substr($card_number, -4);

        $conn->begin_transaction();

        try {
            $order_query = "INSERT INTO orders (buyer_id, seller_id, product_id, amount, status) 
                            VALUES ($user_id, $seller_id, $product_id, $amount, 'pending')";

            if (!$conn->query($order_query)) {
                throw new Exception('Failed to create order.');
            }

            $order_id = $conn->insert_id;

            $escrow_query = "INSERT INTO escrow_transactions (order_id, amount, status) 
                             VALUES ($order_id, $amount, 'held')";

            if (!$conn->query($escrow_query)) {
                throw new Exception('Failed to create escrow transaction.');
            }

            $safe_name = sanitize($conn, $cardholder_name);
            $safe_brand = sanitize($conn, $card_brand);
            $payment_query = "INSERT INTO payments (order_id, cardholder_name, card_last_four, card_brand, payment_status) 
                              VALUES ($order_id, '$safe_name', '$card_last_four', '$safe_brand', 'authorized')";

            if (!$conn->query($payment_query)) {
                throw new Exception('Payment could not be processed.');
            }

            $conn->commit();
            $payment_reference = 'LL-' . strtoupper(substr(md5($order_id . $card_last_four), 0, 8));
            $success = 'Payment successful! ' . format_price($amount) . ' has been charged to your ' . $card_brand . ' ending in ' . $card_last_four . '. Funds are held in escrow until you confirm receipt.';
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Purchase failed: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase - The Outlet</title>
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
                <?php if (has_role('seller') || has_role('admin')): ?>
                    <li><a href="sell.php">Sell</a></li>
                <?php endif; ?>
                <li><a href="wishlist.php">Wishlist</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Purchase Section -->
    <section class="purchase-section">
        <div class="container">
            <div class="purchase-box">
                <h1>Complete Your Purchase</h1>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                    <?php if ($payment_reference): ?>
                        <p class="payment-reference">Payment reference: <strong><?php echo htmlspecialchars($payment_reference); ?></strong></p>
                    <?php endif; ?>
                    <p><a href="dashboard.php">View your order in dashboard</a></p>
                <?php else: ?>

                <div class="order-summary">
                    <h2>Order Summary</h2>
                    <div class="product-summary">
                        <?php if ($product['image']): ?>
                            <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['label_name']); ?>">
                        <?php else: ?>
                            <div class="placeholder-image">No Image</div>
                        <?php endif; ?>
                        <div class="product-details">
                            <h3><?php echo htmlspecialchars($product['brand'] . ' - ' . $product['label_name']); ?></h3>
                            <p>Size: <?php echo htmlspecialchars($product['size']); ?></p>
                            <p>Condition: <?php echo ucfirst(str_replace('_', ' ', $product['condition'])); ?></p>
                            <p>Seller: <?php echo htmlspecialchars($product['seller_name']); ?></p>
                            <p class="price"><?php echo format_price($product['price']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="escrow-info">
                    <h3>Escrow Protection</h3>
                    <p>Your payment will be held in escrow until you confirm that you received the item and it is authentic.</p>
                    <ul>
                        <li>Payment is securely held</li>
                        <li>Seller ships the item</li>
                        <li>You confirm receipt and authenticity</li>
                        <li>Payment is released to seller</li>
                    </ul>
                </div>

                <form method="POST" action="purchase.php?id=<?php echo $product_id; ?>" class="purchase-form" id="purchase-form">
                    <div class="payment-section">
                        <h2>Payment Details</h2>
                        <p class="payment-note">Enter your card details to confirm this purchase. This is a demo checkout — no real charges are made.</p>

                        <div class="form-group">
                            <label for="cardholder_name">Name on Card *</label>
                            <input type="text" id="cardholder_name" name="cardholder_name" placeholder="John Doe" value="<?php echo htmlspecialchars($cardholder_name); ?>" required autocomplete="cc-name">
                        </div>

                        <div class="form-group">
                            <label for="card_number">Card Number *</label>
                            <input type="text" id="card_number" name="card_number" placeholder="4111 1111 1111 1111" value="<?php echo htmlspecialchars($card_number); ?>" inputmode="numeric" maxlength="19" required autocomplete="cc-number">
                        </div>

                        <div class="payment-row">
                            <div class="form-group">
                                <label for="card_expiry">Expiry Date *</label>
                                <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" value="<?php echo htmlspecialchars($card_expiry); ?>" maxlength="5" required autocomplete="cc-exp">
                            </div>
                            <div class="form-group">
                                <label for="card_cvv">CVV *</label>
                                <input type="password" id="card_cvv" name="card_cvv" placeholder="123" maxlength="4" required autocomplete="cc-csc">
                                <small>3 or 4 digits on the back of your card</small>
                            </div>
                        </div>

                        <div class="payment-total">
                            <span>Total to pay</span>
                            <strong><?php echo format_price($product['price']); ?></strong>
                        </div>
                    </div>

                    <div class="purchase-actions">
                        <button type="submit" class="btn btn-primary btn-large">Pay &amp; Confirm Purchase</button>
                        <a href="product.php?id=<?php echo $product_id; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
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
