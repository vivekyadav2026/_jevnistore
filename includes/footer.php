    <!-- Footer CSS -->
    <style>
        .footer-premium {
            background-color: #000;
            color: #fff;
            padding: 80px 40px 40px;
            font-family: 'Inter', sans-serif;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        .footer-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }
        .footer-brand p {
            color: #a1a1aa;
            font-size: 0.85rem;
            line-height: 1.6;
            margin-top: 20px;
            max-width: 300px;
        }
        .footer-col-title {
            font-size: 0.85rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 25px;
            color: #fff;
        }
        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .footer-links a {
            color: #a1a1aa;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }
        .footer-links a:hover {
            color: #fff;
        }
        .footer-newsletter input {
            background: transparent;
            border: none;
            border-bottom: 1px solid #444;
            color: #fff;
            padding: 10px 0;
            width: 100%;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.3s;
            margin-bottom: 15px;
        }
        .footer-newsletter input:focus {
            border-bottom-color: #fff;
        }
        .footer-newsletter button {
            background: #fff;
            color: #000;
            border: none;
            padding: 10px 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .footer-newsletter button:hover {
            background: #e4e4e7;
        }
        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .footer-social {
            display: flex;
            gap: 20px;
        }
        .footer-social a {
            color: #a1a1aa;
            transition: color 0.3s ease;
        }
        .footer-social a:hover {
            color: #fff;
        }
        .footer-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 0.8rem;
            color: #71717a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer-country {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #a1a1aa;
        }
        @media (max-width: 900px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
            .footer-brand {
                grid-column: span 2;
            }
        }
        @media (max-width: 600px) {
            .footer-premium {
                padding: 60px 20px 30px;
            }
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .footer-brand {
                grid-column: span 1;
            }
            .footer-bottom {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            .footer-meta {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>

    <!-- Footer HTML -->
    <footer class="footer-premium">
        <div class="footer-grid">
            
            <!-- Column 1: Brand & Newsletter -->
            <div class="footer-brand">
                <a href="<?php echo BASE_URL; ?>/index.php" style="display: inline-block;">
                    <img src="<?php echo BASE_URL; ?>/assets/logo_gothic.png" alt="JEVANI" style="height: 70px; width: auto; object-fit: contain;">
                </a>
                <p>Every stitch carries purpose. Jevani is a premium streetwear accessories brand redefining everyday carry for the modern urbanite. Proudly homegrown in India.</p>
                
                <div class="footer-newsletter" style="margin-top: 30px;">
                    <div class="footer-col-title" style="margin-bottom: 15px;">Join The Inner Circle</div>
                    <form onsubmit="event.preventDefault(); alert('Subscribed successfully!');">
                        <input type="email" placeholder="ENTER YOUR EMAIL ADDRESS" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>

            <!-- Column 2: Shop -->
            <div>
                <div class="footer-col-title">Shop</div>
                <div class="footer-links">
                    <a href="<?php echo BASE_URL; ?>/shop.php">All Products</a>
                    <a href="<?php echo BASE_URL; ?>/shop.php?category=totes">Signature Totes</a>
                    <a href="<?php echo BASE_URL; ?>/shop.php?category=crossbody">Crossbody Bags</a>
                    <a href="<?php echo BASE_URL; ?>/shop.php?category=accessories">Accessories</a>
                    <a href="<?php echo BASE_URL; ?>/lookbook.php">The Lookbook</a>
                </div>
            </div>

            <!-- Column 3: Support -->
            <div>
                <div class="footer-col-title">Support</div>
                <div class="footer-links">
                    <a href="<?php echo BASE_URL; ?>/customer/index.php">Track My Order</a>
                    <a href="<?php echo BASE_URL; ?>/exchange_policy.php">Returns & Exchanges</a>
                    <a href="<?php echo BASE_URL; ?>/policies.php">Shipping Policy</a>
                    <a href="<?php echo BASE_URL; ?>/contact.php">Contact Us</a>
                </div>
            </div>

            <!-- Column 4: Company -->
            <div>
                <div class="footer-col-title">Company</div>
                <div class="footer-links">
                    <a href="<?php echo BASE_URL; ?>/about.php">About Jevani</a>
                    <a href="<?php echo BASE_URL; ?>/reviews.php">Customer Reviews</a>
                    <a href="<?php echo BASE_URL; ?>/partner_with_us.php">Partner With Us</a>
                    <a href="<?php echo BASE_URL; ?>/terms_of_service.php">Terms of Service</a>
                </div>
            </div>

        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <!-- Social Icons -->
            <div class="footer-social">
                <a href="<?php echo htmlspecialchars(getSetting('social_instagram', '#')); ?>" target="_blank" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 20px; height: 20px;"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                </a>
                <?php 
                    $wa_number = getSetting('contact_whatsapp', ''); 
                    $wa_clean = preg_replace('/[^0-9]/', '', $wa_number);
                    $wa_link = $wa_clean ? 'https://wa.me/' . $wa_clean : '#';
                ?>
                <a href="<?php echo htmlspecialchars($wa_link); ?>" target="_blank" aria-label="WhatsApp">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 20px; height: 20px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                </a>
                <a href="#" target="_blank" aria-label="TikTok">
                    <svg viewBox="0 0 24 24" fill="currentColor" style="width: 18px; height: 18px;"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.951-7.252 4.168 0 7.41 2.967 7.41 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.367 18.592 0 12.017 0z"/></svg>
                </a>
            </div>
            
            <div class="footer-meta">
                <div class="footer-country">
                    <img src="https://flagcdn.com/w20/in.png" alt="India" style="height: 12px; border-radius: 2px;">
                    INDIA (INR ₹) <i data-lucide="chevron-up" style="width: 14px; height: 14px;"></i>
                </div>
                <div>
                    &copy; <?php echo date('Y'); ?> JEVANI STUDIOS. ALL RIGHTS RESERVED.
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Navigation Drawer -->
    <div class="mobile-nav-drawer" id="mobile-nav-drawer">
        <div class="mobile-nav-backdrop" onclick="closeMobileNav()"></div>
        <div class="mobile-nav-panel">
            <div class="mobile-nav-header">
                <button class="mobile-nav-close" onclick="closeMobileNav()">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            $category = isset($_GET['category']) ? $_GET['category'] : null;
            ?>
            <div class="mobile-nav-links">
                <a href="<?php echo BASE_URL; ?>/shop.php" class="<?php echo ($current_page == 'shop.php' && $category === null) ? 'active' : ''; ?>">ALL BAGS</a>
                <!-- <a href="<?php echo BASE_URL; ?>/shop.php?category=1" class="<?php echo ($current_page == 'shop.php' && strval($category) === '1') ? 'active' : ''; ?>">SHOULDER BAGS</a> -->
                <!-- <a href="<?php echo BASE_URL; ?>/shop.php?category=2" class="<?php echo ($current_page == 'shop.php' && strval($category) === '2') ? 'active' : ''; ?>">TOTES</a> -->
                <a href="<?php echo BASE_URL; ?>/wishlist.php" class="<?php echo ($current_page == 'wishlist.php') ? 'active' : ''; ?>">WISHLIST</a>
                <a href="<?php echo BASE_URL; ?>/lookbook.php" class="<?php echo ($current_page == 'lookbook.php') ? 'active' : ''; ?>">LOOKBOOK</a>
                <a href="<?php echo BASE_URL; ?>/about.php" class="<?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">ABOUT</a>

                
                <!-- Spacer pushing login and currency to bottom -->
                <div class="mobile-nav-bottom-section">
                    <?php if(isLoggedIn()): ?>
                        <a href="<?php echo isAdmin() ? BASE_URL . '/admin/index.php' : BASE_URL . '/customer/index.php'; ?>">
                            <i data-lucide="user" class="login-icon"></i>
                            <span>My Account</span>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/logout.php">
                            <i data-lucide="log-out" class="login-icon"></i>
                            <span>Logout</span>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/login.php">
                            <i data-lucide="user" class="login-icon"></i>
                            <span>Login</span>
                        </a>
                    <?php endif; ?>
                    
                    <div class="currency-selector">
                        <div class="currency-left">
                            <svg class="flag-icon" width="20" height="14" viewBox="0 0 900 600">
                                <rect width="900" height="200" fill="#FF9933"/>
                                <rect y="200" width="900" height="200" fill="#FFFFFF"/>
                                <rect y="400" width="900" height="200" fill="#128807"/>
                                <circle cx="450" cy="300" r="80" fill="none" stroke="#000080" stroke-width="10"/>
                                <circle cx="450" cy="300" r="15" fill="#000080"/>
                                <circle cx="450" cy="300" r="80" fill="none" stroke="#000080" stroke-width="8"/>
                                <line x1="450" y1="220" x2="450" y2="380" stroke="#000080" stroke-width="6"/>
                                <line x1="370" y1="300" x2="530" y2="300" stroke="#000080" stroke-width="6"/>
                                <line x1="393" y1="243" x2="507" y2="357" stroke="#000080" stroke-width="6"/>
                                <line x1="393" y1="357" x2="507" y2="243" stroke="#000080" stroke-width="6"/>
                            </svg>
                            <span>INR ₹</span>
                        </div>
                        <i data-lucide="chevron-down" class="currency-chevron"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Overlay & Panel -->
    <div class="cart-overlay" id="cart-overlay" onclick="closeCart()"></div>
    <div class="cart-panel" id="cart-panel">
        <?php include __DIR__ . '/../ajax_cart.php'; ?>
    </div>

    <!-- Chat Widget -->
    <div class="chat-widget-container">
        <!-- Chat Popup Window -->
        <div class="chat-popup" id="chat-popup">
            <div class="chat-header">
                <span>Chat with us</span>
                <button class="close-chat" onclick="toggleChatWidget()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="chat-body">
                <p class="chat-greeting">How can we help you today?</p>
                
                <?php $ig_link = getSetting('social_instagram', 'https://instagram.com'); ?>
                <a href="<?php echo htmlspecialchars($ig_link); ?>" target="_blank" class="chat-option">
                    <div class="chat-option-icon instagram-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </div>
                    <span>Let's talk on Instagram</span>
                    <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
                
                <?php 
                    $wa_number = getSetting('contact_whatsapp', ''); 
                    // Remove any non-numeric characters for the wa.me link
                    $wa_clean = preg_replace('/[^0-9]/', '', $wa_number);
                    $wa_link = $wa_clean ? 'https://wa.me/' . $wa_clean : '#';
                ?>
                <a href="<?php echo htmlspecialchars($wa_link); ?>" target="_blank" class="chat-option">
                    <div class="chat-option-icon whatsapp-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    <span>Lets talk on WhatsApp</span>
                    <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </div>
        </div>

        <!-- Floating Action Button -->
        <button class="chat-fab" id="chat-fab" onclick="toggleChatWidget()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 28px; height: 28px;">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                <line x1="9" y1="10" x2="15" y2="10"></line>
                <line x1="9" y1="14" x2="15" y2="14"></line>
            </svg>
        </button>
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
        
        function toggleChatWidget() {
            const popup = document.getElementById('chat-popup');
            popup.classList.toggle('active');
        }

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
                    if(typeof lucide !== 'undefined') lucide.createIcons();
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

        // JS wishlist toggle function
        function toggleWishlist(productId, btn) {
            const isRemoving = btn.classList.contains('in-wishlist');
            const data = new FormData();
            data.append('action', isRemoving ? 'remove' : 'add');
            data.append('product_id', productId);
            data.append('ajax', '1');
            
            fetch('<?php echo BASE_URL; ?>/wishlist_action.php', {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(resData => {
                if (resData.status === 'success') {
                    // Lucide replaces <i data-lucide> with <svg> at runtime
                    // so we target svg first, fallback to i
                    const icon = btn.querySelector('svg') || btn.querySelector('i');

                    if (isRemoving) {
                        btn.classList.remove('in-wishlist');
                        if (icon) {
                            icon.style.color = '#111111';
                            icon.style.fill  = 'none';
                        }
                    } else {
                        btn.classList.add('in-wishlist');
                        if (icon) {
                            icon.style.color = '#ef4444';
                            icon.style.fill  = '#ef4444';
                        }
                    }
                    
                    // Update Wishlist Badge Counter dynamically if exists
                    const wishlistBadge = document.querySelector('.header-wishlist-icon .wishlist-count');
                    if (wishlistBadge && resData.count !== undefined) {
                        wishlistBadge.textContent = resData.count;
                    }
                }
            })
            .catch(err => console.error('Wishlist error:', err));
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

                            // Show toast notification (disabled)
                            /*
                            if (data.message && typeof showToast === 'function') {
                                const isRemove = form.classList.contains('ajax-remove-form');
                                showToast(data.message, isRemove ? 'info' : 'success');
                            }
                            */
                            
                            // Refresh cart drawer
                            fetch('<?php echo BASE_URL; ?>/ajax_cart.php')
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
                <div class="chk-order-summary" style="display: none;">
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
                <div class="chk-banner-green" style="display: none;">
                    <i data-lucide="badge-percent" style="width: 18px; height: 18px; fill: rgba(22, 163, 74, 0.1);"></i>
                    "JEVANI WELCOME" applied
                </div>
                               <!-- Login Container (Step 1: Email + Password) -->
                <div id="chk-step-login">
                    <div class="chk-login-section">
                        <div class="chk-banner-gold">
                            Login to Redeem Gift Card / Partner Offers
                        </div>
                        <div class="chk-login-header">
                            <i data-lucide="mail" style="width: 16px; height: 16px;"></i>
                            Sign in to continue
                        </div>
                        <div class="chk-login-body">
                            <!-- Email field -->
                            <div style="margin-bottom: 10px;">
                                <div style="position: relative;">
                                    <svg style="position:absolute;left:11px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:#94a3b8;pointer-events:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    <input type="email" id="chk-email-field" placeholder="Email address" autocomplete="email" class="chk-login-input"
                                        style="padding-left: 34px;">
                                </div>
                            </div>
                            <!-- Password field -->
                            <div style="position: relative;">
                                <svg style="position:absolute;left:11px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:#94a3b8;pointer-events:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                <input type="password" id="chk-password-field" placeholder="Password" autocomplete="current-password" class="chk-login-input"
                                    style="padding-left: 34px; padding-right: 36px;">
                                <button type="button" id="chk-pwd-toggle" onclick="chkTogglePwd()" tabindex="-1"
                                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;display:flex;align-items:center;">
                                    <svg id="chk-eye-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <!-- Error message -->
                            <div id="chk-login-error" style="display:none;margin-top:8px;padding:8px 10px;background:#fef2f2;border:1px solid #fecaca;border-radius:6px;font-size:0.78rem;color:#dc2626;"></div>
                            <!-- Forgot password link -->
                            <div style="text-align:right;margin-top:6px;">
                                <a href="<?php echo BASE_URL; ?>/forgot_password.php" style="font-size:0.78rem;color:#6366f1;text-decoration:none;">Forgot password?</a>
                            </div>
                        </div>
                    </div>

                    <label class="chk-checkbox-label">
                        <input type="checkbox" checked id="chk-optin-checkbox">
                        <span>Send me order updates &amp; offers - (no spam)</span>
                    </label>

                    <!-- Sign In Button -->
                    <button type="button" class="chk-continue-btn" id="chk-btn-continue" disabled>
                        Sign In &amp; Continue
                    </button>

                    <!-- Register link -->
                    <div style="text-align:center;margin-top:12px;font-size:0.8rem;color:#64748b;">
                        New here? <a href="<?php echo BASE_URL; ?>/register.php" style="color:#6366f1;font-weight:600;text-decoration:none;">Create an account</a>
                    </div>
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
                        <i data-lucide="circle-check" style="width: 20px; height: 20px; color: var(--accent); fill: rgba(173, 255, 47, 0.05);"></i>
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
                <i data-lucide="x" style="width: 18px; height: 18px; color: #000000;"></i>
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
            const chkStepLogin   = document.getElementById('chk-step-login');
            const chkStepAddress = document.getElementById('chk-step-address');
            const chkStepPayment = document.getElementById('chk-step-payment');

            // Navigation buttons
            const chkBackBtn  = document.getElementById('chk-back-btn');
            const chkCloseBtn = document.getElementById('chk-close-btn');

            // Step 1 controls
            const chkEmailField    = document.getElementById('chk-email-field');
            const chkPasswordField = document.getElementById('chk-password-field');
            const chkContinueBtn   = document.getElementById('chk-btn-continue');
            const chkLoginError    = document.getElementById('chk-login-error');

            // Step 2 controls (address)
            const chkPincode  = document.getElementById('chk-pincode');
            const chkCity     = document.getElementById('chk-city');
            const chkState    = document.getElementById('chk-state');
            const chkFlat     = document.getElementById('chk-flat');
            const chkArea     = document.getElementById('chk-area');
            const chkNameInput  = document.getElementById('chk-name');
            const chkEmailInput = document.getElementById('chk-email');
            const chkAddressContinueBtn = document.getElementById('chk-btn-address-continue');

            // Step 3 controls
            const chkPlaceOrderBtn = document.getElementById('chk-btn-place-order');

            let chkCurrentStep = 1;

            // Password show/hide toggle
            window.chkTogglePwd = function() {
                const isHidden = chkPasswordField.type === 'password';
                chkPasswordField.type = isHidden ? 'text' : 'password';
                document.getElementById('chk-eye-icon').innerHTML = isHidden
                    ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
                    : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            };
            
            // Prefilled user profile details
            window.loggedInUserData = <?php echo $f_logged_in_user ? json_encode($f_logged_in_user) : 'null'; ?>;

            // Open Overlay
            window.openCheckoutOverlay = function() {
                // Sync cart values first to verify actual cart items
                fetch('<?php echo BASE_URL; ?>/ajax_cart.php')
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

                        // Reset all fields
                        chkEmailField.value = '';
                        chkPasswordField.value = '';
                        chkPasswordField.type = 'password';
                        chkLoginError.style.display = 'none';
                        chkLoginError.textContent = '';
                        chkPincode.value = '';
                        chkCity.value = '';
                        chkState.value = '';
                        chkFlat.value = '';
                        chkArea.value = '';
                        chkNameInput.value = '';
                        chkEmailInput.value = '';

                        if (window.loggedInUserData) {
                            chkStepLogin.style.display = 'none';
                            chkStepAddress.style.display = 'block';
                            chkStepPayment.style.display = 'none';
                            chkBackBtn.style.visibility = 'hidden';
                            chkCurrentStep = 2;
                            if (window.loggedInUserData.name) chkNameInput.value = window.loggedInUserData.name;
                            if (window.loggedInUserData.email) chkEmailInput.value = window.loggedInUserData.email;
                            chkPincode.focus();
                        } else {
                            chkStepLogin.style.display = 'block';
                            chkStepAddress.style.display = 'none';
                            chkStepPayment.style.display = 'none';
                            chkBackBtn.style.visibility = 'hidden';
                            chkCurrentStep = 1;
                            chkEmailField.focus();
                        }

                        chkContinueBtn.classList.remove('active');
                        chkContinueBtn.disabled = true;

                        validateAddressForm();
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
                    chkStepAddress.style.display = 'none';
                    chkStepLogin.style.display = 'block';
                    chkBackBtn.style.visibility = 'hidden';
                    chkEmailField.focus();
                    chkCurrentStep = 1;
                } else if (chkCurrentStep === 3) {
                    chkStepPayment.style.display = 'none';
                    chkStepAddress.style.display = 'block';
                    chkCurrentStep = 2;
                }
            });

            // Step 1: validate email+password fields -> enable Sign In button
            function chkValidateLoginFields() {
                const emailOk = chkEmailField.value.trim().includes('@');
                const pwdOk   = chkPasswordField.value.length >= 1;
                if (emailOk && pwdOk) {
                    chkContinueBtn.classList.add('active');
                    chkContinueBtn.disabled = false;
                } else {
                    chkContinueBtn.classList.remove('active');
                    chkContinueBtn.disabled = true;
                }
            }

            chkEmailField.addEventListener('input', chkValidateLoginFields);
            chkPasswordField.addEventListener('input', chkValidateLoginFields);



            chkEmailField.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') { chkPasswordField.focus(); }
            });
            chkPasswordField.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !chkContinueBtn.disabled) { chkContinueBtn.click(); }
            });

            chkContinueBtn.addEventListener('click', performEmailLogin);

            function performEmailLogin() {
                const email    = chkEmailField.value.trim();
                const password = chkPasswordField.value;

                if (!email || !password) return;

                chkContinueBtn.disabled = true;
                chkContinueBtn.innerHTML = 'Signing in...';
                chkLoginError.style.display = 'none';

                const data = new FormData();
                data.append('email', email);
                data.append('password', password);

                fetch('<?php echo BASE_URL; ?>/ajax_email_login.php', {
                    method: 'POST',
                    body: data
                })
                .then(res => res.json())
                .then(resData => {
                    if (resData.status === 'success') {
                        chkStepLogin.style.display = 'none';
                        chkStepAddress.style.display = 'block';
                        chkBackBtn.style.visibility = 'visible';
                        chkCurrentStep = 2;
                        validateAddressForm();
                        chkPincode.focus();
                    } else {
                        chkLoginError.textContent = resData.message || 'Login failed. Please try again.';
                        chkLoginError.style.display = 'block';
                        chkContinueBtn.disabled = false;
                        chkContinueBtn.innerHTML = 'Sign In &amp; Continue';
                        chkContinueBtn.classList.add('active');
                    }
                })
                .catch(function(err) {
                    console.error('Login error:', err);
                    chkLoginError.textContent = 'Network error. Please try again.';
                    chkLoginError.style.display = 'block';
                    chkContinueBtn.disabled = false;
                    chkContinueBtn.innerHTML = 'Sign In &amp; Continue';
                    chkContinueBtn.classList.add('active');
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
                
                fetch('<?php echo BASE_URL; ?>/ajax_create_order.php', {
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
    <script>
        /* ── Global Y2K Fade-In Scroll Animation (runs on every page) ── */
        (function () {
            function initFadeObserver() {
                const fadeElements = document.querySelectorAll('.y2k-fade-in');
                if (!fadeElements.length) return;

                const observer = new IntersectionObserver(function (entries, obs) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animated');
                            obs.unobserve(entry.target);
                        }
                    });
                }, { root: null, rootMargin: '0px 0px -30px 0px', threshold: 0.05 });

                fadeElements.forEach(function (el) {
                    // Show immediately if already in viewport
                    var rect = el.getBoundingClientRect();
                    if (rect.top < window.innerHeight) {
                        el.classList.add('animated');
                    } else {
                        observer.observe(el);
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initFadeObserver);
            } else {
                initFadeObserver();
            }
        })();
    </script>
</body>
</html>
