<?php
require_once 'includes/header.php';

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}
$is_logged_in = isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
?>

<style>
/* Universal Responsive Box Sizing & Grid Overflow Prevention */
.wishlist-page-section,
.wishlist-page-section * {
    box-sizing: border-box !important;
}

.wishlist-page-section {
    padding-top: calc(var(--header-height, 90px) + 20px) !important;
    background: #d6d3d1 !important;
    min-height: 85vh;
    padding-bottom: 80px !important;
    color: #1a1a1a !important;
    width: 100% !important;
    max-width: 100vw !important;
    overflow-x: hidden !important;
}
@media (max-width: 1024px) {
    .wishlist-page-section {
        padding-top: 95px !important;
        padding-bottom: 70px !important;
    }
}
@media (max-width: 768px) {
    .wishlist-page-section {
        padding-top: 80px !important;
        padding-bottom: 60px !important;
    }
}

.account-container-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 16px;
    width: 100%;
}
@media (max-width: 480px) {
    .account-container-inner {
        padding: 0 12px;
    }
}

.account-header-box {
    margin-bottom: 20px;
    width: 100%;
}
.account-breadcrumbs {
    font-size: 0.7rem;
    color: #555555;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 700;
}
.account-breadcrumbs a {
    color: #555555;
    text-decoration: none;
}
.account-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1a1a1a !important;
    margin: 0 0 4px 0;
    font-family: var(--font-primary, sans-serif);
    letter-spacing: 2px;
    text-transform: uppercase;
}
@media (max-width: 480px) {
    .account-title {
        font-size: 1.4rem;
    }
}
.account-subtitle {
    color: #555555 !important;
    font-size: 0.75rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-weight: 600;
}

/* Bulletproof CSS Grid */
.account-grid-layout {
    display: grid !important;
    grid-template-columns: <?php echo $is_logged_in ? '240px 1fr' : '1fr'; ?> !important;
    gap: 20px !important;
    align-items: start !important;
    width: 100% !important;
    min-width: 0 !important;
}
.account-grid-layout > * {
    min-width: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}

@media (max-width: 992px) {
    .account-grid-layout {
        grid-template-columns: 1fr !important;
        gap: 16px !important;
    }
}

/* 2x2 Mobile Nav Tab Grid */
.account-nav-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: #ffffff;
    padding: 12px;
    border-radius: 16px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    width: 100%;
}
@media (max-width: 992px) {
    .account-nav-list {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 6px !important;
        padding: 6px !important;
        border-radius: 12px !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        background: #ffffff !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
        margin-bottom: 16px !important;
        width: 100% !important;
    }
    .account-nav-link {
        width: 100% !important;
        justify-content: center !important;
        padding: 10px 4px !important;
        font-size: 0.66rem !important;
        border: none !important;
        border-radius: 8px !important;
        background: transparent !important;
        text-align: center !important;
        letter-spacing: 0.5px !important;
        gap: 6px !important;
    }
    .account-nav-link.active {
        background: #1a1a1a !important;
        color: #ffffff !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
    }
    .account-nav-link.danger {
        border: none !important;
    }
}

.account-nav-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 18px;
    border-radius: 99px;
    color: #1a1a1a;
    font-weight: 700;
    font-size: 0.72rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
    flex-shrink: 0;
    background: #ffffff;
    border: 1px solid #e5e7eb;
}
.account-nav-link:hover {
    background: #f3f4f6;
    color: #1a1a1a;
}
.account-nav-link.active {
    background: #1a1a1a !important;
    color: #ffffff !important;
    border-color: #1a1a1a !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
.account-nav-link.danger {
    color: #dc2626;
    border-color: #dc2626;
}

.wishlist-panel-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    padding: 24px 20px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    width: 100%;
}
@media (max-width: 600px) {
    .wishlist-panel-card {
        padding: 16px 14px !important;
        border-radius: 14px !important;
    }
}

.wishlist-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 14px;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}
.wishlist-count-text {
    font-size: 0.75rem;
    font-weight: 700;
    color: #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.btn-pill-dark {
    background: #1a1a1a;
    color: #ffffff;
    border: none;
    border-radius: 99px;
    padding: 10px 24px;
    font-weight: 700;
    font-size: 0.72rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Wishlist Products Grid */
.wishlist-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 14px;
    width: 100%;
}
@media (max-width: 600px) {
    .wishlist-products-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 10px !important;
    }
}

.product-card-min {
    background: #ffffff !important;
    border: 1px solid rgba(0, 0, 0, 0.05) !important;
    border-radius: 14px !important;
    padding: 10px !important;
    box-shadow: 0 8px 20px rgba(0,0,0,0.04) !important;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}
