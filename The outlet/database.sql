-- LabelLoom Database Schema
CREATE DATABASE IF NOT EXISTS labelloom;
USE labelloom;

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

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    brand VARCHAR(50) NOT NULL,
    label_name VARCHAR(100) NOT NULL,
    size VARCHAR(20) NOT NULL,
    `condition` ENUM('new_with_tags', 'like_new', 'very_good', 'good') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    auth_document VARCHAR(255),
    auth_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    seller_id INT NOT NULL,
    product_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'shipped', 'received', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

CREATE TABLE escrow_transactions (
    escrow_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL UNIQUE,
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('held', 'released', 'refunded') DEFAULT 'held',
    released_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);

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

CREATE TABLE wishlist (
    wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id)
);

INSERT INTO users (name, email, password, role, status) VALUES
('Admin User', 'admin@labelloom.com', '0192023a7bbd73250516f069df18b500', 'admin', 'active'),
('John Buyer', 'buyer@example.com', '0192023a7bbd73250516f069df18b500', 'buyer', 'active'),
('Jane Seller', 'seller@example.com', '0192023a7bbd73250516f069df18b500', 'seller', 'active'),
('Mike Moderator', 'moderator@example.com', '0192023a7bbd73250516f069df18b500', 'moderator', 'active');

INSERT INTO products (seller_id, brand, label_name, size, `condition`, price, auth_status, description) VALUES
(3, 'Nike', 'Air Jordan 1 Retro High', 'US 10', 'new_with_tags', 4500.00, 'verified', 'Authentic Air Jordan 1 Retro High in original box'),
(3, 'Adidas', 'Yeezy Boost 350 V2', 'US 9', 'like_new', 3800.00, 'verified', 'Worn twice, excellent condition with receipt'),
(3, 'Supreme', 'Box Logo Hoodie Black', 'L', 'new_with_tags', 5200.00, 'pending', 'Brand new Supreme FW21 Box Logo Hoodie'),
(3, 'Essentials', 'Fear of God Hoodie', 'M', 'very_good', 1800.00, 'verified', 'Essentials Fear of God core hoodie, minimal wear'),
(3, 'Gucci', 'Ace Sneaker', 'EU 42', 'new_with_tags', 8500.00, 'verified', 'Authentic Gucci Ace sneakers with web detail'),
(3, 'Off-White', 'Nike Air Presto', 'US 11', 'like_new', 4200.00, 'pending', 'Off-White x Nike collaboration, worn once');

CREATE INDEX idx_products_brand ON products(brand);
CREATE INDEX idx_products_price ON products(price);
CREATE INDEX idx_products_seller ON products(seller_id);
CREATE INDEX idx_orders_buyer ON orders(buyer_id);
CREATE INDEX idx_orders_seller ON orders(seller_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_wishlist_user ON wishlist(user_id);
