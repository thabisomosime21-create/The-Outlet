<?php
// The Outlet Database Configuration
// Edit these values to match your hosting database credentials

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'labelloom';

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Start session
session_start();

// Base URL (dynamically detected from current request)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['SCRIPT_NAME']);
$base_url = $protocol . '://' . $host . $path;

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check user role
function has_role($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// Function to check if user is admin or moderator
function is_admin() {
    return isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'moderator');
}

// Function to redirect
function redirect($url) {
    global $base_url;
    header("Location: " . $base_url . $url);
    exit();
}

// Function to sanitize input
function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, $input);
}

// Function to format price in ZAR
function format_price($price) {
    return 'R ' . number_format($price, 2);
}

// Payment validation helpers (simulated card processing for demo)
function validate_card_number($number) {
    $number = preg_replace('/\D/', '', $number);
    if (strlen($number) < 13 || strlen($number) > 19) {
        return false;
    }

    $sum = 0;
    $alternate = false;
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $digit = (int) $number[$i];
        if ($alternate) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        $sum += $digit;
        $alternate = !$alternate;
    }

    return $sum % 10 === 0;
}

function validate_card_expiry($expiry) {
    if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $expiry, $matches)) {
        return false;
    }

    $month = (int) $matches[1];
    $year = 2000 + (int) $matches[2];
    $expiry_date = DateTime::createFromFormat('Y-m-d', sprintf('%04d-%02d-01', $year, $month));
    if (!$expiry_date) {
        return false;
    }

    $expiry_date->modify('last day of this month')->setTime(23, 59, 59);
    return $expiry_date >= new DateTime();
}

function validate_cvv($cvv) {
    return preg_match('/^\d{3,4}$/', $cvv);
}

function get_card_brand($number) {
    $number = preg_replace('/\D/', '', $number);
    if (preg_match('/^4/', $number)) {
        return 'Visa';
    }
    if (preg_match('/^5[1-5]/', $number) || preg_match('/^2[2-7]/', $number)) {
        return 'Mastercard';
    }
    if (preg_match('/^3[47]/', $number)) {
        return 'Amex';
    }
    return 'Card';
}

function ensure_payments_table($conn) {
    $conn->query("CREATE TABLE IF NOT EXISTS payments (
        payment_id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL UNIQUE,
        cardholder_name VARCHAR(100) NOT NULL,
        card_last_four VARCHAR(4) NOT NULL,
        card_brand VARCHAR(20) NOT NULL,
        payment_status ENUM('authorized', 'failed', 'refunded') DEFAULT 'authorized',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
    )");
}
?>
