# The Outlet - Deployment Guide

## Step-by-Step Deployment Instructions for Notepad++ Users

### Prerequisites
- A web hosting account (Hostinger, A2 Hosting, 000webhost, InfinityFree, etc.)
- cPanel access to your hosting
- phpMyAdmin access
- Notepad++ text editor

### Step 1: Prepare Your Files

1. **Copy each code block into Notepad++**
   - Open Notepad++
   - Copy the code from each file in this project
   - Paste into Notepad++
   - Save with the correct extension (.php, .css, .js, .sql)

2. **File Structure**
   Create the following folder structure on your computer:
   ```
   /labelloom/
   ├── config.php
   ├── index.php
   ├── register.php
   ├── login.php
   ├── logout.php
   ├── dashboard.php
   ├── sell.php
   ├── product.php
   ├── wishlist.php
   ├── purchase.php
   ├── escrow_release.php
   ├── mark_shipped.php
   ├── add_wishlist.php
   ├── remove_wishlist.php
   ├── raise_dispute.php
   ├── edit_product.php
   ├── delete_product.php
   ├── database.sql
   ├── DESIGN_DIAGRAMS.md
   ├── docs/
   │   └── diagrams/
   │       ├── index.html
   │       └── images/
   ├── css/
   │   └── style.css
   ├── js/
   │   └── main.js
   ├── admin/
   │   ├── index.php
   │   ├── manage_users.php
   │   ├── edit_user.php
   │   ├── create_user.php
   │   ├── delete_user.php
   │   ├── manage_products.php
   │   ├── verify_product.php
   │   ├── disputes.php
   │   └── resolve_dispute.php
   └── uploads/
   ```

### Step 2: Zip the Files

1. Select all files and folders in the labelloom directory
2. Right-click and select "Send to" > "Compressed (zipped) folder"
3. Name the file `labelloom.zip`

### Step 3: Upload to Hosting

#### Option A: Using cPanel File Manager

1. **Log into your hosting cPanel**
   - Go to yourdomain.com/cpanel
   - Enter your hosting credentials

2. **Navigate to File Manager**
   - Find "File Manager" in the Files section
   - Click to open

3. **Upload the zip file**
   - Navigate to the `public_html` directory
   - Click "Upload" button
   - Select your `labelloom.zip` file
   - Wait for upload to complete

4. **Extract the files**
   - Right-click on `labelloom.zip`
   - Select "Extract"
   - Click "Extract File(s)"
   - Files will be extracted to a `labelloom` folder

5. **Set folder permissions**
   - Right-click on the `uploads` folder
   - Select "Change Permissions"
   - Set permissions to 755 (or 777 if needed)
   - Click "Change Permissions"

#### Option B: Using FTP Client (FileZilla)

1. **Download and install FileZilla**
   - Get it from filezilla-project.org

2. **Connect to your hosting**
   - Host: yourdomain.com
   - Username: your FTP username
   - Password: your FTP password
   - Port: 21

3. **Upload files**
   - Navigate to `public_html` on the server
   - Drag and drop the `labelloom` folder from your computer
   - Wait for upload to complete

### Step 4: Create MySQL Database

1. **Log into cPanel**
   - Go to yourdomain.com/cpanel

2. **Open phpMyAdmin**
   - Find "phpMyAdmin" in the Databases section
   - Click to open

3. **Create new database**
   - Click "New" in the left sidebar
   - Enter database name: `labelloom`
   - Click "Create"

4. **Import database schema**
   - Select the `labelloom` database
   - Click "Import" tab
   - Click "Choose File"
   - Select your `database.sql` file
   - Click "Go" at the bottom
   - Wait for import to complete

### Step 5: Configure Database Connection

1. **Edit config.php**
   - Open `config.php` in Notepad++
   - Find these lines:
   ```php
   $host = 'localhost';
   $username = 'root';
   $password = '';
   $database = 'labelloom';
   ```

2. **Update with your hosting credentials**
   - Replace `root` with your database username
   - Replace `''` with your database password
   - Keep `labelloom` as the database name (or change if you used a different name)

