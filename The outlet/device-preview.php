<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Preview - The Outlet</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .preview-page {
            padding: 2rem 0 4rem;
        }

        .preview-page h1 {
            margin-bottom: 0.5rem;
            color: #1a1a1a;
        }

        .preview-intro {
            margin-bottom: 2rem;
            color: #555;
            max-width: 900px;
        }

        .preview-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1rem;
            margin-bottom: 2.5rem;
        }

        .method-card {
            background: #fff;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .method-card h2 {
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
            color: #1a1a1a;
        }

        .method-card ol,
        .method-card ul {
            margin-left: 1.25rem;
            color: #444;
        }

        .method-card li {
            margin-bottom: 0.4rem;
        }

        .method-card kbd {
            background: #eee;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 0.1rem 0.35rem;
            font-size: 0.85rem;
        }

        .preview-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .preview-toolbar a {
            text-decoration: none;
        }

        .device-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .device-frame {
            background: #fff;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }

        .device-frame h3 {
            margin-bottom: 0.25rem;
            color: #1a1a1a;
        }

        .device-meta {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .device-shell {
            background: #111;
            border-radius: 18px;
            padding: 12px 8px;
            margin: 0 auto;
            overflow: hidden;
        }

        .device-shell.tablet {
            max-width: 820px;
        }

        .device-shell.desktop {
            max-width: 100%;
        }

        .device-screen {
            background: #fff;
            border: none;
            display: block;
            width: 100%;
            border-radius: 8px;
        }

        .device-shell.mobile .device-screen {
            height: 640px;
        }

        .device-shell.tablet .device-screen {
            height: 700px;
        }

        .device-shell.desktop .device-screen {
            height: 720px;
        }

        @media (max-width: 768px) {
            .device-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php">The Outlet</a>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="device-preview.php" class="active">Device Preview</a></li>
            </ul>
        </div>
    </nav>

    <section class="preview-page">
        <div class="container">
            <h1>Device Preview</h1>
            <p class="preview-intro">
                Use this page to see how The Outlet looks on different screen sizes before you demo or deploy it.
                You can preview live pages below, or use your browser's built-in responsive mode for any page.
            </p>

            <div class="preview-methods">
                <div class="method-card">
                    <h2>Option 1: This preview page</h2>
                    <p>Scroll down to see the homepage in mobile, tablet, and desktop frames.</p>
                </div>
                <div class="method-card">
                    <h2>Option 2: Browser DevTools</h2>
                    <ol>
                        <li>Open any The Outlet page in Chrome or Edge</li>
                        <li>Press <kbd>F12</kbd> or <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>I</kbd></li>
                        <li>Click the device icon (Toggle device toolbar)</li>
                        <li>Choose iPhone, iPad, or Responsive at the top</li>
                    </ol>
                </div>
                <div class="method-card">
                    <h2>Option 3: Real phone</h2>
                    <ul>
                        <li>Connect phone and PC to the same Wi-Fi</li>
                        <li>Find your PC IP with <code>ipconfig</code></li>
                        <li>Open <code>http://YOUR-IP/dashboard/labelloom/</code> on the phone</li>
                    </ul>
                </div>
            </div>

            <div class="preview-toolbar">
                <a href="index.php" class="btn btn-secondary">Open homepage</a>
                <a href="product.php?id=1" class="btn btn-secondary">Product page</a>
                <a href="login.php" class="btn btn-secondary">Login page</a>
                <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
                <a href="admin/index.php" class="btn btn-secondary">Admin</a>
            </div>

            <div class="device-grid">
                <div class="device-frame">
                    <h3>Mobile phone</h3>
                    <p class="device-meta">375 x 640 — typical smartphone</p>
                    <div class="device-shell mobile" style="max-width: 375px;">
                        <iframe class="device-screen" src="index.php" title="Mobile preview"></iframe>
                    </div>
                </div>

                <div class="device-frame">
                    <h3>Tablet</h3>
                    <p class="device-meta">768 x 700 — iPad / tablet</p>
                    <div class="device-shell tablet">
                        <iframe class="device-screen" src="index.php" title="Tablet preview"></iframe>
                    </div>
                </div>

                <div class="device-frame">
                    <h3>Desktop</h3>
                    <p class="device-meta">Full width — laptop / monitor</p>
                    <div class="device-shell desktop">
                        <iframe class="device-screen" src="index.php" title="Desktop preview"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 The Outlet - Device Preview</p>
        </div>
    </footer>
    <script src="js/main.js"></script>
</body>
</html>
