# The Outlet - Screenshot Descriptions

## Main Website Screenshots

### 1. Homepage (index.php)

#### Desktop View (1024px+)
**Description**: Take a full-page screenshot showing:
- Top navbar with "The Outlet" brand logo on the left
- Navigation menu on the right: Home, Sell, Wishlist, Dashboard, Logout (or Login/Register if not logged in)
- Hero section with gradient background (purple to blue) displaying:
  - Large heading: "Authentic Streetwear & Designer Marketplace"
  - Subheading: "Buy and sell the latest label clothing in South Africa"
- Main content area split into two columns:
  - Left sidebar (280px) with filter form:
    - "Filter Products" heading
    - Brand dropdown (Nike, Adidas, Gucci, etc.)
    - Label Name search input
    - Size dropdown (US/EU sizes)
    - Condition dropdown (New with tags, Like new, etc.)
    - Price Range inputs (Min and Max in ZAR)
    - Authentication Status dropdown (All, Verified, Pending)
    - "Apply Filters" button (black)
    - "Clear Filters" link (gray)
  - Right main area with product grid:
    - "Latest Label Clothing" heading
    - 3 columns of product cards
    - Each card shows:
      - Product image (200px height)
      - Authentication badge (green "Verified" or orange "Pending")
      - Brand name (bold)
      - Label/Collection name
      - Size and condition
      - Price in ZAR (large, bold)
      - Seller name
      - "View Details" button (black)
      - "Buy Now" button (purple)
- Footer with copyright text

#### Tablet View (768px)
**Description**: Take a full-page screenshot showing:
- Same navbar but with items wrapped if needed
- Hero section with adjusted text size
- Single column layout:
  - Filters sidebar at top (collapsed or full width)
  - Product grid below with 2 columns
  - Each product card slightly smaller
- Footer visible at bottom

#### Smartphone View (480px)
**Description**: Take a full-page screenshot showing:
- Navbar with stacked or hamburger menu
- Hero section with smaller text
- Single column layout:
  - Filters at top (possibly collapsible)
  - Product grid with 1 column
  - Product cards stacked vertically
  - Buttons stacked vertically within cards
- Bottom navigation bar (if implemented) or standard footer

---

### 2. Registration Page (register.php)

#### Desktop View
**Description**: Take a centered screenshot showing:
- Navbar at top
- White registration box centered on page (max-width 400px)
- Box contains:
  - "Create Account" heading
  - "Join The Outlet to buy and sell authentic streetwear" subtext
  - Form fields:
    - Full Name input
    - Email Address input
    - Password input
    - Confirm Password input
    - "I want to:" dropdown (Buy items / Sell items)
  - "Create Account" button (black, full width)
  - "Already have an account? Login here" link
- Footer at bottom

#### Tablet View
**Description**: Similar to desktop but with:
- Registration box slightly smaller
- Form fields optimized for touch
- Button full width

#### Smartphone View
**Description**: Similar to tablet but with:
- Registration box takes up most of screen width
- Larger touch targets for inputs
- Stacked layout

---

### 3. Login Page (login.php)

#### Desktop View
**Description**: Take a centered screenshot showing:
- Navbar at top
- White login box centered on page (max-width 400px)
- Box contains:
  - "Login" heading
  - "Welcome back to The Outlet" subtext
  - Form fields:
    - Email Address input
    - Password input
  - "Login" button (black, full width)
  - "Don't have an account? Register here" link
- Footer at bottom

#### Tablet & Smartphone Views
**Description**: Similar to registration page with responsive adjustments

---

### 4. Dashboard Page (dashboard.php)

#### Desktop View
**Description**: Take a full-page screenshot showing:
- Navbar at top
- Dashboard header:
  - "Welcome, [User Name]" heading
  - "Role: [Buyer/Seller/Admin]" badge
- Tab navigation:
  - "My Purchases" (active)
  - "My Sales" (if seller/admin)
  - "My Listings" (if seller/admin)
  - "Wishlist"
