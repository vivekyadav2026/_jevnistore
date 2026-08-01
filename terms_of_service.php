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
                <span style="color: #1a1a1a; font-weight: 700;">TERMS OF SERVICE</span>
            </div>
            <h1 class="policy-title">TERMS OF SERVICE</h1>
        </div>

        <!-- Content Card -->
        <div class="policy-card">
            
            <p class="policy-text" style="font-size: 0.85rem; font-weight: 700; color: #666; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">
                Last Updated: July 26, 2026
            </p>

            <h2 style="font-size: 1.15rem; font-weight: 700; color: #1a1a1a; margin-bottom: 12px;">Welcome to Nørva Store</h2>

            <p class="policy-text">
                These Terms of Service ("Terms") govern your access to and use of <strong>https://norvastore.in</strong> (the "Website"), including all products, features, services, content, and functionality provided by <strong>Nørva Store</strong> ("Nørva Store," "we," "our," or "us").
            </p>

            <p class="policy-text">
                By accessing our Website, creating an account, or placing an order, you agree to comply with these Terms. If you do not agree, please discontinue use of our Website.
            </p>

            <hr class="policy-divider">

            <!-- Section 1 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">1. Eligibility</h3>
                <p class="policy-text">By using our Website, you confirm that:</p>
                <ul class="policy-list">
                    <li>You are at least 18 years old, or the legal age of majority in your jurisdiction.</li>
                    <li>You have the legal capacity to enter into a binding agreement.</li>
                    <li>All information you provide is accurate, current, and complete.</li>
                </ul>
            </div>

            <hr class="policy-divider">

            <!-- Section 2 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">2. Your Account</h3>
                <p class="policy-text">You may create an account to access certain features of our Website.</p>
                <p class="policy-text">You are responsible for:</p>
                <ul class="policy-list">
                    <li>Keeping your login credentials confidential.</li>
                    <li>Maintaining accurate account information.</li>
                    <li>All activities that occur under your account.</li>
                </ul>
                <p class="policy-text">We reserve the right to suspend or terminate accounts that violate these Terms or engage in fraudulent or abusive activity.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 3 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">3. Products</h3>
                <p class="policy-text">We strive to display our products as accurately as possible. However:</p>
                <ul class="policy-list">
                    <li>Product colors may vary depending on your screen or device settings.</li>
                    <li>Product images are for illustrative purposes.</li>
                    <li>Minor differences in appearance, texture, or packaging may occur.</li>
                </ul>
                <p class="policy-text">We reserve the right to modify, discontinue, or limit the availability of any product at any time without prior notice.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 4 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">4. Orders</h3>
                <p class="policy-text">Placing an order constitutes an offer to purchase. Your order is accepted only after:</p>
                <ul class="policy-list">
                    <li>Successful payment authorization, and</li>
                    <li>Order confirmation from Nørva Store.</li>
                </ul>
                <p class="policy-text">We reserve the right to refuse, cancel, or limit any order for reasons including:</p>
                <ul class="policy-list">
                    <li>Pricing errors</li>
                    <li>Suspected fraud</li>
                    <li>Stock availability</li>
                    <li>Incorrect customer information</li>
                    <li>Violation of these Terms</li>
                </ul>
                <p class="policy-text">If payment has already been collected for a cancelled order, the applicable amount will be refunded.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 5 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">5. Pricing</h3>
                <p class="policy-text">All prices displayed on the Website are subject to change without notice. Unless otherwise stated, any:</p>
                <ul class="policy-list">
                    <li>Shipping charges</li>
                    <li>Customs duties</li>
                    <li>Import taxes</li>
                    <li>Local taxes</li>
                </ul>
                <p class="policy-text">may not be included in the displayed product price and remain the customer's responsibility where applicable.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 6 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">6. Payment</h3>
                <p class="policy-text">We accept payments through secure third-party payment providers.</p>
                <p class="policy-text">By completing a purchase, you confirm that:</p>
                <ul class="policy-list">
                    <li>You are authorized to use the selected payment method.</li>
                    <li>The payment information provided is accurate.</li>
                    <li>You agree to pay all applicable charges associated with your purchase.</li>
                </ul>
            </div>

            <hr class="policy-divider">

            <!-- Section 7 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">7. Shipping & Delivery</h3>
                <p class="policy-text">Estimated delivery dates are provided for convenience only. Delivery times may vary due to:</p>
                <ul class="policy-list">
                    <li>Customs processing</li>
                    <li>Carrier delays</li>
                    <li>Weather conditions</li>
                    <li>Public holidays</li>
                    <li>Force majeure events</li>
                </ul>
                <p class="policy-text">Nørva Store is not liable for delays beyond our reasonable control. Risk of loss transfers to the customer once the order has been handed over to the shipping carrier.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 8 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">8. Returns & Refunds</h3>
                <p class="policy-text">
                    Returns, exchanges, and refunds are governed by our separate Refund Policy. By placing an order, you agree to that policy.
                </p>
            </div>

            <hr class="policy-divider">

            <!-- Section 9 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">9. Intellectual Property</h3>
                <p class="policy-text">All content available on the Website, including but not limited to:</p>
                <ul class="policy-list">
                    <li>Logos</li>
                    <li>Branding</li>
                    <li>Product photographs</li>
                    <li>Graphics</li>
                    <li>Videos</li>
                    <li>Product descriptions</li>
                    <li>Website design</li>
                    <li>Text</li>
                    <li>Icons</li>
                    <li>Software</li>
                </ul>
                <p class="policy-text">is owned by or licensed to Nørva Store and is protected by applicable intellectual property laws.</p>
                <p class="policy-text">You may not copy, reproduce, distribute, modify, or commercially exploit any Website content without our prior written permission.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 10 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">10. User Content & Reviews</h3>
                <p class="policy-text">If you submit reviews, photographs, comments, or other content, you grant Nørva Store a worldwide, non-exclusive, royalty-free license to use, display, reproduce, modify, and publish such content for business and promotional purposes.</p>
                <p class="policy-text">You confirm that:</p>
                <ul class="policy-list">
                    <li>You own the content you submit.</li>
                    <li>Your content does not infringe the rights of others.</li>
                    <li>Your content is lawful and accurate.</li>
                </ul>
                <p class="policy-text">We reserve the right to remove content that is unlawful, abusive, misleading, or otherwise inappropriate.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 11 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">11. Third-Party Services</h3>
                <p class="policy-text">Our Website may integrate or link to third-party services, including payment processors, shipping providers, analytics tools, and marketing platforms.</p>
                <p class="policy-text">We are not responsible for the content, privacy practices, or services of third-party websites or providers. Your use of those services is governed by their own terms and policies.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 12 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">12. Privacy</h3>
                <p class="policy-text">
                    Your use of our Website is also governed by our Privacy Policy. By using our Website, you acknowledge that you have read and understood our Privacy Policy.
                </p>
            </div>

            <hr class="policy-divider">

            <!-- Section 13 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">13. Prohibited Activities</h3>
                <p class="policy-text">You agree not to:</p>
                <ul class="policy-list">
                    <li>Use the Website for unlawful purposes.</li>
                    <li>Commit fraud or identity theft.</li>
                    <li>Upload malicious software or viruses.</li>
                    <li>Attempt unauthorized access to our systems.</li>
                    <li>Scrape, copy, or reproduce Website content.</li>
                    <li>Interfere with Website security.</li>
                    <li>Misrepresent yourself or impersonate another individual.</li>
                    <li>Resell products in violation of applicable laws.</li>
                </ul>
                <p class="policy-text">Violation of these Terms may result in immediate suspension or termination of your access.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 14 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">14. Disclaimer</h3>
                <p class="policy-text">The Website and all products are provided on an <strong>"AS IS"</strong> and <strong>"AS AVAILABLE"</strong> basis.</p>
                <p class="policy-text">To the fullest extent permitted by law, Nørva Store disclaims all warranties, whether express or implied, including warranties of merchantability, fitness for a particular purpose, accuracy, availability, and non-infringement.</p>
                <p class="policy-text">We do not guarantee uninterrupted or error-free access to the Website.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 15 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">15. Limitation of Liability</h3>
                <p class="policy-text">To the maximum extent permitted by law, Nørva Store shall not be liable for any indirect, incidental, consequential, special, punitive, or exemplary damages arising out of:</p>
                <ul class="policy-list">
                    <li>Use of the Website</li>
                    <li>Inability to access the Website</li>
                    <li>Product use</li>
                    <li>Delayed deliveries</li>
                    <li>Data loss</li>
                    <li>Business interruption</li>
                    <li>Loss of profits</li>
                </ul>
                <p class="policy-text">Our total liability shall not exceed the amount paid by you for the specific order giving rise to the claim.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 16 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">16. Indemnification</h3>
                <p class="policy-text">
                    You agree to indemnify and hold harmless Nørva Store, its owners, employees, affiliates, contractors, and service providers from any claims, liabilities, losses, damages, or expenses arising from your breach of these Terms, your misuse of the Website, or your violation of applicable laws or the rights of any third party.
                </p>
            </div>

            <hr class="policy-divider">

            <!-- Section 17 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">17. Termination</h3>
                <p class="policy-text">
                    We reserve the right to suspend or terminate your access to the Website at any time, with or without notice, if we believe you have violated these Terms or engaged in fraudulent or unlawful activity.
                </p>
            </div>

            <hr class="policy-divider">

            <!-- Section 18 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">18. Governing Law</h3>
                <p class="policy-text">
                    These Terms shall be governed by and interpreted in accordance with the applicable laws governing our business operations, without regard to conflict of law principles.
                </p>
                <p class="policy-text">
                    Nothing in these Terms limits any mandatory consumer rights available under the laws of your country of residence where applicable.
                </p>
            </div>

            <hr class="policy-divider">

            <!-- Section 19 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">19. Changes to These Terms</h3>
                <p class="policy-text">We may revise these Terms at any time.</p>
                <p class="policy-text">Updated versions will be posted on this page with a revised "Last Updated" date. Your continued use of the Website after changes become effective constitutes acceptance of the revised Terms.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 20 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">20. Contact Us</h3>
                <p class="policy-text">If you have any questions regarding these Terms of Service, please contact us:</p>
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
