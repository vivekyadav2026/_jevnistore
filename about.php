<?php require_once 'includes/header.php'; ?>

<div class="about-page-container">
    <!-- Editorial Hero -->
    <div class="hero" style="min-height: 70vh; height: 70vh; margin-bottom: 0;">
        <img src="assets/hero_banner_bags.png" alt="Jevani Store Identity" class="hero-img">
        <div class="hero-overlay" style="background: rgba(0,0,0,0.6);"></div>
        <div class="hero-content" style="align-items: center; text-align: center;">
            <p style="font-size: 0.8rem; letter-spacing: 4px; text-transform: uppercase; color: #fff; margin-bottom: 20px; font-weight: 600;">The Identity</p>
            <h1 class="hero-title font-serif" style="font-size: clamp(3rem, 7vw, 90px); color: #fff; line-height: 1; font-style: italic;">A NEW ERA OF<br>ACCESSORIES</h1>
        </div>
    </div>

    <!-- Editorial Text Section -->
    <section class="section" style="background: var(--bg-primary); padding: 8rem 0;">
        <div class="container" style="max-width: 900px;">
            <p style="font-size: clamp(1.5rem, 3vw, 2.5rem); line-height: 1.4; font-family: var(--font-serif); font-style: italic; text-align: center; color: var(--text-primary); margin-bottom: 6rem;">
                "Jevani was born out of a desire to create statement pieces for a generation that refuses to blend in. We don't just make bags; we design the anchor to your aesthetic."
            </p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 60px; margin-bottom: 6rem;">
                <div>
                    <h3 style="font-size: 0.85rem; letter-spacing: 2px; margin-bottom: 1.5rem; text-transform: uppercase; font-weight: 600; color: var(--text-primary);">The Y2K Revival</h3>
                    <p style="color: var(--text-secondary); line-height: 1.8; font-size: 0.95rem;">
                        Drawing inspiration from early 2000s nostalgia, our silhouettes feature sharp angles, micro-proportions, and striking hardware. We blend vintage futuristic elements with modern luxury craftsmanship to create bags that feel instantly iconic.
                    </p>
                </div>
                <div>
                    <h3 style="font-size: 0.85rem; letter-spacing: 2px; margin-bottom: 1.5rem; text-transform: uppercase; font-weight: 600; color: var(--text-primary);">Premium Materials</h3>
                    <p style="color: var(--text-secondary); line-height: 1.8; font-size: 0.95rem;">
                        Every Jevani bag is constructed using high-grade vegan leathers, custom-molded metallic hardware, and architectural structures. Our commitment to quality ensures that your bag isn't just a trend—it's a staple.
                    </p>
                </div>
            </div>
            
            <!-- Image Split -->
            <div class="about-features-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 6rem;">
                <div style="background: var(--bg-secondary); padding: 40px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color);">
                    <img src="assets/bag_mini.png" alt="Mini Bag" style="width: 100%; max-width: 300px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                <div style="background: var(--bg-secondary); padding: 40px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color);">
                    <img src="assets/bag_shoulder.png" alt="Shoulder Bag" style="width: 100%; max-width: 300px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
            </div>

            <div style="text-align: center;">
                <h3 style="font-size: 0.85rem; letter-spacing: 3px; margin-bottom: 2rem; text-transform: uppercase; font-weight: 600; color: var(--text-primary);">Join The Movement</h3>
                <a href="shop.php" class="btn" style="padding: 18px 50px; font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; transition: all 0.3s;">Shop The Collection</a>
            </div>
        </div>
    </section>

</div>

<?php require_once 'includes/footer.php'; ?>
