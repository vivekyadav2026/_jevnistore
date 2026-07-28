<?php require_once 'includes/header.php'; ?>

<style>
/* Universal Box-Sizing & Reset */
.about-page-wrapper,
.about-page-wrapper * {
    box-sizing: border-box !important;
}

.about-page-wrapper {
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
    .about-page-wrapper {
        padding-top: 95px !important;
        padding-bottom: 70px !important;
    }
}
@media (max-width: 768px) {
    .about-page-wrapper {
        padding-top: 80px !important;
        padding-bottom: 60px !important;
    }
}

.about-container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 16px;
    width: 100%;
}
@media (max-width: 480px) {
    .about-container {
        padding: 0 12px;
    }
}

/* Header Box */
.about-header-box {
    margin-bottom: 32px;
    text-align: center;
}
.about-breadcrumbs {
    font-size: 0.7rem;
    color: #555555;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 700;
}
.about-breadcrumbs a {
    color: #555555;
    text-decoration: none;
}
.about-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1a1a1a !important;
    margin: 0 0 8px 0;
    font-family: var(--font-primary, sans-serif);
    letter-spacing: 2.5px;
    text-transform: uppercase;
}
@media (max-width: 480px) {
    .about-title {
        font-size: 1.6rem;
    }
}
.about-subtitle {
    color: #555555 !important;
    font-size: 0.85rem;
    letter-spacing: 1px;
    max-width: 650px;
    margin: 0 auto;
    line-height: 1.6;
    font-style: italic;
    font-weight: 600;
}

/* Luxury Quote Banner Card */
.quote-banner-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 18px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    margin-bottom: 32px;
}
@media (max-width: 600px) {
    .quote-banner-card {
        padding: 24px 16px;
        border-radius: 14px;
    }
}
.quote-text {
    font-size: 1.25rem;
    line-height: 1.6;
    font-weight: 700;
    color: #1a1a1a;
    letter-spacing: 0.5px;
    margin: 0;
}
@media (max-width: 600px) {
    .quote-text {
        font-size: 1rem;
    }
}

/* Feature 2-Col Grid */
.about-grid-2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 32px;
}
@media (max-width: 768px) {
    .about-grid-2col {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

.feature-info-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    padding: 28px 24px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}
@media (max-width: 480px) {
    .feature-info-card {
        padding: 20px 16px;
    }
}
.feature-card-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #1a1a1a;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    border: none;
}
.feature-card-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: #1a1a1a;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 10px;
}
.feature-card-desc {
    color: #555555;
    font-size: 0.85rem;
    line-height: 1.6;
    margin: 0;
}

/* Product Showcase Grid */
.showcase-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 40px;
}
@media (max-width: 600px) {
    .showcase-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }
}

.showcase-item-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}
.showcase-img {
    width: 100%;
    max-width: 260px;
    height: auto;
    object-fit: contain;
    transition: transform 0.3s ease;
}
.showcase-item-card:hover .showcase-img {
    transform: scale(1.05);
}

/* Call to Action */
.cta-box {
    text-align: center;
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    padding: 36px 20px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
}
.cta-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1a1a1a;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 16px;
}
.cta-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #1a1a1a;
    color: #ffffff;
    border: 1.5px solid #1a1a1a;
    padding: 14px 36px;
    font-weight: 700;
    font-size: 0.78rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    border-radius: 99px;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.cta-btn-primary:hover {
    background: #000000;
    color: #ffffff;
}
</style>

<div class="about-page-wrapper">
    <div class="about-container">
        
        <!-- Header -->
        <div class="about-header-box">
            <div class="about-breadcrumbs">
                <a href="<?php echo BASE_URL; ?>/index.php">HOME</a>
                <span>/</span>
                <span style="color: #1a1a1a; font-weight: 700;">ABOUT US</span>
            </div>
            <h1 class="about-title">OUR BRAND STORY</h1>
            <p class="about-subtitle">Redefining modern everyday carry with Y2K statement silhouettes and luxury craftsmanship.</p>
        </div>

        <!-- Quote Banner -->
        <div class="quote-banner-card">
            <p class="quote-text">
                "Nørva was born out of a desire to create statement pieces for a generation that refuses to blend in. We don't just make bags — we design the anchor to your aesthetic."
            </p>
        </div>

        <!-- 2-Column Features -->
        <div class="about-grid-2col">
            
            <div class="feature-info-card">
                <div class="feature-card-icon">
                    <i data-lucide="sparkles" style="width: 22px; height: 22px;"></i>
                </div>
                <h3 class="feature-card-title">The Y2K Silhouette</h3>
                <p class="feature-card-desc">
                    Drawing inspiration from early 2000s nostalgia, our silhouettes feature sharp geometric angles, micro-proportions, and striking metallic hardware. We blend vintage futuristic elements with modern street style to create timeless staples.
                </p>
            </div>

            <div class="feature-info-card">
                <div class="feature-card-icon">
                    <i data-lucide="award" style="width: 22px; height: 22px;"></i>
                </div>
                <h3 class="feature-card-title">Luxury Craftsmanship</h3>
                <p class="feature-card-desc">
                    Every Nørva accessory is constructed using high-grade eco-conscious vegan leather, custom-molded metallic hardware, and architectural reinforced stitching to ensure durability for everyday wear.
                </p>
            </div>

        </div>


        <!-- Showcase Grid -->
        <div class="showcase-grid">
            <div class="showcase-item-card">
                <img src="<?php echo BASE_URL; ?>/assets/6a61214f54bd3_H0588898196e74c75972f4e6f1f45f67e0-removebg-preview.png" alt="Nørva Mini Bag" class="showcase-img">
            </div>
            <div class="showcase-item-card">
                <img src="<?php echo BASE_URL; ?>/assets/6a61214f56ac5_H7a493d0c699c4dfea39d97875cbc3df7h.avif" alt="Nørva Shoulder Bag" class="showcase-img">
            </div>
        </div>

        <!-- CTA Box -->
        <div class="cta-box">
            <div class="cta-title">EXPLORE THE LATEST DROPS</div>
            <a href="<?php echo BASE_URL; ?>/shop.php" class="cta-btn-primary">
                <i data-lucide="shopping-bag" style="width: 16px; height: 16px;"></i>
                SHOP THE COLLECTION
            </a>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
