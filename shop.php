<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Fetch maximum price in database to set slider limits dynamically
$max_price_query = $conn->query("SELECT MAX(price) as max_p FROM products");
$max_p_db = $max_price_query ? $max_price_query->fetch_assoc()['max_p'] : 10000;
$max_p_limit = ceil($max_p_db / 500) * 500; // round up to nearest 500

// Parse incoming filter values
$selected_category = isset($_GET['category']) && is_numeric($_GET['category']) ? intval($_GET['category']) : null;
$min_price = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? intval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? intval($_GET['max_price']) : $max_p_limit;
$in_stock_only = isset($_GET['in_stock']) && $_GET['in_stock'] == '1' ? true : false;

// 1. Build Query
$query = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

if ($selected_category !== null) {
    $query .= " AND category_id = ?";
    $params[] = $selected_category;
    $types .= "i";
}

$query .= " AND price >= ? AND price <= ?";
$params[] = $min_price;
$params[] = $max_price;
$types .= "dd";

if ($in_stock_only) {
    $query .= " AND stock > 0";
}

$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// 2. Handle AJAX response
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
            $image = $product['image'] ? BASE_URL . '/assets/' . htmlspecialchars($product['image']) : BASE_URL . '/assets/bag_shoulder.png';
            $hover_image = !empty($product['image2']) ? BASE_URL . '/assets/' . htmlspecialchars($product['image2']) : '';
            
            $discount_pct = 0;
            if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']) {
                $discount_pct = round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100);
            }
            
            $in_wish = isset($_SESSION['wishlist']) && in_array($product['id'], $_SESSION['wishlist']);
            $heart_fill = $in_wish ? '#ef4444' : 'none';
            $heart_color = $in_wish ? '#ef4444' : '#ffffff';
            $wish_class = $in_wish ? 'in-wishlist' : '';
            ?>
            <div class="y2k-card">
                <div class="y2k-img-wrapper">
                    <!-- Badges -->
                    <div class="y2k-badges">
                        <?php if ($product['stock'] <= 0): ?>
                            <span class="y2k-badge sold-out">SOLD OUT</span>
                        <?php endif; ?>
                        <?php if ($discount_pct > 0): ?>
                            <span class="y2k-badge sale">SAVE <?php echo $discount_pct; ?>%</span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Wishlist Toggle -->
                    <button type="button" class="y2k-wishlist-btn <?php echo $wish_class; ?>" onclick="toggleWishlist(<?php echo $product['id']; ?>, this)" aria-label="Add to Wishlist">
                        <i data-lucide="heart" fill="<?php echo $heart_fill; ?>" style="width: 18px; height: 18px; color: <?php echo $heart_color; ?>;"></i>
                    </button>
                    
                    <!-- Product Image -->
                    <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>">
                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="y2k-img">
                        <?php if ($hover_image): ?>
                            <img src="<?php echo $hover_image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?> hover" class="y2k-img-hover">
                        <?php endif; ?>
                    </a>
                    
                    <!-- Quick View Trigger -->
                    <button type="button" class="y2k-quickview-btn" onclick="openQuickView(<?php echo $product['id']; ?>)">Quick View</button>
                </div>
                
                <!-- Details -->
                <div class="y2k-info">
                    <div>
                        <span class="y2k-brand">JEVANI STORE</span>
                        <h3 class="y2k-title">
                            <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </a>
                        </h3>
                    </div>
                    
                    <div class="y2k-price-row">
                        <span class="y2k-price-sale">₹<?php echo number_format($product['price']); ?></span>
                        <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                            <span class="y2k-price-compare">₹<?php echo number_format($product['compare_at_price']); ?></span>
                            <span class="y2k-discount-badge">(<?php echo $discount_pct; ?>% OFF)</span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- AJAX Add to Cart form -->
                    <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" class="ajax-cart-form">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="size" value="Silver">
                        <button type="submit" class="y2k-add-btn">
                            <i data-lucide="shopping-bag"></i>
                            Add to Bag
                        </button>
                    </form>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<div style="grid-column: span 3; text-align:center; padding: 60px 0; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; font-weight: 500; font-size: 0.85rem;">No products found matching these filters.</div>';
    }
    exit();
}

// Get total product count
$cnt_res = $conn->query("SELECT COUNT(*) as cnt FROM products");
$total_products_count = $cnt_res ? $cnt_res->fetch_assoc()['cnt'] : 0;

