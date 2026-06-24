# The Outlet - Sample Code Excerpts

## Section 2.4: Sample Code Excerpts

### 1. PHP Code Excerpt - Database Connection (config.php)

```php
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

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check user role
function has_role($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// Function to format price in ZAR
function format_price($price) {
    return 'R ' . number_format($price, 2);
}
?>
```

---

### 2. PHP Code Excerpt - User Registration (register.php)

```php
<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $password = sanitize($conn, $_POST['password']);
    $confirm_password = sanitize($conn, $_POST['confirm_password']);
    $role = sanitize($conn, $_POST['role']);

    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check if email already exists
        $check_email = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($check_email);

        if ($result->num_rows > 0) {
            $error = 'Email already registered.';
        } else {
            // Hash password
            $hashed_password = md5($password);

            // Insert user
            $query = "INSERT INTO users (name, email, password, role) 
                      VALUES ('$name', '$email', '$hashed_password', '$role')";

            if ($conn->query($query)) {
                $success = 'Registration successful!';
            } else {
                $error = 'Registration failed.';
            }
        }
    }
}
?>
```

---

### 3. PHP Code Excerpt - Product Listing with Filters (index.php)

```php
<?php
include 'config.php';

// Get filter parameters
$brand_filter = isset($_GET['brand']) ? sanitize($conn, $_GET['brand']) : '';
$label_filter = isset($_GET['label']) ? sanitize($conn, $_GET['label']) : '';
$size_filter = isset($_GET['size']) ? sanitize($conn, $_GET['size']) : '';
$condition_filter = isset($_GET['condition']) ? sanitize($conn, $_GET['condition']) : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 10000;
$auth_filter = isset($_GET['auth_status']) ? sanitize($conn, $_GET['auth_status']) : '';

// Build query
$query = "SELECT p.*, u.name as seller_name 
          FROM products p 
          JOIN users u ON p.seller_id = u.user_id 
          WHERE 1=1";

if ($brand_filter) {
    $query .= " AND p.brand = '$brand_filter'";
}
if ($label_filter) {
    $query .= " AND p.label_name LIKE '%$label_filter%'";
}
if ($size_filter) {
    $query .= " AND p.size = '$size_filter'";
}
if ($condition_filter) {
    $query .= " AND p.condition = '$condition_filter'";
}
if ($auth_filter) {
    $query .= " AND p.auth_status = '$auth_filter'";
}
$query .= " AND p.price BETWEEN $min_price AND $max_price";
$query .= " ORDER BY p.created_at DESC";

$result = $conn->query($query);
?>
```

---

### 4. PHP Code Excerpt - Escrow Transaction (purchase.php)

```php
<?php
// Start transaction
$conn->begin_transaction();

try {
    // Create order
    $order_query = "INSERT INTO orders (buyer_id, seller_id, product_id, amount, status) 
                    VALUES ($user_id, $seller_id, $product_id, $amount, 'pending')";
    
    if (!$conn->query($order_query)) {
        throw new Exception('Failed to create order.');
    }

    $order_id = $conn->insert_id;

    // Create escrow transaction
    $escrow_query = "INSERT INTO escrow_transactions (order_id, amount, status) 
                     VALUES ($order_id, $amount, 'held')";
    
    if (!$conn->query($escrow_query)) {
        throw new Exception('Failed to create escrow transaction.');
    }

    // Commit transaction
    $conn->commit();
    $success = 'Purchase successful! Payment is held in escrow.';
} catch (Exception $e) {
    $conn->rollback();
    $error = 'Purchase failed: ' . $e->getMessage();
}
?>
```

---

### 5. PHP Code Excerpt - Admin RBAC Check (admin/index.php)

```php
<?php
include '../config.php';

// Check if user is logged in and is admin
if (!is_logged_in()) {
    header("Location: ../login.php");
    exit();
}

if (!has_role('admin') && !has_role('moderator')) {
    header("Location: ../index.php");
    exit();
}

// Get dashboard statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$pending_disputes = $conn->query("SELECT COUNT(*) as count FROM disputes WHERE status = 'open'")->fetch_assoc()['count'];
$pending_verifications = $conn->query("SELECT COUNT(*) as count FROM products WHERE auth_status = 'pending'")->fetch_assoc()['count'];
?>
```

---

### 6. HTML Code Excerpt - Product Card (index.php)

