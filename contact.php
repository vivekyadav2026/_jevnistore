<?php
require_once 'includes/header.php';

$contact_email = getSetting('contact_email', 'norvastorex@gmail.com');
$contact_phone = getSetting('contact_phone', '+91 98712 34567');
$default_address = "NØRVA STORE\nBuilding No. A-881, G.D Colony\nMayur Vihar Phase 3, New Delhi - 110096, India";
$contact_address = getSetting('contact_address', $default_address);
?>

<style>
/* Universal Box-Sizing & Reset */
.contact-page-wrapper,
.contact-page-wrapper * {
    box-sizing: border-box !important;
}

.contact-page-wrapper {
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
    .contact-page-wrapper {
        padding-top: 95px !important;
        padding-bottom: 70px !important;
    }
}
@media (max-width: 768px) {
    .contact-page-wrapper {
        padding-top: 80px !important;
        padding-bottom: 60px !important;
    }
}

.contact-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 16px;
    width: 100%;
}
@media (max-width: 480px) {
    .contact-container {
        padding: 0 12px;
    }
}

/* Header Box */
.contact-header-box {
    margin-bottom: 28px;
    text-align: center;
}
.contact-breadcrumbs {
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
.contact-breadcrumbs a {
    color: #555555;
    text-decoration: none;
}
.contact-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1a1a1a !important;
    margin: 0 0 8px 0;
    font-family: var(--font-primary, sans-serif);
    letter-spacing: 2.5px;
    text-transform: uppercase;
}
@media (max-width: 480px) {
    .contact-title {
        font-size: 1.6rem;
    }
}
.contact-subtitle {
    color: #555555 !important;
    font-size: 0.85rem;
    letter-spacing: 1px;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.5;
}

/* Contact Layout Grid */
.contact-grid {
    display: grid !important;
    grid-template-columns: 1fr 1.1fr !important;
    gap: 24px !important;
    align-items: start !important;
    width: 100% !important;
    min-width: 0 !important;
}
.contact-grid > * {
    min-width: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}

@media (max-width: 992px) {
    .contact-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
}

/* Contact Info Cards */
.info-card-box {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 16px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    width: 100%;
}
@media (max-width: 480px) {
    .info-card-box {
        padding: 16px;
        gap: 12px;
    }
}
.info-icon-badge {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: #1a1a1a;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: none;
}
.info-card-label {
    font-size: 0.68rem;
    font-weight: 700;
    color: #666666;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 4px;
}
.info-card-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.4;
}
.info-card-meta {
    font-size: 0.72rem;
    color: #16a34a;
    font-weight: 700;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Quick Help Pills */
.quick-help-box {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    width: 100%;
}
.quick-help-title {
    font-size: 0.78rem;
    font-weight: 700;
    color: #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}
.quick-help-links {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.quick-help-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 99px;
    color: #1a1a1a;
    font-size: 0.7rem;
    font-weight: 700;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.2s ease;
}
.quick-help-pill:hover {
    background: #1a1a1a;
    color: #ffffff;
}

/* Contact Form Card */
.contact-form-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    padding: 28px 24px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    width: 100%;
}
@media (max-width: 600px) {
    .contact-form-card {
        padding: 20px 16px !important;
        border-radius: 14px !important;
    }
}
.form-card-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-top: 0;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1.5px solid #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

.form-grid-2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
}
@media (max-width: 640px) {
    .form-grid-2col {
        grid-template-columns: 1fr !important;
        gap: 12px !important;
    }
}

.custom-input-label {
    display: block;
    margin-bottom: 6px;
    font-size: 0.68rem;
    font-weight: 700;
    color: #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.custom-input-field {
    width: 100%;
    padding: 12px 14px;
    border-radius: 8px;
    border: 1.5px solid #e5e7eb;
    font-size: 0.85rem;
    background: #ffffff;
    color: #1a1a1a;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: inherit;
}
.custom-input-field:focus {
    border-color: #000000;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.08);
}

.submit-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #1a1a1a;
    color: #ffffff;
    border: 1.5px solid #1a1a1a;
    padding: 13px 32px;
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    border-radius: 99px;
    cursor: pointer;
    transition: all 0.2s ease;
    width: 100%;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.submit-btn-primary:hover {
    background: #000000;
}
</style>

