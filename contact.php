<?php require_once 'includes/header.php'; ?>

    <div class="contact-hero">
        <h1 class="section-title" style="font-size: 4rem; position: absolute; z-index: 10;">CONTACT HQ</h1>
        <img src="assets/hero_banner.png" alt="HQ" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.3;">
    </div>

    <section class="section">
        <div class="container contact-main-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: start;">
            
            <!-- Info -->
            <div>
                <h2 style="font-size: 2rem; margin-bottom: 2rem; font-weight: 500;">GET IN TOUCH</h2>
                <p style="color: var(--text-secondary); font-size: 1rem; line-height: 1.8; margin-bottom: 3rem;">
                    For all inquiries regarding orders, sizing, or styling, please reach out to our client services team. We aim to respond within 24 hours.
                </p>
                
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-size: 0.85rem; letter-spacing: 2px; color: var(--text-secondary); margin-bottom: 5px;">EMAIL</h4>
                    <p style="font-size: 1.1rem;"><?php echo htmlspecialchars(getSetting('contact_email', 'support@jevanistore.com')); ?></p>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-size: 0.85rem; letter-spacing: 2px; color: var(--text-secondary); margin-bottom: 5px;">HEADQUARTERS</h4>
                    <p style="font-size: 1.1rem; line-height: 1.5;">
                        <?php echo nl2br(htmlspecialchars(getSetting('contact_address', "123 Industrial Sector 4\nMumbai, Maharashtra 400001\nIndia"))); ?>
                    </p>
                </div>
            </div>

            <!-- Form -->
            <div class="luxury-form">
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Message sent successfully. We will get back to you soon.');">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Order Number (Optional)</label>
                        <input type="text" name="order_num" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" rows="5" required class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn" style="margin-top: 1rem; padding: 15px 40px;">SEND MESSAGE</button>
                </form>
            </div>

        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>
