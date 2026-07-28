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
.policy-subheading {
    font-size: 0.88rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 16px 0 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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
                <span style="color: #1a1a1a; font-weight: 700;">PRIVACY POLICY</span>
            </div>
            <h1 class="policy-title">PRIVACY POLICY</h1>
        </div>

        <!-- Content Card -->
        <div class="policy-card">
            
            <p class="policy-text" style="font-size: 0.85rem; font-weight: 700; color: #666; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">
                Last Updated: July 26, 2026
            </p>

            <p class="policy-text">
                Welcome to <strong>Nørva Store</strong> ("<strong>Nørva Store</strong>," "<strong>we</strong>," "<strong>our</strong>," or "<strong>us</strong>").
            </p>

            <p class="policy-text">
                Your privacy is important to us. This Privacy Policy explains how we collect, use, store, and protect your personal information when you visit <strong>https://nørva.store</strong>, create an account, make a purchase, or otherwise interact with our website and services.
            </p>

            <p class="policy-text">
                By accessing or using our website, you agree to the practices described in this Privacy Policy. If you do not agree with this policy, please discontinue using our services.
            </p>

            <hr class="policy-divider">

            <!-- Section 1 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Information We Collect</h3>
                <p class="policy-text">To provide a seamless shopping experience, we may collect the following information.</p>
                
                <h4 class="policy-subheading">Personal Information</h4>
                <p class="policy-text">When you place an order or create an account, we may collect:</p>
                <ul class="policy-list">
                    <li>Full Name</li>
                    <li>Email Address</li>
                    <li>Phone Number</li>
                    <li>Shipping Address</li>
                    <li>Billing Address</li>
                    <li>Account Login Information</li>
                    <li>Purchase History</li>
                </ul>

                <h4 class="policy-subheading">Payment Information</h4>
                <p class="policy-text">Payments are processed securely through trusted third-party payment providers. Nørva Store <strong>does not store your complete debit card, credit card, or banking information</strong> on our servers.</p>

                <h4 class="policy-subheading">Device & Technical Information</h4>
                <p class="policy-text">When you browse our website, we may automatically collect:</p>
                <ul class="policy-list">
                    <li>IP Address</li>
                    <li>Browser Type</li>
                    <li>Device Information</li>
                    <li>Operating System</li>
                    <li>Pages Visited</li>
                    <li>Time Spent on Pages</li>
                    <li>Referring Website</li>
                    <li>Cookies and Tracking Data</li>
                </ul>
            </div>

            <hr class="policy-divider">

            <!-- Section 2 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">How We Use Your Information</h3>
                <p class="policy-text">We use your information to:</p>
                <ul class="policy-list">
                    <li>Process and deliver your orders</li>
                    <li>Create and manage your customer account</li>
                    <li>Provide customer support</li>
                    <li>Verify purchases</li>
                    <li>Process refunds and returns</li>
                    <li>Improve website performance</li>
                    <li>Personalize your shopping experience</li>
                    <li>Prevent fraud and unauthorized activity</li>
                    <li>Comply with applicable legal obligations</li>
                    <li>Communicate order updates and service notifications</li>
                </ul>
                <p class="policy-text">With your permission, we may also send promotional emails, newsletters, exclusive offers, and product launches. You can unsubscribe at any time.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 3 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Cookies & Tracking Technologies</h3>
                <p class="policy-text">Nørva Store uses cookies and similar technologies to improve your experience.</p>
                <p class="policy-text">Cookies help us:</p>
                <ul class="policy-list">
                    <li>Remember your preferences</li>
                    <li>Keep you signed in</li>
                    <li>Analyze website traffic</li>
                    <li>Measure marketing performance</li>
                    <li>Improve website functionality</li>
                    <li>Personalize product recommendations</li>
                </ul>
                <p class="policy-text">You may disable cookies through your browser settings, although some website features may not function properly.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 4 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Analytics & Advertising</h3>
                <p class="policy-text">To improve our services and marketing efforts, we may use trusted third-party technologies including:</p>
                <ul class="policy-list">
                    <li>Google Analytics</li>
                    <li>Meta Pixel</li>
                    <li>TikTok Pixel</li>
                    <li>Email Marketing Platforms</li>
                    <li>Other advertising and analytics partners</li>
                </ul>
                <p class="policy-text">These services may collect information regarding your interaction with our website to measure campaign performance and improve customer experience.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 5 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Customer Accounts</h3>
                <p class="policy-text">If you create an account with Nørva Store, you are responsible for maintaining the confidentiality of your login credentials.</p>
                <p class="policy-text">Please notify us immediately if you believe your account has been accessed without authorization.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 6 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Sharing Your Information</h3>
                <p class="policy-text">We do <strong>not</strong> sell your personal information.</p>
                <p class="policy-text">However, we may share necessary information with trusted service providers for purposes such as:</p>
                <ul class="policy-list">
                    <li>Payment processing</li>
                    <li>Shipping and order fulfillment</li>
                    <li>Fraud prevention</li>
                    <li>Website hosting</li>
                    <li>Customer support</li>
                    <li>Analytics</li>
                    <li>Marketing services</li>
                    <li>Legal compliance</li>
                </ul>
                <p class="policy-text">These providers are permitted to use your information only to perform services on our behalf.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 7 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">International Customers</h3>
                <p class="policy-text">Nørva Store serves customers worldwide.</p>
                <p class="policy-text">Your information may be processed or stored in countries other than your own where our technology providers or service partners operate.</p>
                <p class="policy-text">We take reasonable measures to ensure your personal information remains protected during these transfers.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 8 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Data Security</h3>
                <p class="policy-text">We implement appropriate technical and organizational security measures designed to protect your personal information against unauthorized access, disclosure, alteration, or destruction.</p>
                <p class="policy-text">While we strive to protect your information, no internet transmission or electronic storage system can be guaranteed to be 100% secure.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 9 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Data Retention</h3>
                <p class="policy-text">We retain your personal information only for as long as necessary to:</p>
                <ul class="policy-list">
                    <li>Fulfill your orders</li>
                    <li>Maintain your account</li>
                    <li>Meet legal and tax obligations</li>
                    <li>Resolve disputes</li>
                    <li>Prevent fraud</li>
                    <li>Enforce our agreements</li>
                </ul>
                <p class="policy-text">When information is no longer required, it will be securely deleted or anonymized where appropriate.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 10 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Your Privacy Rights</h3>
                <p class="policy-text">Depending on your location, you may have rights regarding your personal information, including:</p>
                <ul class="policy-list">
                    <li>Access your personal information</li>
                    <li>Correct inaccurate information</li>
                    <li>Request deletion of your information</li>
                    <li>Restrict certain processing activities</li>
                    <li>Withdraw consent where applicable</li>
                    <li>Receive a copy of your personal data</li>
                    <li>Object to certain marketing communications</li>
                </ul>
                <p class="policy-text">To exercise any applicable rights, please contact us.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 11 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Children's Privacy</h3>
                <p class="policy-text">Nørva Store is <strong>not intended for individuals under the age of 18</strong>.</p>
                <p class="policy-text">We do not knowingly collect personal information from children. If we become aware that information has been collected from a child, we will take reasonable steps to remove it.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 12 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Third-Party Links</h3>
                <p class="policy-text">Our website may contain links to third-party websites.</p>
                <p class="policy-text">We are not responsible for the privacy practices, security, or content of external websites. We encourage you to review their privacy policies before providing personal information.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 13 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Policy Updates</h3>
                <p class="policy-text">We may update this Privacy Policy from time to time to reflect changes in our business, technology, or legal requirements.</p>
                <p class="policy-text">The updated version will always be published on this page with the revised "Last Updated" date.</p>
                <p class="policy-text">Your continued use of our website after any changes indicates your acceptance of the updated Privacy Policy.</p>
            </div>

            <hr class="policy-divider">

            <!-- Section 14 -->
            <div class="policy-section-block">
                <h3 class="policy-section-heading">Contact Us</h3>
                <p class="policy-text">If you have any questions about this Privacy Policy or how your personal information is handled, please contact us at:</p>
                <p class="policy-text">
                    <strong>Email:</strong> <a href="mailto:norvastorex@gmail.com" style="color: #e8175d; text-decoration: none; font-weight: 600;">norvastorex@gmail.com</a>
                </p>
                <p class="policy-text">
                    <strong>Website:</strong> <a href="https://nørva.store" target="_blank" style="color: #e8175d; text-decoration: none; font-weight: 600;">https://nørva.store</a>
                </p>
            </div>

            <hr class="policy-divider" style="opacity: 0.1;">
            <p class="policy-text" style="font-size: 0.78rem; text-align: center; color: #888; margin-top: 15px;">
                &copy; 2026 Nørva Store. All rights reserved.
            </p>

        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
