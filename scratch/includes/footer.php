    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                
                <div class="footer-col">
                    <a href="/index.php" class="logo" style="margin-bottom: 2rem; display: block;">
                        <img src="/assets/logo.png" alt="Jevani Store" style="height: 60px; width: auto; object-fit: contain;">
                    </a>
                    <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6;">
                        A new era of luxury streetwear. Minimalist silhouettes, premium fabrics, and architectural design for the next generation.
                    </p>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">SHOP</h4>
                    <div class="footer-links">
                        <a href="/shop.php">All Products</a>
                        <a href="/shop.php?category=1">New Arrivals</a>
                        <a href="/shop.php?category=2">Hoodies</a>
                        <a href="/shop.php?category=3">Bottoms</a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">SUPPORT</h4>
                    <div class="footer-links">
                        <a href="#">Contact</a>
                        <a href="#">Shipping Policy</a>
                        <a href="#">Returns</a>
                        <a href="#">Terms of Service</a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">NEWSLETTER</h4>
                    <p style="color: var(--text-secondary); font-size: 0.85rem;">Subscribe to receive updates, access to exclusive deals, and more.</p>
                    <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Subscribed to the newsletter.');">
                        <input type="email" class="newsletter-input" placeholder="ENTER YOUR EMAIL" required>
                        <button type="submit" class="newsletter-btn">JOIN</button>
                    </form>
                </div>

            </div>

            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> JEVANI STORE. ALL RIGHTS RESERVED.</p>
                <div style="display: flex; gap: 20px;">
                    <a href="#" style="font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase;">Instagram</a>
                    <a href="#" style="font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase;">Twitter</a>
    </footer>

    <!-- Mobile Navigation Drawer -->
    <div class="mobile-nav-drawer" id="mobile-nav-drawer">
        <div class="mobile-nav-backdrop" onclick="closeMobileNav()"></div>
        <div class="mobile-nav-panel">
            <div class="mobile-nav-header">
                <div class="logo">
                    <img src="/assets/logo.png" alt="Jevani Store" style="height: 40px; width: auto; object-fit: contain;">
                </div>
                <button class="mobile-nav-close" onclick="closeMobileNav()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="mobile-nav-links">
                <a href="/shop.php">New Arrivals</a>
                <a href="/shop.php?category=1">Apparel</a>
                <a href="/accessories.php">Accessories</a>
                <a href="/lookbook.php">Lookbook</a>
                <a href="/about.php">About</a>
            </div>
            <div class="mobile-nav-footer">
                <?php if(isLoggedIn()): ?>
                    <a href="<?php echo isAdmin() ? '/admin/index.php' : '/customer/index.php'; ?>">My Account</a>
                    <a href="/logout.php" style="color: #666; border-color: rgba(255,255,255,0.05);">Logout</a>
                <?php else: ?>
                    <a href="/login.php">Login</a>
                    <a href="/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Cart Overlay & Panel -->
    <div class="cart-overlay" id="cart-overlay" onclick="closeCart()"></div>
    <div class="cart-panel" id="cart-panel">
        <?php include __DIR__ . '/../ajax_cart.php'; ?>
    </div>

    <!-- Global Quick View Modal -->
    <div class="qv-overlay" id="global-qv-overlay" onclick="if(event.target===this) closeQuickView()">
        <div class="qv-modal" id="global-qv-modal">
            <!-- Content loaded via AJAX -->
        </div>
    </div>

    <!-- Script for Lucide Icons & UI Toggles -->
    <script>
        lucide.createIcons();

        // Mobile Nav Drawer Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileNavDrawer = document.getElementById('mobile-nav-drawer');

        if (mobileMenuBtn && mobileNavDrawer) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileNavDrawer.classList.add('open');
                document.body.style.overflow = 'hidden';
            });
        }

        function closeMobileNav() {
            if (mobileNavDrawer) {
                mobileNavDrawer.classList.remove('open');
                document.body.style.overflow = '';
            }
        }

        function openQuickView(id) {
            const overlay = document.getElementById('global-qv-overlay');
            const modal = document.getElementById('global-qv-modal');
            modal.innerHTML = '<div style="padding: 100px; text-align:center;">Loading...</div>';
            overlay.classList.add('active');

            fetch('ajax_quick_view.php?id=' + id)
                .then(res => res.text())
                .then(html => {
                    modal.innerHTML = html;
                });
        }

        function closeQuickView() {
            document.getElementById('global-qv-overlay').classList.remove('active');
        }

        function closeCart() {
            document.getElementById('cart-overlay').classList.remove('active');
            document.getElementById('cart-panel').classList.remove('active');
            document.body.classList.remove('cart-open');
        }

        // Global AJAX Cart interceptor
        document.addEventListener('submit', function(e) {
            if (e.target && (e.target.classList.contains('ajax-cart-form') || e.target.classList.contains('ajax-remove-form'))) {
                e.preventDefault();
                const form = e.target;
                
                // If it's the buy_now button, let standard form submission happen
                if(e.submitter && e.submitter.name === 'buy_now') {
                    form.submit();
                    return;
                }

                const formData = new FormData(form);
                formData.append('ajax', '1');
                
                fetch(form.getAttribute('action') || window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if(data.status === 'success') {
                            // Update badge count
                            const countBadge = document.getElementById('cart-count');
                            if (countBadge && data.count !== undefined) {
                                countBadge.textContent = data.count;
                            }

                            // Show toast notification
                            if (data.message && typeof showToast === 'function') {
                                const isRemove = form.classList.contains('ajax-remove-form');
                                showToast(data.message, isRemove ? 'info' : 'success');
                            }
                            
                            // Refresh cart drawer
                            fetch('/ajax_cart.php')
                                .then(r => r.text())
                                .then(html => {
                                    document.getElementById('cart-panel').innerHTML = html;
                                    lucide.createIcons();
                                    
                                    // Only slide open if adding an item, not removing
                                    if(form.classList.contains('ajax-cart-form')) {
                                        document.getElementById('cart-overlay').classList.add('active');
                                        document.getElementById('cart-panel').classList.add('active');
                                        document.body.classList.add('cart-open');
                                        if(document.getElementById('global-qv-overlay')) {
                                            closeQuickView(); // close quick view if open
                                        }
                                    }
                                });
                        } else {
                            console.error('AJAX cart error status:', data);
                        }
                    } catch(err) {
                        console.error('Failed to parse JSON response:', err);
                        console.log('Raw response received:', text);
                    }
                })
                .catch(err => {
                    console.error('AJAX cart network error:', err);
                });
            }
        });
    </script>

    <?php
    // Calculate global cart numbers for the checkout overlay
    $f_total = 0;
    $f_count = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $f_count += $item['quantity'];
            $f_total += $item['price'] * $item['quantity'];
        }
    }
    $f_mock_original = round($f_total * 1.45 / 100) * 100;
    $f_mock_savings = $f_mock_original - $f_total;

    // Fetch logged in user details if available
    $f_logged_in_user = null;
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
        $u_stmt = $conn->prepare("SELECT name, email, address FROM users WHERE id = ?");
        $u_stmt->bind_param("i", $_SESSION['user_id']);
        $u_stmt->execute();
        $f_logged_in_user = $u_stmt->get_result()->fetch_assoc();
    }
    ?>

    <!-- Global Checkout Overlay -->
    <div class="checkout-login-overlay" id="checkout-login-overlay" style="display: none;">
        <div class="checkout-login-card">
            <!-- Header -->
            <div class="chk-header">
                <button class="chk-back-btn" id="chk-back-btn" style="visibility: hidden;">
                    <i data-lucide="arrow-left" style="width: 20px; height: 20px;"></i>
                </button>
                <div class="chk-logo">
                    JEVANI
                </div>
                <button id="chk-close-btn" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 5px;" title="Cancel & Return to Cart">
                    <i data-lucide="x" style="width: 20px; height: 20px; color: #000000;"></i>
                </button>
            </div>
            
            <!-- Black Bar -->
            <div class="chk-announcement">
                FREE DOORSTEP DELIVERY ANYWHERE IN INDIA
            </div>
            
            <!-- Body -->
            <div class="chk-body">
                <!-- Order Summary Card -->
                <div class="chk-order-summary">
                    <div class="chk-summary-row">
                        <div class="label">
                            <i data-lucide="shopping-cart" style="width: 16px; height: 16px;"></i>
                            Order Summary
                        </div>
                        <div class="value" id="chk-summary-item-count" style="font-size: 0.8rem; color: #64748b; font-weight: 500;">
                            <?php echo $f_count; ?> <?php echo $f_count === 1 ? 'item' : 'items'; ?>
                        </div>
                    </div>
                    <div class="chk-summary-row" style="margin-top: 10px;">
                        <div class="chk-saved-tag">
                            <i data-lucide="sparkles" style="width: 12px; height: 12px; fill: rgba(21, 128, 61, 0.1);"></i>
                            ₹<span id="chk-summary-savings"><?php echo number_format($f_mock_savings); ?></span> saved so far
                        </div>
                        <div class="value">
                            <span class="price-old">₹<span id="chk-summary-original"><?php echo number_format($f_mock_original); ?></span></span>
                            <span>₹<span id="chk-summary-total"><?php echo number_format($f_total); ?></span></span>
                        </div>
                    </div>
                </div>
                
                <!-- Coupon Banner -->
                <div class="chk-banner-green">
                    <i data-lucide="badge-percent" style="width: 18px; height: 18px; fill: rgba(22, 163, 74, 0.1);"></i>
                    "JEVANI WELCOME" applied
                </div>
                
                <!-- Login Container (State 1: Phone Entry) -->
                <div id="chk-step-phone">
                    <div class="chk-login-section">
                        <div class="chk-banner-gold">
                            Login to Redeem Gift Card / Partner Offers
                        </div>
                        <div class="chk-login-header">
                            <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                            Login to continue
                        </div>
                        <div class="chk-login-body">
                            <div class="chk-phone-input-group">
                                <span class="chk-country-code">+91</span>
                                <div class="chk-divider"></div>
                                <input type="tel" id="chk-phone-field" class="chk-phone-input" placeholder="Enter Mobile Number" maxlength="10" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    
                    <label class="chk-checkbox-label">
                        <input type="checkbox" checked id="chk-optin-checkbox">
                        <span>Send me order updates & offers - (no spam)</span>
                    </label>
                    
                    <!-- Continue Button -->
                    <button type="button" class="chk-continue-btn" id="chk-btn-continue" disabled>
                        Continue
                    </button>
                </div>
                
                <!-- Login Container (State 2: OTP Verification) -->
                <div id="chk-step-otp" style="display: none;">
                    <div class="chk-login-section">
                        <div class="chk-login-header">
                            <i data-lucide="key-round" style="width: 16px; height: 16px;"></i>
                            Verify Mobile Number
                        </div>
                        <div class="chk-login-body" style="text-align: center;">
                            <p style="font-size: 0.85rem; color: #475569; margin-bottom: 5px;">Enter the 4-digit code sent to</p>
                            <p id="chk-otp-phone-display" style="font-weight: 700; font-size: 0.95rem; color: #0f172a; margin-bottom: 5px;"></p>
                            
                            <div class="chk-otp-grid">
                                <input type="text" class="chk-otp-input" maxlength="1" data-index="0" inputmode="numeric" pattern="[0-9]*">
                                <input type="text" class="chk-otp-input" maxlength="1" data-index="1" inputmode="numeric" pattern="[0-9]*">
                                <input type="text" class="chk-otp-input" maxlength="1" data-index="2" inputmode="numeric" pattern="[0-9]*">
                                <input type="text" class="chk-otp-input" maxlength="1" data-index="3" inputmode="numeric" pattern="[0-9]*">
                            </div>
                            
                            <div class="chk-timer">
                                Resend OTP in <span id="chk-timer-countdown">29s</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Verify Button -->
                    <button type="button" class="chk-continue-btn" id="chk-btn-verify" disabled>
                        Verify & Log In
                    </button>
                </div>

                <!-- Login Container (State 3: Add Delivery Address) -->
                <div id="chk-step-address" style="display: none;">
                    <div class="chk-section-title">Add Delivery Address</div>
                    
                    <div class="chk-input-box">
                        <label>Pincode *</label>
                        <input type="text" id="chk-pincode" placeholder="Enter Pincode" maxlength="6" inputmode="numeric" pattern="[0-9]*">
                    </div>
                    
                    <div class="chk-input-row">
                        <div class="chk-input-box">
                            <label>City *</label>
                            <input type="text" id="chk-city" placeholder="Enter City">
                        </div>
                        <div class="chk-input-box">
                            <label>State *</label>
                            <input type="text" id="chk-state" placeholder="Enter State">
                        </div>
                    </div>
                    
                    <div class="chk-input-box">
                        <label>Flat, House no. *</label>
                        <input type="text" id="chk-flat" placeholder="Enter Flat, House no.">
                    </div>
                    
                    <div class="chk-input-box">
                        <label>Apartment, Area, Sector, Village *</label>
                        <input type="text" id="chk-area" placeholder="Enter Apartment, Area, Sector, Village">
                    </div>
                    
                    <div class="chk-section-title" style="margin-top: 20px;">Customer Information</div>
                    
                    <div class="chk-input-box">
                        <label>Full Name *</label>
                        <input type="text" id="chk-name" placeholder="Enter Full Name">
                    </div>
                    
                    <div class="chk-input-box">
                        <label>Email Address *</label>
                        <input type="email" id="chk-email" placeholder="Enter Email Address">
                    </div>
                    
                    <span class="chk-label-bold">Save Address As</span>
                    <div class="chk-address-type-selector">
                        <div class="chk-type-btn active" id="chk-type-home" onclick="window.setAddressType('Home')">
                            <span class="chk-radio-circle"></span>
                            Home
                        </div>
                        <div class="chk-type-btn" id="chk-type-work" onclick="window.setAddressType('Work')">
                            <span class="chk-radio-circle"></span>
                            Work
                        </div>
                    </div>
                    
                    <span class="chk-label-bold">Shipping Method</span>
                    <div class="chk-shipping-card">
                        <div>
                            <div class="chk-shipping-title">Standard Shipping</div>
                            <div class="chk-shipping-badge">Free</div>
                        </div>
                        <i data-lucide="circle-check" style="width: 20px; height: 20px; color: #000000; fill: rgba(0,0,0,0.05);"></i>
                    </div>
                    
                    <!-- Hidden input for address type -->
                    <input type="hidden" id="chk-address-type" value="Home">

                    <!-- Continue Button -->
                    <button type="button" class="chk-continue-btn" id="chk-btn-address-continue" disabled>
                        Continue
                    </button>
                </div>

                <!-- Login Container (State 4: Payment Method) -->
                <div id="chk-step-payment" style="display: none;">
                    <div class="chk-section-title">Select Payment Method</div>
                    
                    <div class="chk-payment-list">
                        <div class="chk-payment-item active" id="chk-pay-cod" onclick="window.setPaymentMethod('cod')">
                            <div class="chk-payment-info">
                                <i data-lucide="hand-coins" style="width: 20px; height: 20px;"></i>
                                <span class="chk-payment-text">Cash on Delivery (COD)</span>
                            </div>
                            <span class="chk-radio-circle"></span>
                        </div>
                        
                        <div class="chk-payment-item" id="chk-pay-razorpay" onclick="window.setPaymentMethod('razorpay')">
                            <div class="chk-payment-info">
                                <i data-lucide="credit-card" style="width: 20px; height: 20px;"></i>
                                <span class="chk-payment-text">Credit Card / UPI (Razorpay)</span>
                            </div>
                            <span class="chk-radio-circle"></span>
                        </div>
                    </div>
                    
                    <!-- Hidden input for payment method -->
                    <input type="hidden" id="chk-payment-method" value="cod">

                    <!-- Place Order Button -->
                    <button type="button" class="chk-continue-btn active" id="chk-btn-place-order">
                        Place Order (₹<span id="chk-payment-btn-total"><?php echo number_format($f_total); ?></span>)
                    </button>
                </div>
                
                <!-- Trust Badges -->
                <div class="chk-trust-badges">
                    <span class="chk-trust-title">Powering secure checkout experiences for JEVANI</span>
                    <div class="chk-badge-grid">
                        <span class="chk-badge-item">PCI DSS Certified</span>
                        <span class="chk-badge-item">100% Secured Payments</span>
                        <span class="chk-badge-item">Verified Merchant</span>
                    </div>
                </div>
                
                <!-- Footer Text -->
                <div class="chk-footer-text">
                    By proceeding, I agree to Jevani's <a href="#">Privacy Policy</a> and <a href="#">T&C</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancellation Feedback Survey Overlay Card -->
    <div class="chk-cancel-overlay" id="chk-cancel-overlay" style="display: none;">
        <div class="chk-cancel-card">
            <!-- Circular Close Button on Top -->
            <button type="button" class="chk-cancel-close-circle" onclick="window.closeCancelModal()">
                <i data-lucide="x" style="width: 18px; height: 18px; color: #000;"></i>
            </button>
            
            <div class="chk-cancel-body">
                <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 24px; padding: 10px 0;">
                    <label class="chk-survey-item">
                        <input type="checkbox" name="cancel_reason" value="modify_cart">
                        <span>I want to add or modify items in my cart</span>
                    </label>
                    <label class="chk-survey-item">
                        <input type="checkbox" name="cancel_reason" value="price_high">
                        <span>I find pricing too high or unclear</span>
                    </label>
                    <label class="chk-survey-item">
                        <input type="checkbox" name="cancel_reason" value="quality_policy">
                        <span>I am not sure about quality and return/exchange policy</span>
                    </label>
                    <label class="chk-survey-item">
                        <input type="checkbox" name="cancel_reason" value="coupon_issues">
                        <span>I am facing issues in applying coupons</span>
                    </label>
                    <label class="chk-survey-item">
                        <input type="checkbox" name="cancel_reason" value="delivery_dates">
                        <span>I am not sure about the delivery dates</span>
                    </label>
                    <label class="chk-survey-item">
                        <input type="checkbox" name="cancel_reason" value="others">
                        <span>Others</span>
                    </label>
                </div>
                
                <!-- Bottom Action Box -->
                <div class="chk-cancel-action-box">
                    <p class="chk-cancel-action-title">Do you want to still cancel the payment?</p>
                    <div class="chk-cancel-action-buttons">
                        <button type="button" class="chk-btn-continue-shopping" onclick="window.closeCancelModal()">
                            Continue Shopping
                        </button>
                        <button type="button" class="chk-btn-skip-exit" onclick="window.confirmCancelCheckout()">
                            Skip and exit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global Checkout Overlay JS Controller
        (function() {
            const chkOverlay = document.getElementById('checkout-login-overlay');
            const chkCancelOverlay = document.getElementById('chk-cancel-overlay');
            
            // Steps
            const chkStepPhone = document.getElementById('chk-step-phone');
            const chkStepOtp = document.getElementById('chk-step-otp');
            const chkStepAddress = document.getElementById('chk-step-address');
            const chkStepPayment = document.getElementById('chk-step-payment');
            
            // Navigation buttons
            const chkBackBtn = document.getElementById('chk-back-btn');
            const chkCloseBtn = document.getElementById('chk-close-btn');
            
            // Step 1 controls
            const chkPhoneField = document.getElementById('chk-phone-field');
            const chkContinueBtn = document.getElementById('chk-btn-continue');
            
            // Step 2 controls
            const chkVerifyBtn = document.getElementById('chk-btn-verify');
            const chkOtpPhoneDisplay = document.getElementById('chk-otp-phone-display');
            const chkTimerCountdown = document.getElementById('chk-timer-countdown');
            const chkOtpInputs = document.querySelectorAll('.chk-otp-input');
            
            // Step 3 controls
            const chkPincode = document.getElementById('chk-pincode');
            const chkCity = document.getElementById('chk-city');
            const chkState = document.getElementById('chk-state');
            const chkFlat = document.getElementById('chk-flat');
            const chkArea = document.getElementById('chk-area');
            const chkNameInput = document.getElementById('chk-name');
            const chkEmailInput = document.getElementById('chk-email');
            const chkAddressContinueBtn = document.getElementById('chk-btn-address-continue');
            
            // Step 4 controls
            const chkPlaceOrderBtn = document.getElementById('chk-btn-place-order');
            
            let chkEnteredPhone = '';
            let chkCountdownInterval = null;
            let chkCurrentStep = 1;
            
            // Prefilled user profile details
            window.loggedInUserData = <?php echo $f_logged_in_user ? json_encode($f_logged_in_user) : 'null'; ?>;

            // Open Overlay
            window.openCheckoutOverlay = function() {
                // Sync cart values first to verify actual cart items
                fetch('/ajax_cart.php')
                    .then(r => r.text())
                    .then(html => {
                        document.getElementById('cart-panel').innerHTML = html;
                        lucide.createIcons();
                        
                        // Count unique items inside the cart panel
                        const cartItems = document.querySelectorAll('.cart-panel .cart-item');
                        const itemCount = cartItems.length;
                        
                        // Sync header badge dynamically to prevent out-of-sync block
                        const countBadge = document.getElementById('cart-count');
                        if (countBadge) {
                            countBadge.textContent = itemCount;
                        }
                        
                        if (itemCount === 0) {
                            alert('Your cart is empty');
                            return;
                        }
                        
                        const totalStr = document.querySelector('.cart-total span:last-child')?.textContent || '₹0';
                        const numericTotal = parseInt(totalStr.replace(/[^0-9]/g, '')) || 0;
                        
                        if (numericTotal === 0) {
                            alert('Your cart is empty');
                            return;
                        }

                        const mockOriginal = Math.round(numericTotal * 1.45);
                        const mockSavings = mockOriginal - numericTotal;
                        
                        document.getElementById('chk-summary-item-count').textContent = itemCount + (itemCount === 1 ? ' item' : ' items');
                        document.getElementById('chk-summary-savings').textContent = mockSavings.toLocaleString();
                        document.getElementById('chk-summary-original').textContent = mockOriginal.toLocaleString();
                        document.getElementById('chk-summary-total').textContent = numericTotal.toLocaleString();
                        document.getElementById('chk-payment-btn-total').textContent = numericTotal.toLocaleString();
                        
                        chkOverlay.style.display = 'flex';
                        closeCart();
                        
                        // Clear all input values to ensure they only show dummy placeholders and not pre-filled/saved data
                        chkPhoneField.value = '';
                        chkOtpInputs.forEach(i => i.value = '');
                        chkPincode.value = '';
                        chkCity.value = '';
                        chkState.value = '';
                        chkFlat.value = '';
                        chkArea.value = '';
                        chkNameInput.value = '';
                        chkEmailInput.value = '';
                        
                        chkStepPhone.style.display = 'block';
                        chkStepOtp.style.display = 'none';
                        chkStepAddress.style.display = 'none';
                        chkStepPayment.style.display = 'none';
                        chkBackBtn.style.visibility = 'hidden';
                        chkCurrentStep = 1;
                        
                        chkContinueBtn.classList.remove('active');
                        chkContinueBtn.disabled = true;
                        chkVerifyBtn.classList.remove('active');
                        chkVerifyBtn.disabled = true;
                        
                        validateAddressForm();
                        chkPhoneField.focus();
                        lucide.createIcons();
                    });
            };

            window.closeCheckoutOverlay = function() {
                chkOverlay.style.display = 'none';
            };

            // Intercept all checkout clicks globally
            document.addEventListener('click', function(e) {
                const checkoutLink = e.target.closest('a[href*="checkout.php"]');
                if (checkoutLink) {
                    e.preventDefault();
                    window.openCheckoutOverlay();
                }
            });

            // Close triggers
            if (chkCloseBtn) {
                chkCloseBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    window.showCancelModal();
                });
            }
            if (chkOverlay) {
                chkOverlay.addEventListener('click', function(e) {
                    if (e.target === chkOverlay) {
                        window.showCancelModal();
                    }
                });
            }

            window.showCancelModal = function() {
                chkCancelOverlay.style.display = 'flex';
                lucide.createIcons();
            };

            window.closeCancelModal = function() {
                chkCancelOverlay.style.display = 'none';
            };

            window.confirmCancelCheckout = function() {
                window.closeCancelModal();
                window.closeCheckoutOverlay();
                if (window.location.pathname.endsWith('checkout.php')) {
                    window.location.href = 'cart.php';
                }
            };

            // Back button navigation
            chkBackBtn.addEventListener('click', function() {
                if (chkCurrentStep === 2) {
                    clearInterval(chkCountdownInterval);
                    chkStepOtp.style.display = 'none';
                    chkStepPhone.style.display = 'block';
                    chkBackBtn.style.visibility = 'hidden';
                    chkOtpInputs.forEach(i => i.value = '');
                    chkVerifyBtn.classList.remove('active');
                    chkVerifyBtn.disabled = true;
                    chkPhoneField.focus();
                    chkCurrentStep = 1;
                } else if (chkCurrentStep === 3) {
                    chkStepAddress.style.display = 'none';
                    chkStepPhone.style.display = 'block';
                    chkBackBtn.style.visibility = 'hidden';
                    chkPhoneField.focus();
                    chkCurrentStep = 1;
                } else if (chkCurrentStep === 4) {
                    chkStepPayment.style.display = 'none';
                    chkStepAddress.style.display = 'block';
                    chkCurrentStep = 3;
                }
            });

            // Step 1 validation
            chkPhoneField.addEventListener('input', function(e) {
                let val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = val;
                if (val.length === 10) {
                    chkContinueBtn.classList.add('active');
                    chkContinueBtn.disabled = false;
                } else {
                    chkContinueBtn.classList.remove('active');
                    chkContinueBtn.disabled = true;
                }
            });

            chkPhoneField.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && chkPhoneField.value.length === 10) {
                    chkContinueBtn.click();
                }
            });

            chkContinueBtn.addEventListener('click', function() {
                chkEnteredPhone = chkPhoneField.value;
                if (chkEnteredPhone.length !== 10) return;
                
                chkStepPhone.style.display = 'none';
                chkStepOtp.style.display = 'block';
                chkBackBtn.style.visibility = 'visible';
                chkOtpPhoneDisplay.textContent = '+91 ' + chkEnteredPhone.slice(0, 5) + ' ' + chkEnteredPhone.slice(5);
                
                setTimeout(() => chkOtpInputs[0].focus(), 100);
                startTimer();
                chkCurrentStep = 2;
            });

            // Step 2 validation
            chkOtpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    let val = e.target.value.replace(/[^0-9]/g, '');
                    e.target.value = val;
                    if (val && index < chkOtpInputs.length - 1) {
                        chkOtpInputs[index + 1].focus();
                    }
                    checkOtpCompletion();
                });
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        chkOtpInputs[index - 1].focus();
                    }
                });
            });

            function checkOtpCompletion() {
                let code = getOtpCode();
                if (code.length === 4) {
                    chkVerifyBtn.classList.add('active');
                    chkVerifyBtn.disabled = false;
                } else {
                    chkVerifyBtn.classList.remove('active');
                    chkVerifyBtn.disabled = true;
                }
            }

            function getOtpCode() {
                let code = '';
                chkOtpInputs.forEach(i => code += i.value);
                return code;
            }

            function startTimer() {
                let seconds = 29;
                chkTimerCountdown.innerHTML = seconds + 's';
                clearInterval(chkCountdownInterval);
                chkCountdownInterval = setInterval(() => {
                    seconds--;
                    if (seconds <= 0) {
                        clearInterval(chkCountdownInterval);
                        chkTimerCountdown.innerHTML = '<span class="chk-timer-link" onclick="window.resendOtp()">Resend OTP</span>';
                    } else {
                        chkTimerCountdown.innerHTML = seconds + 's';
                    }
                }, 1000);
            }

            window.resendOtp = function() {
                alert('Mock OTP resent. Please use code 1234 to verify.');
                startTimer();
            };

            chkVerifyBtn.addEventListener('click', performLogin);
            chkOtpInputs[3].addEventListener('keyup', function(e) {
                if (e.key === 'Enter' && getOtpCode().length === 4) {
                    performLogin();
                }
            });

            function performLogin() {
                const otpCode = getOtpCode();
                if (otpCode.length !== 4) return;
                
                chkVerifyBtn.disabled = true;
                chkVerifyBtn.innerHTML = 'Verifying...';

                const data = new FormData();
                data.append('phone', chkEnteredPhone);
                data.append('otp', otpCode);

                fetch('ajax_phone_login.php', {
                    method: 'POST',
                    body: data
                })
                .then(res => res.json())
                .then(resData => {
                    if (resData.status === 'success') {
                        // Keep inputs completely blank so dummy placeholders are shown and not original saved data
                        clearInterval(chkCountdownInterval);
                        chkStepOtp.style.display = 'none';
                        chkStepAddress.style.display = 'block';
                        chkBackBtn.style.visibility = 'visible';
                        chkCurrentStep = 3;
                        validateAddressForm();
                        chkPincode.focus();
                    } else {
                        alert(resData.message || 'Verification failed. Try again.');
                        chkVerifyBtn.disabled = false;
                        chkVerifyBtn.innerHTML = 'Verify & Log In';
                        chkOtpInputs.forEach(i => i.value = '');
                        chkOtpInputs[0].focus();
                        chkVerifyBtn.classList.remove('active');
                    }
                })
                .catch(err => {
                    console.error('Login error:', err);
                    alert('Network error. Please try again.');
                    chkVerifyBtn.disabled = false;
                    chkVerifyBtn.innerHTML = 'Verify & Log In';
                });
            }

            // Step 3 helpers
            window.setAddressType = function(type) {
                document.getElementById('chk-address-type').value = type;
                document.querySelectorAll('.chk-address-type-selector .chk-type-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                if (type === 'Home') {
                    document.getElementById('chk-type-home').classList.add('active');
                } else {
                    document.getElementById('chk-type-work').classList.add('active');
                }
                validateAddressForm();
            };

            function validateAddressForm() {
                const isPincodeValid = chkPincode.value.replace(/[^0-9]/g, '').length === 6;
                const isCityValid = chkCity.value.trim().length > 0;
                const isStateValid = chkState.value.trim().length > 0;
                const isFlatValid = chkFlat.value.trim().length > 0;
                const isAreaValid = chkArea.value.trim().length > 0;
                const isNameValid = chkNameInput.value.trim().length > 0;
                const isEmailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(chkEmailInput.value.trim());
                
                if (isPincodeValid && isCityValid && isStateValid && isFlatValid && isAreaValid && isNameValid && isEmailValid) {
                    chkAddressContinueBtn.classList.add('active');
                    chkAddressContinueBtn.disabled = false;
                } else {
                    chkAddressContinueBtn.classList.remove('active');
                    chkAddressContinueBtn.disabled = true;
                }
            }

            [chkPincode, chkCity, chkState, chkFlat, chkArea, chkNameInput, chkEmailInput].forEach(i => {
                i.addEventListener('input', validateAddressForm);
            });

            chkPincode.addEventListener('input', function(e) {
                let val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = val;
            });

            chkAddressContinueBtn.addEventListener('click', function() {
                chkStepAddress.style.display = 'none';
                chkStepPayment.style.display = 'block';
                chkCurrentStep = 4;
            });

            // Step 4 helpers
            window.setPaymentMethod = function(method) {
                document.getElementById('chk-payment-method').value = method;
                document.querySelectorAll('.chk-payment-list .chk-payment-item').forEach(i => {
                    i.classList.remove('active');
                });
                if (method === 'cod') {
                    document.getElementById('chk-pay-cod').classList.add('active');
                } else {
                    document.getElementById('chk-pay-razorpay').classList.add('active');
                }
            };

            chkPlaceOrderBtn.addEventListener('click', function() {
                chkPlaceOrderBtn.disabled = true;
                chkPlaceOrderBtn.innerHTML = 'Placing Order...';
                
                const data = new FormData();
                data.append('name', chkNameInput.value.trim());
                data.append('email', chkEmailInput.value.trim());
                data.append('pincode', chkPincode.value.trim());
                data.append('city', chkCity.value.trim());
                data.append('state', chkState.value.trim());
                data.append('flat', chkFlat.value.trim());
                data.append('area', chkArea.value.trim());
                data.append('address_type', document.getElementById('chk-address-type').value);
                data.append('payment_method', document.getElementById('chk-payment-method').value);
                
                fetch('ajax_create_order.php', {
                    method: 'POST',
                    body: data
                })
                .then(res => res.json())
                .then(resData => {
                    if (resData.status === 'success') {
                        window.location.href = 'order_success.php?order_id=' + resData.order_id + '&method=' + resData.payment_method;
                    } else {
                        alert(resData.message || 'Failed to place order. Please try again.');
                        chkPlaceOrderBtn.disabled = false;
                        chkPlaceOrderBtn.innerHTML = 'Place Order';
                    }
                })
                .catch(err => {
                    console.error('Order creation error:', err);
                    alert('Network error. Please try again.');
                    chkPlaceOrderBtn.disabled = false;
                    chkPlaceOrderBtn.innerHTML = 'Place Order';
                });
            });

            // Combined address parser
            function parseAddress(addrStr) {
                if (!addrStr) return null;
                try {
                    const typeMatch = addrStr.match(/\((Home|Work)\)$/i);
                    const addressType = typeMatch ? typeMatch[1] : 'Home';
                    
                    let cleanStr = addrStr.replace(/\((Home|Work)\)$/i, '').trim();
                    const pinMatch = cleanStr.match(/-\s*(\d{6})$/);
                    const pincode = pinMatch ? pinMatch[1] : '';
                    
                    cleanStr = cleanStr.replace(/-\s*(\d{6})$/, '').trim();
                    const parts = cleanStr.split(',').map(s => s.trim());
                    
                    let state = '';
                    let city = '';
                    let area = '';
                    let flat = '';
                    
                    if (parts.length >= 4) {
                        state = parts[parts.length - 1];
                        city = parts[parts.length - 2];
                        area = parts[parts.length - 3];
                        flat = parts.slice(0, parts.length - 3).join(', ');
                    } else if (parts.length === 3) {
                        state = parts[2];
                        city = parts[1];
                        flat = parts[0];
                    } else {
                        flat = cleanStr;
                    }
                    
                    return { pincode, city, state, flat, area, addressType };
                } catch (e) {
                    return { pincode: '', city: '', state: '', flat: addrStr, area: '', addressType: 'Home' };
                }
            }
        })();
    </script>
</body>
</html>