- Tab content area showing:
  - "My Purchases" heading
  - List of order cards (if any) or "No purchases yet" message
  - Each order card shows:
    - Product brand and label name
    - Seller name
    - Amount in ZAR
    - Status badge (pending, shipped, received, completed)
    - Date
    - Action buttons (Confirm Receipt, Raise Dispute) if applicable
- Footer at bottom

#### Tablet View
**Description**: Similar to desktop but with:
- Tab navigation possibly wrapped
- Order cards stacked vertically
- Buttons stacked within cards

#### Smartphone View
**Description**: Similar to tablet but with:
- Tab navigation as vertical or horizontal scroll
- Order cards full width
- All actions stacked

---

### 5. Sell Page (sell.php)

#### Desktop View
**Description**: Take a centered screenshot showing:
- Navbar at top
- White sell box centered (max-width 600px)
- Box contains:
  - "List Your Item" heading
  - "Sell authentic designer and streetwear clothing" subtext
  - Form with:
    - Brand dropdown (Nike, Adidas, Gucci, etc.)
    - Label/Collection Name input
    - Size dropdown (US/EU/Clothing sizes)
    - Condition dropdown
    - Price input (ZAR)
    - Product Image file input
    - Authentication Document file input
    - Description textarea
  - "List Item" button (black, full width)
- Footer at bottom

#### Tablet & Smartphone Views
**Description**: Similar to desktop with responsive form layout

---

### 6. Product Detail Page (product.php)

#### Desktop View
**Description**: Take a full-page screenshot showing:
- Navbar at top
- Two-column layout:
  - Left column: Product image (large)
    - Authentication badge overlay
  - Right column: Product info
    - Brand name (large heading)
    - Label/Collection name (subheading)
    - Product meta (Size, Condition, Seller, Listed date)
    - Price (very large, bold)
    - Description section (if exists)
    - Action buttons:
      - "Buy Now" (black, large)
      - "Add to Wishlist" (purple, large)
    - Authentication info section (if auth document exists)
- Footer at bottom

#### Tablet View
**Description**: Similar to desktop but with:
- Stacked layout (image on top, info below)
- Adjusted image size

#### Smartphone View
**Description**: Similar to tablet with:
- Full-width image
- Stacked buttons
- Optimized spacing

---

### 7. Wishlist Page (wishlist.php)

#### Desktop View
**Description**: Take a full-page screenshot showing:
- Navbar at top
- "My Wishlist" heading
- Product grid (3 columns) showing saved items
- Each product card same as homepage but with:
  - "Remove" button (red) in addition to other buttons
- "Your wishlist is empty" message if no items
- Footer at bottom

#### Tablet & Smartphone Views
**Description**: Similar to homepage with responsive grid

---

### 8. Purchase Page (purchase.php)

#### Desktop View
**Description**: Take a centered screenshot showing:
- Navbar at top
- White purchase box centered (max-width 600px)
- Box contains:
  - "Complete Your Purchase" heading
  - Order Summary section:
    - Product image (100x100px)
    - Product details (brand, label, size, condition, seller, price)
  - Escrow Protection section:
    - "Escrow Protection" heading
    - List of 4 steps (Payment held, Seller ships, You confirm, Payment released)
  - "Confirm Purchase" button (black, large)
  - "Cancel" link (gray)
- Footer at bottom

#### Tablet & Smartphone Views
**Description**: Similar to desktop with responsive layout

---

### 9. Escrow Release Page (escrow_release.php)

#### Desktop View
**Description**: Take a centered screenshot showing:
- Navbar at top
- White escrow box centered (max-width 600px)
- Box contains:
  - "Confirm Receipt & Authenticity" heading
  - Order information:
    - Order ID
    - Status
    - Amount
  - Warning box (yellow background):
    - "⚠️ Important" heading
    - List of acknowledgments
  - Link to raise dispute
  - "Confirm Receipt & Release Payment" button (green, large)
  - "Cancel" link (gray)
