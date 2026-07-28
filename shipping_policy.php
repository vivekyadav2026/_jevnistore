<?php require_once 'includes/header.php'; ?>

<style>
/* Universal Box-Sizing & Reset */
.policy-page-wrapper,
.policy-page-wrapper * {
    box-sizing: border-box !important;
}

.policy-page-wrapper {
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
    .policy-page-wrapper {
        padding-top: 95px !important;
        padding-bottom: 70px !important;
    }
}
@media (max-width: 768px) {
    .policy-page-wrapper {
        padding-top: 80px !important;
        padding-bottom: 60px !important;
    }
}

.policy-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 16px;
    width: 100%;
}
@media (max-width: 480px) {
    .policy-container {
        padding: 0 12px;
    }
}

/* Header Box */
.policy-header-box {
    margin-bottom: 28px;
    text-align: center;
}
.policy-breadcrumbs {
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
.policy-breadcrumbs a {
    color: #555555;
    text-decoration: none;
}
.policy-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1a1a1a !important;
    margin: 0 0 8px 0;
    font-family: var(--font-primary, sans-serif);
    letter-spacing: 2.5px;
    text-transform: uppercase;
}
@media (max-width: 480px) {
    .policy-title {
        font-size: 1.6rem;
    }
}

/* Main Content Card */
.policy-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 20px;
    padding: 32px 28px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    width: 100%;
}
@media (max-width: 600px) {
    .policy-card {
        padding: 20px 16px !important;
        border-radius: 14px !important;
    }
}

.policy-section-block {
    margin-bottom: 24px;
}
.policy-section-block:last-child {
    margin-bottom: 0;
}
.policy-section-heading {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 0;
    margin-bottom: 10px;
    padding-bottom: 6px;
    border-bottom: 1.5px solid #1a1a1a;
}
.policy-text {
    font-size: 0.88rem;
    color: #444444;
    line-height: 1.7;
    margin: 0 0 10px 0;
}
.policy-divider {
    border: 0;
    height: 1.5px;
    background: #1a1a1a;
    margin: 25px 0;
    opacity: 0.25;
}
</style>

<div class="policy-page-wrapper">
    <div class="policy-container">
        
        <!-- Header -->
        <div class="policy-header-box">
            <div class="policy-breadcrumbs">
                <a href="<?php echo BASE_URL; ?>/index.php">HOME</a>
                <span>/</span>
                <span style="color: #1a1a1a; font-weight: 700;">SHIPPING POLICY</span>
            </div>
            <h1 class="policy-title">SHIPPING POLICY</h1>
        </div>

        <!-- Content Card -->
        <div class="policy-card">
            
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Order Processing & Dispatch Time</h3>
                <p class="policy-text">
                    All orders are processed and delivered within 15-20 business days.
                </p>
                <p class="policy-text">
                    During sale or high-demand periods, delivery time may be extended beyond 15 days due to higher order volumes.
                </p>
                <p class="policy-text">
                    Once your order is shipped, you will receive a confirmation email with tracking details.
                </p>
            </div>

            <hr class="policy-divider">

            <div class="policy-section-block">
                <h3 class="policy-section-heading">Failed Delivery / Return to Sender</h3>
                <p class="policy-text">
                    If the courier is unable to deliver the package due to the customer being unavailable, wrong address, or failure to collect, and the order is returned to us, the customer will be responsible for reshipping charges.
                </p>
                <p class="policy-text">
                    Reshipping will only be initiated once the additional shipping fee is paid.
                </p>
            </div>

            <hr class="policy-divider">

            <div class="policy-section-block">
                <h3 class="policy-section-heading">Reshipping Time</h3>
                <p class="policy-text">
                    Please note that reshipping may take longer than the standard delivery period due to return handling, reprocessing, and re-dispatch scheduling.
                </p>
            </div>

            <hr class="policy-divider">

            <div class="policy-section-block">
                <h3 class="policy-section-heading" style="border-bottom-color: #ef4444; color: #ef4444;">Note:</h3>
                <p class="policy-text" style="font-weight: 600;">
                    We recommend double-checking your shipping details and ensuring availability during the expected delivery window to avoid delays.
                </p>
            </div>

        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
