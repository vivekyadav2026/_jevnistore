<?php
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$user_id = $_SESSION['user_id'];

// Handle order cancel
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_order_id'])) {
    $cancel_id = intval($_POST['cancel_order_id']);
    $chk = $conn->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ?");
    $chk->bind_param("ii", $cancel_id, $user_id);
    $chk->execute();
    $o_status = $chk->get_result()->fetch_assoc();
    if ($o_status && ($o_status['status'] === 'pending' || $o_status['status'] === 'processing')) {
        $upd = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
        $upd->bind_param("i", $cancel_id);
        $upd->execute();
    }
    redirect('order_history.php');
}

// Handle reorder
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reorder_id'])) {
    $reorder_id = intval($_POST['reorder_id']);
    
    // Fetch order items
    $items_stmt = $conn->prepare("SELECT oi.product_id, oi.quantity, p.name, p.price, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
    $items_stmt->bind_param("i", $reorder_id);
    $items_stmt->execute();
    $items_res = $items_stmt->get_result();
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    while ($item = $items_res->fetch_assoc()) {
        $pid = $item['product_id'];
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]['quantity'] += $item['quantity'];
        } else {
            $_SESSION['cart'][$pid] = [
                'name' => $item['name'],
                'price' => $item['price'],
                'image' => $item['image'],
                'quantity' => $item['quantity']
            ];
        }
    }
    redirect('/cart.php');
}
?>

<div class="page-header">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 10px;">ORDER HISTORY</h1>
    </div>
</div>