<div class="contact-page-wrapper">
    <div class="contact-container">
        
        <!-- Header -->
        <div class="contact-header-box">
            <div class="contact-breadcrumbs">
                <a href="<?php echo BASE_URL; ?>/index.php">HOME</a>
                <span>/</span>
                <span style="color: #1a1a1a; font-weight: 700;">CONTACT US</span>
            </div>
            <h1 class="contact-title">CLIENT SERVICES</h1>
            <p class="contact-subtitle">Have questions about an order, styling advice, or shipping? Our support team is here to assist you.</p>
        </div>

        <!-- Main Contact Grid -->
        <div class="contact-grid">
            
            <!-- Left Column: Contact Cards -->
            <div>
                
                <!-- Email Card -->
                <div class="info-card-box">
                    <div class="info-icon-badge">
                        <i data-lucide="mail" style="width: 22px; height: 22px;"></i>
                    </div>
                    <div>
                        <div class="info-card-label">Email Support</div>
                        <div class="info-card-value"><?php echo htmlspecialchars($contact_email); ?></div>
                        <div class="info-card-meta">
                            <i data-lucide="clock" style="width: 13px; height: 13px;"></i> Response within 24 hours
                        </div>
                    </div>
                </div>

                <!-- Phone / WhatsApp Card -->
                <div class="info-card-box">
                    <div class="info-icon-badge">
                        <i data-lucide="phone-call" style="width: 22px; height: 22px;"></i>
                    </div>
                    <div>
                        <div class="info-card-label">Customer Helpline</div>
                        <div class="info-card-value"><?php echo htmlspecialchars($contact_phone); ?></div>
                        <div class="info-card-meta" style="color: #555555;">
                            Mon - Sat | 10:00 AM - 7:00 PM IST
                        </div>
                    </div>
                </div>

                <!-- HQ Address Card -->
                <div class="info-card-box">
                    <div class="info-icon-badge">
                        <i data-lucide="map-pin" style="width: 22px; height: 22px;"></i>
                    </div>
                    <div>
                        <div class="info-card-label">Headquarters Address</div>
                        <div class="info-card-value" style="font-size: 0.85rem; font-family: inherit; font-weight: 600;">
                            <?php echo nl2br(htmlspecialchars($contact_address)); ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Help Pills -->
                <div class="quick-help-box">
                    <div class="quick-help-title">Quick Help & Assistance</div>
                    <div class="quick-help-links">
                        <a href="<?php echo BASE_URL; ?>/track_order.php" class="quick-help-pill">
                            <i data-lucide="truck" style="width: 14px; height: 14px;"></i> Track Order
                        </a>
                        <!--
                        <a href="<?php echo BASE_URL; ?>/exchange_portal.php" class="quick-help-pill">
                            <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i> Return / Exchange
                        </a>
                        -->
                        <a href="<?php echo BASE_URL; ?>/privacy_policy.php" class="quick-help-pill">
                            <i data-lucide="shield-check" style="width: 14px; height: 14px;"></i> Store Policies
                        </a>
                    </div>
                </div>

            </div>

            <!-- Right Column: Contact Form -->
            <div>
                <div class="contact-form-card">
                    <h2 class="form-card-title">Send Us a Message</h2>
                    
                    <form action="#" method="POST" id="contact-form" onsubmit="handleContactSubmit(event)">
                        <div class="form-grid-2col">
                            <div>
                                <label class="custom-input-label">Full Name *</label>
                                <input type="text" name="name" required class="custom-input-field" placeholder="Enter your name">
                            </div>
                            <div>
                                <label class="custom-input-label">Email Address *</label>
                                <input type="email" name="email" required class="custom-input-field" placeholder="name@example.com">
                            </div>
                        </div>

                        <div class="form-grid-2col">
                            <div>
                                <label class="custom-input-label">Phone / WhatsApp</label>
                                <input type="tel" name="phone" class="custom-input-field" placeholder="+91 9876543210">
                            </div>
                            <div>
                                <label class="custom-input-label">Order ID (Optional)</label>
                                <input type="text" name="order_num" class="custom-input-field" placeholder="e.g. #1024">
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label class="custom-input-label">How can we help you? *</label>
                            <textarea name="message" rows="5" required class="custom-input-field" placeholder="Please provide detail about your query or order..."></textarea>
                        </div>

                        <button type="submit" class="submit-btn-primary">
                            <i data-lucide="send" style="width: 16px; height: 16px;"></i>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
function handleContactSubmit(event) {
    event.preventDefault();
    const btn = event.target.querySelector('button[type="submit"]');
    const origText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="check-circle" style="width:16px;height:16px;"></i> Message Sent!';
    lucide.createIcons();
    
    setTimeout(() => {
        alert('Thank you! Your inquiry has been received. Our client support team will get back to you shortly.');
        event.target.reset();
        btn.disabled = false;
        btn.innerHTML = origText;
        lucide.createIcons();
    }, 400);
}
</script>

<?php require_once 'includes/footer.php'; ?>
