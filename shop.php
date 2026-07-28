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
$sort = $_GET['sort'] ?? 'created_at-desc';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 1. Build Query
$query = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
    $search_term = "%" . $search . "%";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= "ss";
}

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

// Whitelist and map sorting options to order by clauses
$allowed_sorts = ['created_at-desc', 'price-asc', 'price-desc', 'name-asc'];
if (!in_array($sort, $allowed_sorts)) {
    $sort = 'created_at-desc';
}
$order_by = "created_at DESC";
if ($sort === 'price-asc') {
    $order_by = "price ASC";
} elseif ($sort === 'price-desc') {
    $order_by = "price DESC";
} elseif ($sort === 'name-asc') {
    $order_by = "name ASC";
}

$query .= " ORDER BY " . $order_by;

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
            $image = $product['image'] ? BASE_URL . '/assets/' . htmlspecialchars($product['image']) : BASE_URL . '/assets/bags/acc_2.jpeg';
            $hover_image = !empty($product['image2']) ? BASE_URL . '/assets/' . htmlspecialchars($product['image2']) : '';
            
            $discount_pct = 0;
            if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']) {
                $discount_pct = round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100);
            }
            
            $in_wish = isset($_SESSION['wishlist']) && in_array($product['id'], $_SESSION['wishlist']);
            $heart_fill = $in_wish ? '#ef4444' : 'none';
            $heart_color = $in_wish ? '#ef4444' : '#1a1a1a';
            $wish_class = $in_wish ? 'in-wishlist' : '';
            ?>
            <div class="product-card-min" style="position: relative;">
                <div class="product-img-box" style="position: relative;">
                    <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>">
                        <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </a>
                    <?php 
                    $has_sale = (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']);
                    $sale_pct = $has_sale ? round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100) : 0;
                    ?>
                    
                    <div style="position: absolute; top: 8px; left: 8px; display: flex; flex-direction: row; flex-wrap: wrap; gap: 4px; z-index: 2; max-width: 90%;">
                        <?php if ($product['stock'] <= 0 && (!isset($product['is_waitlist']) || $product['is_waitlist'] == 0)): ?>
                            <div style="background: #ef4444; color: white; font-size: 0.5rem; font-weight: 700; padding: 2px 5px; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); white-space: nowrap;">OUT OF STOCK</div>
                        <?php elseif ($has_sale): ?>
                            <div style="background: #10b981; color: white; font-size: 0.5rem; font-weight: 700; padding: 2px 5px; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); white-space: nowrap;">SALE -<?php echo $sale_pct; ?>%</div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (isset($product['is_waitlist']) && $product['is_waitlist'] == 1): ?>
                        <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" class="add-btn-overlay" aria-label="Join Waitlist" style="display:flex; align-items:center; justify-content:center; text-decoration:none;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:20px;height:20px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </a>
                    <?php elseif ($product['stock'] <= 0): ?>
                        <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" class="add-btn-overlay" aria-label="Out of Stock" style="display:flex; align-items:center; justify-content:center; text-decoration:none; opacity: 0.7;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:20px;height:20px;"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                        </a>
                    <?php else: ?>
                        <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" class="ajax-cart-form">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-btn-overlay" aria-label="Add to Bag">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5v14"/></svg>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="product-min-title"><?php echo htmlspecialchars($product['name']); ?></div>
                <div class="product-min-price">₹<?php echo number_format($product['price']); ?></div>
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

$page_title_text = "Collection";
if ($selected_category !== null) {
    $cat_title_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $cat_title_stmt->bind_param("i", $selected_category);
    $cat_title_stmt->execute();
    $cat_title_res = $cat_title_stmt->get_result();
    if ($cat_title_row = $cat_title_res->fetch_assoc()) {
        $page_title_text = $cat_title_row['name'];
    }
}

require_once 'includes/header.php';
?>

<style>
.shop-header-row.hide-desktop {
    display: none !important;
}
@media (max-width: 1024px) {
    .shop-header-row.hide-desktop {
        display: flex !important;
    }
}
/* iOS-Style Toggle Switch */
.switch-slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
input:checked + .switch-slider {
    background-color: #1a1a1a !important;
}
input:checked + .switch-slider:before {
    transform: translateX(22px);
}
/* Smooth Transitions for Collapsible Filters */
.filter-group-content {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
}
.filter-group-content.open {
    max-height: 500px;
    opacity: 1;
}