<div class="container" style="margin-bottom: 8rem;">
    <div class="dashboard-layout">
        
        <aside class="dashboard-nav">
            <a href="index.php">Profile</a>
            <a href="order_history.php" class="active">Order History</a>
            <a href="/wishlist.php">Wishlist</a>
            <a href="/logout.php" style="color: var(--text-secondary);">Logout</a>
        </aside>

        <div>
            <h3 style="margin-top:0; margin-bottom: 2rem; font-size: 1rem; letter-spacing: 2px; text-transform: uppercase; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">MY ORDERS</h3>
            
            <div class="order-accordion">
                <?php
                $orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
                $orders->bind_param("i", $user_id);
                $orders->execute();
                $result = $orders->get_result();
                
                if ($result->num_rows > 0) {
                    while ($o = $result->fetch_assoc()) {
                        $order_id = $o['id'];
                        
                        // Get order items
                        $items_stmt = $conn->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                        $items_stmt->bind_param("i", $order_id);
                        $items_stmt->execute();
                        $items_res = $items_stmt->get_result();
                        $items = $items_res->fetch_all(MYSQLI_ASSOC);
                        
                        $status = $o['status'];
                        $p_width = '0%';
                        $step_pending = 'active';
                        $step_processing = '';
                        $step_completed = '';
                        
                        if ($status === 'processing') {
                            $p_width = '50%';
                            $step_pending = 'completed';
                            $step_processing = 'active';
                        } elseif ($status === 'completed') {
                            $p_width = '100%';
                            $step_pending = 'completed';
                            $step_processing = 'completed';
                            $step_completed = 'completed';
                        }
                        ?>
                        <div class="order-card" id="order-card-<?php echo $order_id; ?>">
                            <div class="order-header" onclick="toggleOrderDetails(<?php echo $order_id; ?>)">
                                <div class="order-header-cell">
                                    <span class="order-header-label">Order Number</span>
                                    <span class="order-header-value order-number">#<?php echo $order_id; ?></span>
                                </div>
                                <div class="order-header-cell">
                                    <span class="order-header-label">Date Placed</span>
                                    <span class="order-header-value"><?php echo date('M d, Y', strtotime($o['created_at'])); ?></span>
                                </div>
                                <div class="order-header-cell">
                                    <span class="order-header-label">Total Amount</span>
                                    <span class="order-header-value">₹<?php echo number_format($o['total_amount']); ?></span>
                                </div>
                                <div class="order-header-cell">
                                    <span class="order-header-label">Status</span>
                                    <div>
                                        <span class="status-badge <?php echo $status; ?>"><?php echo $status; ?></span>
                                    </div>
                                </div>
                                <div class="order-chevron">
                                    <i data-lucide="chevron-down" style="width: 18px; height: 18px;"></i>
                                </div>
                            </div>
                            
                            <div class="order-details" id="order-details-<?php echo $order_id; ?>">
                                <?php if ($status !== 'cancelled'): ?>
                                <!-- Timeline Progress -->
                                <div class="order-timeline-wrapper">
                                    <div class="order-timeline">
                                        <div class="order-timeline-progress" style="width: <?php echo $p_width; ?>;"></div>
                                        
                                        <div class="order-timeline-step <?php echo $step_pending; ?>">
                                            <div class="order-timeline-dot"></div>
                                            <span class="order-timeline-label">Confirmed</span>
                                        </div>
                                        <div class="order-timeline-step <?php echo $step_processing; ?>">
                                            <div class="order-timeline-dot"></div>
                                            <span class="order-timeline-label">Processing</span>
                                        </div>
                                        <div class="order-timeline-step <?php echo $step_completed; ?>">
                                            <div class="order-timeline-dot"></div>
                                            <span class="order-timeline-label">Delivered</span>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; padding: 12px; margin-bottom: 20px; color: #dc2626; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;">
                                    <i data-lucide="alert-circle" style="width: 18px; height: 18px;"></i>
                                    This order has been cancelled
                                </div>
                                <?php endif; ?>

                                <div class="order-details-grid">
                                    <!-- Left Col: Items List -->
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-primary); margin-bottom: 12px;">Items in Order</div>
                                        <div class="order-items-list">
                                            <?php foreach ($items as $item): 
                                                $img_src = $item['image'] ? '/assets/' . htmlspecialchars($item['image']) : '/assets/product_hoodie.png';
                                            ?>
                                            <div class="order-item-row">
                                                <img src="<?php echo $img_src; ?>" class="order-item-img" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                <div class="order-item-info">
                                                    <a href="/product.php?id=<?php echo $item['product_id']; ?>" class="order-item-name"><?php echo htmlspecialchars($item['name']); ?></a>
                                                    <div class="order-item-meta">Qty: <?php echo $item['quantity']; ?></div>
                                                </div>
                                                <div class="order-item-price-qty">
                                                    ₹<?php echo number_format($item['price']); ?>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Right Col: Shipping & Payment Info -->
                                    <div>
                                        <div class="order-meta-card">
                                            <div class="order-meta-title">Delivery Details</div>
                                            <div class="order-meta-row">
                                                <span class="order-meta-label">Shipping Address</span>
                                                <span class="order-meta-val"><?php echo htmlspecialchars($o['shipping_address']); ?></span>
                                            </div>
                                            <div class="order-meta-row">
                                                <span class="order-meta-label">Payment Method</span>
                                                <span class="order-meta-val" style="text-transform: uppercase;"><?php echo htmlspecialchars($o['payment_method']); ?></span>
                                            </div>
                                            <div class="order-meta-row">
                                                <span class="order-meta-label">Payment Status</span>
                                                <span class="order-meta-val" style="text-transform: uppercase;"><?php echo htmlspecialchars($o['payment_status']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="order-details-actions">
                                    <form method="POST" style="margin: 0;">
                                        <input type="hidden" name="reorder_id" value="<?php echo $order_id; ?>">
                                        <button type="submit" class="btn btn-sm">REORDER ITEMS</button>
                                    </form>
                                    
                                    <?php if ($status === 'pending' || $status === 'processing'): ?>
                                    <form method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        <input type="hidden" name="cancel_order_id" value="<?php echo $order_id; ?>">
                                        <button type="submit" class="btn btn-outline btn-sm" style="border-color: #dc2626; color: #dc2626; background: transparent;">CANCEL ORDER</button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div style="text-align: center; padding: 40px 0; border: 1px dashed var(--border-color); border-radius: 8px;">
                        <p style="color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px;">No orders found</p>
                        <a href="/shop.php" class="btn btn-sm">Start Shopping</a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
function toggleOrderDetails(orderId) {
    const card = document.getElementById('order-card-' + orderId);
    const details = document.getElementById('order-details-' + orderId);
    
    if (card.classList.contains('expanded')) {
        card.classList.remove('expanded');
        details.style.display = 'none';
    } else {
        // Collapse all others
        document.querySelectorAll('.order-card').forEach(c => {
            if (c.id !== 'order-card-' + orderId) {
                c.classList.remove('expanded');
                const det = c.querySelector('.order-details');
                if (det) det.style.display = 'none';
            }
        });
        
        card.classList.add('expanded');
        details.style.display = 'block';
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
