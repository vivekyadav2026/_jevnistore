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
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 15px;">
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
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; padding-top: 10px; border-top: 1px solid #222;">
            <div>
                <span style="color: var(--text-secondary); font-size: 0.8rem; text-transform: uppercase;">Shiprocket Order ID</span>
                <p style="margin: 5px 0 0 0; font-weight: 600; color: <?php echo $order['shiprocket_order_id'] ? '#3b82f6' : '#ef4444'; ?>;">
                    <?php echo $order['shiprocket_order_id'] ?: 'Not Synced'; ?>
                </p>
            </div>
            <div>
                <span style="color: var(--text-secondary); font-size: 0.8rem; text-transform: uppercase;">Shiprocket Shipment ID</span>
                <p style="margin: 5px 0 0 0; font-weight: 600; color: <?php echo $order['shiprocket_shipment_id'] ? '#10b981' : '#ef4444'; ?>;">
                    <?php echo $order['shiprocket_shipment_id'] ?: 'Not Synced'; ?>
                </p>
            </div>
        </div>
        
        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #222;">
            <button id="btn-check-shipping-<?php echo $order_id; ?>" onclick="checkEstimatedShipping(<?php echo $order_id; ?>)" style="background: var(--bg-card); color: var(--text-primary); border: 1px solid #475569; padding: 6px 12px; border-radius: 4px; font-size: 0.8rem; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 5px;">
                <i data-lucide="calculator" style="width: 14px; height: 14px;"></i> Estimate Shipping Cost
            </button>
            <div id="shipping-result-<?php echo $order_id; ?>" style="margin-top: 10px; font-size: 0.85rem;"></div>
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
                $p_image = $item['prod_image'] ? BASE_URL . '/assets/' . htmlspecialchars($item['prod_image']) : '/assets/product_pants.png';
                ?>
                <tr style="border-bottom: 1px solid #222;">
                    <td style="padding: 10px; display: flex; align-items: center; gap: 10px;">
                        <img src="<?php echo $p_image; ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                        <span>
                            <?php echo htmlspecialchars($item['prod_name']); ?>
                            <?php if (!empty($item['variant'])): ?>
                                <br><span style="font-size: 11px; color: #888; text-transform: uppercase; font-weight: 500;">Model: <?php echo htmlspecialchars($item['variant']); ?></span>
                            <?php endif; ?>
                        </span>
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
    
    <script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    function checkEstimatedShipping(orderId) {
        const btn = document.getElementById('btn-check-shipping-' + orderId);
        const resDiv = document.getElementById('shipping-result-' + orderId);
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';
        resDiv.innerHTML = '';
        
        fetch('ajax_check_shipping.php?order_id=' + orderId)
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="calculator" style="width: 14px; height: 14px;"></i> Estimate Shipping Cost';
                if (typeof lucide !== 'undefined') { lucide.createIcons(); }
                
                if (data.status === 'success') {
                    resDiv.innerHTML = `<div style="background: rgba(16, 185, 129, 0.1); border-left: 3px solid #10b981; padding: 10px; color: #10b981; border-radius: 4px;">
                        <strong>Estimated Cost:</strong> ₹${data.rate}<br>
                        <strong>Courier:</strong> ${data.courier_name}<br>
                        <span style="font-size: 0.75rem; color: #888;">(Weight: ${data.weight}kg | Pincode: ${data.pickup_pincode} &rarr; ${data.delivery_pincode})</span>
                    </div>`;
                } else {
                    resDiv.innerHTML = `<div style="background: rgba(239, 68, 68, 0.1); border-left: 3px solid #ef4444; padding: 10px; color: #ef4444; border-radius: 4px;">
                        ${data.message}
                    </div>`;
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = 'Estimate Shipping Cost';
                resDiv.innerHTML = `<span style="color: red;">Error checking shipping cost.</span>`;
            });
    }
    </script>
</div>