- Footer at bottom

#### Tablet & Smartphone Views
**Description**: Similar to desktop with responsive layout

---

## Admin Website Screenshots

### 10. Admin Dashboard (admin/index.php)

#### Desktop View
**Description**: Take a full-page screenshot showing:
- Dark admin navbar (dark blue/gray):
  - "The Outlet Admin" brand on left
  - Navigation: Dashboard, Users, Products, Disputes, View Site, Logout
- Dashboard header:
  - "Admin Dashboard" heading
  - "Welcome, [Name] (Admin/Moderator)" text
- Statistics grid (5 cards):
  - Total Users (number)
  - Total Products (number)
  - Total Orders (number)
  - Pending Disputes (number, yellow card)
  - Pending Verifications (number, blue card)
- Recent Orders table:
  - Columns: Order ID, Buyer, Seller, Amount, Status, Date
  - 5 most recent orders
  - Status badges (pending, shipped, etc.)
- Recent Products table:
  - Columns: Product ID, Brand, Label Name, Seller, Price, Auth Status
  - 5 most recent products
  - Auth status badges (verified, pending, rejected)
- Footer at bottom

#### Tablet View
**Description**: Similar to desktop but with:
- Statistics grid possibly 2-3 columns
- Tables with horizontal scroll if needed
- Navbar items wrapped

#### Smartphone View
**Description**: Similar to tablet but with:
- Statistics grid 1-2 columns
- Tables stacked or with card view
- Navbar as hamburger menu

---

### 11. Manage Users Page (admin/manage_users.php)

#### Desktop View
**Description**: Take a full-page screenshot showing:
- Admin navbar at top
- Section header:
  - "Manage Users" heading on left
  - "Create New User" button (black) on right
- Users table:
  - Columns: ID, Name, Email, Role, Status, Created, Actions
  - Role badges (admin=red, moderator=purple, seller=blue, buyer=gray)
  - Status badges (active=green, suspended=red)
  - Action buttons: Edit (gray), Delete (red)
- Footer at bottom

#### Tablet & Smartphone Views
**Description**: Similar to admin dashboard with responsive table

---

### 12. Edit User Page (admin/edit_user.php)

#### Desktop View
**Description**: Take a centered screenshot showing:
- Admin navbar at top
- "Edit User" heading
- Form with:
  - Name input
  - Email input
  - Role dropdown (Buyer, Seller, Moderator, Admin)
  - Status dropdown (Active, Suspended)
  - "Update User" button (black)
  - "Cancel" link (gray)
- Footer at bottom

#### Tablet & Smartphone Views
**Description**: Similar to desktop with responsive form

---

### 13. Create User Page (admin/create_user.php)

#### Desktop View
**Description**: Similar to Edit User page but with:
- "Create New User" heading
- Additional "Password" field
- Default values for role and status

#### Tablet & Smartphone Views
**Description**: Similar to Edit User page

---

### 14. Manage Products Page (admin/manage_products.php)

#### Desktop View
**Description**: Take a full-page screenshot showing:
- Admin navbar at top
- Section header:
  - "Manage Products" heading on left
  - Filter form on right:
    - Auth Status dropdown (All, Pending, Verified, Rejected)
    - "Filter" button (gray)
- Products table:
  - Columns: ID, Image, Brand, Label Name, Seller, Price, Auth Status, Actions
  - Thumbnail images (50x50px)
  - Auth status badges
  - Action buttons: Verify (gray), View (blue - admin only)
- Footer at bottom

#### Tablet & Smartphone Views
**Description**: Similar to admin dashboard with responsive table

---

### 15. Verify Product Page (admin/verify_product.php)

