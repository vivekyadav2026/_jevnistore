<?php require_once 'includes/header.php'; ?>

    <!-- 1. Hero Banner -->
    <section class="hero" style="min-height: 85vh;">
        <picture>
            <source media="(max-width: 768px)" srcset="<?php echo BASE_URL; ?>/assets/hero_mobile.jpg">
            <img src="<?php echo BASE_URL; ?>/assets/hero_banner_model.png" alt="Premium Gen-Z Bag Campaign" class="hero-img">
        </picture>
        <div class="hero-overlay" style="background: linear-gradient(to top, rgba(9, 9, 11, 0.95) 0%, rgba(9, 9, 11, 0.3) 50%, rgba(9, 9, 11, 0.6) 100%);"></div>
        <div class="container hero-content" style="align-self: flex-end; margin-bottom: 15vh; z-index: 2; display: flex; justify-content: center; align-items: center;">
            <div class="hero-actions" style="width: 100%; display: flex; justify-content: center;">
                <a href="<?php echo BASE_URL; ?>/shop.php" style="color: #ffffff; text-transform: uppercase; font-size: clamp(0.9rem, 2.5vw, 1.2rem); letter-spacing: 6px; text-decoration: underline; text-underline-offset: 8px; text-decoration-thickness: 1px; font-weight: 500; font-family: var(--font); transition: opacity 0.3s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">JUST DROPPED</a>
            </div>
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="new-arrivals-section">

        
        <div class="container">
            <div class="new-arrivals-header" style="margin-bottom: 3rem;">
                <h2 class="new-arrivals-title" style="font-size: clamp(1.2rem, 3vw, 1.8rem); letter-spacing: 5px; font-weight: 400; color: var(--text-primary); text-transform: uppercase;">DROPPED TODAY</h2>
            </div>
            
            <div class="new-arrivals-grid">
                <?php
                $na_stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT 12");
                $na_stmt->execute();
                $na_result = $na_stmt->get_result();
                $index = 0;
                while ($product = $na_result->fetch_assoc()) {
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
                    <div class="y2k-card y2k-fade-in" style="transition-delay: <?php echo ($index % 4) * 0.1; ?>s;">
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
                    $index++;
                }
                ?>
            </div>
        </div>
    </section>

    <script>
        // Scroll Intersection Observer for animations
        document.addEventListener('DOMContentLoaded', () => {
            const fadeElements = document.querySelectorAll('.y2k-fade-in');
            
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };
            
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            fadeElements.forEach(el => observer.observe(el));
        });
    </script>

    <!-- Video Campaign Section -->
    <section class="video-campaign-section" style="background: #000; border-top: 1px solid #1a1a1a; border-bottom: 1px solid #1a1a1a; padding: 0; line-height: 0;">
        <div style="width: 100%; position: relative; overflow: hidden; height: auto;">
            <video id="campaign-video" autoplay loop muted playsinline style="width: 100%; height: auto; display: block; object-fit: cover; max-height: 80vh;">
                <source src="<?php echo BASE_URL; ?>/assets/campaign.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </section>

    <!-- Genrage Bags Showcase -->
    <section class="bags-showcase" style="background-color: #cfcfcf; padding: 4rem 20px; font-family: var(--font); overflow: hidden;">
        <div class="container" style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; align-items: flex-start;">
            
            <!-- Showcase Composition -->
            <div style="width: 100%; max-width: 600px; position: relative; margin-bottom: 3rem; padding-right: 20%; align-self: center;">
                
                <!-- Video Container (Replaces the Man) -->
                <div style="width: 100%; background-color: #1a1a1a; position: relative; z-index: 1;">
                    <video autoplay loop muted playsinline style="width: 100%; height: auto; display: block; object-fit: cover; aspect-ratio: 4/5;">
                        <source src="<?php echo BASE_URL; ?>/assets/y2k_video.mp4" type="video/mp4">
                    </video>
                </div>

                <!-- Bag Image Overlapping (Replaces the Pants) -->
                <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); z-index: 2; width: 45%;">
                    <img src="<?php echo BASE_URL; ?>/assets/streetwear_bag.png" alt="Genrage Bag" style="width: 100%; height: auto; object-fit: contain; filter: drop-shadow(-10px 15px 25px rgba(0,0,0,0.3));">
                </div>
            </div>

            <!-- Text Content -->
            <div style="width: 100%; max-width: 600px; margin: 0 auto; text-align: left;">
                <h2 style="font-size: 1.4rem; font-weight: 500; letter-spacing: 2px; color: #1a1a1a; margin-bottom: 1.5rem; text-transform: uppercase; border-bottom: 1px solid #1a1a1a; display: inline-block; padding-bottom: 4px;">
                    JEVANI BAGS
                </h2>
                
                <p style="font-size: 1rem; line-height: 1.8; color: #222; font-weight: 400; margin-bottom: 0;">
                   Crafted for the modern muse, JEVANI redefines everyday luxury through bold design and timeless attitude.
