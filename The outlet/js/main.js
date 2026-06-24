// The Outlet - Main JavaScript

// Document ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initFilterForm();
    initPriceSlider();
    initProductCards();
    initEscrowConfirmation();
    initFormValidation();
    initPaymentForm();
    initMobileNav();
});

// Mobile navigation toggle
function initMobileNav() {
    const navbar = document.querySelector('.navbar .container');
    const navMenu = document.querySelector('.navbar .nav-menu');

    if (!navbar || !navMenu || navbar.querySelector('.nav-toggle')) {
        return;
    }

    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'nav-toggle';
    toggle.setAttribute('aria-label', 'Toggle navigation menu');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.textContent = 'Menu';

    toggle.addEventListener('click', function() {
        const isOpen = navMenu.classList.toggle('nav-open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        toggle.textContent = isOpen ? 'Close' : 'Menu';
    });

    navbar.appendChild(toggle);
}

// Filter form handling
function initFilterForm() {
    const filterForm = document.querySelector('.filters form');
    if (filterForm) {
        // Auto-submit on brand change
        const brandSelect = document.querySelector('select[name="brand"]');
        if (brandSelect) {
            brandSelect.addEventListener('change', function() {
                filterForm.submit();
            });
        }

        // Auto-submit on auth status change
        const authSelect = document.querySelector('select[name="auth_status"]');
        if (authSelect) {
            authSelect.addEventListener('change', function() {
                filterForm.submit();
            });
        }
    }
}

// Price range slider (if implemented)
function initPriceSlider() {
    const minPrice = document.querySelector('input[name="min_price"]');
    const maxPrice = document.querySelector('input[name="max_price"]');
    
    if (minPrice && maxPrice) {
        // Validate price range
        minPrice.addEventListener('change', function() {
            if (parseFloat(this.value) > parseFloat(maxPrice.value)) {
                this.value = maxPrice.value;
            }
        });
        
        maxPrice.addEventListener('change', function() {
            if (parseFloat(this.value) < parseFloat(minPrice.value)) {
                this.value = minPrice.value;
            }
        });
    }
}

// Product card interactions
function initProductCards() {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        // Add hover effects
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

// Escrow confirmation popup
function initEscrowConfirmation() {
    const escrowButtons = document.querySelectorAll('.escrow-confirm-btn');
    
    escrowButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const orderId = this.getAttribute('data-order-id');
            const message = 'Are you sure you want to confirm receipt and release payment?\n\nThis action cannot be undone.';
            
            if (confirm(message)) {
                window.location.href = 'escrow_release.php?order_id=' + orderId;
            }
        });
    });
}

