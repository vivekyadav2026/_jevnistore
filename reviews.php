<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 
?>

<style>
/* Universal Box-Sizing & Reset */
.reviews-page-wrapper,
.reviews-page-wrapper * {
    box-sizing: border-box !important;
}

.reviews-page-wrapper {
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
    .reviews-page-wrapper {
        padding-top: 95px !important;
        padding-bottom: 70px !important;
    }
}
@media (max-width: 768px) {
    .reviews-page-wrapper {
        padding-top: 80px !important;
        padding-bottom: 60px !important;
    }
}

.reviews-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 16px;
    width: 100%;
}
@media (max-width: 480px) {
    .reviews-container {
        padding: 0 12px;
    }
}

/* Header Box */
.reviews-header-box {
    margin-bottom: 28px;
    text-align: center;
}
.reviews-breadcrumbs {
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
.reviews-breadcrumbs a {
    color: #555555;
    text-decoration: none;
}
.reviews-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1a1a1a !important;
    margin: 0 0 8px 0;
    font-family: var(--font-primary, sans-serif);
    letter-spacing: 2.5px;
    text-transform: uppercase;
}
@media (max-width: 480px) {
    .reviews-title {
        font-size: 1.6rem;
    }
}
.reviews-subtitle {
    color: #555555 !important;
    font-size: 0.85rem;
    letter-spacing: 1px;
    margin: 0 auto;
}

/* Reviews List - Original Clean Design */
.reviews-grid {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.review-item-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}
@media (max-width: 600px) {
    .review-item-card {
        padding: 18px 16px;
        border-radius: 12px;
    }
}

.review-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.reviewer-name {
    font-weight: 700;
    font-size: 0.92rem;
    color: #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.review-stars {
    color: #f59e0b;
    letter-spacing: 2px;
    font-size: 0.95rem;
}
.review-body-text {
    color: #444444;
    font-size: 0.88rem;
    line-height: 1.6;
    margin: 0;
}
</style>

<div class="reviews-page-wrapper">
    <div class="reviews-container">
        
        <!-- Header -->
        <div class="reviews-header-box">
            <div class="reviews-breadcrumbs">
                <a href="<?php echo BASE_URL; ?>/index.php">HOME</a>
                <span>/</span>
                <span style="color: #1a1a1a; font-weight: 700;">COMMUNITY</span>
            </div>
            <h1 class="reviews-title">NØRVA REVIEWS</h1>
            <p class="reviews-subtitle">What our community is saying about our streetwear accessories collection.</p>
        </div>

        <div class="reviews-grid">
            
            <div class="review-item-card">
                <div class="review-card-header">
                    <span class="reviewer-name">Priya S.</span>
                    <span class="review-stars">★★★★★</span>
                </div>
                <p class="review-body-text">
                    "Absolutely obsessed with the Eva Shoulder Bag. The design is so clean and minimalist. I get compliments every time I wear it out!"
                </p>
            </div>

            <div class="review-item-card">
                <div class="review-card-header">
                    <span class="reviewer-name">Aarav M.</span>
                    <span class="review-stars">★★★★★</span>
                </div>
                <p class="review-body-text">
                    "The quality of the hardware is unbelievable for the price. Very heavy, custom molded metal and feels extremely premium. Definitely buying another one."
                </p>
            </div>

            <div class="review-item-card">
                <div class="review-card-header">
                    <span class="reviewer-name">Nisha R.</span>
                    <span class="review-stars">★★★★☆</span>
                </div>
                <p class="review-body-text">
                    "Super fast shipping and the packaging was gorgeous. The bag fits all my daily essentials comfortably."
                </p>
            </div>

            <div class="review-item-card">
                <div class="review-card-header">
                    <span class="reviewer-name">Rohan K.</span>
                    <span class="review-stars">★★★★★</span>
                </div>
                <p class="review-body-text">
                    "The Gothic Tote Bag exceeded my expectations. The eco-leather material is thick, sturdy, and holds its shape perfectly."
                </p>
            </div>

            <div class="review-item-card">
                <div class="review-card-header">
                    <span class="reviewer-name">Ananya V.</span>
                    <span class="review-stars">★★★★★</span>
                </div>
                <p class="review-body-text">
                    "Best handbag purchase this year! The Y2K metallic hardware detail gives it such a high-end streetwear aesthetic."
                </p>
            </div>

            <div class="review-item-card">
                <div class="review-card-header">
                    <span class="reviewer-name">Sneha P.</span>
                    <span class="review-stars">★★★★★</span>
                </div>
                <p class="review-body-text">
                    "Delivered to Ahmedabad in just 3 days! The stitching is immaculate and the inner lining has custom zip compartments."
                </p>
            </div>

            <div class="review-item-card">
                <div class="review-card-header">
                    <span class="reviewer-name">Vikram S.</span>
                    <span class="review-stars">★★★★★</span>
                </div>
                <p class="review-body-text">
                    "Bought this as a gift for my sister and she loved it! Premium quality product and great customer support team."
                </p>
            </div>

            <div class="review-item-card">
                <div class="review-card-header">
                    <span class="reviewer-name">Kavya D.</span>
                    <span class="review-stars">★★★★☆</span>
                </div>
                <p class="review-body-text">
                    "StYLish, edgy, and functional. Fits my iPad, phone, power bank, and makeup pouch with room to spare."
                </p>
            </div>

            <div class="review-item-card">
                <div class="review-card-header">
                    <span class="reviewer-name">Meera H.</span>
                    <span class="review-stars">★★★★★</span>
                </div>
                <p class="review-body-text">
                    "Loved the unboxing experience! The dust bag and sturdy brand box made it feel like buying from a luxury boutique."
                </p>
            </div>

            <div class="review-item-card">
                <div class="review-card-header">
                    <span class="reviewer-name">Kabir T.</span>
                    <span class="review-stars">★★★★★</span>
                </div>
                <p class="review-body-text">
                    "10/10 recommendation for anyone looking for statement accessories that refuse to blend in with generic high street brands."
                </p>
            </div>

        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
