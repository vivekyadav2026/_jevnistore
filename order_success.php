<?php
require_once 'includes/header.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$method = $_GET['method'] ?? 'cod';

if ($method == 'razorpay' && isset($_SESSION['pending_order_id']) && $_SESSION['pending_order_id'] == $order_id) {
    // Simulate successful razorpay payment
    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid', payment_id = 'rzp_mock_12345' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    unset($_SESSION['cart']);
    unset($_SESSION['pending_order_id']);
}
?>

<div class="container" style="text-align: center; padding: 150px 0; max-width: 600px;">
    <div style="color: var(--accent); margin-bottom: 2rem;">
        <i data-lucide="check" style="width: 64px; height: 64px;"></i>
    </div>
    <h1 class="section-title" style="font-size: 2.5rem; margin-bottom: 1rem;">ORDER SECURED</h1>
    <p style="color: var(--text-secondary); font-size: 1rem; line-height: 1.8; margin-bottom: 3rem;">
        Your order <strong>#<?php echo $order_id; ?></strong> has been successfully placed.<br>
        A confirmation email will be sent to you shortly.
    </p>
    
    <div style="display: flex; gap: 20px; justify-content: center;">
        <a href="customer/index.php" class="btn btn-outline" style="padding: 15px 30px; letter-spacing: 2px;">VIEW ORDER</a>
        <a href="shop.php" class="btn" style="padding: 15px 30px; letter-spacing: 2px;">CONTINUE SHOPPING</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
