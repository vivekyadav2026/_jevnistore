    <!-- Footer CSS -->
    <style>
        .footer-premium {
            background-color: #111111;
            color: #f3f4f6;
            padding: 95px 40px 45px;
            font-family: 'Inter', sans-serif;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        .footer-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
            gap: 50px;
            margin-bottom: 30px;
        }
        .footer-brand p {
            color: #9ca3af;
            font-size: 0.82rem;
            line-height: 1.7;
            margin-top: 20px;
            max-width: 280px;
        }
        .footer-col-title {
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 24px;
            color: #ffffff;
            position: relative;
        }
        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            font-size: 0.82rem;
            transition: all 0.3s ease;
            width: fit-content;
            display: inline-block;
        }
        .footer-links a:hover {
            color: #ffffff;
            transform: translateX(4px);
        }
        .footer-newsletter p {
            color: #9ca3af;
            font-size: 0.8rem;
            line-height: 1.6;
            margin-bottom: 16px;
        }
        .footer-newsletter form {
            position: relative;
            display: flex;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            align-items: center;
            transition: border-color 0.3s;
        }
        .footer-newsletter form:focus-within {
            border-bottom-color: #ffffff;
        }
        .footer-newsletter input {
            background: transparent;
            border: none;
            color: #ffffff;
            padding: 10px 0;
            width: 100%;
            font-size: 0.8rem;
            outline: none;
            letter-spacing: 0.5px;
        }
        .footer-newsletter input::placeholder {
            color: #6b7280;
        }
        .footer-newsletter button {
            background: transparent;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0 5px;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }
        .footer-newsletter button:hover {
            color: #ffffff;
        }
        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        .footer-social {
            display: flex;
            gap: 12px;
        }
        .footer-social a {
            color: #111111;
            background: #ffffff;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            text-decoration: none;
        }
        .footer-social a:hover {
            color: #ffffff;
            background: #e60067;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(230,0,103,0.4);
        }
        .footer-social a svg {
            width: 20px !important;
            height: 20px !important;
        }
        .footer-meta {
            display: flex;
            align-items: center;
            gap: 30px;
            font-size: 0.78rem;
            color: #9ca3af;
        }
        .footer-country {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: color 0.3s;
        }
        .footer-country:hover {
            color: #ffffff;
        }
        .footer-trust-badges {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .footer-trust-badge {
            opacity: 0.5;
            transition: opacity 0.3s;
        }
        .footer-trust-badge:hover {
            opacity: 0.8;
        }
        @media (max-width: 900px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }
            .footer-brand, .footer-newsletter {
                grid-column: span 2;
            }
        }
        @media (max-width: 600px) {
            .footer-premium {
                padding: 40px 20px 25px;
            }
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 24px;
                margin-bottom: 20px;
            }
            .footer-brand, .footer-newsletter {
                grid-column: span 1;
            }
            .footer-bottom {
                flex-direction: column;
                gap: 25px;
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
            
            <!-- Column 1: Brand Info -->
            <div class="footer-brand">
                <a href="<?php echo BASE_URL; ?>/index.php" style="display: inline-block;">
                    <?php 
                    $site_logo = getSetting('site_logo') ?: 'logo.png'; 
                    ?>
                    <img src="<?php echo BASE_URL; ?>/assets/<?php echo htmlspecialchars($site_logo); ?>" alt="NØRVA" style="height: 42px; width: auto; object-fit: contain;">
                </a>
                <p>Every stitch carries purpose. Nørva Store is a dark luxury alternative streetwear brand redefining urban carry with punk hardware and Y2K silhouettes. Homegrown in India.</p>
                
                <!-- Social links moved here for better layout -->
                <div class="footer-social" style="margin-top: 24px;">
                    <a href="<?php echo htmlspecialchars(getSetting('social_instagram', '#')); ?>" target="_blank" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 18px; height: 18px;"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <?php 
                        $wa_number = getSetting('contact_whatsapp', ''); 
                        $wa_clean = preg_replace('/[^0-9]/', '', $wa_number);
                        $wa_link = $wa_clean ? 'https://wa.me/' . $wa_clean : '#';
                    ?>
                    <a href="<?php echo htmlspecialchars($wa_link); ?>" target="_blank" aria-label="WhatsApp">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 18px; height: 18px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </a>
                    <a href="<?php echo htmlspecialchars(getSetting('social_twitter', '#')); ?>" target="_blank" aria-label="Twitter">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 18px; height: 18px;"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg>
                    </a>
                    <a href="<?php echo htmlspecialchars(getSetting('social_tiktok', '#')); ?>" target="_blank" aria-label="TikTok">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width: 16px; height: 16px;"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.951-7.252 4.168 0 7.41 2.967 7.41 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.367 18.592 0 12.017 0z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Column 2: Shop Menu -->
            <div>
                <div class="footer-col-title">Shop</div>
                <div class="footer-links">
                    <a href="<?php echo BASE_URL; ?>/shop.php">All Products</a>
                    <a href="<?php echo BASE_URL; ?>/shop.php?search=bag">Signature Bags</a>
                    <a href="<?php echo BASE_URL; ?>/shop.php?search=accessories">Accessories</a>
                    <a href="<?php echo BASE_URL; ?>/reviews.php">Customer Reviews</a>
                </div>
            </div>

            <!-- Column 3: Support & Policy -->
            <div>
                <div class="footer-col-title">Support & Policies</div>
                <div class="footer-links">
                    <a href="<?php echo BASE_URL; ?>/track_order.php">Track Order</a>
                    <a href="<?php echo BASE_URL; ?>/shipping_policy.php">Shipping Policy</a>
                    <a href="<?php echo BASE_URL; ?>/refund_policy.php">Refund & Returns</a>
                    <a href="<?php echo BASE_URL; ?>/privacy_policy.php">Privacy Policy</a>
                    <a href="<?php echo BASE_URL; ?>/terms_of_service.php">Terms of Service</a>
                </div>
            </div>

            <!-- Column 4: Newsletter -->
            <div class="footer-newsletter">
                <div class="footer-col-title">Join The Club</div>
                <p>Subscribe to receive updates, access to exclusive drops, and more.</p>
                <form onsubmit="event.preventDefault(); showToast('Subscribed successfully!', 'success');">
                    <input type="email" placeholder="ENTER YOUR EMAIL" required>
                    <button type="submit" aria-label="Subscribe">
                        <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                    </button>
                </form>
            </div>

        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-trust-badges">
                <!-- Inline SVG payment badges for razorpay, upi, visa, mastercard -->
                <div class="footer-trust-badge" title="Visa">
                    <svg width="36" height="22" viewBox="0 0 36 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="36" height="22" rx="4" fill="#1A1A1A"/>
                        <path d="M14.5 7L12.5 15H10.5L8.5 7H10.5L11.5 12L12.5 7H14.5ZM20.5 7L18.5 15H16.5L17.5 7H19.5L18.5 12L19.5 15H20.5ZM26.5 7.5C26 7.2 25.5 7 24.8 7C23.2 7 22 8 22 9.5C22 11.5 24 11.5 24 12.5C24 13 23.5 13.2 23 13.2C22.2 13.2 21.8 13 21.5 12.8L21 14C21.5 14.2 22.2 14.5 23 14.5C24.8 14.5 26 13.5 26 12C26 10 24 10 24 9C24 8.5 24.5 8.2 25 8.2C25.5 8.2 26 8.4 26.2 8.5L26.5 7.5Z" fill="#9CA3AF"/>
                    </svg>
                </div>
                <div class="footer-trust-badge" title="Mastercard">
                    <svg width="36" height="22" viewBox="0 0 36 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="36" height="22" rx="4" fill="#1A1A1A"/>
                        <circle cx="15.5" cy="11" r="5.5" fill="#9CA3AF" fill-opacity="0.6"/>
                        <circle cx="20.5" cy="11" r="5.5" fill="#9CA3AF" fill-opacity="0.8"/>
                    </svg>
                </div>
                <div class="footer-trust-badge" title="UPI Secure Payments">
                    <svg width="36" height="22" viewBox="0 0 36 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="36" height="22" rx="4" fill="#1A1A1A"/>
                        <rect x="7" y="6" width="22" height="10" rx="1.5" stroke="#9CA3AF" stroke-width="1.2"/>
                        <path d="M12 9.5L14 12.5L16 9.5" stroke="#9CA3AF" stroke-width="1.2" stroke-linecap="round"/>
                        <path d="M19 9.5H23V11H21V12.5" stroke="#9CA3AF" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            
            <div class="footer-meta">
                <div class="footer-country">
                    <img src="https://flagcdn.com/w20/in.png" alt="India" style="height: 12px; border-radius: 2px;">
                    INDIA (INR ₹)
                </div>
                <div style="font-size: 0.72rem; letter-spacing: 0.5px; opacity: 0.8;">
                    &copy; <?php echo date('Y'); ?> NØRVA STORE. ALL RIGHTS RESERVED.
                </div>
            </div>
        </div>
    </footer>

     <!-- Mobile Navigation Drawer -->
    <style>
    .mobile-nav-panel {
        max-width: 100% !important;
        width: 100vw !important;
        background: #ffffff !important;
    }
    .mobile-menu-item {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
        font-size: 1.15rem !important;
        font-weight: 500 !important;
        color: #1a1a1a !important;
        text-decoration: none !important;
        padding: 16px 0 !important;
        border-bottom: 1px solid #f3f4f6 !important;
        transition: opacity 0.2s ease !important;
        display: block !important;
        text-transform: none !important;
        letter-spacing: normal !important;
    }
    .mobile-menu-item:hover {
        opacity: 0.7 !important;
    }
    .mobile-menu-item:last-child {
        border-bottom: none !important;
    }
    </style>
    
    <div class="mobile-nav-drawer" id="mobile-nav-drawer">
        <div class="mobile-nav-backdrop" onclick="closeMobileNav()"></div>
        <div class="mobile-nav-panel">
            <?php
            $site_logo = getSetting('site_logo') ?: 'logo.png'; 
            ?>
            <div class="mobile-nav-header" style="display: flex; justify-content: space-between; align-items: center; padding: 24px 24px 10px 24px; border: none;">
                <img src="<?php echo BASE_URL; ?>/assets/<?php echo htmlspecialchars($site_logo); ?>" alt="Nørva Store" style="height: 32px; width: auto;" onerror="this.src='<?php echo BASE_URL; ?>/assets/logoOD.png';">
                <button class="mobile-nav-close" onclick="closeMobileNav()" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; padding: 0;">
                    <i data-lucide="x" style="width: 24px; height: 24px; color: #1a1a1a;"></i>
                </button>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 24px 5px 24px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                <span style="font-size: 0.72rem; font-weight: 700; color: #999999; letter-spacing: 1px; text-transform: uppercase;">SHOP</span>
                <a href="<?php echo BASE_URL; ?>/shop.php" style="font-size: 0.75rem; font-weight: 700; color: #e60067; text-decoration: none; letter-spacing: 0.5px;">Browse all</a>
            </div>

            <div class="mobile-nav-links" style="padding: 10px 24px; display: flex; flex-direction: column; flex: 1;">
                <a href="<?php echo BASE_URL; ?>/index.php" class="mobile-menu-item">Home</a>
                <a href="<?php echo BASE_URL; ?>/shop.php" class="mobile-menu-item">Catalog</a>
                <a href="<?php echo isLoggedIn() ? BASE_URL . '/customer/index.php' : BASE_URL . '/login.php'; ?>" class="mobile-menu-item">My Orders</a>
                <a href="<?php echo BASE_URL; ?>/track_order.php" class="mobile-menu-item">Track Order</a>
                <a href="<?php echo BASE_URL; ?>/about.php" class="mobile-menu-item">Contact Us</a>
                
                <!-- Spacer pushing login and currency to bottom -->
                <div class="mobile-nav-bottom-section" style="margin-top: auto; padding: 24px 0 10px 0; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f3f4f6; background: #ffffff;">
                    <div class="currency-selector-pill" style="display: flex; align-items: center; gap: 8px; background: #f3f4f6; border-radius: 99px; padding: 10px 16px; cursor: pointer; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 0.8rem; font-weight: 600; color: #1a1a1a;">
                        <svg class="flag-icon" width="16" height="11" viewBox="0 0 900 600" style="border-radius: 2px;">
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
                        <span>India <span style="color: #666; font-weight: 500;">INR</span></span>
                        <i data-lucide="chevron-down" style="width: 14px; height: 14px; color: #666;"></i>
                    </div>
                    
                    <?php if(isLoggedIn()): ?>
                        <a href="<?php echo BASE_URL; ?>/logout.php" style="display: inline-flex; align-items: center; gap: 8px; background: #e60067; color: #ffffff; border-radius: 99px; padding: 10px 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 0.8rem; font-weight: 700; text-decoration: none; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i data-lucide="log-out" style="width: 14px; height: 14px;"></i> Logout
                        </a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/login.php" style="display: inline-flex; align-items: center; gap: 8px; background: #e60067; color: #ffffff; border-radius: 99px; padding: 10px 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 0.8rem; font-weight: 700; text-decoration: none; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(230,0,103,0.2);">
                            <i data-lucide="user" style="width: 14px; height: 14px;"></i> Sign in
                        </a>
                    <?php endif; ?>
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
                    NØRVA
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
                            <i data-lucide="sparkles" style="width: 12px; height: 12px; fill: rgba(136, 19, 55, 0.1);"></i>
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
                    <i data-lucide="badge-percent" style="width: 18px; height: 18px; fill: rgba(136, 19, 55, 0.1);"></i>
                    "NØRVA WELCOME" applied
                </div>
                               <!-- Login Container (Step 1: Email + Password) -->
                <div id="chk-step-login">
                    <div class="chk-login-section">
                        <div class="chk-banner-gold">
                            Login to Redeem Gift Card / Partner Offers
                        </div>

                        <!-- Google Continue Button -->
                        <div style="padding: 12px 14px 0 14px;">
                            <a href="<?php echo BASE_URL; ?>/google_login.php" style="display:flex; align-items:center; justify-content:center; gap:10px; padding:11px; border:1.5px solid #1a1a1a; border-radius:10px; background:#ffffff; color:#1a1a1a; font-weight:700; text-decoration:none; font-size:0.88rem; box-shadow: 0 2px 6px rgba(0,0,0,0.04); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.01)'" onmouseout="this.style.transform='scale(1)'">
                                <svg width="18" height="18" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Continue with Google</span>
                            </a>

                            <div style="display:flex; align-items:center; gap:10px; margin: 12px 0 4px 0; color:#94a3b8; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:1px;">
                                <div style="flex:1; height:1px; background:#cbd5e1;"></div>
                                <span>OR</span>
                                <div style="flex:1; height:1px; background:#cbd5e1;"></div>
                            </div>
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
                    <span class="chk-trust-title">Powering secure checkout experiences for NØRVA</span>
                    <div class="chk-badge-grid">
                        <span class="chk-badge-item">PCI DSS Certified</span>
                        <span class="chk-badge-item">100% Secured Payments</span>
                        <span class="chk-badge-item">Verified Merchant</span>
                    </div>
                </div>
                
                <!-- Footer Text -->
                <div class="chk-footer-text">
                    By proceeding, I agree to Nørva's <a href="#">Privacy Policy</a> and <a href="#">T&C</a>
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
                        if (resData.payment_method === 'razorpay') {
                            // Launch Razorpay Checkout Modal
                            chkPlaceOrderBtn.innerHTML = 'Opening Payment Gateway...';
                            var options = {
                                "key": resData.razorpay_key,
                                "amount": resData.amount,
                                "currency": "INR",
                                "name": "NØRVA STORE",
                                "description": "Order #" + resData.order_id,
                                "order_id": resData.razorpay_order_id,
                                "handler": function (response) {
                                    chkPlaceOrderBtn.innerHTML = 'Verifying Payment...';
                                    const payData = new FormData();
                                    payData.append('razorpay_payment_id', response.razorpay_payment_id);
                                    payData.append('razorpay_order_id', response.razorpay_order_id);
                                    payData.append('razorpay_signature', response.razorpay_signature);
                                    payData.append('order_id', resData.order_id);
                                    
                                    fetch('<?php echo BASE_URL; ?>/ajax_verify_payment.php', {
                                        method: 'POST',
                                        body: payData
                                    })
                                    .then(vRes => vRes.json())
                                    .then(vData => {
                                        if (vData.status === 'success') {
                                            window.location.href = 'order_success.php?order_id=' + resData.order_id + '&method=razorpay';
                                        } else {
                                            alert(vData.message || 'Payment verification failed. Please contact support.');
                                            chkPlaceOrderBtn.disabled = false;
                                            chkPlaceOrderBtn.innerHTML = 'Place Order';
                                        }
                                    })
                                    .catch(err => {
                                        alert('Connection error verifying payment. Please do not close this window and contact support.');
                                        chkPlaceOrderBtn.disabled = false;
                                        chkPlaceOrderBtn.innerHTML = 'Place Order';
                                    });
                                },
                                "prefill": {
                                    "name": chkNameInput.value.trim(),
                                    "email": chkEmailInput.value.trim()
                                },
                                "theme": {
                                    "color": "#000000"
                                },
                                "modal": {
                                    "ondismiss": function() {
                                        alert('Payment checkout window closed.');
                                        chkPlaceOrderBtn.disabled = false;
                                        chkPlaceOrderBtn.innerHTML = 'Place Order';
                                    }
                                }
                            };
                            var rzp = new Razorpay(options);
                            rzp.open();
                        } else {
                            window.location.href = 'order_success.php?order_id=' + resData.order_id + '&method=' + resData.payment_method;
                        }
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
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</body>
</html>
