<?php
include 'config.php';

$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $subject = sanitize($conn, $_POST['subject']);
    $message = sanitize($conn, $_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // In a real application, you would send an email here
        // For now, we'll just show a success message
        $message_sent = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - The Outlet</title>
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
                <li><a href="contact.php" class="active">Contact</a></li>
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

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <h1>Contact Us</h1>
            <p class="contact-intro">Have questions, concerns, or feedback? We'd love to hear from you.</p>
            
            <?php if ($message_sent): ?>
                <div class="success-message">
                    <h2>Message Sent!</h2>
                    <p>Thank you for contacting us. Our team will get back to you within 24-48 hours.</p>
                    <a href="index.php" class="btn btn-primary">Return to Home</a>
                </div>
            <?php else: ?>
                <?php if ($error_message): ?>
                    <div class="error-message">
                        <p><?php echo $error_message; ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="contact-wrapper">
                    <div class="contact-info">
                        <h2>Get in Touch</h2>
                        <div class="contact-item">
                            <h3>Admin Inquiries</h3>
                            <p>For account issues, disputes, or platform-related questions, use the form or email us directly.</p>
                        </div>
                        <div class="contact-item">
                            <h3>Business Hours</h3>
                            <p>Monday - Friday: 9:00 AM - 5:00 PM SAST</p>
                            <p>Saturday - Sunday: Closed</p>
                        </div>
                        <div class="contact-item">
                            <h3>Response Time</h3>
                            <p>We typically respond within 24-48 hours during business days.</p>
                        </div>
                        <div class="contact-item">
                            <h3>Email</h3>
                            <p>admin@labelloom.co.za</p>
                        </div>
                    </div>
                    
                    <div class="contact-form">
                        <h2>Send us a Message</h2>
                        <form method="POST" action="contact.php">
                            <div class="form-group">
                                <label for="name">Your Name</label>
                                <input type="text" id="name" name="name" required 
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" required 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <select id="subject" name="subject" required>
                                    <option value="">Select a topic</option>
                                    <option value="general" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'general') ? 'selected' : ''; ?>>General Inquiry</option>
                                    <option value="account" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'account') ? 'selected' : ''; ?>>Account Issue</option>
                                    <option value="dispute" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'dispute') ? 'selected' : ''; ?>>Order Dispute</option>
                                    <option value="authentication" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'authentication') ? 'selected' : ''; ?>>Authentication Question</option>
                                    <option value="feedback" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'feedback') ? 'selected' : ''; ?>>Feedback</option>
                                    <option value="other" <?php echo (isset($_POST['subject']) && $_POST['subject'] == 'other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="6" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
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