/* Fix double line issue on filter headers */
body:has(.shop-page-container) .filter-group-header,
.filter-group-header {
    border-bottom: none !important;
    padding-bottom: 4px !important;
}
.filter-group-divider {
    height: 1px !important;
    background: rgba(0, 0, 0, 0.08) !important;
    margin: 8px 0 12px 0 !important;
    border: none !important;
}

/* Black Header Icons for Shop Page */
.header #mobile-menu-btn,
.header .header-search-icon,
.header .header-user-icon,
.header .header-cart-icon,
.header .icon-btn,
.header .menu-toggle,
.header #mobile-menu-btn *,
.header .header-search-icon *,
.header .header-user-icon *,
.header .header-cart-icon *,
.header .icon-btn *,
.header .cart-count {
    color: #1a1a1a !important;
    stroke: #1a1a1a !important;
}

/* View Toggle Switcher & Header Bar */
.shop-editorial-bg {
    padding-top: 100px !important;
}
@media (max-width: 1024px) {
    .shop-editorial-bg {
        padding-top: 90px !important;
    }
}
@media (max-width: 768px) {
    .shop-editorial-bg {
        padding-top: 80px !important;
        padding-bottom: 2rem !important;
    }
}

.shop-toolbar-row {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 10px !important;
    width: 100% !important;
    margin: 0 0 24px 0 !important;
    padding: 0 !important;
}

@media (min-width: 1025px) {
    .shop-toolbar-row {
        display: none !important;
    }
}

.mobile-filter-btn {
    flex: 1 !important;
    min-width: 0 !important;
    width: auto !important;
    margin: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    background: #ffffff !important;
    color: #1a1a1a !important;
    border: 1.5px solid #1a1a1a !important;
    padding: 0 16px !important;
    height: 44px !important;
    line-height: 1 !important;
    box-sizing: border-box !important;
    font-family: inherit !important;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
    letter-spacing: 1px !important;
    text-transform: uppercase !important;
    border-radius: 99px !important;
    cursor: pointer !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04) !important;
    white-space: nowrap !important;
}

.view-toggle-group {
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    flex-shrink: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
}

.view-toggle-btn {
    width: 44px !important;
    height: 44px !important;
    margin: 0 !important;
    padding: 0 !important;
    box-sizing: border-box !important;
    border-radius: 50% !important;
    border: 1.5px solid #e5e7eb !important;
    background: #ffffff !important;
    color: #374151 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
    flex-shrink: 0 !important;
}
.view-toggle-btn.active {
    background: #e11d48 !important;
    color: #ffffff !important;
    border-color: #e11d48 !important;
}
.view-toggle-btn:hover:not(.active) {
    border-color: #9ca3af;
    background: #f9fafb;
}

@media (max-width: 480px) {
    .mobile-filter-btn {
        height: 40px !important;
        font-size: 0.7rem !important;
        padding: 0 10px !important;
        margin: 0 !important;
    }
    .view-toggle-btn {
        width: 40px !important;
        height: 40px !important;
        margin: 0 !important;
    }
}

/* List View Layout */
.shop-grid.view-list {
    grid-template-columns: 1fr !important;
    gap: 16px !important;
}
.shop-grid.view-list .product-card-min {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    gap: 20px !important;
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 12px !important;
    padding: 14px !important;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03) !important;
}
.shop-grid.view-list .product-card-min .product-img-box {
    width: 120px !important;
    height: 120px !important;
    flex-shrink: 0 !important;
    border-radius: 8px !important;
    overflow: hidden !important;
}
.shop-grid.view-list .product-card-min .product-img-box img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
}
.shop-grid.view-list .product-card-min .product-min-title {
    font-size: 1rem !important;
    font-weight: 600 !important;
    margin-top: 0 !important;
    margin-bottom: 6px !important;
    text-align: left !important;
}
.shop-grid.view-list .product-card-min .product-min-price {
    font-size: 1rem !important;
    font-weight: 700 !important;
    text-align: left !important;
    color: #111827 !important;
}
</style>

