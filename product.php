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
?>

    <!-- Main Product Section -->
    <div class="container product-detail-container" style="max-width: 1600px; margin-bottom: 3rem;">
        <div class="product-split-layout">
            <?php
            $img_stmt = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY sort_order ASC");
            $img_stmt->bind_param("i", $product_id);
            $img_stmt->execute();
            $img_res = $img_stmt->get_result();
            $images = [];
            while ($img_row = $img_res->fetch_assoc()) {
                $images[] = BASE_URL . '/assets/' . htmlspecialchars($img_row['image_path']);
            }
            if(empty($images)) $images[] = BASE_URL . '/assets/bag_shoulder.png';
            ?>
            
            <!-- Left: Sticky Thumbnails -->
            <div class="product-thumbnails">
                <?php foreach($images as $idx => $imgSrc): ?>
                    <a href="#gallery-img-<?php echo $idx; ?>">
                        <img src="<?php echo $imgSrc; ?>" alt="Thumbnail <?php echo $idx+1; ?>">
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Center: Large Image Gallery -->
            <div class="product-gallery">
                <?php foreach($images as $idx => $imgSrc): ?>
                    <img id="gallery-img-<?php echo $idx; ?>" src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($product['name']); ?> Image <?php echo $idx+1; ?>">
                <?php endforeach; ?>
            </div>
            
            <!-- Right: Product Info -->
            <div class="product-info-panel">
                <div class="product-vendor">JEVANI STORE</div>
                <h1 class="product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="product-detail-price">
                    RS. <?php echo number_format($product['price'], 2); ?>
                </div>
                
                <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" id="add-to-cart-form" class="ajax-cart-form">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="size" id="selected-size" value="">

                    <!-- Hardware Selector -->
                    <div style="margin-bottom: 2rem;">
                        <div class="size-selector-label">
                            <span>HARDWARE FINISH</span>
                        </div>
                        <div class="size-grid" style="grid-template-columns: repeat(2, 1fr);">
                            <button type="button" class="size-btn active" onclick="selectSize(this, 'Silver')">SILVER</button>
                            <button type="button" class="size-btn" onclick="selectSize(this, 'Gold')">GOLD</button>
                        </div>
                    </div>

                    <!-- Quantity & Buttons -->
                    <div class="detail-actions">
                        <div class="quantity-selector">
                            <button type="button" class="qty-btn-large" onclick="updateDetailQty(-1)">-</button>
                            <input type="number" id="detail-qty" name="quantity" value="1" min="1" readonly>
                            <button type="button" class="qty-btn-large" onclick="updateDetailQty(1)">+</button>
                        </div>
                        <button type="submit" class="btn btn-outline" style="height: 50px; font-size: 0.9rem; letter-spacing: 2px;">ADD TO CART</button>
                    </div>
                    
                    <button type="button" class="btn-buy-now" onclick="buyNow()">BUY IT NOW</button>
                </form>
            </div>
        </div>

        <!-- Below: Product Meta Information (Accordions) -->
        <div class="product-meta-accordion" style="margin-top: 2rem; max-width: 800px; margin-left: auto; margin-right: auto;">
            <div class="accordion-item" style="border-bottom: 1px solid var(--border-color); padding: 20px 0;">
                <h4 style="margin: 0; font-size: 1rem; letter-spacing: 1px; display: flex; justify-content: space-between; cursor: pointer;" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'none' ? 'block' : 'none';">
                    DESCRIPTION & DETAILS <i data-lucide="chevron-down"></i>
                </h4>
                <div style="display: none; padding-top: 20px; color: var(--text-secondary); line-height: 1.8; font-size: 0.95rem;">
                    <div class="product-description-content">
                        <?php echo $product['description'] ?: '<p>Premium bag designed for the modern landscape. Featuring custom hardware and luxury finish.</p>'; ?>
                    </div>
                </div>
            </div>

            <!-- <div class="accordion-item" style="border-bottom: 1px solid var(--border-color); padding: 20px 0;">
                <h4 style="margin: 0; font-size: 1rem; letter-spacing: 1px; display: flex; justify-content: space-between; cursor: pointer;" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'none' ? 'block' : 'none';">
                    SHIPPING & RETURNS <i data-lucide="chevron-down"></i>
                </h4>
                <div style="display: none; padding-top: 20px; color: var(--text-secondary); line-height: 1.8; font-size: 0.95rem;">
                    <p><strong>Free Standard Shipping:</strong> 3-5 Business Days on orders over ₹1500.<br><strong>Express Shipping:</strong> 1-2 Business Days available at checkout.</p>
                    <p><strong>Returns:</strong> Accepted within 14 days of delivery. Items must be unused with original tags and hardware protectors attached.</p>
                </div>
            </div> -->
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
            <div class="reviews-header">
                <h2 class="section-title" style="margin-bottom: 0;">CUSTOMER REVIEWS</h2>
                <div class="reviews-rating-summary">
                    <div class="stars">
                        <i data-lucide="star"></i>
                        <i data-lucide="star"></i>
                        <i data-lucide="star"></i>
                        <i data-lucide="star"></i>
                        <i data-lucide="star" style="fill: transparent; stroke: var(--accent);"></i>
                    </div>
                    <span style="color: var(--text-secondary); font-size: 0.9rem; letter-spacing: 1px;">4.8 / 5 based on 124 reviews</span>
                </div>
            </div>

            <div class="reviews-grid">
                <div class="review-card y2k-fade-in">
                    <div class="stars" style="margin-bottom: 15px;">
                        <i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px;"></i>
                    </div>
                    <div class="review-author">Sarah M. <span class="verified-badge"><i data-lucide="check-circle" style="width: 12px; height: 12px;"></i> Verified</span></div>
                    <div class="review-date">Oct 12, 2026</div>
                    <p class="review-text">Absolutely obsessed with this bag. The hardware feels super heavy and premium, and the leather quality is insane for the price. Best streetwear accessory I own.</p>
                </div>
                <div class="review-card y2k-fade-in" style="transition-delay: 0.1s;">
                    <div class="stars" style="margin-bottom: 15px;">
                        <i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px;"></i>
                    </div>
                    <div class="review-author">Jordan K. <span class="verified-badge"><i data-lucide="check-circle" style="width: 12px; height: 12px;"></i> Verified</span></div>
                    <div class="review-date">Oct 08, 2026</div>
                    <p class="review-text">The chain detailing is everything. It instantly elevates my fits. Holds all my essentials perfectly. 10/10 would recommend.</p>
                </div>
                <div class="review-card y2k-fade-in" style="transition-delay: 0.2s;">
                    <div class="stars" style="margin-bottom: 15px;">
                        <i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px;"></i><i data-lucide="star" style="width: 14px; height: 14px; fill: transparent; stroke: var(--accent);"></i>
                    </div>
                    <div class="review-author">Elena R. <span class="verified-badge"><i data-lucide="check-circle" style="width: 12px; height: 12px;"></i> Verified</span></div>
                    <div class="review-date">Sep 29, 2026</div>
                    <p class="review-text">Love the aesthetic! It's slightly smaller than I expected, but it still fits my phone and wallet. The magnetic closure is really satisfying.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <section style="padding: 4rem 0; border-top: 1px solid var(--border-color); background: var(--bg-secondary);">
        <div class="container">
            <h2 class="section-title" style="font-size: 2rem; margin-bottom: 2rem;">COMPLETE THE LOOK</h2>
            <div class="new-arrivals-grid" id="related-products">
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
                
                $index = 0;
                foreach ($related_products as $rel_product) {
                    $image = $rel_product['image'] ? BASE_URL . '/assets/' . htmlspecialchars($rel_product['image']) : BASE_URL . '/assets/bag_shoulder.png';
                    $hover_image = !empty($rel_product['image2']) ? BASE_URL . '/assets/' . htmlspecialchars($rel_product['image2']) : '';
                    
                    $discount_pct = 0;
                    if (!empty($rel_product['compare_at_price']) && $rel_product['compare_at_price'] > $rel_product['price']) {
                        $discount_pct = round((($rel_product['compare_at_price'] - $rel_product['price']) / $rel_product['compare_at_price']) * 100);
                    }
                    
                    $in_wish = isset($_SESSION['wishlist']) && in_array($rel_product['id'], $_SESSION['wishlist']);
                    $heart_fill = $in_wish ? '#ef4444' : 'none';
                    $heart_color = $in_wish ? '#ef4444' : '#ffffff';
                    $wish_class = $in_wish ? 'in-wishlist' : '';
                    ?>
                    <div class="y2k-card y2k-fade-in" style="transition-delay: <?php echo ($index % 4) * 0.1; ?>s;">
                        <div class="y2k-img-wrapper">
                            <!-- Badges -->
                            <div class="y2k-badges">
                                <?php if ($rel_product['stock'] <= 0): ?>
                                    <span class="y2k-badge sold-out">SOLD OUT</span>
                                <?php endif; ?>
                                <?php if ($discount_pct > 0): ?>
                                    <span class="y2k-badge sale">SAVE <?php echo $discount_pct; ?>%</span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Wishlist Toggle -->
                            <button type="button" class="y2k-wishlist-btn <?php echo $wish_class; ?>" onclick="toggleWishlist(<?php echo $rel_product['id']; ?>, this)" aria-label="Add to Wishlist">
                                <i data-lucide="heart" fill="<?php echo $heart_fill; ?>" style="width: 18px; height: 18px; color: <?php echo $heart_color; ?>;"></i>
                            </button>
                            
                            <!-- Product Image -->
                            <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $rel_product['id']; ?>">
                                <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($rel_product['name']); ?>" class="y2k-img">
                                <?php if ($hover_image): ?>
                                    <img src="<?php echo $hover_image; ?>" alt="<?php echo htmlspecialchars($rel_product['name']); ?> hover" class="y2k-img-hover">
                                <?php endif; ?>
                            </a>
                            
                            <!-- Quick View Trigger -->
                            <button type="button" class="y2k-quickview-btn" onclick="openQuickView(<?php echo $rel_product['id']; ?>)">Quick View</button>
                        </div>
                        
                        <!-- Details -->
                        <div class="y2k-info">
                            <div>
                                <span class="y2k-brand">JEVANI STORE</span>
                                <h3 class="y2k-title">
                                    <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $rel_product['id']; ?>">
                                        <?php echo htmlspecialchars($rel_product['name']); ?>
                                    </a>
                                </h3>
                            </div>
                            
                            <div class="y2k-price-row">
                                <span class="y2k-price-sale">₹<?php echo number_format($rel_product['price']); ?></span>
                                <?php if (!empty($rel_product['compare_at_price']) && $rel_product['compare_at_price'] > $rel_product['price']): ?>
                                    <span class="y2k-price-compare">₹<?php echo number_format($rel_product['compare_at_price']); ?></span>
                                    <span class="y2k-discount-badge">(<?php echo $discount_pct; ?>% OFF)</span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- AJAX Add to Cart form -->
                            <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" class="ajax-cart-form">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $rel_product['id']; ?>">
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
                    $index++;
                }
                ?>
            </div>
        </div>
    </section>

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

        // Set default hardware
        document.getElementById('selected-size').value = 'Silver';

        function selectSize(btn, size) {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('selected-size').value = size;
        }

        function updateDetailQty(change) {
            const input = document.getElementById('detail-qty');
            let val = parseInt(input.value) + change;
            if (val >= 1) input.value = val;
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

        // Bind click events on gallery images
        document.addEventListener('DOMContentLoaded', () => {
            const galleryImgs = document.querySelectorAll('.product-gallery img');
            galleryImgs.forEach((img, idx) => {
                img.style.cursor = 'zoom-in';
                img.addEventListener('click', (e) => {
                    let targetIdx = idx;
                    if (window.innerWidth <= 991 && idx === 0) {
                        const activeIdxAttr = e.currentTarget.getAttribute('data-active-idx');
                        if (activeIdxAttr !== null) targetIdx = parseInt(activeIdxAttr);
                    }
                    openLightbox(targetIdx);
                });
            });
            
            const thumbnailLinks = document.querySelectorAll('.product-thumbnails a');
            thumbnailLinks.forEach((link, idx) => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (window.innerWidth <= 991) {
                        const mainImg = document.querySelector('.product-gallery img:first-child');
                        if (mainImg) {
                            mainImg.src = lightboxImages[idx];
                            mainImg.setAttribute('data-active-idx', idx);
                            mainImg.style.opacity = '0.5';
                            setTimeout(() => mainImg.style.opacity = '1', 50);
                            
                            // Highlight active thumbnail
                            thumbnailLinks.forEach(l => l.classList.remove('active-thumb'));
                            link.classList.add('active-thumb');
                        }
                    } else {
                        openLightbox(idx);
                    }
                });
            });
            
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
        });
    </script>

<?php require_once 'includes/footer.php'; ?>