// Purchase payment form
function initPaymentForm() {
    const purchaseForm = document.getElementById('purchase-form');
    if (!purchaseForm) {
        return;
    }

    const cardNumber = document.getElementById('card_number');
    const cardExpiry = document.getElementById('card_expiry');
    const cardCvv = document.getElementById('card_cvv');

    if (cardNumber) {
        cardNumber.addEventListener('input', function() {
            const digits = this.value.replace(/\D/g, '').slice(0, 16);
            this.value = digits.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
        });
    }

    if (cardExpiry) {
        cardExpiry.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '').slice(0, 4);
            if (value.length >= 3) {
                value = value.slice(0, 2) + '/' + value.slice(2);
            }
            this.value = value;
        });
    }

    if (cardCvv) {
        cardCvv.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 4);
        });
    }

    purchaseForm.addEventListener('submit', function(e) {
        const number = cardNumber.value.replace(/\D/g, '');
        const expiry = cardExpiry.value.trim();
        const cvv = cardCvv.value.trim();

        if (number.length < 13) {
            e.preventDefault();
            alert('Please enter a valid card number.');
            cardNumber.focus();
            return;
        }

        if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry)) {
            e.preventDefault();
            alert('Please enter expiry as MM/YY.');
            cardExpiry.focus();
            return;
        }

        if (!/^\d{3,4}$/.test(cvv)) {
            e.preventDefault();
            alert('Please enter a valid CVV.');
            cardCvv.focus();
        }
    });
}

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
            
            // Validate password match (for registration)
            const password = form.querySelector('input[name="password"]');
            const confirmPassword = form.querySelector('input[name="confirm_password"]');
            
            if (password && confirmPassword) {
                if (password.value !== confirmPassword.value) {
                    isValid = false;
                    confirmPassword.style.borderColor = '#f44336';
                    alert('Passwords do not match.');
                }
            }
            
            // Validate price
            const priceFields = form.querySelectorAll('input[type="number"]');
            priceFields.forEach(field => {
                if (field.value && parseFloat(field.value) < 0) {
                    isValid = false;
                    field.style.borderColor = '#f44336';
                    alert('Price cannot be negative.');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
}

// Dynamic filter (AJAX - optional enhancement)
function filterProducts() {
    const brand = document.querySelector('select[name="brand"]')?.value || '';
    const label = document.querySelector('input[name="label"]')?.value || '';
    const size = document.querySelector('select[name="size"]')?.value || '';
    const condition = document.querySelector('select[name="condition"]')?.value || '';
    const minPrice = document.querySelector('input[name="min_price"]')?.value || 0;
    const maxPrice = document.querySelector('input[name="max_price"]')?.value || 10000;
    const authStatus = document.querySelector('select[name="auth_status"]')?.value || '';
    
    // Build query string
    const params = new URLSearchParams();
    if (brand) params.append('brand', brand);
    if (label) params.append('label', label);
    if (size) params.append('size', size);
    if (condition) params.append('condition', condition);
    if (minPrice) params.append('min_price', minPrice);
    if (maxPrice) params.append('max_price', maxPrice);
    if (authStatus) params.append('auth_status', authStatus);
    
    // Redirect with filters
    window.location.href = 'index.php?' + params.toString();
}

// Add to wishlist with animation
function addToWishlist(productId) {
    // Show loading state
    const btn = event.target;
    const originalText = btn.textContent;
    btn.textContent = 'Adding...';
    btn.disabled = true;
    
    // Make request
    fetch('add_wishlist.php?id=' + productId)
        .then(response => response.text())
        .then(data => {
            btn.textContent = 'Added!';
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.classList.remove('btn-success');
                btn.disabled = false;
            }, 2000);
        })
        .catch(error => {
            console.error('Error:', error);
            btn.textContent = originalText;
            btn.disabled = false;
            alert('Failed to add to wishlist. Please try again.');
        });
}

// Remove from wishlist
function removeFromWishlist(productId) {
    if (confirm('Are you sure you want to remove this item from your wishlist?')) {
        window.location.href = 'remove_wishlist.php?id=' + productId;
    }
}

// Show/hide password toggle
function togglePassword(inputId, toggleId) {
    const input = document.getElementById(inputId);
    const toggle = document.getElementById(toggleId);
    
    if (input.type === 'password') {
        input.type = 'text';
        toggle.textContent = 'Hide';
    } else {
        input.type = 'password';
        toggle.textContent = 'Show';
    }
}

// Image preview before upload
function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-ZA', {
        style: 'currency',
        currency: 'ZAR'
    }).format(amount);
}

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

// Mobile menu toggle (for responsive navigation)
function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    navMenu.classList.toggle('active');
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const navMenu = document.querySelector('.nav-menu');
    const navBrand = document.querySelector('.nav-brand');
    
    if (!navMenu.contains(event.target) && !navBrand.contains(event.target)) {
        navMenu.classList.remove('active');
    }
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Loading indicator for AJAX requests
function showLoading() {
    const loading = document.createElement('div');
    loading.id = 'loading-indicator';
    loading.innerHTML = '<div class="spinner"></div>';
    loading.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    `;
    document.body.appendChild(loading);
}

function hideLoading() {
    const loading = document.getElementById('loading-indicator');
    if (loading) {
        loading.remove();
    }
}

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

// Add CSS animations for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .spinner {
        border: 4px solid rgba(255,255,255,0.3);
        border-top: 4px solid #fff;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
