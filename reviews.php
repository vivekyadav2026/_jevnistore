<?php 
require_once 'includes/db.php';
require_once 'includes/header.php'; 
?>

    <section class="section" style="background: var(--bg-primary); padding: 8rem 0; min-height: 80vh; font-family: var(--font);">
        <div class="container" style="max-width: 900px; padding: 0 20px;">
            <span style="font-size: 0.75rem; letter-spacing: 3px; color: var(--accent); text-transform: uppercase; font-weight: 600; display: block; margin-bottom: 1rem;">COMMUNITY</span>
            <h1 style="font-size: clamp(2rem, 5vw, 3rem); font-weight: 300; letter-spacing: 3px; text-transform: uppercase; color: var(--text-primary); margin-bottom: 3rem; line-height: 1.2;">JEVANI REVIEWS</h1>
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 30px;">
                <div style="background: var(--bg-secondary); padding: 30px; border-radius: 12px; border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span style="font-weight: 600; color: var(--text-primary);">Priya S.</span>
                        <span style="color: var(--accent); letter-spacing: 1px;">★★★★★</span>
                    </div>
                    <p style="color: var(--text-secondary); line-height: 1.6; margin-bottom: 0;">
                        "Absolutely obsessed with the Eva Shoulder Bag. The design is so clean and minimalist. I get compliments every time I wear it out!"
                    </p>
                </div>
                
                <div style="background: var(--bg-secondary); padding: 30px; border-radius: 12px; border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span style="font-weight: 600; color: var(--text-primary);">Aarav M.</span>
                        <span style="color: var(--accent); letter-spacing: 1px;">★★★★★</span>
                    </div>
                    <p style="color: var(--text-secondary); line-height: 1.6; margin-bottom: 0;">
                        "The quality of the hardware is unbelievable for the price. Very heavy and feels extremely premium. Definitely buying another one."
                    </p>
                </div>

                <div style="background: var(--bg-secondary); padding: 30px; border-radius: 12px; border: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span style="font-weight: 600; color: var(--text-primary);">Nisha R.</span>
                        <span style="color: var(--accent); letter-spacing: 1px;">★★★★☆</span>
                    </div>
                    <p style="color: var(--text-secondary); line-height: 1.6; margin-bottom: 0;">
                        "Super fast shipping and the packaging was gorgeous. The bag is slightly smaller than I expected but it fits all my essentials."
                    </p>
                </div>
            </div>
        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>
