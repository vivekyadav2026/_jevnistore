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
            
            <!-- Left: Main Image & Zoom Button -->
            <div class="product-images-section">
                <!-- Center: Large Image with Zoom Button -->
                <div class="product-gallery">
                    <div class="main-image-container" id="main-image-container" onclick="openLightbox(currentImageIndex)">
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
                <div class="product-vendor">JEVANI STORE</div>
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
                
                <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" id="add-to-cart-form" class="ajax-cart-form">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="size" id="selected-size" value="Standard">

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
            <div class="trust-seals-grid">
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
            </div>
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
                        <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $rel_product['id']; ?>" class="product-img-box">
                            <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($rel_product['name']); ?>">
                        </a>
                        <div class="product-min-title"><?php echo htmlspecialchars($rel_product['name']); ?></div>
                        <div class="product-min-price">₹<?php echo number_format($rel_product['price']); ?></div>
                        <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" class="ajax-cart-form">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $rel_product['id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-btn-small" aria-label="Add to Bag">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5v14"/></svg>
                            </button>
                        </form>
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
                        <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $rv_product['id']; ?>" class="product-img-box">
                            <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($rv_product['name']); ?>">
                        </a>
                        <div class="product-min-title"><?php echo htmlspecialchars($rv_product['name']); ?></div>
                        <div class="product-min-price">₹<?php echo number_format($rv_product['price']); ?></div>
                        <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" class="ajax-cart-form">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $rv_product['id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-btn-small" aria-label="Add to Bag">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14M12 5v14"/></svg>
                            </button>
                        </form>
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
            mainImg.src = src;
            currentImageIndex = index;
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

        // Bind click events and initial Lucide icons
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
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
    </script>

<?php require_once 'includes/footer.php'; ?>