```html
<div class="product-card">
    <div class="product-image">
        <?php if ($product['image']): ?>
            <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['label_name']); ?>">
        <?php else: ?>
            <div class="placeholder-image">No Image</div>
        <?php endif; ?>
        <span class="auth-badge <?php echo $product['auth_status']; ?>">
            <?php echo ucfirst($product['auth_status']); ?>
        </span>
    </div>
    <div class="product-info">
        <h3><?php echo htmlspecialchars($product['brand']); ?></h3>
        <p class="label-name"><?php echo htmlspecialchars($product['label_name']); ?></p>
        <p class="size">Size: <?php echo htmlspecialchars($product['size']); ?></p>
        <p class="condition"><?php echo ucfirst(str_replace('_', ' ', $product['condition'])); ?></p>
        <p class="price"><?php echo format_price($product['price']); ?></p>
        <p class="seller">Sold by: <?php echo htmlspecialchars($product['seller_name']); ?></p>
        <div class="product-actions">
            <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary">View Details</a>
            <?php if (is_logged_in()): ?>
                <a href="purchase.php?id=<?php echo $product['product_id']; ?>" class="btn btn-secondary">Buy Now</a>
            <?php endif; ?>
        </div>
    </div>
</div>
```

---

### 7. HTML Code Excerpt - Filter Form (index.php)

```html
<aside class="filters">
    <h3>Filter Products</h3>
    <form method="GET" action="index.php">
        <div class="filter-group">
            <label>Brand</label>
            <select name="brand">
                <option value="">All Brands</option>
                <?php while ($brand = $brands_result->fetch_assoc()): ?>
                    <option value="<?php echo $brand['brand']; ?>" <?php echo $brand_filter == $brand['brand'] ? 'selected' : ''; ?>>
                        <?php echo $brand['brand']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="filter-group">
            <label>Label Name</label>
            <input type="text" name="label" placeholder="Search label..." value="<?php echo htmlspecialchars($label_filter); ?>">
        </div>

        <div class="filter-group">
            <label>Size</label>
            <select name="size">
                <option value="">All Sizes</option>
                <option value="US 7">US 7</option>
                <option value="US 8">US 8</option>
                <option value="US 9">US 9</option>
                <option value="US 10">US 10</option>
                <option value="US 11">US 11</option>
                <option value="US 12">US 12</option>
                <option value="EU 40">EU 40</option>
                <option value="EU 41">EU 41</option>
                <option value="EU 42">EU 42</option>
                <option value="EU 43">EU 43</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Condition</label>
            <select name="condition">
                <option value="">All Conditions</option>
                <option value="new_with_tags">New with tags</option>
                <option value="like_new">Like new</option>
                <option value="very_good">Very good</option>
                <option value="good">Good</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Price Range (ZAR)</label>
            <div class="price-range">
                <input type="number" name="min_price" placeholder="Min" value="<?php echo $min_price; ?>">
                <span>to</span>
                <input type="number" name="max_price" placeholder="Max" value="<?php echo $max_price; ?>">
            </div>
        </div>

        <div class="filter-group">
            <label>Authentication Status</label>
            <select name="auth_status">
                <option value="">All</option>
                <option value="verified">Verified</option>
                <option value="pending">Pending</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Apply Filters</button>
        <a href="index.php" class="btn btn-secondary">Clear Filters</a>
    </form>
</aside>
```

---

### 8. CSS Code Excerpt - Responsive Grid (style.css)

```css
/* Product Grid */
.products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.product-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .main-content {
        grid-template-columns: 1fr;
    }

    .filters {
        order: 2;
    }

    .product-grid {
        order: 1;
    }

    .products {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .products {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }

    .product-actions {
        flex-direction: column;
    }

    .product-actions a {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .products {
        grid-template-columns: 1fr;
    }
}
```

---

### 9. CSS Code Excerpt - Authentication Badges (style.css)

```css
/* Authentication Badges */
.auth-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: bold;
    color: #fff;
}

.auth-badge.verified {
    background-color: #4caf50;
}

.auth-badge.pending {
    background-color: #ff9800;
}

.auth-badge.rejected {
    background-color: #f44336;
}
```

---

### 10. CSS Code Excerpt - Admin Dashboard (admin.css)

