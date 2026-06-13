<?php
require_once 'includes/header.php';

if (empty($_SESSION['cart'])) {
    redirect('cart.php');
}
?>

<div class="container checkout-page-container" style="max-width: 600px; padding: 150px 20px; text-align: center; min-height: 80vh;">
    <h2 style="font-size: 1.2rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 20px; font-weight: 500;">Securing Checkout Session...</h2>
    <div style="font-size: 0.9rem; color: var(--text-secondary); letter-spacing: 1px; text-transform: uppercase;">Please complete your checkout in the secure window.</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.openCheckoutOverlay === 'function') {
            window.openCheckoutOverlay();
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
