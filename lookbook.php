<?php require_once 'includes/header.php'; ?>

<div class="lookbook-page-container">
    <!-- Lookbook Hero -->
    <section class="lookbook-hero">
        <img src="<?php echo BASE_URL; ?>/assets/lookbook_hero.png" alt="Editorial Archive" class="lb-hero-img">
        <div class="lb-hero-overlay"></div>
        <div class="lb-hero-content container">
            <h1 class="lb-title font-serif">Editorial Archive</h1>
            <p class="lb-subtitle">Curated aesthetics for the rebellious soul.</p>
        </div>
    </section>

    <!-- Editorial Grid -->
    <section class="section" style="padding: 5rem 0; background: var(--bg-primary);">
        <div class="container">
            <div class="lb-header">
                <span class="lb-kicker">VOLUME 01</span>
                <h2 class="lb-section-title">DARK LUXURY</h2>
            </div>
            
            <div class="editorial-grid">
                <!-- Large Editorial Tile 1 -->
                <div class="ed-tile ed-large">
                    <img src="<?php echo BASE_URL; ?>/assets/lookbook_edit_1.png" alt="Editorial 1">
                    <div class="ed-overlay">
                        <span class="ed-tag">CYBERPUNK ESSENTIALS</span>
                        <a href="shop.php?category=1" class="ed-btn">Shop The Vibe</a>
                    </div>
                </div>

                <!-- Product Tiles from DB -->
                <?php
                // Get 2 product images
                $images_query = $conn->query("
                    SELECT DISTINCT image AS img, id FROM products WHERE image IS NOT NULL AND image != '' LIMIT 2
                ");
                while ($row = $images_query->fetch_assoc()) {
                    ?>
                    <div class="ed-tile ed-small">
                        <img src="<?php echo BASE_URL . '/assets/' . htmlspecialchars($row['img']); ?>" alt="Product">
                        <div class="ed-overlay" style="align-items: center; justify-content: center; padding: 0;">
                            <a href="product.php?id=<?php echo $row['id']; ?>" class="ed-btn-small"><i data-lucide="arrow-right"></i></a>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <!-- Wide Editorial Tile 2 -->
                <div class="ed-tile ed-wide">
                    <img src="<?php echo BASE_URL; ?>/assets/lookbook_edit_2.png" alt="Editorial 2">
                    <div class="ed-overlay">
                        <span class="ed-tag">MINIMALIST REBELLION</span>
                        <a href="shop.php?category=2" class="ed-btn">Shop The Vibe</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Masonry Grid for rest of products -->
    <section class="section" style="padding: 2rem 0 6rem; background: var(--bg-secondary);">
        <div class="container">
            <div class="lb-header" style="text-align: center; margin-bottom: 4rem;">
                <h2 class="lb-section-title" style="font-size: 2rem;">THE ARCHIVE</h2>
            </div>
            <div class="masonry-grid" id="lb-masonry">
                <?php
                $images_query = $conn->query("
                    SELECT DISTINCT image_path AS img FROM product_images WHERE image_path IS NOT NULL AND image_path != ''
                    UNION
                    SELECT DISTINCT image AS img FROM products WHERE image IS NOT NULL AND image != ''
                    UNION
                    SELECT DISTINCT image2 AS img FROM products WHERE image2 IS NOT NULL AND image2 != ''
                    LIMIT 15
                ");
                
                if ($images_query && $images_query->num_rows > 0) {
                    while ($row = $images_query->fetch_assoc()) {
                        $imgSrc = BASE_URL . '/assets/' . htmlspecialchars($row['img']);
                        ?>
                        <div class="masonry-item y2k-fade-in">
                            <img src="<?php echo $imgSrc; ?>" alt="Archive Image" loading="lazy">
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Custom Styles for Lookbook -->
    <style>
        /* Lookbook Hero */
        .lookbook-hero {
            position: relative;
            height: 70vh;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-top: -85px; /* Overlap header */
        }
        .lb-hero-img {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
            z-index: 0;
            filter: brightness(0.85);
        }
        .lb-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.1) 40%, rgba(0,0,0,0.9) 100%);
            z-index: 1;
        }
        .lb-hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding-top: 85px;
        }
        .lb-title {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 400;
            font-style: italic;
            color: #fff;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }
        .lb-subtitle {
            font-size: 0.9rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
        }

        /* Editorial Headers */
        .lb-header {
            margin-bottom: 3rem;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .lb-kicker {
            font-size: 0.75rem;
            letter-spacing: 4px;
            color: var(--text-secondary);
            text-transform: uppercase;
        }
        .lb-section-title {
            font-family: var(--font-serif);
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 400;
            font-style: italic;
            color: var(--text-primary);
            line-height: 1.1;
        }

        /* Editorial Grid Layout */
        .editorial-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: 300px;
            gap: 20px;
        }
        .ed-tile {
            position: relative;
            overflow: hidden;
            background: #111;
        }
        .ed-tile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .ed-tile:hover img {
            transform: scale(1.05);
        }
        .ed-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent 50%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 30px;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .ed-tile:hover .ed-overlay {
            opacity: 1;
        }
        .ed-tag {
            font-size: 0.8rem;
            letter-spacing: 2px;
            color: #fff;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .ed-btn {
            align-self: flex-start;
            padding: 12px 24px;
            background: #fff;
            color: #000;
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .ed-btn:hover { background: #e0e0e0; }
        
        .ed-btn-small {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: transform 0.3s;
        }
        .ed-btn-small:hover { transform: scale(1.1); }
        .ed-btn-small svg { width: 20px; height: 20px; }

        /* Grid specific sizing */
        .ed-large { grid-column: span 2; grid-row: span 2; }
        .ed-wide { grid-column: span 2; grid-row: span 1; }
        .ed-small { grid-column: span 1; grid-row: span 1; }

        /* Grid Layout for Lookbook Archive */
        #lb-masonry {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        #lb-masonry .masonry-item {
            overflow: hidden;
            background: var(--bg-secondary);
            aspect-ratio: 1 / 1;
        }
        #lb-masonry img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), filter 0.4s;
        }
        #lb-masonry .masonry-item:hover img {
            transform: scale(1.05);
            filter: brightness(0.9);
        }

        /* Touch screen accessibility */
        @media (hover: none) {
            .ed-overlay {
                opacity: 1 !important;
                background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.3) 60%, transparent 100%) !important;
            }
        }
    </style>

</div>

<?php require_once 'includes/footer.php'; ?>
