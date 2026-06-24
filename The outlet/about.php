<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - The Outlet</title>
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
                <li><a href="about.php" class="active">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (is_logged_in()): ?>
                    <?php if (has_role('seller') || has_role('admin')): ?>
                        <li><a href="sell.php">Sell</a></li>
                    <?php endif; ?>
                    <li><a href="wishlist.php">Wishlist</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <?php if (has_role('admin') || has_role('moderator')): ?>
                        <li><a href="admin/index.php">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <h1>About The Outlet</h1>
            <div class="about-content">
                <div class="about-text">
                    <h2>Our Mission</h2>
                    <p>The Outlet is South Africa's premier marketplace for authentic streetwear and designer clothing. We connect passionate collectors, fashion enthusiasts, and trusted sellers in a secure environment where authenticity is guaranteed.</p>
                    
                    <h2>What We Do</h2>
                    <p>We provide a platform where buyers can purchase verified authentic streetwear and designer items with confidence. Our authentication process ensures that every item listed on our platform meets the highest standards of authenticity.</p>
                    
                    <h2>Our Values</h2>
                    <ul>
                        <li><strong>Authenticity First:</strong> Every item goes through our rigorous verification process</li>
                        <li><strong>Secure Transactions:</strong> Escrow protection for both buyers and sellers</li>
                        <li><strong>Community Driven:</strong> Built by streetwear enthusiasts, for streetwear enthusiasts</li>
                        <li><strong>Transparent Pricing:</strong> No hidden fees, fair marketplace for all</li>
                    </ul>
                    
                    <h2>How It Works</h2>
                    <p>Sellers list their authentic streetwear and designer items, which are then verified by our team. Buyers can browse and purchase with confidence, knowing that their items are protected through our escrow system until delivery is confirmed.</p>
                </div>
                
                <div class="about-features">
                    <div class="feature-card">
                        <h3>Verified Authentic</h3>
                        <p>Every item is authenticated by our expert team before listing</p>
                    </div>
                    <div class="feature-card">
                        <h3>Secure Escrow</h3>
                        <p>Payments are held securely until you confirm receipt</p>
                    </div>
                    <div class="feature-card">
                        <h3>Dispute Resolution</h3>
                        <p>Fair and transparent dispute handling process</p>
                    </div>
                    <div class="feature-card">
                        <h3>South African Focus</h3>
                        <p>Built for the SA streetwear community</p>
                    </div>
                </div>
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
                <div class="footer-section">
                    <h3>Connect</h3>
                    <ul>
                        <li><a href="https://github.com/thabisomosime21-create" target="_blank">GitHub Profile</a></li>
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
