<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(getSetting('site_title', 'Jevani Store | Modern Edgy Clothing')); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(getSetting('site_description', 'Shop the latest streetwear. Baggy pants, hoodies, and cyberpunk fashion.')); ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/responsive.css?v=<?php echo time(); ?>">
    <!-- Google Fonts -->
    <!-- Lucide Icons for minimal elegant icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .header-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .header-user a {
            color: var(--text-primary);
            text-decoration: none;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .header-user a:hover {
            color: var(--accent);
        }

        /* ── Toast Notification System ── */
        #toast-container {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @media (min-width: 1025px) {
            body.cart-open #toast-container {
                right: 520px;
            }
        }
        @media (max-width: 1024px) and (min-width: 769px) {
            body.cart-open #toast-container {
                right: 440px;
            }
        }
        @media (max-width: 768px) {
            body.cart-open #toast-container {
                top: auto;
                bottom: 20px;
                right: 20px;
                left: 20px;
                align-items: center;
            }
        }
        .toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            min-width: 300px;
            max-width: 380px;
            padding: 14px 18px;
            border-radius: 10px;
            background: #111;
            border: 1px solid #2a2a2a;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            pointer-events: all;
            animation: toastSlideIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards;
            position: relative;
            overflow: hidden;
        }
        .toast.toast-hide {
            animation: toastSlideOut 0.3s ease-in forwards;
        }
        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(120%); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes toastSlideOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(120%); }
        }
        .toast-icon {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .toast-body {
            flex: 1;
        }
        .toast-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .toast-msg {
            font-size: 0.88rem;
            color: #bbb;
            line-height: 1.4;
        }
        .toast-close {
            background: none;
            border: none;
            color: #555;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            transition: color 0.2s;
            flex-shrink: 0;
        }
        .toast-close:hover { color: #fff; }
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 10px 10px;
            animation: toastProgress linear forwards;
        }
        @keyframes toastProgress {
            from { width: 100%; }
            to   { width: 0%; }
        }
        /* Type colours */
        .toast-success .toast-icon { color: #22c55e; }
        .toast-success .toast-title { color: #22c55e; }
        .toast-success .toast-progress { background: #22c55e; }
        .toast-error .toast-icon { color: #ef4444; }
        .toast-error .toast-title { color: #ef4444; }
        .toast-error .toast-progress { background: #ef4444; }
        .toast-info .toast-icon { color: #38bdf8; }
        .toast-info .toast-title { color: #38bdf8; }
        .toast-info .toast-progress { background: #38bdf8; }
        .toast-warning .toast-icon { color: #f59e0b; }
        .toast-warning .toast-title { color: #f59e0b; }
        .toast-warning .toast-progress { background: #f59e0b; }
    </style>
</head>
<body>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <!-- Inject PHP Flash Message -->
    <?php $flash = getFlash(); if ($flash): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast(<?php echo json_encode($flash['msg']); ?>, '<?php echo $flash['type']; ?>');
        });
    </script>
    <?php endif; ?>

    <!-- Toast JS (available globally) -->
    <script>
        const _toastIcons = {
            success: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            error:   '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            info:    '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
            warning: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>'
        };
        const _toastTitles = { success:'Success', error:'Error', info:'Info', warning:'Warning' };

        function showToast(message, type = 'success', duration = 4000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                ${_toastIcons[type] || _toastIcons.info}
                <div class="toast-body">
                    <div class="toast-title">${_toastTitles[type] || type}</div>
                    <div class="toast-msg">${message}</div>
                </div>
                <button class="toast-close" onclick="_dismissToast(this.parentElement)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
                <div class="toast-progress" style="animation-duration:${duration}ms"></div>
            `;
            container.appendChild(toast);
            setTimeout(() => _dismissToast(toast), duration);
        }

        function _dismissToast(toast) {
            if (!toast || toast.classList.contains('toast-hide')) return;
            toast.classList.add('toast-hide');
            setTimeout(() => toast.remove(), 300);
        }
    </script>

    <!-- Header -->
    <div class="announcement-bar" id="announcement-slider">
        <button class="announcement-arrow prev-arrow" onclick="prevAnnouncement()" aria-label="Previous Announcement">
            <i data-lucide="chevron-left"></i>
        </button>
        <div class="announcement-content">
            <?php 
                $a_text_raw = getSetting('announcement_bar', 'FREE DOORSTEP DELIVERY ANYWHERE IN INDIA 🇮🇳'); 
                $announcements = array_filter(array_map('trim', explode('|', $a_text_raw)));
                if (empty($announcements)) {
                    $announcements = ['FREE DOORSTEP DELIVERY ANYWHERE IN INDIA 🇮🇳'];
                }
                // Duplicate if only one announcement so the slider animation works
                if (count($announcements) === 1) {
                    $announcements[] = $announcements[0];
                }
                foreach($announcements as $index => $ann) {
                    $activeClass = $index === 0 ? 'active' : 'prev';
                    $style = $index === 0 ? '' : 'style="transform: translateY(100%);"';
                    echo '<div class="announcement-slide ' . $activeClass . '" ' . $style . '>' . htmlspecialchars($ann) . '</div>';
                }
            ?>
        </div>
        <button class="announcement-arrow next-arrow" onclick="nextAnnouncement()" aria-label="Next Announcement">
            <i data-lucide="chevron-right"></i>
        </button>
    </div>
    <script>
        let currentAnnouncement = 0;
        const slides = document.querySelectorAll('.announcement-slide');
        
        function nextAnnouncement() {
            if(slides.length <= 1) return;
            slides[currentAnnouncement].classList.remove('active');
            slides[currentAnnouncement].style.transform = 'translateY(-100%)';
            slides[currentAnnouncement].style.opacity = '0';
            
            currentAnnouncement = (currentAnnouncement + 1) % slides.length;
            
            slides[currentAnnouncement].style.transition = 'none';
            slides[currentAnnouncement].style.transform = 'translateY(100%)';
            
            // Force reflow
            void slides[currentAnnouncement].offsetWidth;
            
            setTimeout(() => {
                slides[currentAnnouncement].style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.5s ease';
                slides[currentAnnouncement].classList.add('active');
                slides[currentAnnouncement].style.transform = '';
                slides[currentAnnouncement].style.opacity = '1';
            }, 20);
        }

        function prevAnnouncement() {
            if(slides.length <= 1) return;
            slides[currentAnnouncement].classList.remove('active');
            slides[currentAnnouncement].style.transform = 'translateY(100%)';
            slides[currentAnnouncement].style.opacity = '0';
            
            currentAnnouncement = (currentAnnouncement - 1 + slides.length) % slides.length;
            
            slides[currentAnnouncement].style.transition = 'none';
            slides[currentAnnouncement].style.transform = 'translateY(-100%)';
            
            // Force reflow
            void slides[currentAnnouncement].offsetWidth;
            
            setTimeout(() => {
                slides[currentAnnouncement].style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.5s ease';
                slides[currentAnnouncement].classList.add('active');
                slides[currentAnnouncement].style.transform = '';
                slides[currentAnnouncement].style.opacity = '1';
            }, 20);
        }

        if(slides.length > 1) {
            setInterval(nextAnnouncement, 4000);
        }
    </script>
    <header class="header" id="main-header">
        <div class="container header-inner">
            <button class="menu-toggle header-menu-icon" id="mobile-menu-btn" onclick="document.getElementById('mobile-nav-drawer').classList.add('open'); document.body.style.overflow = 'hidden';">
                <i data-lucide="menu"></i>
            </button>
            <div class="logo-wrapper">
                <a href="<?php echo BASE_URL; ?>/index.php" class="logo">
                    <?php $site_logo = getSetting('site_logo'); ?>
                    <?php if ($site_logo): ?>
                        <img src="<?php echo BASE_URL; ?>/assets/<?php echo htmlspecialchars($site_logo); ?>" alt="<?php echo htmlspecialchars(getSetting('site_title', 'Jevani Store')); ?>">
                    <?php else: ?>
                        <img src="<?php echo BASE_URL; ?>/assets/logo.png" alt="<?php echo htmlspecialchars(getSetting('site_title', 'Jevani Store')); ?>">
                    <?php endif; ?>
                </a>
            </div>
            
            <nav class="nav-links" id="nav-links">
                <a href="<?php echo BASE_URL; ?>/shop.php">All Bags</a>
                <a href="<?php echo BASE_URL; ?>/shop.php?category=1">Shoulder Bags</a>
                <a href="<?php echo BASE_URL; ?>/shop.php?category=2">Totes</a>
                <a href="<?php echo BASE_URL; ?>/lookbook.php">Lookbook</a>
                <a href="<?php echo BASE_URL; ?>/about.php">About</a>
            </nav>
            
            <div class="header-icons">
                <button class="icon-btn header-search-icon" aria-label="Search" onclick="document.getElementById('header-search-bar').classList.toggle('active'); document.getElementById('search-input').focus();"><i data-lucide="search"></i></button>
                <?php if(isLoggedIn()): ?>
                    <a href="<?php echo isAdmin() ? BASE_URL . '/admin/index.php' : BASE_URL . '/customer/index.php'; ?>" class="icon-btn header-user-icon hide-mobile" aria-label="Account" title="Dashboard">
                        <i data-lucide="user"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/login.php" class="icon-btn header-user-icon hide-mobile" aria-label="Account" title="Login">
                        <i data-lucide="user"></i>
                    </a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>/wishlist.php" class="icon-btn header-wishlist-icon hide-mobile" aria-label="Wishlist"><i data-lucide="heart"></i></a>
                <button class="icon-btn header-cart-icon" id="cart-toggle-btn" aria-label="Cart" onclick="document.getElementById('cart-overlay').classList.add('active'); document.getElementById('cart-panel').classList.add('active'); document.body.classList.add('cart-open');">
                    <i data-lucide="shopping-bag"></i>
                    <span class="cart-count" id="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : '0'; ?></span>
                </button>
            </div>
        </div>

        <!-- Slide-down Search Bar -->
        <div class="header-search-bar" id="header-search-bar">
            <div class="container">
                <form action="<?php echo BASE_URL; ?>/shop.php" method="GET" class="search-form-wrapper">
                    <i data-lucide="search" class="search-form-icon"></i>
                    <input type="text" name="search" id="search-input" placeholder="SEARCH FOR PRODUCTS, CATEGORIES..." class="header-search-input" required>
                    <button type="button" class="search-close-btn" onclick="document.getElementById('header-search-bar').classList.remove('active');">
                        <i data-lucide="x"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>
