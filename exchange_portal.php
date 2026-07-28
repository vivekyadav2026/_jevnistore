<?php require_once 'includes/header.php'; ?>

<style>
/* Universal Box-Sizing & Reset */
.portal-page-wrapper,
.portal-page-wrapper * {
    box-sizing: border-box !important;
}

.portal-page-wrapper {
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
    .portal-page-wrapper {
        padding-top: 95px !important;
        padding-bottom: 70px !important;
    }
}
@media (max-width: 768px) {
    .portal-page-wrapper {
        padding-top: 80px !important;
        padding-bottom: 60px !important;
    }
}

.portal-container {
    max-width: 700px;
    margin: 0 auto;
    padding: 0 16px;
    width: 100%;
}
@media (max-width: 480px) {
    .portal-container {
        padding: 0 12px;
    }
}

/* Header Box */
.portal-header-box {
    margin-bottom: 28px;
    text-align: center;
}
.portal-breadcrumbs {
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
.portal-breadcrumbs a {
    color: #555555;
    text-decoration: none;
}
.portal-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1a1a1a !important;
    margin: 0 0 8px 0;
    font-family: var(--font-primary, sans-serif);
    letter-spacing: 2.5px;
    text-transform: uppercase;
}
@media (max-width: 480px) {
    .portal-title {
        font-size: 1.6rem;
    }
}
.portal-subtitle {
    color: #555555 !important;
    font-size: 0.85rem;
    letter-spacing: 1px;
    margin: 0 auto;
    line-height: 1.5;
}

/* Form Card */
.portal-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    padding: 32px 28px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    width: 100%;
}
@media (max-width: 600px) {
    .portal-card {
        padding: 20px 16px !important;
        border-radius: 14px !important;
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

<div class="portal-page-wrapper">
    <div class="portal-container">
        
        <!-- Header -->
        <div class="portal-header-box">
            <div class="portal-breadcrumbs">
                <a href="<?php echo BASE_URL; ?>/index.php">HOME</a>
                <span>/</span>
                <span style="color: #1a1a1a; font-weight: 700;">EXCHANGE PORTAL</span>
            </div>
            <h1 class="portal-title">EXCHANGE PORTAL</h1>
            <p class="portal-subtitle">Need to exchange a size or item? Enter your order details below to initiate a hassle-free exchange.</p>
        </div>

        <!-- Form Card -->
        <div class="portal-card">
            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Exchange request submitted successfully! Our team will contact you within 24 hours with pickup details.');">
                
                <div style="margin-bottom: 16px;">
                    <label class="custom-input-label">Order Number *</label>
                    <input type="text" placeholder="e.g. #JV-1024" required class="custom-input-field">
                </div>

                <div style="margin-bottom: 16px;">
                    <label class="custom-input-label">Email Address *</label>
                    <input type="email" placeholder="you@example.com" required class="custom-input-field">
                </div>

                <div style="margin-bottom: 24px;">
                    <label class="custom-input-label">Reason for Exchange (Optional)</label>
                    <textarea rows="3" class="custom-input-field" placeholder="e.g. Need a different size or color..."></textarea>
                </div>

                <button type="submit" class="submit-btn-primary">
                    <i data-lucide="refresh-cw" style="width: 16px; height: 16px;"></i>
                    Submit Exchange Request
                </button>
            </form>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
