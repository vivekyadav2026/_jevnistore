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
    margin-bottom: 30px;
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
    margin-bottom: 12px;
    padding-bottom: 6px;
    border-bottom: 1.5px solid #1a1a1a;
}
.policy-text {
    font-size: 0.88rem;
    color: #444444;
    line-height: 1.7;
    margin: 0 0 12px 0;
}
.policy-list {
    margin: 0 0 16px 0;
    padding-left: 20px;
}
.policy-list li {
    font-size: 0.88rem;
    color: #444444;
    line-height: 1.7;
    margin-bottom: 6px;
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
                <span style="color: #1a1a1a; font-weight: 700;">REFUND & RETURN POLICY</span>
            </div>
            <h1 class="policy-title">REFUND & RETURN POLICY</h1>
        </div>

        <!-- Content Card -->
        <div class="policy-card">
            
            <p class="policy-text" style="font-size: 0.85rem; font-weight: 700; color: #666; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">
                Last Updated: July 26, 2026
            </p>

            <p class="policy-text">
                At <strong>Nørva Store</strong>, we are committed to delivering products that meet our quality standards. If your order arrives damaged, defective, or significantly different from its description, we're here to help.
            </p>
            
            <p class="policy-text">
                Please read this policy carefully before requesting a return or refund.
            </p>

            <hr class="policy-divider">

            <!-- Section 1 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Return Eligibility</h3>
                
                <h4 style="font-size: 0.88rem; font-weight: 700; color: #1a1a1a; margin: 16px 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">Eligible for Return:</h4>
                <p class="policy-text">You may request a return if <strong>all</strong> of the following conditions are met:</p>
                <ul class="policy-list">
                    <li>The item was received with significant damage caused during shipping or transit.</li>
                    <li>The product has a manufacturing defect that was not disclosed in the product description.</li>
                    <li>The item received is significantly different from the product description or images.</li>
                    <li>The item is unused, unwashed, unaltered, and returned in its original condition with all original tags and packaging (where applicable).</li>
                    <li>A <strong>clear and uninterrupted unboxing video</strong> is provided. An unboxing video is mandatory for all return, refund, and damage claims.</li>
                </ul>

                <h4 style="font-size: 0.88rem; font-weight: 700; color: #ef4444; margin: 20px 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">Not Eligible for Return:</h4>
                <p class="policy-text">Returns will <strong>not</strong> be accepted for:</p>
                <ul class="policy-list">
                    <li>Clearance or final sale items.</li>
                    <li>Change of mind or buyer's remorse.</li>
                    <li>Incorrect size selection, as product measurements are clearly listed before purchase.</li>
                    <li>Minor imperfections that are typical of handmade, vintage, or pre-owned products.</li>
                    <li>Products that have been worn, washed, altered, or damaged after delivery.</li>
                    <li>Items returned without proof of purchase.</li>
                    <li>Claims submitted without a valid unboxing video.</li>
                </ul>
            </div>

            <hr class="policy-divider">

            <!-- Section 2 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Return Timeframe</h3>
                <p class="policy-text">To be eligible for a return:</p>
                <ul class="policy-list">
                    <li>A return request must be submitted within <strong>48 hours</strong> of the delivery date.</li>
                    <li>Once your return request is approved, the item must be shipped back within <strong>7 days</strong> of receiving the return authorization.</li>
                </ul>
                <p class="policy-text" style="font-style: italic;">Requests submitted outside these timeframes may be declined.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 3 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Refund Eligibility</h3>
                <p class="policy-text">A refund may be requested only if:</p>
                <ul class="policy-list">
                    <li><strong>15 days</strong> have passed since the order was placed, <strong>and</strong></li>
                    <li>The order has <strong>not yet been dispatched</strong>.</li>
                </ul>
                <p class="policy-text">If approved, refunds will be issued to the original payment method within <strong>7 business days</strong>.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 4 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Return Process</h3>
                
                <h4 style="font-size: 0.88rem; font-weight: 700; color: #1a1a1a; margin-bottom: 8px;">Step 1 – Submit Your Request</h4>
                <p class="policy-text">Contact our support team within <strong>48 hours</strong> of receiving your order and provide:</p>
                <ul class="policy-list">
                    <li>Your order number.</li>
                    <li>A detailed explanation of the issue.</li>
                    <li>Clear photographs showing the problem.</li>
                    <li>A complete unboxing video.</li>
                </ul>
                <p class="policy-text" style="font-weight: 600;">Please wait for confirmation before sending any item back.</p>

                <h4 style="font-size: 0.88rem; font-weight: 700; color: #1a1a1a; margin: 18px 0 8px 0;">Step 2 – Return Authorization</h4>
                <p class="policy-text">If your request is approved, you will receive:</p>
                <ul class="policy-list">
                    <li>A Return Authorization.</li>
                    <li>Return shipping instructions.</li>
                </ul>
                <p class="policy-text">Returns sent without prior authorization will not be accepted. Your Return Authorization is valid for <strong>7 days</strong> from the date it is issued.</p>

                <h4 style="font-size: 0.88rem; font-weight: 700; color: #1a1a1a; margin: 18px 0 8px 0;">Step 3 – Ship the Item</h4>
                <p class="policy-text">When returning your item:</p>
                <ul class="policy-list">
                    <li>Pack the product securely to prevent shipping damage.</li>
                    <li>Include the original packaging whenever possible.</li>
                    <li>Clearly mention the Return Authorization number on the package.</li>
                    <li>Use a tracked shipping service, as we cannot guarantee receipt of untracked parcels.</li>
                </ul>
            </div>

            <hr class="policy-divider">

            <!-- Section 5 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Processing Timeline</h3>
                <p class="policy-text">After we receive your returned item:</p>
                <ul class="policy-list">
                    <li>Return inspection: <strong>3–5 business days</strong></li>
                    <li>Refund processing (if approved): <strong>5–7 business days</strong></li>
                </ul>
                <p class="policy-text">You will receive an email notification once your return or refund has been processed.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 6 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Damaged or Defective Items</h3>
                <p class="policy-text">If your order arrives damaged or defective:</p>
                <ul class="policy-list">
                    <li>Record a complete unboxing video immediately upon opening the package.</li>
                    <li>Take clear photographs showing the damage.</li>
                    <li>Do not use, wash, repair, or alter the item.</li>
                    <li>Contact us as soon as possible with your order number, photos, and unboxing video.</li>
                    <li>Keep all original packaging until your claim has been resolved.</li>
                </ul>
                <p class="policy-text" style="font-weight: 600; color: #ef4444;">Failure to provide an unboxing video may result in the rejection of your claim.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 7 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Exchange Policy</h3>
                <p class="policy-text">Where applicable, exchanges may be offered instead of a refund.</p>
                <p class="policy-text">Exchange requests are subject to:</p>
                <ul class="policy-list">
                    <li>Product availability.</li>
                    <li>Approval by our customer support team.</li>
                    <li>The replacement item being of equal or lower value.</li>
                </ul>
                <p class="policy-text">Exchange requests must:</p>
                <ul class="policy-list">
                    <li>Be initiated within <strong>48 hours</strong> of delivery.</li>
                    <li>Be shipped back within <strong>7 days</strong> after approval.</li>
                </ul>
                <p class="policy-text">Approved exchanges are generally completed within <strong>15 business days</strong> after the returned item has been received and inspected.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 8 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Return Shipping Costs</h3>
                <p class="policy-text">
                    Unless the return is the result of our error, a defective product, or an incorrect item sent by us, customers are responsible for all return shipping costs.
                </p>
                <p class="policy-text">
                    Original shipping charges, customs duties, taxes, and import fees are non-refundable unless required by applicable law.
                </p>
            </div>

            <hr class="policy-divider">

            <!-- Section 9 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Important Information</h3>
                <p class="policy-text">Please note the following:</p>
                <ul class="policy-list">
                    <li>All returns must be initiated within <strong>48 hours</strong> of delivery.</li>
                    <li>Products must be returned in their original condition.</li>
                    <li>Every return and refund request is subject to inspection and approval.</li>
                    <li>If a returned item does not meet our return requirements, we reserve the right to reject the request or return the item using an alternative courier at the customer's expense.</li>
                    <li><strong>An unboxing video is mandatory for all return, exchange, damage, or missing-item claims.</strong></li>
                    <li>We reserve the right to refuse any request that does not comply with this policy.</li>
                    <li>Decisions made by Nørva Store regarding returns, exchanges, and refunds shall be final, subject to applicable consumer protection laws.</li>
                </ul>
            </div>

            <hr class="policy-divider">

            <!-- Section 10 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Need Assistance?</h3>
                <p class="policy-text">If you have any questions regarding returns, refunds, or exchanges, please contact us:</p>
                <p class="policy-text">
                    <strong>Email:</strong> <a href="mailto:norvastorex@gmail.com" style="color: #e8175d; text-decoration: none; font-weight: 600;">norvastorex@gmail.com</a>
                </p>
                <p class="policy-text">
                    <strong>Website:</strong> <a href="https://norvastore.in" target="_blank" style="color: #e8175d; text-decoration: none; font-weight: 600;">https://norvastore.in</a>
                </p>
            </div>

            <hr class="policy-divider" style="opacity: 0.1;">
            <p class="policy-text" style="font-size: 0.78rem; text-align: center; color: #888; margin-top: 15px;">
                &copy; 2026 Nørva Store. All Rights Reserved.
            </p>

        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
