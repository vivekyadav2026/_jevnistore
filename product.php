<?php
require_once 'includes/header.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="container" style="padding: 100px 0; text-align:center;"><h2>Product not found</h2><a href="shop.php" class="btn">Back to Shop</a></div>';
    require_once 'includes/footer.php';
    exit();
}

$product = $result->fetch_assoc();
$image = $product['image'] ? BASE_URL . '/assets/' . htmlspecialchars($product['image']) : BASE_URL . '/assets/bag_shoulder.png';

// Recently Viewed Session Tracking
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['recently_viewed'])) {
    $_SESSION['recently_viewed'] = [];
}
if (($key = array_search($product_id, $_SESSION['recently_viewed'])) !== false) {
    unset($_SESSION['recently_viewed'][$key]);
}
array_unshift($_SESSION['recently_viewed'], $product_id);
$_SESSION['recently_viewed'] = array_slice($_SESSION['recently_viewed'], 0, 5);
?>

    <!-- Main Product Section -->
    <div class="container product-detail-container" style="max-width: 1600px; margin-bottom: 3rem;">
        
        <!-- Responsive Breadcrumbs -->
        <div class="breadcrumb">
            <a href="<?php echo BASE_URL; ?>">Home</a> / 
            <a href="<?php echo BASE_URL; ?>/shop.php">Shop</a> / 
            <span class="breadcrumb-current"><?php echo htmlspecialchars($product['name']); ?></span>
        </div>

        <div class="product-split-layout">
            <?php
            $images = [];
            // Add primary image from products table
            if (!empty($product['image'])) {
                $images[] = BASE_URL . '/assets/' . htmlspecialchars($product['image']);
            }
            // Add secondary image from products table
            if (!empty($product['image2'])) {
                $images[] = BASE_URL . '/assets/' . htmlspecialchars($product['image2']);
            }
            // Fetch additional gallery images (sort_order >= 2)
            $img_stmt = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ? AND sort_order >= 2 ORDER BY sort_order ASC");
            $img_stmt->bind_param("i", $product_id);
            $img_stmt->execute();
            $img_res = $img_stmt->get_result();
            while ($img_row = $img_res->fetch_assoc()) {
                $gal_img = BASE_URL . '/assets/' . htmlspecialchars($img_row['image_path']);
                if (!in_array($gal_img, $images)) {
                    $images[] = $gal_img;
                }
            }
            // Fallback if no images are set
            if(empty($images)) {
                $images[] = BASE_URL . '/assets/bag_shoulder.png';
            }
            ?>
            
            <!-- Left: Main Image & Zoom Button -->
            <div class="product-images-section">
                <!-- Center: Large Image with Zoom Button -->
                <div class="product-gallery">
                    <div class="main-image-container" id="main-image-container">
                        <img id="main-product-image" src="<?php echo $images[0]; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <!-- Zoom button in top right -->
                        <button type="button" class="zoom-trigger-btn" onclick="event.stopPropagation(); openLightbox(currentImageIndex)" aria-label="Zoom Image">
                            <i data-lucide="zoom-in"></i>
                        </button>
                    </div>
                </div>
                <!-- Thumbnails -->
                <div class="product-thumbnails-horizontal">
                    <?php foreach($images as $idx => $imgSrc): ?>
                        <div class="thumb-item <?php echo $idx === 0 ? 'active' : ''; ?>" onclick="changeMainImage(this, '<?php echo $imgSrc; ?>', <?php echo $idx; ?>)">
                            <img src="<?php echo $imgSrc; ?>" alt="Thumbnail <?php echo $idx+1; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Right: Product Info -->
            <div class="product-info-panel">
                <div class="product-vendor">NØRVA STORE</div>
                <h1 class="product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <!-- Star Rating Block -->
                <div class="product-rating-stars">
                    <div class="stars">
                        <i data-lucide="star" class="star-filled"></i>
                        <i data-lucide="star" class="star-filled"></i>
                        <i data-lucide="star" class="star-filled"></i>
                        <i data-lucide="star" class="star-filled"></i>
                        <div class="star-half-container">
                            <i data-lucide="star" class="star-empty"></i>
                            <div class="star-half-clip">
                                <i data-lucide="star" class="star-filled"></i>
                            </div>
                        </div>
                        <span class="rating-count-text">(15)</span>
                    </div>
                </div>

                <div class="product-detail-price">
                    <span class="current-price">RS. <?php echo number_format($product['price'], 2); ?></span>
                    <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                        <span class="compare-price">RS. <?php echo number_format($product['compare_at_price'], 2); ?></span>
                    <?php endif; ?>
                </div>
                <div class="tax-shipping-text">Tax included. Shipping calculated at checkout.</div>
                
                <?php if (isset($product['is_waitlist']) && $product['is_waitlist'] == 1): ?>
                    <button type="button" class="btn-add-cart" style="background: transparent; color: white; border: 1px solid white; cursor: pointer;" onclick="openWaitlistModal()">JOIN WAITING LIST</button>
                    <p style="margin-top: 15px; font-size: 0.85rem; color: #888;">This product is currently in manufacturing. Join the waitlist to be notified.</p>
                <?php elseif ($product['stock'] <= 0): ?>
                    <button type="button" class="btn-add-cart" style="background: #e5e7eb; color: #9ca3af; border: 1px solid #d1d5db; cursor: not-allowed; text-transform: uppercase;" disabled>OUT OF STOCK</button>
                    <p style="margin-top: 15px; font-size: 0.85rem; color: #ef4444; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">This item is currently out of stock.</p>
                <?php else: ?>
                    <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" id="add-to-cart-form" class="ajax-cart-form">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="size" id="selected-size" value="Standard">

                        <?php if (isset($product['has_variants']) && $product['has_variants'] == 1 && !empty($product['variants_list'])): ?>
                            <div style="margin-bottom: 24px;">
                                <label style="display:block; margin-bottom:10px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#1a1a1a;">
                                    Select <?php echo htmlspecialchars($product['variant_name'] ?: 'Option'); ?>: <span id="selected-variant-label" style="font-weight: 500; color: #555;"></span>
                                </label>
                                <input type="hidden" name="variant" id="selected-variant-input" value="" required>
                                
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;" id="variant-pills-container">
                                    <?php 
                                    $options = array_map('trim', explode(',', $product['variants_list']));
                                    $is_color_variant = stripos($product['variant_name'], 'color') !== false;
                                    
                                    // Known color map helper
                                    $color_map = [
                                        'black' => '#000000', 'white' => '#ffffff', 'red' => '#ef4444',
                                        'blue' => '#3b82f6', 'green' => '#22c55e', 'yellow' => '#eab308',
                                        'purple' => '#a855f7', 'pink' => '#ec4899', 'grey' => '#64748b',
                                        'gray' => '#64748b', 'navy' => '#1e3a8a', 'gold' => '#d97706',
                                        'silver' => '#cbd5e1', 'beige' => '#f5f5dc', 'brown' => '#78350f'
                                    ];
                                    
                                    foreach ($options as $idx => $opt):
                                        $lower_opt = strtolower($opt);
                                        $hex = $color_map[$lower_opt] ?? null;
                                    ?>
                                        <?php if ($is_color_variant && $hex): ?>
                                            <button type="button" class="variant-swatch-btn <?php echo $idx === 0 ? 'active' : ''; ?>" 
                                                    data-value="<?php echo htmlspecialchars($opt); ?>"
                                                    title="<?php echo htmlspecialchars($opt); ?>"
                                                    onclick="selectVariant(this, '<?php echo htmlspecialchars($opt, ENT_QUOTES); ?>', <?php echo $idx; ?>)"
                                                    style="width: 34px; height: 34px; border-radius: 50%; background-color: <?php echo $hex; ?>; border: 2px solid <?php echo $lower_opt === 'white' ? '#ccc' : $hex; ?>; cursor: pointer; position: relative; transition: all 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="variant-pill-btn <?php echo $idx === 0 ? 'active' : ''; ?>" 
                                                    data-value="<?php echo htmlspecialchars($opt); ?>"
                                                    onclick="selectVariant(this, '<?php echo htmlspecialchars($opt, ENT_QUOTES); ?>', <?php echo $idx; ?>)"
                                                    style="padding: 10px 18px; border: 1.5px solid #1a1a1a; background: <?php echo $idx === 0 ? '#1a1a1a' : 'transparent'; ?>; color: <?php echo $idx === 0 ? '#fff' : '#1a1a1a'; ?>; font-family: inherit; font-size: 0.78rem; font-weight: 700; cursor: pointer; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.2s;">
                                                <?php echo htmlspecialchars($opt); ?>
                                            </button>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <style>
                            .variant-swatch-btn.active {
                                outline: 2px solid #000000;
                                outline-offset: 3px;
                                transform: scale(1.1);
                            }
                            .variant-pill-btn.active {
                                background: #000000 !important;
                                color: #ffffff !important;
                            }
                            .variant-pill-btn:hover:not(.active) {
                                background: rgba(0,0,0,0.05);
                            }
                            </style>
                            
                            <script>
                            function selectVariant(btn, val, index) {
                                document.getElementById('selected-variant-input').value = val;
                                const lbl = document.getElementById('selected-variant-label');
                                if (lbl) lbl.textContent = val;
                                
                                const container = document.getElementById('variant-pills-container');
                                if (container) {
                                    container.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                                }
                                btn.classList.add('active');

                                // Apply dynamic CSS color filter fallback to visually demonstrate color changes on image
                                const mainImg = document.getElementById('main-product-image');
                                if (mainImg) {
                                    const lowerVal = val.toLowerCase();
                                    let filterVal = 'none';
                                    
                                    if (lowerVal === 'red') {
                                        filterVal = 'sepia(1) hue-rotate(-50deg) saturate(4) opacity(0.9)';
                                    } else if (lowerVal === 'blue') {
                                        filterVal = 'sepia(1) hue-rotate(180deg) saturate(3) opacity(0.9)';
                                    } else if (lowerVal === 'green') {
                                        filterVal = 'sepia(1) hue-rotate(80deg) saturate(3) opacity(0.9)';
                                    } else if (lowerVal === 'white') {
                                        filterVal = 'brightness(1.35) contrast(0.85) grayscale(1)';
                                    } else if (lowerVal === 'beige') {
                                        filterVal = 'sepia(0.6) saturate(1.8) brightness(1.1)';
                                    } else if (lowerVal === 'black') {
                                        filterVal = 'brightness(0.75) contrast(1.3) grayscale(1)';
                                    }
                                    
                                    mainImg.style.transition = 'filter 0.4s ease, transform 0.3s ease';
                                    mainImg.style.filter = filterVal;
                                }

                                // If gallery thumbnails exist, switch main image corresponding to selected color index
                                if (typeof index !== 'undefined') {
                                    const thumbs = document.querySelectorAll('.thumb-item');
                                    if (thumbs.length > 0) {
                                        const targetIndex = index % thumbs.length;
                                        const targetThumb = thumbs[targetIndex];
                                        if (targetThumb) {
                                            const img = targetThumb.querySelector('img');
                                            if (img) {
                                                changeMainImage(targetThumb, img.src, targetIndex);
                                            }
                                        }
                                    }
                                }
                            }
                            // Auto select first option on load
                            document.addEventListener('DOMContentLoaded', () => {
                                const firstBtn = document.querySelector('#variant-pills-container button');
                                if (firstBtn) {
                                    selectVariant(firstBtn, firstBtn.getAttribute('data-value'), 0);
                                }
                            });
                            </script>
                        <?php endif; ?>

                        <!-- Quantity & Buttons -->
                        <div class="detail-actions">
                            <div class="quantity-selector">
                                <button type="button" class="qty-btn" onclick="updateDetailQty(-1)">-</button>
                                <input type="number" id="detail-qty" name="quantity" value="1" min="1" readonly>
                                <button type="button" class="qty-btn" onclick="updateDetailQty(1)">+</button>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-add-cart">ADD TO CART</button>
                        <button type="button" class="btn-buy-now" onclick="buyNow()">BUY IT NOW</button>
                    </form>
                <?php endif; ?>

                <!-- Product Inline Details -->
                <div class="product-inline-details">
                    <?php echo $product['description'] ?: '<p>Premium bag designed for the modern landscape. Featuring custom hardware and luxury finish.</p>'; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Craft Section -->
    <section class="craft-section">
        <div class="container craft-container">
            <div class="craft-collage">
                <div class="craft-img-horizontal">
                    <img src="<?php echo BASE_URL; ?>/assets/craft_workshop.jpg" alt="Workshop sewing process">
                </div>
                <div class="craft-img-vertical">
                    <img src="<?php echo BASE_URL; ?>/assets/craft_machine.jpg" alt="Industrial printing machinery">
                </div>
            </div>
            <div class="craft-details y2k-fade-in">
                <span class="craft-subtitle">The Process</span>
                <h2 class="craft-headline">HOMEGROWN PROSPERITY:<br>EMPOWERING INDIAN COMMUNITIES</h2>
                <p class="craft-desc">
                    Empowering through employment, restoring culture. We're preserving heritage, transforming communities. Together, let's craft a brighter tomorrow.
                </p>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <div class="container">
            <h2 class="reviews-title">CUSTOMER REVIEWS</h2>
            
            <div class="reviews-summary-block">
                <div class="rating-overall">
                    <div class="rating-score">4.8</div>
                    <div class="stars">
                        <i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star" style="fill: transparent; stroke: var(--accent);"></i>
                    </div>
                    <div class="rating-count">Based on 124 reviews</div>
                </div>
                
                <div class="rating-bars">
                    <div class="rating-bar-row">
                        <div class="rating-stars">5 Stars</div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 80%;"></div></div>
                        <div class="rating-pct">80%</div>
                    </div>
                    <div class="rating-bar-row">
                        <div class="rating-stars">4 Stars</div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 15%;"></div></div>
                        <div class="rating-pct">15%</div>
                    </div>
                    <div class="rating-bar-row">
                        <div class="rating-stars">3 Stars</div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 5%;"></div></div>
                        <div class="rating-pct">5%</div>
                    </div>
                    <div class="rating-bar-row">
                        <div class="rating-stars">2 Stars</div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 0%;"></div></div>
                        <div class="rating-pct">0%</div>
                    </div>
                    <div class="rating-bar-row">
                        <div class="rating-stars">1 Star</div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 0%;"></div></div>
                        <div class="rating-pct">0%</div>
                    </div>
                </div>
                
                <div class="write-review-action">
                    <button class="btn btn-outline btn-write-review">Write a Review</button>
                </div>
            </div>

            <div class="reviews-grid new-reviews-grid">
                <div class="review-card">
                    <div class="review-header">
                        <div class="stars">
                            <i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i>
                        </div>
                        <div class="review-date">10/12/2026</div>
                    </div>
                    <h4 class="review-title">Absolutely obsessed!</h4>
                    <p class="review-text">The hardware feels super heavy and premium, and the leather quality is insane for the price. Best streetwear accessory I own.</p>
                    <div class="review-author">Sarah M. <span class="verified-badge"><i data-lucide="check-circle"></i> Verified Buyer</span></div>
                </div>
                
                <div class="review-card">
                    <div class="review-header">
                        <div class="stars">
                            <i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i>
                        </div>
                        <div class="review-date">10/08/2026</div>
                    </div>
                    <h4 class="review-title">Elevates my fits</h4>
                    <p class="review-text">The chain detailing is everything. It instantly elevates my fits. Holds all my essentials perfectly. 10/10 would recommend.</p>
                    <div class="review-author">Jordan K. <span class="verified-badge"><i data-lucide="check-circle"></i> Verified Buyer</span></div>
                </div>
                
                <div class="review-card">
                    <div class="review-header">
                        <div class="stars">
                            <i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star"></i><i data-lucide="star" style="fill: transparent; stroke: var(--accent);"></i>
                        </div>
                        <div class="review-date">09/29/2026</div>
                    </div>
                    <h4 class="review-title">Love the aesthetic!</h4>
                    <p class="review-text">It's slightly smaller than I expected, but it still fits my phone and wallet. The magnetic closure is really satisfying.</p>
                    <div class="review-author">Elena R. <span class="verified-badge"><i data-lucide="check-circle"></i> Verified Buyer</span></div>
                </div>
            </div>
            
            <!-- Trust Seals circular badges at bottom of reviews container -->
            <!-- <div class="trust-seals-grid">
                <div class="trust-seal-circle">
                    <span>Free<br>Shipping</span>
                </div>
                <div class="trust-seal-circle" style="animation-direction: reverse;">
                    <span>3 Days<br>Return</span>
                </div>
                <div class="trust-seal-circle">
                    <span>100%<br>Secure</span>
                </div>
                <div class="trust-seal-circle" style="animation-direction: reverse;">
                    <span>Premium<br>Quality</span>
                </div>
            </div> -->
        </div>
    </section>

    <!-- Related Products -->
    <section style="padding: 4rem 0; border-top: 1px solid var(--border-color); background: var(--bg-secondary);">
        <div class="container">
            <h2 class="section-title" style="font-size: 2rem; margin-bottom: 2rem; text-align: center; text-transform: uppercase; letter-spacing: 2px;">RELATED PRODUCTS</h2>
            <div class="products-grid-detail" id="related-products">
                <?php
                // Fetch up to 4 products of the same category
                $related_products = [];
                if (!empty($product['category_id'])) {
                    $rel_stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT 4");
                    $rel_stmt->bind_param("ii", $product['category_id'], $product_id);
                    $rel_stmt->execute();
                    $rel_result = $rel_stmt->get_result();
                    while ($row = $rel_result->fetch_assoc()) {
                        $related_products[] = $row;
                    }
                }
                
                // Fallback to random products if we have fewer than 4 items
                if (count($related_products) < 4) {
                    $needed = 4 - count($related_products);
                    $exclude_ids = [$product_id];
                    foreach ($related_products as $rp) {
                        $exclude_ids[] = $rp['id'];
                    }
                    $exclude_placeholders = implode(',', array_fill(0, count($exclude_ids), '?'));
                    
                    $fallback_query = "SELECT * FROM products WHERE id NOT IN ($exclude_placeholders) ORDER BY RAND() LIMIT ?";
                    $fallback_stmt = $conn->prepare($fallback_query);
                    
                    $types = str_repeat('i', count($exclude_ids)) . 'i';
                    $bind_args = array_merge($exclude_ids, [$needed]);
                    $fallback_stmt->bind_param($types, ...$bind_args);
                    $fallback_stmt->execute();
                    $fallback_res = $fallback_stmt->get_result();
                    while ($row = $fallback_res->fetch_assoc()) {
                        $related_products[] = $row;
                    }
                }
                
                foreach ($related_products as $rel_product) {
                    $image = $rel_product['image'] ? BASE_URL . '/assets/' . htmlspecialchars($rel_product['image']) : BASE_URL . '/assets/bag_shoulder.png';
                    ?>
                    <div class="product-card-min">
                        <div class="product-img-box">
                            <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $rel_product['id']; ?>">
                                <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($rel_product['name']); ?>">
                            </a>
                            <?php if (isset($rel_product['is_waitlist']) && $rel_product['is_waitlist'] == 1): ?>
                                <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $rel_product['id']; ?>" class="add-btn-overlay" aria-label="Join Waitlist" style="display:flex; align-items:center; justify-content:center; text-decoration:none;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:20px;height:20px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </a>
                            <?php else: ?>
                                <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" class="ajax-cart-form">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?php echo $rel_product['id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="add-btn-overlay" aria-label="Add to Bag">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5v14"/></svg>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <div class="product-min-title"><?php echo htmlspecialchars($rel_product['name']); ?></div>
                        <div class="product-min-price">₹<?php echo number_format($rel_product['price']); ?></div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Recently Viewed Products -->
    <?php
    $recent_ids = [];
    if (isset($_SESSION['recently_viewed'])) {
        $recent_ids = array_filter($_SESSION['recently_viewed'], function($id) use ($product_id) {
            return $id != $product_id;
        });
    }
    if (!empty($recent_ids)):
    ?>
    <section style="padding: 4rem 0; border-top: 1px solid var(--border-color); background: var(--bg-primary);">
        <div class="container">
            <h2 class="section-title" style="font-size: 2rem; margin-bottom: 2rem; text-align: center; text-transform: uppercase; letter-spacing: 2px;">RECENTLY VIEWED PRODUCTS</h2>
            <div class="products-grid-detail">
                <?php
                $placeholders = implode(',', array_fill(0, count($recent_ids), '?'));
                $rv_stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders) LIMIT 4");
                $types = str_repeat('i', count($recent_ids));
                $rv_stmt->bind_param($types, ...$recent_ids);
                $rv_stmt->execute();
                $rv_res = $rv_stmt->get_result();
                while ($rv_product = $rv_res->fetch_assoc()):
                    $image = $rv_product['image'] ? BASE_URL . '/assets/' . htmlspecialchars($rv_product['image']) : BASE_URL . '/assets/bag_shoulder.png';
                    ?>
                    <div class="product-card-min">
                        <div class="product-img-box">
                            <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $rv_product['id']; ?>">
                                <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($rv_product['name']); ?>">
                            </a>
                            <?php if (isset($rv_product['is_waitlist']) && $rv_product['is_waitlist'] == 1): ?>
                                <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $rv_product['id']; ?>" class="add-btn-overlay" aria-label="Join Waitlist" style="display:flex; align-items:center; justify-content:center; text-decoration:none;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:20px;height:20px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </a>
                            <?php else: ?>
                                <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" class="ajax-cart-form">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?php echo $rv_product['id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="add-btn-overlay" aria-label="Add to Bag">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5v14"/></svg>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <div class="product-min-title"><?php echo htmlspecialchars($rv_product['name']); ?></div>
                        <div class="product-min-price">₹<?php echo number_format($rv_product['price']); ?></div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <script>
        // Scroll Intersection Observer — makes related product cards visible
        document.addEventListener('DOMContentLoaded', () => {
            const fadeElements = document.querySelectorAll('.y2k-fade-in');

            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        obs.unobserve(entry.target);
                    }
                });
            }, { root: null, rootMargin: '0px', threshold: 0.05 });

            fadeElements.forEach(el => observer.observe(el));

            // Fallback: if element is already in viewport on load, show it immediately
            fadeElements.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight) {
                    el.classList.add('animated');
                }
            });
        });



        function updateDetailQty(change) {
            const input = document.getElementById('detail-qty');
            let val = parseInt(input.value) + change;
            if (val >= 1) input.value = val;
        }

        function toggleAccordion(el) {
            const content = el.nextElementSibling;
            const icon = el.querySelector('i');
            
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
                icon.style.transform = "rotate(0deg)";
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
                icon.style.transform = "rotate(180deg)";
            }
        }
        
        // Initialize first accordion as open
        document.addEventListener('DOMContentLoaded', () => {
            const firstAccordion = document.querySelector('.accordion-item:first-child .accordion-content');
            if(firstAccordion) {
                firstAccordion.style.maxHeight = firstAccordion.scrollHeight + "px";
                firstAccordion.previousElementSibling.querySelector('i').style.transform = "rotate(180deg)";
            }
        });

        // Image click index and Thumbnail click
        let currentImageIndex = 0;

        function changeMainImage(thumb, src, index) {
            document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
            const mainImg = document.getElementById('main-product-image');
            if (mainImg) {
                mainImg.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
                mainImg.style.opacity = '0.3';
                mainImg.style.transform = 'scale(0.97)';
                setTimeout(() => {
                    mainImg.src = src;
                    mainImg.style.opacity = '1';
                    mainImg.style.transform = 'scale(1)';
                }, 100);
            }
            currentImageIndex = index;
        }

        function swipeImage(direction) {
            const thumbs = document.querySelectorAll('.thumb-item');
            if (thumbs.length <= 1) return;
            let nextIndex = (currentImageIndex + direction + thumbs.length) % thumbs.length;
            const nextThumb = thumbs[nextIndex];
            const imgEl = nextThumb.querySelector('img');
            if (imgEl) {
                changeMainImage(nextThumb, imgEl.src, nextIndex);
            }
        }

        function buyNow() {
            // For Buy Now, we add to cart then redirect to checkout
            const form = document.getElementById('add-to-cart-form');
            
            // We can intercept form submission, or use a hidden input field
            const buyNowInput = document.createElement('input');
            buyNowInput.type = 'hidden';
            buyNowInput.name = 'buy_now';
            buyNowInput.value = '1';
            form.appendChild(buyNowInput);
            
            form.submit();
        }
    </script>

    <!-- Full-Screen Lightbox Overlay -->
    <div class="product-lightbox" id="product-lightbox" style="display: none;">
        <div class="lightbox-content" onclick="closeLightbox()">
            <img id="lightbox-active-img" src="" alt="Active View" onclick="event.stopPropagation()">
        </div>
        
        <!-- Navigation Buttons at the bottom center -->
        <div class="lightbox-controls">
            <button type="button" class="lightbox-btn prev-btn" id="lightbox-prev" onclick="changeLightboxImage(-1)">
                <i data-lucide="chevron-left"></i>
            </button>
            <button type="button" class="lightbox-btn close-btn" id="lightbox-close" onclick="closeLightbox()">
                <i data-lucide="x"></i>
            </button>
            <button type="button" class="lightbox-btn next-btn" id="lightbox-next" onclick="changeLightboxImage(1)">
                <i data-lucide="chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- Waitlist Modal -->
    <div id="waitlist-modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.85); z-index:100; padding: 40px 20px; overflow-y: auto;">
        <div style="background:#111; max-width: 500px; margin: 50px auto; padding: 30px; border-radius: 8px; border: 1px solid #333; color: white;">
            <div style="display:flex; justify-content:space-between; margin-bottom: 20px; border-bottom: 1px solid #222; padding-bottom: 10px;">
                <h4 style="margin:0; font-size:1.1rem; text-transform:uppercase; letter-spacing:1px;">Join Waiting List</h4>
                <button onclick="closeWaitlistModal()" style="background:none; border:none; color:white; cursor:pointer;"><i data-lucide="x"></i></button>
            </div>
            <p style="color: #ccc; font-size: 0.9rem; margin-bottom: 20px;">This product is currently in manufacturing. Join the waitlist to be notified as soon as it becomes available.</p>
            <form method="POST" action="<?php echo BASE_URL; ?>/waitlist_action.php">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-size:13px; color:#888;">Name <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-size:13px; color:#888;">Email Address <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="Your Email Address" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display:block; margin-bottom:5px; font-size:13px; color:#888;">Phone Number</label>
                    <input type="tel" name="phone" class="form-control" placeholder="Your Phone Number (Optional)">
                </div>
                <button type="submit" class="btn btn-block" style="margin-top: 15px; padding: 12px; width: 100%; background: white; color: black; font-weight: bold; border-radius: 4px; border: none; cursor: pointer; text-transform: uppercase;">Join List</button>
            </form>
        </div>
    </div>

    <script>
        // Lightbox Gallery Javascript
        let lightboxActiveIndex = 0;
        const lightboxImages = <?php echo json_encode($images); ?>;

        function openLightbox(index) {
            lightboxActiveIndex = index;
            const lightbox = document.getElementById('product-lightbox');
            const activeImg = document.getElementById('lightbox-active-img');
            
            activeImg.src = lightboxImages[lightboxActiveIndex];
            activeImg.style.opacity = '1';
            activeImg.style.transform = 'scale(1)';
            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // prevent scrolling behind
            
            lucide.createIcons();
        }

        function closeLightbox() {
            const lightbox = document.getElementById('product-lightbox');
            lightbox.style.display = 'none';
            document.body.style.overflow = ''; // restore scrolling
        }

        function openWaitlistModal() {
            document.getElementById('waitlist-modal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeWaitlistModal() {
            document.getElementById('waitlist-modal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function changeLightboxImage(direction) {
            const activeImg = document.getElementById('lightbox-active-img');
            
            // Smooth transition
            activeImg.style.opacity = '0';
            activeImg.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                lightboxActiveIndex = (lightboxActiveIndex + direction + lightboxImages.length) % lightboxImages.length;
                activeImg.src = lightboxImages[lightboxActiveIndex];
                activeImg.style.opacity = '1';
                activeImg.style.transform = 'scale(1)';
            }, 150);
        }

        // Bind click events and initial Lucide icons
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            // Sticky mobile cart bar Intersection Observer
            const mainAddToCartBtn = document.querySelector('.btn-add-cart');
            const stickyBar = document.getElementById('sticky-mobile-cart-bar');
            if (mainAddToCartBtn && stickyBar) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (!entry.isIntersecting) {
                            stickyBar.classList.add('visible');
                        } else {
                            stickyBar.classList.remove('visible');
                        }
                    });
                }, {
                    threshold: 0,
                    rootMargin: '0px'
                });
                observer.observe(mainAddToCartBtn);
            }

            // Swipe and Tap Gestures for Main Image
            const container = document.getElementById('main-image-container');
            let touchstartX = 0;
            let touchendX = 0;
            let touchstartY = 0;
            let touchendY = 0;
            let isSwiping = false;

            if (container) {
                container.addEventListener('touchstart', function(event) {
                    touchstartX = event.changedTouches[0].clientX;
                    touchstartY = event.changedTouches[0].clientY;
                    isSwiping = false;
                }, { passive: true });

                container.addEventListener('touchmove', function(event) {
                    const currentX = event.changedTouches[0].clientX;
                    const currentY = event.changedTouches[0].clientY;
                    if (Math.abs(currentX - touchstartX) > 10 || Math.abs(currentY - touchstartY) > 10) {
                        isSwiping = true;
                    }
                }, { passive: true });

                container.addEventListener('touchend', function(event) {
                    touchendX = event.changedTouches[0].clientX;
                    touchendY = event.changedTouches[0].clientY;
                    
                    const diffX = touchendX - touchstartX;
                    const diffY = touchendY - touchstartY;
                    
                    if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 40) {
                        isSwiping = true;
                        if (diffX < 0) {
                            swipeImage(1); // Swipe left -> Next
                        } else {
                            swipeImage(-1); // Swipe right -> Prev
                        }
                    }
                }, { passive: true });

                container.addEventListener('click', function(event) {
                    // Block lightbox from opening if we just did a swipe gesture
                    if (!isSwiping) {
                        openLightbox(currentImageIndex);
                    }
                });
            }
        });

        function submitMainAddToCartForm() {
            const form = document.getElementById('add-to-cart-form');
            if (form) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.click();
                } else {
                    form.submit();
                }
            }
        }
            
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            const lightbox = document.getElementById('product-lightbox');
            if (lightbox && lightbox.style.display === 'flex') {
                if (e.key === 'ArrowLeft') {
                    changeLightboxImage(-1);
                } else if (e.key === 'ArrowRight') {
                    changeLightboxImage(1);
                } else if (e.key === 'Escape') {
                    closeLightbox();
                }
            }
        });
    </script>

    <!-- Sticky Mobile Add to Cart Bar -->
    <?php if (isset($product['is_waitlist']) && $product['is_waitlist'] == 1): ?>
        <div class="sticky-mobile-cart-bar" id="sticky-mobile-cart-bar" onclick="openWaitlistModal()">
            <div class="sticky-cart-btn-text">JOIN WAITLIST</div>
        </div>
    <?php else: ?>
        <div class="sticky-mobile-cart-bar" id="sticky-mobile-cart-bar" onclick="submitMainAddToCartForm()">
            <div class="sticky-cart-btn-text">ADD TO CART</div>
        </div>
    <?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