#### Desktop View
**Description**: Take a full-page screenshot showing:
- Admin navbar at top
- Two-column layout:
  - Left column: Product Details
    - "Product Details" heading
    - Info grid (Brand, Label Name, Size, Condition, Price, Seller, Current Status)
    - Product Image (large)
    - Authentication Document section with "View Document" button
    - Description section (if exists)
  - Right column: Verification Form
    - "Verification Decision" heading
    - Verification Status dropdown (Verified, Rejected, Pending)
    - Reason textarea (if rejected)
    - "Update Status" button (black)
    - "Cancel" link (gray)
- Footer at bottom

#### Tablet View
**Description**: Similar to desktop but with stacked layout

#### Smartphone View
**Description**: Similar to tablet with full-width columns

---

### 16. Disputes Page (admin/disputes.php)

#### Desktop View
**Description**: Take a full-page screenshot showing:
- Admin navbar at top
- Section header:
  - "Manage Disputes" heading on left
  - Filter form on right:
    - Status dropdown (All, Open, Investigating, Resolved, Closed)
    - "Filter" button (gray)
- Disputes table:
  - Columns: Dispute ID, Order ID, Product, Raised By, Buyer, Seller, Amount, Status, Created, Actions
  - Status badges (open=yellow, investigating=blue, resolved=green, closed=gray)
  - Action button: Resolve (gray)
- Footer at bottom

#### Tablet & Smartphone Views
**Description**: Similar to admin dashboard with responsive table

---

### 17. Resolve Dispute Page (admin/resolve_dispute.php)

#### Desktop View
**Description**: Take a full-page screenshot showing:
- Admin navbar at top
- Dispute Details section:
  - "Resolve Dispute #[ID]" heading
  - Info grid (Order ID, Product, Amount, Raised By, Buyer, Seller, Status, Created)
  - Dispute Description section
  - Current Resolution section (if exists)
- Resolution Form section:
  - "Resolution Decision" heading
  - Status dropdown (Investigating, Resolved, Closed)
  - Escrow Action dropdown (Refund Buyer, Release to Seller)
  - Resolution Details textarea
  - "Resolve Dispute" button (black)
  - "Cancel" link (gray)
- Footer at bottom

#### Tablet View
**Description**: Similar to desktop but with stacked layout

#### Smartphone View
**Description**: Similar to tablet with full-width sections

---

## Additional Screenshots

### 18. Database Schema (phpMyAdmin)
**Description**: Take a screenshot of phpMyAdmin showing:
- Left sidebar with database "labelloom" selected
- Main area showing all tables:
  - users
  - products
  - orders
  - escrow_transactions
  - disputes
  - wishlist
- Table structure view showing columns and data types

### 19. Sample Data in Database
**Description**: Take a screenshot showing:
- Browse view of "products" table
- Sample product records with all columns
- Authentication status values
- Price values in ZAR

### 20. File Structure (File Manager)
**Description**: Take a screenshot of cPanel File Manager showing:
- labelloom folder expanded
- All files and folders visible
- Proper file structure as specified

---

## Screenshot Capture Tips

1. **Browser Settings**:
   - Use full-screen mode (F11)
   - Clear browser cache before capturing
   - Disable browser extensions that might interfere
   - Use consistent browser (Chrome or Firefox recommended)

2. **Device Testing**:
   - Use Chrome DevTools (F12) for responsive testing
   - Test at: 1920x1080 (desktop), 768x1024 (tablet), 375x667 (smartphone)
   - Or use actual devices if available

3. **Image Quality**:
   - Capture at 100% zoom
   - Use PNG format for best quality
   - Ensure text is readable
   - Crop unnecessary browser chrome if needed

4. **Data Preparation**:
   - Have sample data in database
   - Create test users (buyer, seller, admin)
   - List sample products
   - Create sample orders
   - Raise a sample dispute

5. **Consistency**:
   - Use same color scheme across all screenshots
   - Ensure consistent branding
   - Maintain same font sizes
   - Use same browser for all screenshots
