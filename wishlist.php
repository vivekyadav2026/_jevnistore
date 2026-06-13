<?php
require_once 'includes/header.php';

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}
?>

<div class="wishlist-page-container">
<div class="page-header">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 10px;">WISHLIST</h1>
        <p style="color: var(--text-secondary); letter-spacing: 2px; text-transform: uppercase; font-size: 0.85rem;">Saved for later.</p>
    </div>
</div>

<div class="container" style="min-height: 50vh; margin-bottom: 5rem;">
    <!-- Wishlist Wrapper -->
    <div id="wishlist-wrapper">
        <?php if (empty($_SESSION['wishlist'])): ?>
            <div class="wishlist-empty-state" style="text-align: center; padding: 5rem 0;">
                <i data-lucide="heart" style="width: 48px; height: 48px; color: var(--text-secondary); margin-bottom: 1.5rem; stroke-width: 1;"></i>
                <p style="color: var(--text-secondary); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 2rem;">Your wishlist is empty</p>
                <a href="shop.php" class="btn">DISCOVER</a>
            </div>
        <?php else: 
            $ids = implode(',', array_map('intval', $_SESSION['wishlist']));
            $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($ids)");
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->num_rows;
            
            // Store all product IDs in a JSON array for "Add All to Cart"
            $all_ids = $_SESSION['wishlist'];
        ?>
            <!-- Wishlist Header Actions Bar -->
            <div class="wishlist-header-bar">
                <span class="wishlist-count-text" id="wishlist-count-display"><?php echo $count; ?> <?php echo $count === 1 ? 'item' : 'items'; ?> saved</span>
                <button type="button" class="btn btn-sm" onclick="addAllToCart(<?php echo htmlspecialchars(json_encode($all_ids)); ?>)">ADD ALL TO CART</button>
            </div>
            
            <div class="new-arrivals-grid" id="wishlist-grid">
                <?php
                while ($product = $result->fetch_assoc()) {
                    $image = $product['image'] ? BASE_URL . '/assets/' . htmlspecialchars($product['image']) : BASE_URL . '/assets/bag_shoulder.png';
                    $hover_image = !empty($product['image2']) ? BASE_URL . '/assets/' . htmlspecialchars($product['image2']) : '';
                    
                    $discount_pct = 0;
                    if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']) {
                        $discount_pct = round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100);
                    }
                    ?>
                    <div class="product-card-min" id="wishlist-item-<?php echo $product['id']; ?>" style="transition: all 0.4s ease; text-align: center; display: flex; flex-direction: column; align-items: center;">
                        <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" class="product-img-box">
                            <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </a>
                        
                        <div class="product-min-title"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div class="product-min-price">₹<?php echo number_format($product['price']); ?></div>
                        
                        <!-- Add to Bag button specific to wishlist page -->
                        <button type="button" class="add-btn-small" onclick="addWishlistItemToCart(<?php echo $product['id']; ?>)" aria-label="Add to Bag">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5v14"/></svg>
                        </button>

                        <!-- Remove Link -->
                        <a href="#" class="wishlist-remove-link" onclick="removeWishlistItem(<?php echo $product['id']; ?>); return false;" style="font-size: 0.55rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #555555; margin-top: 8px; cursor: pointer; border-bottom: 1px solid rgba(0, 0, 0, 0.2); padding-bottom: 1px; display: inline-block;">REMOVE</a>
                    </div>
                    <?php
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function removeWishlistItem(productId) {
    const card = document.getElementById('wishlist-item-' + productId);
    if (!card) return;
    
    // Smoothly fade out the card
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
            /*
            if (resData.message && typeof showToast === 'function') {
                showToast(resData.message, resData.type || 'info');
            }
            */
            setTimeout(() => {
                card.remove();
                updateWishlistCount(resData.count);
            }, 400);
        } else {
            // Restore styling if failed
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
            // Trigger AJAX cart panel update and open cart drawer
            fetch('<?php echo BASE_URL; ?>/ajax_cart.php')
                .then(r => r.text())
                .then(html => {
                    document.getElementById('cart-panel').innerHTML = html;
                    lucide.createIcons();
                    
                    // Update cart count icon if exists
                    const countBadge = document.getElementById('cart-count');
                    if (countBadge) {
                        let currentCount = parseInt(countBadge.textContent || '0');
                        countBadge.textContent = currentCount + 1;
                    }
                    
                    // Open cart
                    document.getElementById('cart-overlay').classList.add('active');
                    document.getElementById('cart-panel').classList.add('active');
                    document.body.classList.add('cart-open');
                    
                    // Remove from wishlist dynamically
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
            // Remove all items from wishlist session
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
                    // Refresh cart panel and open drawer
                    fetch('<?php echo BASE_URL; ?>/ajax_cart.php')
                        .then(r => r.text())
                        .then(html => {
                            document.getElementById('cart-panel').innerHTML = html;
                            lucide.createIcons();
                            
                            // Open cart
                            document.getElementById('cart-overlay').classList.add('active');
                            document.getElementById('cart-panel').classList.add('active');
                            document.body.classList.add('cart-open');
                            
                            // Show empty state dynamically
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
    
    // If no items left, show empty state
    if (count === 0) {
        showEmptyWishlistState();
    }
}

function showEmptyWishlistState() {
    const wrapper = document.getElementById('wishlist-wrapper');
    wrapper.innerHTML = `
        <div class="wishlist-empty-state" style="text-align: center; padding: 5rem 0; opacity: 0; transform: translateY(10px); transition: all 0.4s ease;">
            <i data-lucide="heart" style="width: 48px; height: 48px; color: var(--text-secondary); margin-bottom: 1.5rem; stroke-width: 1;"></i>
            <p style="color: var(--text-secondary); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 2rem;">Your wishlist is empty</p>
            <a href="shop.php" class="btn">DISCOVER</a>
        </div>
    `;
    lucide.createIcons();
    setTimeout(() => {
        const emptyState = wrapper.querySelector('.wishlist-empty-state');
        emptyState.style.opacity = '1';
        emptyState.style.transform = 'translateY(0)';
    }, 100);
}
</script>
</div>

<?php require_once 'includes/footer.php'; ?>
