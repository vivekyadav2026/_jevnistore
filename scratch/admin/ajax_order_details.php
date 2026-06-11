<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

// Verify Admin
if (!isAdmin()) {
    echo '<div style="color:red; padding: 20px;">Unauthorized access</div>';
    exit();
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
    echo '<div style="padding: 20px;">Invalid order ID</div>';
    exit();
}

$stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo '<div style="padding: 20px;">Order not found</div>';
    exit();
}

// Get order items
$item_stmt = $conn->prepare("SELECT oi.*, p.name as prod_name, p.image as prod_image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$items = $item_stmt->get_result();
?>
<div style="color: white; font-family: inherit;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; border-bottom: 1px solid #333; padding-bottom: 20px; margin-bottom: 20px;">
        <div>
            <h4 style="margin: 0 0 10px 0; color: var(--accent); font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase;">Customer Details</h4>
            <p style="margin: 3px 0; font-size: 0.95rem;"><strong>Name:</strong> <?php echo htmlspecialchars($order['user_name']); ?></p>
            <p style="margin: 3px 0; font-size: 0.95rem;"><strong>Email:</strong> <?php echo htmlspecialchars($order['user_email']); ?></p>
            <p style="margin: 3px 0; font-size: 0.95rem;"><strong>Phone:</strong> <?php echo htmlspecialchars($order['user_phone'] ?: 'Not Provided'); ?></p>
        </div>
        <div>
            <h4 style="margin: 0 0 10px 0; color: var(--accent); font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase;">Shipping Info</h4>
            <p style="margin: 3px 0; font-size: 0.95rem; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
        </div>
    </div>
    
    <div style="border-bottom: 1px solid #333; padding-bottom: 20px; margin-bottom: 20px;">
        <h4 style="margin: 0 0 10px 0; color: var(--accent); font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase;">Order Info</h4>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
            <div>
                <span style="color: var(--text-secondary); font-size: 0.8rem; text-transform: uppercase;">Payment Method</span>
                <p style="margin: 5px 0 0 0; font-weight: 600;"><?php echo strtoupper($order['payment_method']); ?></p>
            </div>
            <div>
                <span style="color: var(--text-secondary); font-size: 0.8rem; text-transform: uppercase;">Payment Status</span>
                <p style="margin: 5px 0 0 0; font-weight: 600;"><?php echo strtoupper($order['payment_status']); ?></p>
            </div>
            <div>
                <span style="color: var(--text-secondary); font-size: 0.8rem; text-transform: uppercase;">Order Status</span>
                <p style="margin: 5px 0 0 0; font-weight: 600; color: #16a34a;"><?php echo strtoupper($order['status']); ?></p>
            </div>
        </div>
    </div>

    <h4 style="margin: 0 0 15px 0; color: var(--accent); font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase;">Line Items</h4>
    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
        <thead>
            <tr style="border-bottom: 1px solid #333; text-align: left;">
                <th style="padding: 10px; color: var(--text-secondary); font-weight: normal; text-transform: uppercase; font-size: 0.8rem;">Product</th>
                <th style="padding: 10px; color: var(--text-secondary); font-weight: normal; text-transform: uppercase; font-size: 0.8rem; text-align: right;">Price</th>
                <th style="padding: 10px; color: var(--text-secondary); font-weight: normal; text-transform: uppercase; font-size: 0.8rem; text-align: center;">Qty</th>
                <th style="padding: 10px; color: var(--text-secondary); font-weight: normal; text-transform: uppercase; font-size: 0.8rem; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $subtotal = 0;
            while ($item = $items->fetch_assoc()) {
                $line_total = $item['price'] * $item['quantity'];
                $subtotal += $line_total;
                $p_image = $item['prod_image'] ? '/assets/' . htmlspecialchars($item['prod_image']) : '/assets/product_pants.png';
                ?>
                <tr style="border-bottom: 1px solid #222;">
                    <td style="padding: 10px; display: flex; align-items: center; gap: 10px;">
                        <img src="<?php echo $p_image; ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                        <span><?php echo htmlspecialchars($item['prod_name']); ?></span>
                    </td>
                    <td style="padding: 10px; text-align: right;">₹<?php echo number_format($item['price']); ?></td>
                    <td style="padding: 10px; text-align: center;"><?php echo $item['quantity']; ?></td>
                    <td style="padding: 10px; text-align: right;">₹<?php echo number_format($line_total); ?></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td colspan="3" style="padding: 15px 10px 5px 10px; text-align: right; color: var(--text-secondary);">Subtotal:</td>
                <td style="padding: 15px 10px 5px 10px; text-align: right; font-weight: 600;">₹<?php echo number_format($subtotal); ?></td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 5px 10px; text-align: right; color: var(--text-secondary);">Shipping:</td>
                <td style="padding: 5px 10px; text-align: right; font-weight: 600; color: #16a34a;">FREE</td>
            </tr>
            <tr style="border-top: 1px solid #333;">
                <td colspan="3" style="padding: 10px; text-align: right; font-size: 1rem; color: var(--accent); font-weight: 600; text-transform: uppercase;">Total:</td>
                <td style="padding: 10px; text-align: right; font-size: 1rem; font-weight: bold; color: var(--accent);">₹<?php echo number_format($subtotal); ?></td>
            </tr>
        </tbody>
    </table>
</div>