```css
/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
}

.stat-card h3 {
    margin-bottom: 1rem;
    color: #7f8c8d;
    font-size: 1rem;
}

.stat-card .stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #2c3e50;
}

.stat-card.warning .stat-number {
    color: #f39c12;
}

.stat-card.info .stat-number {
    color: #3498db;
}
```

---

### 11. JavaScript Code Excerpt - Form Validation (main.js)

```javascript
// Form validation
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Check required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#f44336';
                } else {
                    field.style.borderColor = '#ddd';
                }
            });
            
            // Validate email format
            const emailFields = form.querySelectorAll('input[type="email"]');
            emailFields.forEach(field => {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (field.value && !emailRegex.test(field.value)) {
                    isValid = false;
                    field.style.borderColor = '#f44336';
                    alert('Please enter a valid email address.');
                }
            });
            
            // Validate password match
            const password = form.querySelector('input[name="password"]');
            const confirmPassword = form.querySelector('input[name="confirm_password"]');
            
            if (password && confirmPassword) {
                if (password.value !== confirmPassword.value) {
                    isValid = false;
                    confirmPassword.style.borderColor = '#f44336';
                    alert('Passwords do not match.');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
}
```

---

### 12. JavaScript Code Excerpt - Debounced Search (main.js)

```javascript
// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Search functionality with debounce
const searchInput = document.querySelector('input[name="label"]');
if (searchInput) {
    searchInput.addEventListener('input', debounce(function() {
        filterProducts();
    }, 500));
}
```

---

### 13. JavaScript Code Excerpt - Notification System (main.js)

```javascript
// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196f3'};
        color: white;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
```

---

### 14. MySQL Code Excerpt - Users Table (database.sql)

```sql
-- Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('buyer', 'seller', 'moderator', 'admin') DEFAULT 'buyer',
    status ENUM('active', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Default Admin User
INSERT INTO users (name, email, password, role, status) VALUES
('Admin User', 'admin@labelloom.com', '0192023a7bbd73250516f069df18b500', 'admin', 'active');
```

---

### 15. MySQL Code Excerpt - Products Table (database.sql)

```sql
-- Products Table
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    brand VARCHAR(50) NOT NULL,
    label_name VARCHAR(100) NOT NULL,
    size VARCHAR(20) NOT NULL,
    condition ENUM('new_with_tags', 'like_new', 'very_good', 'good') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    auth_document VARCHAR(255),
    auth_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Insert Sample Products
INSERT INTO products (seller_id, brand, label_name, size, condition, price, auth_status, description) VALUES
(2, 'Nike', 'Air Jordan 1 Retro High', 'US 10', 'new_with_tags', 4500.00, 'verified', 'Authentic Air Jordan 1 Retro High in original box'),
(2, 'Adidas', 'Yeezy Boost 350 V2', 'US 9', 'like_new', 3800.00, 'verified', 'Worn twice, excellent condition with receipt'),
(2, 'Supreme', 'Box Logo Hoodie Black', 'L', 'new_with_tags', 5200.00, 'pending', 'Brand new Supreme FW21 Box Logo Hoodie');
```

---

### 16. MySQL Code Excerpt - Escrow Transactions Table (database.sql)

```sql
-- Escrow Transactions Table
CREATE TABLE escrow_transactions (
    escrow_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL UNIQUE,
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('held', 'released', 'refunded') DEFAULT 'held',
    released_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);
```

---

### 17. MySQL Code Excerpt - Disputes Table (database.sql)

```sql
-- Disputes Table
CREATE TABLE disputes (
    dispute_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL UNIQUE,
    raised_by INT NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'investigating', 'resolved', 'closed') DEFAULT 'open',
    resolution TEXT,
    resolved_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (raised_by) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (resolved_by) REFERENCES users(user_id) ON DELETE SET NULL
);
```

---

## Usage Instructions

These code excerpts are provided for Section 2.4 of your deliverable. Each excerpt demonstrates:

1. **PHP**: Database connection, user authentication, product filtering, escrow transactions, RBAC
2. **HTML**: Product cards, filter forms, responsive layout structure
3. **CSS**: Responsive grid design, authentication badges, admin dashboard styling
4. **JavaScript**: Form validation, debounced search, notification system
5. **MySQL**: Table creation, foreign key relationships, sample data insertion

Copy these excerpts into your documentation as needed to demonstrate the technical implementation of the The Outlet platform.
