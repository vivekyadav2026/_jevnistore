<?php require_once 'includes/header.php'; ?>

<style>
    :root {
        --bg-tan: #d6d3d1; /* For the drop section and general background */
        --text-dark: #1a1a1a;
    }

    body {
        background-color: var(--text-dark); /* Page is generally dark or sections define it */
        color: var(--text-dark);
        font-family: 'Inter', -apple-system, sans-serif;
    }

    /* 1. Hero Full Width Image */
    .hero-main {
        width: 100%;
        background-color: #000;
        display: flex;
    }
    .hero-main img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    /* 2. Products Row Section (THE DROP +) */
    .products-row-section {
        background-color: var(--bg-tan);
        padding: 40px 20px;
    }
    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
    }
    .products-header a {
        text-decoration: none;
        color: var(--text-dark);
        border-bottom: 1px solid var(--text-dark);
        padding-bottom: 2px;
    }
    .products-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 15px;
        max-width: 1400px;
        margin: 0 auto;
    }
    .product-card-min {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .product-img-box {
        background: #fff;
        width: 100%;
        aspect-ratio: 1/1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .product-img-box img {
        width: 80%;
        height: 80%;
        object-fit: contain;
    }
    .product-min-title {
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 4px;
        letter-spacing: 0.5px;
    }
    .product-min-price {
        font-size: 0.6rem;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .add-btn-small {
        width: 28px;
        height: 28px;
        border: 1px solid var(--text-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        cursor: pointer;
        transition: 0.3s;
    }
    .add-btn-small:hover {
        background: var(--text-dark);
        color: #fff;
    }
    .add-btn-small svg {
        width: 14px;
        height: 14px;
    }

    /* 3. Alleyway Girl Section */
    .alleyway-section {
        width: 100%;
        background-color: #000;
    }
    .alleyway-section img {
        width: 100%;
        height: auto;
        display: block;
    }

    /* 4. Icon Infographic Section */
    .icon-infographic {
        background-color: #e3ded7;
        width: 100%;
        padding: 60px 20px;
        position: relative;
        overflow: hidden;
        color: #1a1a1a;
        font-family: 'Inter', sans-serif;
    }
    .info-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 30px;
        position: relative;
    }
    .info-handwriting {
        font-family: 'Caveat', cursive;
        font-size: 1.8rem;
        color: #333;
        transform: rotate(-3deg);
    }
    .info-header {
        grid-column: 1 / 2;
    }
    .info-title {
        font-family: 'Impact', 'Inter', sans-serif;
        font-size: 4rem;
        line-height: 0.9;
        text-transform: uppercase;
        margin-bottom: 20px;
        color: #111;
    }
    .info-subtitle {
        font-family: monospace;
        font-size: 0.8rem;
        line-height: 1.6;
        color: #444;
        max-width: 280px;
    }
    .info-center-bag {
        grid-column: 2 / 3;
        grid-row: 1 / 3;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .info-bag-img {
        width: 100%;
        max-width: 450px;
        filter: drop-shadow(0 20px 30px rgba(0,0,0,0.3));
    }
    .info-measurements {
        display: flex;
        justify-content: space-between;
        width: 100%;
        max-width: 380px;
        font-size: 0.7rem;
        color: #666;
        border-top: 1px dashed #999;
        padding-top: 5px;
        margin-top: 10px;
    }
    .info-made-for {
        grid-column: 3 / 4;
        padding-left: 20px;
    }
    .info-made-for h4 {
        font-size: 0.9rem;
        font-weight: 800;
        margin-bottom: 15px;
        text-transform: uppercase;
    }
    .info-made-for ul {
        list-style: none;
        padding: 0;
    }
    .info-made-for li {
        font-size: 0.8rem;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-transform: uppercase;
        font-weight: 600;
    }
    .info-made-for li i {
        color: #666;
    }
    
    .polaroid {
        background: #fff;
        padding: 10px 10px 35px 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        position: absolute;
        width: 180px;
        transform: rotate(4deg);
        z-index: 10;
    }
    .polaroid img {
        width: 100%;
        aspect-ratio: 4/5;
        object-fit: cover;
    }
    .polaroid.p-left {
        left: -40px;
        top: 250px;
        transform: rotate(-6deg);
    }
    .polaroid.p-right {
        right: 0px;
        top: 80px;
        transform: rotate(3deg);
    }
    
    .info-bottom-row {
        grid-column: 1 / -1;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-top: 40px;
    }
    .info-box {
        border: 1.5px solid #1a1a1a;
        padding: 20px;
        background: transparent;
        position: relative;
    }
    .info-box-label {
        position: absolute;
        top: -12px;
        left: 20px;
        background: #1a1a1a;
        color: #fff;
        font-size: 0.6rem;
        padding: 4px 8px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .fits-icons {
        display: flex;
        justify-content: space-around;
        align-items: flex-end;
        padding-top: 15px;
        gap: 10px;
        flex-wrap: wrap;
    }
    .fit-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    .fit-item i {
        width: 32px;
        height: 32px;
        color: #333;
    }
    .fit-item span {
        font-size: 0.55rem;
        text-transform: uppercase;
        font-weight: 700;
        text-align: center;
    }
    
    .info-details-row {
        grid-column: 1 / -1;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-top: 20px;
    }
    .detail-card {
        border: 1px solid #1a1a1a;
        position: relative;
    }
    .detail-card img {
        width: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
        display: block;
    }
    .detail-label {
        position: absolute;
        top: 0;
        left: 0;
        background: #1a1a1a;
        color: #fff;
        font-size: 0.5rem;
        padding: 4px 6px;
        font-weight: 700;
        text-transform: uppercase;
    }

    @media(max-width: 900px) {
        .info-container { grid-template-columns: 1fr; }
        .info-header { grid-column: 1 / -1; text-align: center; }
        .info-subtitle { margin: 0 auto; }
        .info-center-bag { grid-column: 1 / -1; grid-row: auto; }
        .info-made-for { grid-column: 1 / -1; padding-left: 0; display: flex; flex-direction: column; align-items: center; }
        .polaroid { display: none; }
        .info-bottom-row { grid-template-columns: 1fr; }
        .info-details-row { grid-template-columns: repeat(2, 1fr); }
    }



    /* 5. Lookbook Section (Signature Bag) */
    .lookbook-section {
        background: linear-gradient(135deg, #d3ccc5 0%, #c1b9b1 100%);
        padding: 60px 20px 40px 20px;
    }
    .lookbook-container {
        max-width: 800px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .lookbook-images {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 20px;
        width: 100%;
        margin-bottom: 40px; 
    }
    .lookbook-main-img {
        width: 65%;
        aspect-ratio: 3/4;
        display: block;
        object-fit: cover;
        object-position: top center;
    }
    .lookbook-sub-img {
        width: 30%;
        height: auto;
        display: block;
        filter: drop-shadow(-10px 15px 20px rgba(0,0,0,0.15));
    }
    .lookbook-sub-img:hover {
        transform: scale(1.05);
    }
    .lookbook-text-area {
        width: 65%; /* Matches the width of the main image perfectly */
        text-align: left;
    }
    .lookbook-title {
        font-family: 'Inter', sans-serif;
        font-size: 1.5rem;
        font-weight: 400;
        letter-spacing: 5px;
        color: #222;
        text-transform: uppercase;
        border-bottom: 1px solid #444;
        display: block; /* Make it a block so border goes across or fits content */
        padding-bottom: 5px;
        margin-bottom: 15px;
        width: max-content; /* Border only under text */
    }
    .lookbook-desc {
        font-family: 'Inter', sans-serif;
        font-size: 0.75rem;
        line-height: 1.8;
        color: #333;
        font-weight: 500;
        margin: 0;
    }
    .lookbook-divider {
        position: relative;
        width: 100%;
        margin: 40px 0 10px 0;
        border-top: 1px solid rgba(0,0,0,0.15);
    }
    .lookbook-divider::after {
        content: "▼";
        position: absolute;
        top: -9px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        color: #111;
        background-color: transparent; /* No need to mask since we have a gradient now, but let's use a soft mask */
        text-shadow: 0 0 10px #c1b9b1, 0 0 10px #c1b9b1; /* Pseudo-mask for gradient */
        padding: 0 8px;
    }

    /* Details That Inspire CSS Removed */

    /* 7. Ragers Video Section */
    .ragers-section {
        position: relative;
        width: 100%;
        display: flex;
        flex-direction: column;
    }
    .ragers-video-container {
        width: 100%;
        height: 60vh;
        overflow: hidden;
    }
    .ragers-video-container video, .ragers-video-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .ragers-content {
        background: var(--bg-tan);
        padding: 60px 20px;
        text-align: center;
        width: 100%;
    }
    .ragers-title {
        font-size: 1.6rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .ragers-stars {
        color: #22c55e;
        font-size: 1.4rem;
        letter-spacing: 4px;
        margin-bottom: 5px;
    }
    .ragers-count {
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        margin-bottom: 40px;
    }
    .ragers-count svg {
        width: 16px;
        height: 16px;
        color: #0ea5e9;
    }
    .ragers-review-title {
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .ragers-review-text {
        font-size: 0.85rem;
        line-height: 1.8;
        color: #333;
        max-width: 600px;
        margin: 0 auto 20px;
        font-weight: 500;
    }
    .ragers-author {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 30px;
    }
    .ragers-prod-img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        margin: 0 auto;
        display: block;
    }

    @media (max-width: 1024px) {
        .products-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .products-grid { grid-template-columns: repeat(2, 1fr); }
        .lookbook-images { display: flex; align-items: center; justify-content: flex-start; gap: 10px; width: 100%; margin: 0 auto 25px auto; }
        .lookbook-main-img { width: 65%; margin-bottom: 0px; }
        .lookbook-sub-img { width: 35%; margin-bottom: 0px; position: static; }
        .lookbook-text-area { width: 100%; margin: 0; text-align: left; }
        .lookbook-title { margin: 0 0 15px 0; display: inline-block; width: max-content; }
        .lookbook-desc { text-align: left; }
        .lookbook-divider { margin-left: 0; margin-right: 0; }
    }
</style>

<!-- 1. Hero Main Banner -->
<section class="hero-main">
    <picture style="width: 100%;">
        <source media="(max-width: 768px)" srcset="<?php echo BASE_URL; ?>/assets/hero_mobile.jpg">
        <source media="(min-width: 769px)" srcset="<?php echo BASE_URL; ?>/assets/hero_banner_model.png">
        <img src="<?php echo BASE_URL; ?>/assets/hero_banner_model.png" alt="JEVANI Campaign" style="width: 100%; height: auto; object-fit: cover;">
    </picture>
</section>

<!-- 2. Products Row (THE DROP +) -->
<section class="products-row-section">
    <div class="products-header">
        <div>THE DROP +</div>
        <a href="<?php echo BASE_URL; ?>/shop.php">VIEW ALL <i data-lucide="arrow-right" style="width:12px; height:12px; margin-left:4px;"></i></a>
    </div>
    
    <div class="products-grid">
        <?php
        // Fetch 6 products to match the screenshot
        $na_stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT 6");
        $na_stmt->execute();
        $na_result = $na_stmt->get_result();
        while ($product = $na_result->fetch_assoc()) {
            $image = $product['image'] ? BASE_URL . '/assets/' . htmlspecialchars($product['image']) : BASE_URL . '/assets/bag_shoulder.png';
            ?>
            <div class="product-card-min">
                <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $product['id']; ?>" class="product-img-box">
                    <img src="<?php echo $image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </a>
                <div class="product-min-title"><?php echo htmlspecialchars($product['name']); ?></div>
                <div class="product-min-price">₹<?php echo number_format($product['price']); ?></div>
                <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" class="ajax-cart-form">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
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
</section>

<!-- 3. Alleyway Girl Section -->
<section class="alleyway-section">
    <img src="<?php echo BASE_URL; ?>/assets/alleyway_model.png" alt="Editorial Look">
</section>

<!-- 4. Icon Infographic Section -->
<section class="icon-infographic">
    <div class="info-container">
        <!-- Decorative Text -->
        <div style="position: absolute; top: 10px; right: 28%; z-index: 20;" class="info-handwriting">
            <div style="border: 1px solid #333; border-radius: 50%; padding: 25px 35px; text-align: center; background: rgba(255,255,255,0.2);">
                The best Y2K<br>shoulder bag for<br>laptops & other<br>essentials
            </div>
        </div>

        <div class="info-header">
            <h2 class="info-title">THE ICON.<br>YOUR EVERYDAY.</h2>
            <p class="info-subtitle">
                The ultimate Y2K shoulder bag designed to carry your world. Laptop, essentials, and attitude.
            </p>
        </div>

        <div class="info-center-bag">
            <img src="<?php echo BASE_URL; ?>/assets/bag_shoulder.png" alt="The Icon Bag" class="info-bag-img">
            <div class="info-measurements">
                <span>← 42CM →</span>
                <span>↑ 28CM ↓</span>
                <span>↗ 15CM ↙</span>
            </div>
        </div>

        <div class="info-made-for">
            <h4>Made For:</h4>
            <ul>
                <li><i data-lucide="check-circle-2" style="width:16px; height:16px;"></i> Laptops up to 15"</li>
                <li><i data-lucide="check-circle-2" style="width:16px; height:16px;"></i> Daily essentials</li>
                <li><i data-lucide="check-circle-2" style="width:16px; height:16px;"></i> Books & Notebooks</li>
                <li><i data-lucide="check-circle-2" style="width:16px; height:16px;"></i> Wallets & Pouches</li>
                <li><i data-lucide="check-circle-2" style="width:16px; height:16px;"></i> Gym Gear & More</li>
            </ul>
        </div>

        <!-- Polaroids -->
        <div class="polaroid p-left">
            <img src="<?php echo BASE_URL; ?>/assets/campaign_lifestyle.jpg" alt="Model">
            <div class="info-handwriting" style="text-align:center; font-size:1.4rem; margin-top:10px; color:#555;">chaotic life<br>iconic bag</div>
        </div>
        <div class="polaroid p-right">
            <img src="<?php echo BASE_URL; ?>/assets/lookbook_edit_2.png" alt="Model">
            <div class="info-handwriting" style="text-align:center; font-size:1.4rem; margin-top:10px; color:#555;">your go to<br>every damn day</div>
        </div>

        <div class="info-bottom-row">
            <div class="info-box">
                <div class="info-box-label">FITS YOUR WORLD</div>
                <div class="fits-icons">
                    <div class="fit-item"><i data-lucide="laptop"></i><span>Laptop<br>up to 15"</span></div>
                    <div class="fit-item"><i data-lucide="cup-soda"></i><span>Water<br>Bottle</span></div>
                    <div class="fit-item"><i data-lucide="book"></i><span>Notebooks</span></div>
                    <div class="fit-item"><i data-lucide="headphones"></i><span>Headphones</span></div>
                    <div class="fit-item"><i data-lucide="briefcase"></i><span>Makeup<br>Pouch</span></div>
                    <div class="fit-item"><i data-lucide="wallet"></i><span>Wallet</span></div>
                    <div class="fit-item"><i data-lucide="smartphone"></i><span>Phone</span></div>
                    <div class="fit-item"><i data-lucide="glasses"></i><span>Sunglasses</span></div>
                </div>
            </div>
            <div class="info-box">
                <div class="info-box-label">SPACIOUS & FUNCTIONAL</div>
                <img src="<?php echo BASE_URL; ?>/assets/hero_banner_bags.png" style="width:100%; height:80px; object-fit:cover; margin-bottom:10px; filter:grayscale(30%);" alt="Inside Bag">
                <p style="font-size:0.7rem; color:#444; margin:0; font-family:monospace;">Everything has its place.<br>You have everything you need.</p>
            </div>
        </div>

        <div class="info-details-row">
            <div class="detail-card">
                <div class="detail-label">PREMIUM SUEDE FINISH</div>
                <img src="<?php echo BASE_URL; ?>/assets/craft_machine.jpg" alt="Suede">
            </div>
            <div class="detail-card">
                <div class="detail-label">SIGNATURE HARDWARE</div>
                <img src="<?php echo BASE_URL; ?>/assets/bags/acc_2.jpeg" alt="Hardware">
            </div>
            <div class="detail-card">
                <div class="detail-label">SECURE ZIP CLOSURE</div>
                <img src="<?php echo BASE_URL; ?>/assets/craft_workshop.jpg" alt="Zip">
            </div>
            <div class="detail-card">
                <div class="detail-label">STRONG SHOULDER STRAPS</div>
                <img src="<?php echo BASE_URL; ?>/assets/lookbook_hero.png" alt="Straps">
            </div>
        </div>

    </div>
</section>

<!-- 5. Lookbook Section (Signature Bag) -->
<section class="lookbook-section">
    <div class="lookbook-container">
        <div class="lookbook-images">
            <img src="<?php echo BASE_URL; ?>/assets/model_with_bag.png" alt="Model with Bag" class="lookbook-main-img" onerror="this.src='<?php echo BASE_URL; ?>/assets/hero_mobile.jpg';">
            <img src="<?php echo BASE_URL; ?>/assets/bag_tote.png" alt="Signature Bag" class="lookbook-sub-img">
        </div>
        <div class="lookbook-text-area">
            <h2 class="lookbook-title">SIGNATURE BAG</h2>
            <p class="lookbook-desc">
                Every stitch carries purpose, every detail reflects power,<br>
                every wear defines endurance.<br>
                Subtle confidence meets precision design, offering lasting<br>
                comfort, movement, and presence in every moment you<br>
                choose to carry it.
            </p>
        </div>
        <div class="lookbook-divider"></div>
    </div>
</section>

<!-- 7. Let Ragers Speak -->
<section class="ragers-section">
    <div class="ragers-video-container">
        <!-- Assuming y2k_video.mp4 is the background video -->
        <video autoplay loop muted playsinline>
            <source src="<?php echo BASE_URL; ?>/assets/y2k_video.mp4" type="video/mp4">
        </video>
    </div>
    
    <div class="ragers-content">
        <h2 class="ragers-title">Let RAGERS' speak for us</h2>
        
        <div class="ragers-stars">★★★★★</div>
        <div class="ragers-count">
            from 3649 reviews 
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        
        <div class="ragers-stars" style="font-size: 1.2rem; margin-bottom: 15px;">★★★★★</div>
        <div class="ragers-review-title">slay check passed with full marks</div>
        <div class="ragers-review-text">
            bindass hai yaar, fabric se pata chalta hai premium hai design looks even better in person than the product photos corduroy texture is so tactile and premium feeling will be a loyal customer from her...
        </div>
        <div class="ragers-author">Lavanya Gupta</div>
        
        <img src="<?php echo BASE_URL; ?>/assets/product_tshirt.png" alt="Purchased Product" class="ragers-prod-img" onerror="this.src='<?php echo BASE_URL; ?>/assets/bag_mini.png';">
        
        <div style="display: flex; justify-content: center; gap: 25px; margin-top: 25px;">
            <i data-lucide="chevron-left" style="width: 24px; height: 24px; color: #888; cursor: pointer;"></i>
            <i data-lucide="chevron-right" style="width: 24px; height: 24px; color: #888; cursor: pointer;"></i>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