<div class="shop-page-container">
    <!-- Main Editorial Shop Section -->
    <section class="section shop-editorial-bg" style="padding-bottom: 3rem; background: var(--bg-primary);">
        <div class="container">
            <!-- Breadcrumbs & Collection Header Bar -->
            <div class="shop-header-top-section" style="margin-bottom: 24px;">
                <div class="shop-breadcrumbs" style="font-size: 0.85rem; color: #6b7280; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                    <a href="index.php" style="color: #4b5563; text-decoration: none;">Home</a>
                    <span style="font-size: 0.75rem; color: #9ca3af;">›</span>
                    <span style="color: #111827; font-weight: 500;"><?php echo htmlspecialchars($page_title_text); ?></span>
                </div>
                <h1 style="font-size: 2.25rem; font-weight: 700; color: #111827; margin: 0 0 20px 0; font-family: var(--font-primary, sans-serif); letter-spacing: -0.5px;"><?php echo htmlspecialchars($page_title_text); ?></h1>
                
                <div class="shop-toolbar-row">
                    <button class="mobile-filter-btn" onclick="toggleMobileFilter()">
                        <i data-lucide="sliders-horizontal" style="width: 16px; height: 16px; stroke-width: 2.5;"></i> SORT & FILTER
                    </button>

                    <div class="view-toggle-group">
                        <button type="button" class="view-toggle-btn grid-btn active" onclick="setShopView('grid')" title="Grid View">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                        </button>
                        <button type="button" class="view-toggle-btn list-btn" onclick="setShopView('list')" title="List View">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="filter-drawer-overlay" id="mobile-filter-overlay" onclick="toggleMobileFilter()"></div>

            <div class="shop-layout">
                
                <!-- Sidebar Filters matching Reference Screenshot -->
                <aside class="sidebar-filter-ref" id="shop-filter-drawer">
                    <div class="filter-drawer-header hide-desktop" style="display: none;">
                        <h3 style="color: var(--text-primary);">FILTERS</h3>
                        <button class="filter-drawer-close" style="color: var(--text-primary);" onclick="toggleMobileFilter()"><i data-lucide="x"></i></button>
                    </div>
                    
                    <!-- Sort By Filter Group -->
                    <div class="filter-group-ref">
                        <div class="filter-group-header" onclick="toggleFilterSection('sort')">
                            <span>SORT BY</span>
                            <i data-lucide="chevron-up" id="filter-chevron-sort" style="transform: rotate(180deg); transition: transform 0.3s;"></i>
                        </div>
                        <div class="filter-group-content" id="filter-content-sort">
                            <ul class="filter-list-ref">
                                <li>
                                    <a href="#" class="filter-link-ref filter-sort-link <?php echo $sort === 'created_at-desc' ? 'active' : ''; ?>" data-sort="created_at-desc" onclick="selectSort(event, 'created_at-desc')">
                                        Newest Arrivals
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="filter-link-ref filter-sort-link <?php echo $sort === 'price-asc' ? 'active' : ''; ?>" data-sort="price-asc" onclick="selectSort(event, 'price-asc')">
                                        Price: Low to High
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="filter-link-ref filter-sort-link <?php echo $sort === 'price-desc' ? 'active' : ''; ?>" data-sort="price-desc" onclick="selectSort(event, 'price-desc')">
                                        Price: High to Low
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="filter-link-ref filter-sort-link <?php echo $sort === 'name-asc' ? 'active' : ''; ?>" data-sort="name-asc" onclick="selectSort(event, 'name-asc')">
                                        Alphabetically: A-Z
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="filter-group-divider"></div>
                    </div>

                    <!-- Category Filter Group -->
                    <div class="filter-group-ref">
                        <div class="filter-group-header" onclick="toggleFilterSection('cat')">
                            <span>CATEGORY</span>
                            <i data-lucide="chevron-up" id="filter-chevron-cat" style="transform: rotate(180deg); transition: transform 0.3s;"></i>
                        </div>
                        <div class="filter-group-content" id="filter-content-cat">
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

                    <!-- Availability Filter Group -->
                    <div class="filter-group-ref">
                        <div class="filter-group-header" onclick="toggleFilterSection('stock')">
                            <span>AVAILABILITY</span>
                            <i data-lucide="chevron-up" id="filter-chevron-stock" style="transform: rotate(180deg); transition: transform 0.3s;"></i>
                        </div>
                        <div class="filter-group-content" id="filter-content-stock">
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0;">
                                <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-primary); letter-spacing: 0.5px;">IN STOCK ONLY</span>
                                <label class="switch-container" style="position: relative; display: inline-block; width: 44px; height: 22px; flex-shrink: 0;">
                                    <input type="checkbox" id="in-stock-toggle" <?php echo $in_stock_only ? 'checked' : ''; ?> onchange="applyStockFilter()" style="opacity: 0; width: 0; height: 0;">
                                    <span class="switch-slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e5e7eb; transition: .3s; border-radius: 34px;"></span>
                                </label>
                            </div>
                        </div>
                        <div class="filter-group-divider"></div>
                    </div>
                    
                    <!-- Price Filter Group -->
                    <div class="filter-group-ref">
                        <div class="filter-group-header" onclick="toggleFilterSection('price')">
                            <span>PRICE</span>
                            <i data-lucide="chevron-up" id="filter-chevron-price" style="transform: rotate(180deg); transition: transform 0.3s;"></i>
                        </div>
                        <div class="filter-group-content" id="filter-content-price">
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
                <div style="flex: 1;">
                    <?php if (!empty($search)): ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; background: #ffffff; border: 1.5px solid #1a1a1a; padding: 12px 20px; border-radius: 4px; margin-bottom: 24px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #1a1a1a;">
                            <div>
                                SEARCH RESULTS FOR: <strong style="color: #ef4444;">"<?php echo htmlspecialchars($search); ?>"</strong>
                                <span style="color: #666; font-size: 0.65rem; margin-left: 8px; font-weight: 500;">(<?php echo $result->num_rows; ?> ITEMS FOUND)</span>
                            </div>
                            <a href="shop.php" style="color: #ef4444; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; font-size: 0.7rem;">
                                CLEAR SEARCH <i data-lucide="x" style="width: 14px; height: 14px; stroke-width: 3;"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="shop-grid" id="all-apparel">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($product = $result->fetch_assoc()) {
                                $image = $product['image'] ? BASE_URL . '/assets/' . htmlspecialchars($product['image']) : BASE_URL . '/assets/bags/acc_2.jpeg';
                                $hover_image = !empty($product['image2']) ? BASE_URL . '/assets/' . htmlspecialchars($product['image2']) : '';
                                
                                $discount_pct = 0;
                                if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']) {
                                    $discount_pct = round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100);
                                }
                                
                                $in_wish = isset($_SESSION['wishlist']) && in_array($product['id'], $_SESSION['wishlist']);
                                $heart_fill = $in_wish ? '#ef4444' : 'none';
                                $heart_color = $in_wish ? '#ef4444' : '#1a1a1a';
                                $wish_class = $in_wish ? 'in-wishlist' : '';
                                ?>
                                <div class="product-card-min" style="position: relative;">
                                     <div class="product-img-box" style="position: relative;">
                                         <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>">
                                             <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                         </a>
                    <?php 
                    $has_sale = (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']);
                    $sale_pct = $has_sale ? round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100) : 0;
                    ?>
                    
                    <div style="position: absolute; top: 8px; left: 8px; display: flex; flex-direction: row; flex-wrap: wrap; gap: 4px; z-index: 2; max-width: 90%;">
                        <?php if ($product['stock'] <= 0 && (!isset($product['is_waitlist']) || $product['is_waitlist'] == 0)): ?>
                            <div style="background: #ef4444; color: white; font-size: 0.5rem; font-weight: 700; padding: 2px 5px; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); white-space: nowrap;">OUT OF STOCK</div>
                        <?php elseif ($has_sale): ?>
                            <div style="background: #10b981; color: white; font-size: 0.5rem; font-weight: 700; padding: 2px 5px; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.2); white-space: nowrap;">SALE -<?php echo $sale_pct; ?>%</div>
                        <?php endif; ?>
                    </div>
                                         
                                         <?php if (isset($product['is_waitlist']) && $product['is_waitlist'] == 1): ?>
                                             <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" class="add-btn-overlay" aria-label="Join Waitlist" style="display:flex; align-items:center; justify-content:center; text-decoration:none;">
                                                 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:20px;height:20px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                             </a>
                                         <?php elseif ($product['stock'] <= 0): ?>
                                             <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" class="add-btn-overlay" aria-label="Out of Stock" style="display:flex; align-items:center; justify-content:center; text-decoration:none; opacity: 0.7;">
                                                 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:20px;height:20px;"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                             </a>
                                         <?php else: ?>
                                             <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" class="ajax-cart-form">
                                                 <input type="hidden" name="action" value="add">
                                                 <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                 <input type="hidden" name="quantity" value="1">
                                                 <button type="submit" class="add-btn-overlay" aria-label="Add to Bag">
                                                     <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5v14"/></svg>
                                                 </button>
                                             </form>
                                         <?php endif; ?>
                                     </div>
                                    <div class="product-min-title"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <div class="product-min-price">₹<?php echo number_format($product['price']); ?></div>
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
        <div class="marquee-track">
            <span class="marquee-logo">Nørva Store</span>
            <span class="marquee-logo">Nørva Store</span>
            <span class="marquee-logo">Nørva Store</span>
            <span class="marquee-logo">Nørva Store</span>
            <span class="marquee-logo">Nørva Store</span>
            <span class="marquee-logo">Nørva Store</span>
            <span class="marquee-logo">Nørva Store</span>
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
        
        content.classList.toggle('open');
        
        if (content.classList.contains('open')) {
            chevron.style.transform = 'rotate(0deg)';
        } else {
            chevron.style.transform = 'rotate(180deg)';
        }
    }

    function selectCategory(e, catId) {
        e.preventDefault();
        document.querySelectorAll('.filter-link-ref:not(.filter-sort-link)').forEach(link => link.classList.remove('active'));
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

    function selectSort(e, sortVal) {
        e.preventDefault();
        document.querySelectorAll('.filter-sort-link').forEach(link => link.classList.remove('active'));
        e.currentTarget.classList.add('active');
        updateFilters();
    }

    function updateFilters() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            const activeLink = document.querySelector('.filter-link-ref:not(.filter-sort-link).active');
            const categoryId = activeLink ? activeLink.dataset.id : '';
            
            const activeSort = document.querySelector('.filter-sort-link.active');
            const sortVal = activeSort ? activeSort.dataset.sort : 'created_at-desc';
            
            const minPrice = document.getElementById('min-price-slider').value;
            const maxPrice = document.getElementById('max-price-slider').value;
            const inStock = document.getElementById('in-stock-toggle').checked ? '1' : '0';
            
            // Build query parameters
            const urlParams = new URLSearchParams(window.location.search);
            const searchVal = urlParams.get('search') || '';

            const params = new URLSearchParams();
            if (searchVal) params.append('search', searchVal);
            if (categoryId) params.append('category', categoryId);
            if (parseInt(minPrice) > 0) params.append('min_price', minPrice);
            if (parseInt(maxPrice) < <?php echo $max_p_limit; ?>) params.append('max_price', maxPrice);
            if (sortVal && sortVal !== 'created_at-desc') params.append('sort', sortVal);
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
                    
                    // Close mobile filter drawer
                    const drawer = document.getElementById('shop-filter-drawer');
                    const overlay = document.getElementById('mobile-filter-overlay');
                    if (drawer && drawer.classList.contains('open')) {
                        drawer.classList.remove('open');
                    }
                    if (drawer && drawer.classList.contains('active')) {
                        drawer.classList.remove('active');
                    }
                    if (overlay && overlay.classList.contains('open')) {
                        overlay.classList.remove('open');
                    }
                })
                .catch(err => {
                    grid.style.opacity = '1';
                    console.error('Filter error:', err);
                });
        }, 300);
    }

    function setShopView(view) {
        const grid = document.getElementById('all-apparel');
        const gridBtn = document.querySelector('.view-toggle-btn.grid-btn');
        const listBtn = document.querySelector('.view-toggle-btn.list-btn');
        
        if (!grid) return;
        
        if (view === 'list') {
            grid.classList.add('view-list');
            if (listBtn) listBtn.classList.add('active');
            if (gridBtn) gridBtn.classList.remove('active');
            localStorage.setItem('shop_view_pref', 'list');
        } else {
            grid.classList.remove('view-list');
            if (gridBtn) gridBtn.classList.add('active');
            if (listBtn) listBtn.classList.remove('active');
            localStorage.setItem('shop_view_pref', 'grid');
        }
    }

    // Initialize track highlight and view state on load
    window.addEventListener('DOMContentLoaded', () => {
        updateTrackHighlight();
        const savedView = localStorage.getItem('shop_view_pref');
        if (savedView === 'list') {
            setShopView('list');
        }
    });
    </script>
</div>

<?php require_once 'includes/footer.php'; ?>