3. **Update base URL**
   - Find this line:
   ```php
   $base_url = 'http://localhost/labelloom';
   ```
   - Replace with your actual domain:
   ```php
   $base_url = 'https://yourdomain.com/labelloom';
   ```

4. **Save the file**
   - Upload the updated `config.php` to your server
   - Overwrite the existing file

### Step 6: Test Your Website

1. **Access your website**
   - Open browser and go to: `https://yourdomain.com/labelloom`
   - You should see the The Outlet homepage

2. **Test registration**
   - Click "Register"
   - Create a test account
   - Verify you can login

3. **Test admin access**
   - Login with default admin:
     - Email: admin@labelloom.com
     - Password: admin123
   - Access admin panel at: `https://yourdomain.com/labelloom/admin`

### Step 7: Configure Email (Optional)

If you want email notifications (password reset, order confirmations):

1. **Install PHPMailer** (optional)
   - Download from github.com/PHPMailer/PHPMailer
   - Upload to your server
   - Configure in your PHP files

2. **Or use hosting email**
   - Use the built-in `mail()` function
   - Configure in cPanel > Email

### Step 8: Security Recommendations

1. **Change default admin password**
   - Login as admin
   - Go to admin panel
   - Edit the admin user
   - Change password immediately

2. **Enable SSL/HTTPS**
   - Get free SSL certificate (Let's Encrypt)
   - Available in cPanel > SSL/TLS Status
   - Force HTTPS in config.php

3. **Protect admin directory**
   - Create `.htaccess` file in `/admin` folder
   - Add IP restriction or additional authentication

4. **Regular backups**
   - Use cPanel > Backup Wizard
   - Schedule automatic backups
   - Backup database regularly

### Troubleshooting

#### Issue: "Connection failed" error
- **Solution**: Check your database credentials in config.php
- Verify database exists in phpMyAdmin
- Check database user has proper permissions

#### Issue: Images not uploading
- **Solution**: Check `uploads` folder permissions (755 or 777)
- Verify PHP upload_max_filesize in php.ini
- Check disk space on server

#### Issue: White screen / blank page
- **Solution**: Enable PHP error reporting
- Add to config.php:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```
- Check error logs in cPanel > Errors

#### Issue: Admin panel not accessible
- **Solution**: Verify user has admin role in database
- Check session is working properly
- Clear browser cookies

### Hosting Provider Specific Instructions

#### Hostinger
1. Log in to hPanel
2. Go to Files > File Manager
3. Upload to `public_html`
4. Create database in MySQL Databases
5. Import via phpMyAdmin

#### A2 Hosting
1. Log in to cPanel
2. Use File Manager
3. Upload to `public_html`
4. Create database in MySQL Database Wizard
5. Import via phpMyAdmin

#### 000webhost
1. Log in to control panel
2. Use File Manager
3. Upload to `public_html`
4. Create database in MySQL
5. Import via phpMyAdmin

#### InfinityFree
1. Log in to control panel
2. Use Online File Manager
3. Upload to `htdocs`
4. Create database in MySQL
5. Import via phpMyAdmin

### Post-Deployment Checklist

- [ ] Website loads correctly
- [ ] Registration works
- [ ] Login works
- [ ] Admin panel accessible
- [ ] Product upload works
- [ ] Images upload correctly
- [ ] Filtering works
- [ ] Purchase flow works
- [ ] Escrow system works
- [ ] Email notifications (if configured)
- [ ] SSL certificate installed
- [ ] Default admin password changed
- [ ] Backups configured
- [ ] Mobile responsive design tested

### Support Resources

- PHP Documentation: php.net/docs.php
- MySQL Documentation: dev.mysql.com/doc
- cPanel Documentation: docs.cpanel.net
- Notepad++: notepad-plus-plus.org

### Notes

- This project uses MD5 for password hashing (simple for demonstration)
- For production, use `password_hash()` and `password_verify()`
- Escrow system is simulated - integrate with payment gateway for real transactions
- Images are stored locally - consider cloud storage for production
- Session timeout can be adjusted in php.ini