require_once 'includes/header.php';
?>

    <!-- Shop Hero Banner -->
    <div class="hero" style="min-height: 40vh; height: 40vh; margin-bottom: 0;">
        <img src="<?php echo BASE_URL; ?>/assets/hero_banner_bags.png" alt="Shop Collection" class="hero-img">
        <div class="hero-overlay" style="background: rgba(9,9,11,0.65);"></div>
        <div class="hero-content">
            <h1 class="hero-title font-serif" style="font-size: clamp(2.5rem, 5vw, 60px); color: var(--text-primary); font-weight: 400; font-style: italic;">THE COLLECTION</h1>
        </div>
    </div>

    <!-- Main Editorial Shop Section -->
    <section class="section shop-editorial-bg" style="padding-top: 5rem; padding-bottom: 8rem; background: var(--bg-primary);">
        <div class="container">
            <!-- Mobile Filter Toggle -->
            <div class="shop-header-row hide-desktop" style="margin-bottom: 20px; display: none;">
                <button class="mobile-filter-btn" onclick="toggleMobileFilter()">
                    <i data-lucide="sliders-horizontal" style="width: 16px; height: 16px;"></i> FILTERS
                </button>
            </div>
            
            <div class="filter-drawer-overlay" id="mobile-filter-overlay" onclick="toggleMobileFilter()"></div>

            <div class="shop-layout">
                
                <!-- Sidebar Filters matching Reference Screenshot -->
                <aside class="sidebar-filter-ref" id="shop-filter-drawer">
                    <div class="filter-drawer-header hide-desktop" style="display: none;">
                        <h3 style="color: var(--text-primary);">FILTERS</h3>
                        <button class="filter-drawer-close" style="color: var(--text-primary);" onclick="toggleMobileFilter()"><i data-lucide="x"></i></button>
                    </div>
                    
                    <!-- Category Filter Group -->
                    <div class="filter-group-ref">
                        <div class="filter-group-header" onclick="toggleFilterSection('cat')">
                            <span>CATEGORY</span>
                            <i data-lucide="chevron-up" id="filter-chevron-cat" style="transform: rotate(180deg); transition: transform 0.3s;"></i>
                        </div>
                        <div class="filter-group-content" id="filter-content-cat" style="display: none;">
                            <ul class="filter-list-ref">
                                <li>
                                    <a href="shop.php" class="filter-link-ref <?php echo $selected_category === null ? 'active' : ''; ?>" data-id="" onclick="selectCategory(event, '')">
                                        All Bags & Accessories (<?php echo $total_products_count; ?>)
                                    </a>
                                </li>
                                <?php
                                $cat_stmt = $conn->prepare("SELECT c.id, c.name, COUNT(p.id) as p_count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.name ASC");
                                $cat_stmt->execute();
                                $cats = $cat_stmt->get_result();
                                while ($cat = $cats->fetch_assoc()) {
                                    $isActive = ($selected_category == $cat['id']) ? 'active' : '';
                                    echo '<li>';
                                    echo '<a href="shop.php?category='.$cat['id'].'" class="filter-link-ref '.$isActive.'" data-id="'.$cat['id'].'" onclick="selectCategory(event, '.$cat['id'].')">';
                                    echo htmlspecialchars($cat['name']) . ' (' . $cat['p_count'] . ')';
                                    echo '</a>';
                                    echo '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="filter-group-divider"></div>
                    </div>
                    
                    <!-- Price Filter Group -->
                    <div class="filter-group-ref">
                        <div class="filter-group-header" onclick="toggleFilterSection('price')">
                            <span>PRICE</span>
                            <i data-lucide="chevron-up" id="filter-chevron-price" style="transform: rotate(180deg); transition: transform 0.3s;"></i>
                        </div>
                        <div class="filter-group-content" id="filter-content-price" style="display: none;">
                            <div class="price-slider-wrapper">
                                <div class="price-slider-container">
                                    <input type="range" min="0" max="<?php echo $max_p_limit; ?>" value="<?php echo $min_price; ?>" class="slider-handle min-slider" id="min-price-slider" oninput="updatePriceInputs()">
                                    <input type="range" min="0" max="<?php echo $max_p_limit; ?>" value="<?php echo $max_price; ?>" class="slider-handle max-slider" id="max-price-slider" oninput="updatePriceInputs()">
                                    <div class="slider-track" id="slider-track-line"></div>
                                </div>
                                <div class="price-inputs-row">
                                    <div class="price-input-box">
                                        <span class="currency-symbol">₹</span>
                                        <input type="number" id="min-price-input" value="<?php echo $min_price; ?>" onchange="applyPriceFilter()">
                                    </div>
                                    <span class="to-text">to</span>
                                    <div class="price-input-box">
                                        <span class="currency-symbol">₹</span>
                                        <input type="number" id="max-price-input" value="<?php echo $max_price; ?>" onchange="applyPriceFilter()">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="filter-group-divider"></div>
                    </div>
                    
                </aside>
                
                <!-- Product Grid Container -->
                <div>
                    <div class="shop-grid" id="all-apparel">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($product = $result->fetch_assoc()) {
                                $image = $product['image'] ? BASE_URL . '/assets/' . htmlspecialchars($product['image']) : BASE_URL . '/assets/bag_shoulder.png';
                                $hover_image = !empty($product['image2']) ? BASE_URL . '/assets/' . htmlspecialchars($product['image2']) : '';
                                
                                $discount_pct = 0;
                                if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']) {
                                    $discount_pct = round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100);
                                }
                                
                                $in_wish = isset($_SESSION['wishlist']) && in_array($product['id'], $_SESSION['wishlist']);
                                $heart_fill = $in_wish ? '#ef4444' : 'none';
                                $heart_color = $in_wish ? '#ef4444' : '#ffffff';
                                $wish_class = $in_wish ? 'in-wishlist' : '';
                                ?>
                                <div class="y2k-card">
                                    <div class="y2k-img-wrapper">
                                        <!-- Badges -->
                                        <div class="y2k-badges">
                                            <?php if ($product['stock'] <= 0): ?>
                                                <span class="y2k-badge sold-out">SOLD OUT</span>
                                            <?php endif; ?>
                                            <?php if ($discount_pct > 0): ?>
                                                <span class="y2k-badge sale">SAVE <?php echo $discount_pct; ?>%</span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Wishlist Toggle -->
                                        <button type="button" class="y2k-wishlist-btn <?php echo $wish_class; ?>" onclick="toggleWishlist(<?php echo $product['id']; ?>, this)" aria-label="Add to Wishlist">
                                            <i data-lucide="heart" fill="<?php echo $heart_fill; ?>" style="width: 18px; height: 18px; color: <?php echo $heart_color; ?>;"></i>
                                        </button>
                                        
                                        <!-- Product Image -->
                                        <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>">
                                            <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="y2k-img">
                                            <?php if ($hover_image): ?>
                                                <img src="<?php echo $hover_image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?> hover" class="y2k-img-hover">
                                            <?php endif; ?>
                                        </a>
                                        
                                        <!-- Quick View Trigger -->
                                        <button type="button" class="y2k-quickview-btn" onclick="openQuickView(<?php echo $product['id']; ?>)">Quick View</button>
                                    </div>
                                    
                                    <!-- Details -->
                                    <div class="y2k-info">
                                        <div>
                                            <span class="y2k-brand">JEVANI STORE</span>
                                            <h3 class="y2k-title">
                                                <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>">
                                                    <?php echo htmlspecialchars($product['name']); ?>
                                                </a>
                                            </h3>
                                        </div>
                                        
                                        <div class="y2k-price-row">
                                            <span class="y2k-price-sale">₹<?php echo number_format($product['price']); ?></span>
                                            <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                                                <span class="y2k-price-compare">₹<?php echo number_format($product['compare_at_price']); ?></span>
                                                <span class="y2k-discount-badge">(<?php echo $discount_pct; ?>% OFF)</span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- AJAX Add to Cart form -->
                                        <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" class="ajax-cart-form">
                                            <input type="hidden" name="action" value="add">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="size" value="Silver">
                                            <button type="submit" class="y2k-add-btn">
                                                <i data-lucide="shopping-bag"></i>
                                                Add to Bag
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<div style="grid-column: span 3; text-align:center; padding: 60px 0; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; font-weight: 500; font-size: 0.85rem;">No products found matching these filters.</div>';
                        }
                        ?>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Marquee Section -->
    <section class="marquee-section">
        <div class="marquee-title">AS FEATURED IN</div>
        <div class="marquee-track">
            <span class="marquee-logo">VOGUE</span>
            <span class="marquee-logo">HYPEBEAST</span>
            <span class="marquee-logo">GQ</span>
            <span class="marquee-logo">HIGHSNOBIETY</span>
            <span class="marquee-logo">COMPLEX</span>
            <span class="marquee-logo">VOGUE</span>
            <span class="marquee-logo">HYPEBEAST</span>
            <span class="marquee-logo">GQ</span>
            <span class="marquee-logo">HIGHSNOBIETY</span>
            <span class="marquee-logo">COMPLEX</span>
        </div>
    </section>

    <script>
    let filterTimeout;

    function toggleMobileFilter() {
        const drawer = document.getElementById('shop-filter-drawer');
        const overlay = document.getElementById('mobile-filter-overlay');
        drawer.classList.toggle('open');
        overlay.classList.toggle('open');
    }

    function toggleFilterSection(section) {
        const content = document.getElementById('filter-content-' + section);
        const chevron = document.getElementById('filter-chevron-' + section);
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            chevron.style.transform = 'rotate(0deg)';
        } else {
            content.style.display = 'none';
            chevron.style.transform = 'rotate(180deg)';
        }
    }

    function selectCategory(e, catId) {
        e.preventDefault();
        document.querySelectorAll('.filter-link-ref').forEach(link => link.classList.remove('active'));
        e.currentTarget.classList.add('active');
        updateFilters();
    }

    function updatePriceInputs() {
        const minSlider = document.getElementById('min-price-slider');
        const maxSlider = document.getElementById('max-price-slider');
        
        // Ensure handles don't cross
        if (parseInt(minSlider.value) > parseInt(maxSlider.value)) {
            minSlider.value = maxSlider.value;
        }
        
        document.getElementById('min-price-input').value = minSlider.value;
        document.getElementById('max-price-input').value = maxSlider.value;
        
        updateTrackHighlight();
        updateFilters();
    }

    function applyPriceFilter() {
        const minInput = document.getElementById('min-price-input');
        const maxInput = document.getElementById('max-price-input');
        
        let minVal = parseInt(minInput.value) || 0;
        let maxVal = parseInt(maxInput.value) || <?php echo $max_p_limit; ?>;
        
        if (minVal > maxVal) {
            minVal = maxVal;
            minInput.value = minVal;
        }
        
        document.getElementById('min-price-slider').value = minVal;
        document.getElementById('max-price-slider').value = maxVal;
        
        updateTrackHighlight();
        updateFilters();
    }

    function applyStockFilter() {
        updateFilters();
    }

    function updateTrackHighlight() {
        const minSlider = document.getElementById('min-price-slider');
        const maxSlider = document.getElementById('max-price-slider');
        const track = document.getElementById('slider-track-line');
        
        const minPercent = (minSlider.value / minSlider.max) * 100;
        const maxPercent = (maxSlider.value / maxSlider.max) * 100;
        
        track.style.left = minPercent + '%';
        track.style.width = (maxPercent - minPercent) + '%';
    }

    function updateFilters() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            const activeLink = document.querySelector('.filter-link-ref.active');
            const categoryId = activeLink ? activeLink.dataset.id : '';
            const minPrice = document.getElementById('min-price-slider').value;
            const maxPrice = document.getElementById('max-price-slider').value;
            const inStock = '0';
            
            // Build query parameters
            const params = new URLSearchParams();
            if (categoryId) params.append('category', categoryId);
            if (parseInt(minPrice) > 0) params.append('min_price', minPrice);
            if (parseInt(maxPrice) < <?php echo $max_p_limit; ?>) params.append('max_price', maxPrice);
            if (inStock === '1') params.append('in_stock', '1');
            
            // Update browser history URL
            const queryString = params.toString();
            const newUrl = window.location.pathname + (queryString ? '?' + queryString : '');
            window.history.pushState({ path: newUrl }, '', newUrl);
            
            // Fetch filtered results
            params.append('ajax', '1');
            
            const grid = document.getElementById('all-apparel');
            grid.style.opacity = '0.5';
            grid.style.transition = 'opacity 0.2s';
            
            fetch('shop.php?' + params.toString())
                .then(res => res.text())
                .then(html => {
                    grid.innerHTML = html;
                    grid.style.opacity = '1';
                    lucide.createIcons();
                })
                .catch(err => {
                    grid.style.opacity = '1';
                    console.error('Filter error:', err);
                });
        }, 300);
    }

    // Initialize track highlight on load
    window.addEventListener('DOMContentLoaded', () => {
        updateTrackHighlight();
    });
    </script>

<?php require_once 'includes/footer.php'; ?>
