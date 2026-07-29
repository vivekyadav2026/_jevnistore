<?php
require_once 'includes/header.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$method = $_GET['method'] ?? 'cod';

// Verify the order exists in our system
$order_check = $conn->prepare("SELECT id FROM orders WHERE id = ?");
$order_check->bind_param("i", $order_id);
$order_check->execute();
$order_exists = $order_check->get_result()->num_rows > 0;

if (!$order_exists) {
    redirect(BASE_URL . '/index.php');
}
?>

<div class="container order-success-container" style="text-align: center; padding: 150px 0; max-width: 600px;">
    <div style="color: #16a34a; margin-bottom: 2rem;">
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