.product-img-box {
    width: 100%;
    aspect-ratio: 1;
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    background: #f3f4f6;
    margin-bottom: 8px;
}
.product-img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.product-min-title {
    color: #1a1a1a !important;
    font-weight: 700 !important;
    font-size: 0.78rem !important;
    margin-bottom: 2px;
    text-transform: uppercase;
}
.product-min-price {
    color: #1a1a1a !important;
    font-weight: 700 !important;
    font-size: 0.8rem !important;
}
.wishlist-remove-link {
    color: #dc2626 !important;
    font-size: 0.65rem !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 6px;
    cursor: pointer;
    border-bottom: 1px dashed #dc2626;
    text-decoration: none;
}
</style>

<div class="wishlist-page-section">
    <div class="account-container-inner">
        
        <!-- Breadcrumbs & Account Header -->
        <div class="account-header-box">
            <div class="account-breadcrumbs">
                <a href="<?php echo BASE_URL; ?>/index.php">HOME</a>
                <span>/</span>
                <?php if ($is_logged_in): ?>
                    <a href="<?php echo BASE_URL; ?>/customer/index.php">ACCOUNT</a>
                    <span>/</span>
                <?php endif; ?>
                <span style="color: #1a1a1a; font-weight: 700;">WISHLIST</span>
            </div>
            <h1 class="account-title">MY WISHLIST</h1>
            <p class="account-subtitle">Saved items for your personal collection</p>
        </div>

        <div class="account-grid-layout">
            
            <?php if ($is_logged_in): ?>
            <!-- Sidebar Navigation Tabs -->
            <aside>
                <div class="account-nav-list">
                    <a href="<?php echo BASE_URL; ?>/customer/index.php" class="account-nav-link">
                        <i data-lucide="user" style="width: 16px; height: 16px;"></i> Profile Details
                    </a>
                    <a href="<?php echo BASE_URL; ?>/customer/order_history.php" class="account-nav-link">
                        <i data-lucide="package" style="width: 16px; height: 16px;"></i> Order History
                    </a>
                    <a href="<?php echo BASE_URL; ?>/wishlist.php" class="account-nav-link active">
                        <i data-lucide="heart" style="width: 16px; height: 16px;"></i> Wishlist
                    </a>
                    <a href="<?php echo BASE_URL; ?>/logout.php" class="account-nav-link danger">
                        <i data-lucide="log-out" style="width: 16px; height: 16px;"></i> Logout
                    </a>
                </div>
            </aside>
            <?php endif; ?>

            <div>
                <div class="wishlist-panel-card">
                    <!-- Wishlist Wrapper -->
                    <div id="wishlist-wrapper" style="width: 100%;">
                        <?php if (empty($_SESSION['wishlist'])): ?>
                            <div class="wishlist-empty-state" style="text-align: center; padding: 40px 16px; width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <i data-lucide="heart" style="width: 44px; height: 44px; color: #888888; margin-bottom: 12px; stroke-width: 1.5;"></i>
                                <p style="color: #1a1a1a; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; margin: 0 0 16px 0; font-size: 0.8rem; text-align: center; width: 100%;">Your wishlist is empty</p>
                                <a href="<?php echo BASE_URL; ?>/shop.php" class="btn-pill-dark" style="text-decoration: none;">DISCOVER COLLECTION</a>
                            </div>
                        <?php else: 
                            $ids = implode(',', array_map('intval', $_SESSION['wishlist']));
                            $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($ids)");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $count = $result->num_rows;
                            
                            $all_ids = $_SESSION['wishlist'];
                        ?>
                            <!-- Wishlist Header Actions Bar -->
                            <div class="wishlist-header-bar">
                                <span class="wishlist-count-text" id="wishlist-count-display"><?php echo $count; ?> <?php echo $count === 1 ? 'item' : 'items'; ?> saved</span>
                                <button type="button" class="btn-pill-dark" onclick="addAllToCart(<?php echo htmlspecialchars(json_encode($all_ids)); ?>)">ADD ALL TO CART</button>
                            </div>
                            
                            <div class="wishlist-products-grid" id="wishlist-grid">
                                <?php
                                while ($product = $result->fetch_assoc()) {
                                    $image = $product['image'] ? BASE_URL . '/assets/' . htmlspecialchars($product['image']) : BASE_URL . '/assets/bag_shoulder.png';
                                    ?>
                                    <div class="product-card-min" id="wishlist-item-<?php echo $product['id']; ?>">
                                        <div class="product-img-box">
                                            <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>">
                                                <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                            </a>
                                            <button type="button" class="add-btn-overlay" onclick="addWishlistItemToCart(<?php echo $product['id']; ?>)" aria-label="Add to Bag" style="position: absolute; bottom: 6px; right: 6px; background: #1a1a1a; color: white; border: none; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 14px; height: 14px;"><path d="M5 12h14M12 5v14"/></svg>
                                            </button>
                                        </div>
                                        
                                        <div class="product-min-title"><?php echo htmlspecialchars($product['name']); ?></div>
                                        <div class="product-min-price">₹<?php echo number_format($product['price']); ?></div>
                                        
                                        <!-- Remove Link -->
                                        <a href="#" class="wishlist-remove-link" onclick="removeWishlistItem(<?php echo $product['id']; ?>); return false;">REMOVE</a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
function removeWishlistItem(productId) {
    const card = document.getElementById('wishlist-item-' + productId);
    if (!card) return;
    
    card.style.opacity = '0';
    card.style.transform = 'scale(0.9)';
    
    const data = new FormData();
    data.append('action', 'remove');
    data.append('product_id', productId);
    data.append('ajax', '1');
    
    fetch('wishlist_action.php', {
        method: 'POST',
        body: data
    })
    .then(res => res.json())
    .then(resData => {
        if (resData.status === 'success') {
            setTimeout(() => {
                card.remove();
                updateWishlistCount(resData.count);
            }, 400);
        } else {
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
        }
    })
    .catch(err => {
        card.style.opacity = '1';
        card.style.transform = 'scale(1)';
        console.error('Wishlist removal error:', err);
    });
}

function addWishlistItemToCart(productId) {
    const data = new FormData();
    data.append('action', 'add');
    data.append('product_id', productId);
    data.append('quantity', '1');
    data.append('ajax', '1');
    
    fetch('cart_action.php', {
        method: 'POST',
        body: data
    })
    .then(res => res.json())
    .then(resData => {
        if (resData.status === 'success') {
            fetch('<?php echo BASE_URL; ?>/ajax_cart.php')
                .then(r => r.text())
                .then(html => {
                    document.getElementById('cart-panel').innerHTML = html;
                    lucide.createIcons();
                    
                    const countBadge = document.getElementById('cart-count');
                    if (countBadge) {
                        let currentCount = parseInt(countBadge.textContent || '0');
                        countBadge.textContent = currentCount + 1;
                    }
                    
                    document.getElementById('cart-overlay').classList.add('active');
                    document.getElementById('cart-panel').classList.add('active');
                    document.body.classList.add('cart-open');
                    
                    removeWishlistItem(productId);
                });
        }
    })
    .catch(err => {
        console.error('Add to cart error:', err);
    });
}

function addAllToCart(productIds) {
    if (!productIds || productIds.length === 0) return;
    
    const data = new FormData();
    data.append('action', 'bulk_add');
    productIds.forEach(id => data.append('product_ids[]', id));
    data.append('ajax', '1');
    
    fetch('cart_action.php', {
        method: 'POST',
        body: data
    })
    .then(res => res.json())
    .then(resData => {
        if (resData.status === 'success') {
            const removePromises = productIds.map(id => {
                const innerData = new FormData();
                innerData.append('action', 'remove');
                innerData.append('product_id', id);
                innerData.append('ajax', '1');
                return fetch('wishlist_action.php', {
                    method: 'POST',
                    body: innerData
                });
            });
            
            Promise.all(removePromises)
                .then(() => {
                    fetch('<?php echo BASE_URL; ?>/ajax_cart.php')
                        .then(r => r.text())
                        .then(html => {
                            document.getElementById('cart-panel').innerHTML = html;
                            lucide.createIcons();
                            
                            document.getElementById('cart-overlay').classList.add('active');
                            document.getElementById('cart-panel').classList.add('active');
                            document.body.classList.add('cart-open');
                            
                            showEmptyWishlistState();
                        });
                });
        }
    })
    .catch(err => {
        console.error('Add all to cart error:', err);
    });
}

function updateWishlistCount(count) {
    const countDisplay = document.getElementById('wishlist-count-display');
    if (countDisplay) {
        countDisplay.textContent = count + (count === 1 ? ' item' : ' items') + ' saved';
    }
    
    if (count === 0) {
        showEmptyWishlistState();
    }
}

function showEmptyWishlistState() {
    const wrapper = document.getElementById('wishlist-wrapper');
    wrapper.innerHTML = `
        <div class="wishlist-empty-state" style="text-align: center; padding: 40px 16px; width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <i data-lucide="heart" style="width: 44px; height: 44px; color: #888888; margin-bottom: 12px; stroke-width: 1.5;"></i>
            <p style="color: #1a1a1a; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; margin: 0 0 16px 0; font-size: 0.8rem; text-align: center; width: 100%;">Your wishlist is empty</p>
            <a href="shop.php" class="btn-pill-dark" style="text-decoration: none;">DISCOVER COLLECTION</a>
        </div>
    `;
    lucide.createIcons();
}
</script>

<?php require_once 'includes/footer.php'; ?>