Each bag is thoughtfully created with premium materials, refined details, and a distinctive Y2K-inspired aesthetic, delivering the perfect balance of fashion and function.
Designed to elevate every outfit and every occasion, JEVANI is more than an accessory—it's a statement of confidence, individuality, and style.
Luxury with attitude. Designed for the unforgettable. ✦
                </p>
            </div>
            
        </div>
    </section>

    


    <section class="section" style="padding: 0; background: #000; position: relative;">
        <div style="width: 100%; height: 80vh; min-height: 600px; overflow: hidden; position: relative; display: flex; justify-content: center; align-items: center;">
            <img src="<?php echo BASE_URL; ?>/assets/campaign_lifestyle.jpg" alt="Lifestyle Campaign" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5;">
            <!-- <div style="position: absolute; text-align: center; color: #fff; z-index: 2; width: 100%; padding: 0 20px;">
                <h2 class="font-serif" style="font-size: clamp(3rem, 8vw, 6rem); font-style: italic; font-weight: 400; margin-bottom: 30px; line-height: 1;">ARCHITECTURAL<br>ELEGANCE</h2>
                <a href="<?php echo BASE_URL; ?>/shop.php" class="btn" style="padding: 18px 40px; letter-spacing: 2px;">DISCOVER THE COLLECTION</a>
            </div> -->
        </div>
    </section>

    <!-- 8. About Us -->
    <section class="section" style="background: var(--bg-primary); padding: 8rem 0; border-top: 1px solid rgba(255, 255, 255, 0.05);">
        <div class="container" style="max-width: 800px; text-align: center;">
            <h2 style="font-family: var(--font); font-size: 0.9rem; letter-spacing: 4px; color: var(--accent); margin-bottom: 2.5rem; font-weight: 600; text-transform: uppercase;">ABOUT US</h2>
            <p style="font-family: var(--font); font-size: clamp(1.1rem, 2.5vw, 1.4rem); line-height: 1.8; color: var(--text-primary); font-weight: 300; letter-spacing: 0.5px; margin-bottom: 3rem;">
                Jevani is a modern Y2K-inspired fashion brand focused on creating stylish, trend-driven bags and accessories for fashion-conscious women. The brand blends nostalgic early-2000s aesthetics with contemporary luxury, offering statement pieces that are both fashionable and functional.
            </p>
            <a href="<?php echo BASE_URL; ?>/about.php" style="font-family: var(--font); text-decoration: underline; text-underline-offset: 6px; font-weight: 600; text-transform: uppercase; letter-spacing: 2.5px; color: var(--text-primary); font-size: 0.85rem; transition: color 0.3s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-primary)'">Learn More</a>
        </div>
    </section>

    <!-- 9. Customer Reviews Section -->
    <section class="reviews-section" style="background: var(--bg-secondary); color: var(--text-primary); padding: 6rem 0; font-family: var(--font); text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05); border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
        <div class="container" style="max-width: 600px; padding: 0 20px; position: relative;">
            
            <h2 style="font-size: 1.8rem; font-weight: 600; margin-bottom: 8px; color: #fff; font-family: var(--font); letter-spacing: 1.5px; text-transform: uppercase;">Let JEVANIS' speak for us</h2>
            
            <!-- Global Rating -->
            <div style="display: flex; flex-direction: column; align-items: center; gap: 4px; margin-bottom: 2.5rem;">
                <div style="color: var(--accent); font-size: 1.2rem; letter-spacing: 2px;">★★★★★</div>
                <div style="font-size: 0.85rem; color: var(--text-secondary); display: flex; align-items: center; gap: 6px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">
                    from 1,420 reviews 
                    <span style="display: inline-flex; align-items: center; justify-content: center; background: #009688; color: #fff; width: 14px; height: 14px; border-radius: 50%; font-size: 8px; font-weight: 700;">✓</span>
                </div>
            </div>

            <!-- Review Card Container (Slider) -->
            <div class="review-slider" style="position: relative; min-height: 250px;">
                
                <?php
                $reviews_list = [
                    [
                        'stars' => '★★★★★',
                        'title' => 'This Is for the Looks',
                        'body' => 'The quality is amazing and exactly what I was looking for.',
                        'author' => 'Ankit Sharma',
                        'image' => 'assets/bag_mini.png'
                    ],
                    [
                        'stars' => '★★★★★',
                        'title' => 'Obsessed with the hardware',
                        'body' => 'Super heavy, robust hardware. Perfect for daily streetwear styling.',
                        'author' => 'Rohan Sen',
                        'image' => 'assets/bag_shoulder.png'
                    ],
                    [
                        'stars' => '★★★★★',
                        'title' => 'Instantly elevated my fits',
                        'body' => 'This Y2K baguette bag is exactly what my closet was missing. Love it!',
                        'author' => 'Priya S.',
                        'image' => 'assets/bag_tote.png'
                    ]
                ];
                foreach ($reviews_list as $idx => $rev):
                ?>
                <div class="review-slide" id="review-slide-<?php echo $idx; ?>" style="display: <?php echo $idx === 0 ? 'block' : 'none'; ?>; transition: opacity 0.4s ease;">
                    <div style="color: var(--accent); font-size: 1.1rem; letter-spacing: 2px; margin-bottom: 8px;">★★★★★</div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 8px; color: #fff; font-family: var(--font); text-transform: uppercase; letter-spacing: 1px;"><?php echo htmlspecialchars($rev['title']); ?></h3>
                    <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary); max-width: 450px; margin: 0 auto 1.5rem; font-weight: 350; font-family: var(--font);"><?php echo htmlspecialchars($rev['body']); ?></p>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.2rem; font-weight: 500; font-family: var(--font); text-transform: uppercase; letter-spacing: 1px;"><?php echo htmlspecialchars($rev['author']); ?></div>
                    
                    <!-- Product Image Link -->
                    <div style="display: inline-block; margin-bottom: 1.5rem;">
                        <img src="<?php echo BASE_URL . '/' . $rev['image']; ?>" alt="Product" style="height: 60px; width: auto; object-fit: contain; background: rgba(255,255,255,0.02); padding: 5px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.08);">
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Navigation Arrows -->
                <div style="display: flex; justify-content: center; gap: 24px; align-items: center; margin-top: 1rem;">
                    <button onclick="prevReview()" style="background: none; border: none; font-size: 2.5rem; color: var(--text-secondary); cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-secondary)'">‹</button>
                    <button onclick="nextReview()" style="background: none; border: none; font-size: 2.5rem; color: var(--text-secondary); cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-secondary)'">›</button>
                </div>
            </div>
        </div>
    </section>

    <script>
        let currentReviewIdx = 0;
        const totalReviews = <?php echo count($reviews_list); ?>;
        
        function showReview(idx) {
            for (let i = 0; i < totalReviews; i++) {
                document.getElementById('review-slide-' + i).style.display = 'none';
            }
            document.getElementById('review-slide-' + idx).style.display = 'block';
        }
        
        function nextReview() {
            currentReviewIdx = (currentReviewIdx + 1) % totalReviews;
            showReview(currentReviewIdx);
        }
        
        function prevReview() {
            currentReviewIdx = (currentReviewIdx - 1 + totalReviews) % totalReviews;
            showReview(currentReviewIdx);
        }
    </script>


    <style>
    /* ═══════════════════════════════════════════
       INSTAGRAM / COMMUNITY SECTION
    ═══════════════════════════════════════════ */
    .ig-section {
        background: linear-gradient(160deg, #0a0a0a 0%, #111 60%, #1a1010 100%);
        padding: 5rem 0 0;
        overflow: hidden;
    }

    /* Header */
    .ig-header {
        text-align: center;
        padding: 0 20px 3.5rem;
    }
    .ig-handle-wrap {
        display: inline-flex;
        align-items: baseline;
        gap: 4px;
        margin-bottom: 1rem;
    }
    .ig-at {
        font-size: clamp(1.2rem, 4vw, 2rem);
        font-weight: 700;
        background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .ig-handle {
        font-size: clamp(1.6rem, 5vw, 3rem);
        font-weight: 800;
        letter-spacing: 3px;
        color: #fff;
        font-family: var(--font-serif);
        font-style: italic;
    }
    .ig-tagline {
        color: rgba(255,255,255,0.55);
        font-size: 1rem;
        line-height: 1.7;
        letter-spacing: 0.5px;
        margin-bottom: 2.5rem;
    }

    /* Stats */
    .ig-stats {
        display: inline-flex;
        align-items: center;
        gap: 0;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 60px;
        padding: 16px 32px;
        backdrop-filter: blur(10px);
    }
    .ig-stat { text-align: center; padding: 0 24px; }
    .ig-stat-num {
        display: block;
        font-size: 1.4rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        margin-bottom: 4px;
    }
    .ig-stat-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: rgba(255,255,255,0.4);
    }
    .ig-stat-divider {
        width: 1px;
        height: 36px;
        background: rgba(255,255,255,0.12);
        flex-shrink: 0;
    }

    /* Mosaic Grid */
    .ig-mosaic {
        display: grid;
        grid-template-columns: 1.8fr 1fr 1fr;
        gap: 6px;
        padding: 0 6px;
        max-height: 560px;
    }
    .ig-tile-col {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .ig-tile {
        position: relative;
        overflow: hidden;
        display: block;
        cursor: pointer;
        background: #1a1a1a;
    }
    .ig-tile--large {
        grid-row: span 1;
    }
    .ig-tile,
    .ig-tile-col .ig-tile {
        flex: 1;
        min-height: 0;
    }
    .ig-mosaic > .ig-tile--large {
        height: 100%;
    }
    .ig-tile-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
        transition: transform 0.7s cubic-bezier(0.16,1,0.3,1);
        background: #141414;
        mix-blend-mode: lighten;
        padding: 10px;
    }
    .ig-tile:hover .ig-tile-img {
        transform: scale(1.07);
    }
    .ig-tile-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(240,148,51,0.6), rgba(188,24,136,0.6));
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        opacity: 0;
        transition: opacity 0.4s ease;
        color: #fff;
    }
    .ig-tile-overlay svg { width: 32px; height: 32px; }
    .ig-tile-overlay span {
        font-size: 0.75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 600;
    }
    .ig-tile:hover .ig-tile-overlay { opacity: 1; }

    /* CTA */
    .ig-cta-wrap {
        text-align: center;
        padding: 3rem 20px;
    }
    .ig-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        color: #fff;
        text-decoration: none;
        padding: 16px 40px;
        border-radius: 60px;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.3s ease;
        box-shadow: 0 8px 30px rgba(220,39,67,0.35);
    }
    .ig-cta-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 45px rgba(220,39,67,0.5);
        opacity: 0.92;
    }
    .ig-cta-btn svg { width: 20px; height: 20px; }

    /* Hashtag Marquee */
    .ig-marquee-wrap {
        background: rgba(255,255,255,0.04);
        border-top: 1px solid rgba(255,255,255,0.08);
        padding: 18px 0;
        overflow: hidden;
        white-space: nowrap;
    }
    .ig-marquee {
        display: inline-block;
        animation: igMarquee 22s linear infinite;
        font-size: 0.75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.35);
        font-weight: 500;
    }
    @keyframes igMarquee {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .ig-section { padding: 3.5rem 0 0; }
        .ig-mosaic {
            grid-template-columns: 1fr 1fr;
            max-height: none;
            gap: 5px;
            padding: 0 5px;
        }
        /* Hide the large tile spanning behaviour, show all as equal */
        .ig-tile--large { grid-column: span 2; height: 220px; }
        .ig-tile-col { gap: 5px; }
        .ig-stats { padding: 14px 20px; }
        .ig-stat { padding: 0 16px; }
        .ig-stat-num { font-size: 1.1rem; }
        .ig-tagline { font-size: 0.9rem; }
    }
    @media (max-width: 480px) {
        .ig-header { padding: 0 16px 2.5rem; }
        .ig-mosaic {
            grid-template-columns: 1fr 1fr;
            gap: 4px;
            padding: 0 4px;
        }
        .ig-tile--large { height: 180px; }
        .ig-tile-col .ig-tile { height: 130px; }
        .ig-stats {
            flex-wrap: wrap;
            border-radius: 16px;
            padding: 16px;
            gap: 0;
        }
        .ig-stat { padding: 8px 12px; }
        .ig-stat-divider { display: none; }
        .ig-cta-btn { padding: 14px 28px; font-size: 0.8rem; }
        .ig-tile-overlay span { display: none; }
    }
    @media (max-width: 375px) {
        .ig-tile--large { height: 150px; }
        .ig-tile-col .ig-tile { height: 110px; }
    }
    </style>



<?php require_once 'includes/footer.php'; ?>
