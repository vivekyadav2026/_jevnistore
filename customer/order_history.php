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
    redirect(BASE_URL . '/cart.php');
}
?>

<style>
/* Universal Responsive Box Sizing & Grid Overflow Prevention */
.customer-page-section,
.customer-page-section * {
    box-sizing: border-box !important;
}

.customer-page-section {
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
    .customer-page-section {
        padding-top: 95px !important;
        padding-bottom: 70px !important;
    }
}
@media (max-width: 768px) {
    .customer-page-section {
        padding-top: 80px !important;
        padding-bottom: 60px !important;
    }
}

.account-container-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 16px;
    width: 100%;
}
@media (max-width: 480px) {
    .account-container-inner {
        padding: 0 12px;
    }
}

.account-header-box {
    margin-bottom: 20px;
    width: 100%;
}
.account-breadcrumbs {
    font-size: 0.7rem;
    color: #555555;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 700;
}
.account-breadcrumbs a {
    color: #555555;
    text-decoration: none;
}
.account-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1a1a1a !important;
    margin: 0 0 4px 0;
    font-family: var(--font-primary, sans-serif);
    letter-spacing: 2px;
    text-transform: uppercase;
}
@media (max-width: 480px) {
    .account-title {
        font-size: 1.4rem;
    }
}
.account-subtitle {
    color: #555555 !important;
    font-size: 0.75rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-weight: 600;
}

/* Bulletproof CSS Grid */
.account-grid-layout {
    display: grid !important;
    grid-template-columns: 240px 1fr !important;
    gap: 20px !important;
    align-items: start !important;
    width: 100% !important;
    min-width: 0 !important;
}
.account-grid-layout > * {
    min-width: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}

@media (max-width: 992px) {
    .account-grid-layout {
        grid-template-columns: 1fr !important;
        gap: 16px !important;
    }
}

/* 2x2 Mobile Nav Tab Grid */
.account-nav-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: #ffffff;
    padding: 12px;
    border-radius: 16px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    width: 100%;
}
@media (max-width: 992px) {
    .account-nav-list {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 6px !important;
        padding: 6px !important;
        border-radius: 12px !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        background: #ffffff !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
        margin-bottom: 16px !important;
        width: 100% !important;
    }
    .account-nav-link {
        width: 100% !important;
        justify-content: center !important;
        padding: 10px 4px !important;
        font-size: 0.66rem !important;
        border: none !important;
        border-radius: 8px !important;
        background: transparent !important;
        text-align: center !important;
        letter-spacing: 0.5px !important;
        gap: 6px !important;
    }
    .account-nav-link.active {
        background: #1a1a1a !important;
        color: #ffffff !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
    }
    .account-nav-link.danger {
        border: none !important;
    }
}

.account-nav-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 18px;
    border-radius: 99px;
    color: #1a1a1a;
    font-weight: 700;
    font-size: 0.72rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
    flex-shrink: 0;
    background: #ffffff;
    border: 1px solid #e5e7eb;
}
.account-nav-link:hover {
    background: #f3f4f6;
    color: #1a1a1a;
}
.account-nav-link.active {
    background: #1a1a1a !important;
    color: #ffffff !important;
    border-color: #1a1a1a !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
.account-nav-link.danger {
    color: #dc2626;
    border-color: #dc2626;
}

/* Order Accordion Cards */
.order-card {
    background: #ffffff !important;
    border: 1px solid rgba(0, 0, 0, 0.05) !important;
    border-radius: 16px !important;
    overflow: hidden !important;
    margin-bottom: 14px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
    width: 100% !important;
}
.order-header {
    padding: 16px 18px !important;
    background: #ffffff !important;
    cursor: pointer;
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) auto !important;
    align-items: center !important;
    gap: 10px !important;
}
@media (max-width: 640px) {
    .order-header {
        grid-template-columns: 1fr 1fr auto !important;
        padding: 12px 10px !important;
        gap: 8px !important;
    }
}
.order-header-label {
    color: #555555 !important;
    font-size: 0.65rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    display: block !important;
    margin-bottom: 2px !important;
}
.order-header-value {
    color: #1a1a1a !important;
    font-weight: 700 !important;
    font-size: 0.8rem !important;
}
.order-details {
    padding: 18px 16px !important;
    border-top: 1px solid rgba(0, 0, 0, 0.05) !important;
    background: #fafafa !important;
}
@media (max-width: 600px) {
    .order-details {
        padding: 14px 10px !important;
    }
}

.order-details-grid {
    display: grid !important;
    grid-template-columns: 1.5fr 1fr !important;
    gap: 16px !important;
}
@media (max-width: 768px) {
    .order-details-grid {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
    }
}

.btn-black-sm {
    background: #1a1a1a !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 99px !important;
    font-weight: 700 !important;
    padding: 10px 20px !important;
    font-size: 0.7rem !important;
    letter-spacing: 1px !important;
    text-transform: uppercase !important;
    cursor: pointer !important;
}

.status-badge {
    display: inline-block;
    padding: 3px 8px;
    font-size: 0.6rem;
    font-weight: 700;
    border-radius: 99px;
    text-transform: uppercase;
}
.status-badge.pending { background: #fef3c7 !important; color: #d97706 !important; border: 1px solid #d97706 !important; }
.status-badge.processing { background: #dbeafe !important; color: #2563eb !important; border: 1px solid #2563eb !important; }
.status-badge.completed { background: #dcfce7 !important; color: #15803d !important; border: 1px solid #16a34a !important; }
.status-badge.cancelled { background: #fee2e2 !important; color: #b91c1c !important; border: 1px solid #dc2626 !important; }
</style>

<div class="customer-page-section">
    <div class="account-container-inner">
        
        <!-- Breadcrumbs & Account Header -->
        <div class="account-header-box">
            <div class="account-breadcrumbs">
                <a href="<?php echo BASE_URL; ?>/index.php">HOME</a>
                <span>/</span>
                <a href="index.php">ACCOUNT</a>
                <span>/</span>
                <span style="color: #1a1a1a; font-weight: 700;">ORDER HISTORY</span>
            </div>
            <h1 class="account-title">ORDER HISTORY</h1>
            <p class="account-subtitle">View and track all your previous purchases</p>
        </div>

        <div class="account-grid-layout">
            
            <!-- Sidebar Navigation Tabs -->
            <aside>
                <div class="account-nav-list">
                    <a href="index.php" class="account-nav-link">
                        <i data-lucide="user" style="width: 16px; height: 16px;"></i> Profile Details
                    </a>
                    <a href="order_history.php" class="account-nav-link active">
                        <i data-lucide="package" style="width: 16px; height: 16px;"></i> Order History
                    </a>
                    <a href="<?php echo BASE_URL; ?>/wishlist.php" class="account-nav-link">
                        <i data-lucide="heart" style="width: 16px; height: 16px;"></i> Wishlist
                    </a>
                    <a href="<?php echo BASE_URL; ?>/logout.php" class="account-nav-link danger">
                        <i data-lucide="log-out" style="width: 16px; height: 16px;"></i> Logout
                    </a>
                </div>
            </aside>

            <div>
                <div class="order-accordion" style = "margin-bottom: 4rem;">
                <?php
                $orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
                $orders->bind_param("i", $user_id);
                $orders->execute();
                $result = $orders->get_result();
                
                if ($result->num_rows > 0) {
                    while ($o = $result->fetch_assoc()) {
                        $order_id = $o['id'];
                        
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
                                    <span class="order-header-label">Order</span>
                                    <span class="order-header-value order-number">#<?php echo $order_id; ?></span>
                                </div>
                                <div class="order-header-cell">
                                    <span class="order-header-label">Date</span>
                                    <span class="order-header-value"><?php echo date('M d, Y', strtotime($o['created_at'])); ?></span>
                                </div>
                                <div class="order-header-cell">
                                    <span class="order-header-label">Total</span>
                                    <span class="order-header-value">₹<?php echo number_format($o['total_amount']); ?></span>
                                </div>
                                <div class="order-header-cell">
                                    <span class="order-header-label">Status</span>
                                    <div>
                                        <span class="status-badge <?php echo $status; ?>"><?php echo $status; ?></span>
                                    </div>
                                </div>
                                <div class="order-chevron">
                                    <i data-lucide="chevron-down" style="width: 16px; height: 16px;"></i>
                                </div>
                            </div>
                            
                            <div class="order-details" id="order-details-<?php echo $order_id; ?>" style="display: none;">
                                <?php if ($status !== 'cancelled'): ?>
                                <div class="order-timeline-wrapper" style="margin-bottom: 16px;">
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
                                <div style="background: #fee2e2; border: 1px solid #dc2626; border-radius: 8px; padding: 10px; margin-bottom: 14px; color: #b91c1c; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;">
                                    <i data-lucide="alert-circle" style="width: 16px; height: 16px;"></i>
                                    This order has been cancelled
                                </div>
                                <?php endif; ?>

                                <div class="order-details-grid">
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #1a1a1a; margin-bottom: 10px;">Items in Order</div>
                                        <div class="order-items-list">
                                            <?php foreach ($items as $item): 
                                                $img_src = $item['image'] ? BASE_URL . '/assets/' . htmlspecialchars($item['image']) : '/assets/product_hoodie.png';
                                            ?>
                                            <div class="order-item-row" style="display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 8px 0; border-bottom: 1px solid #eaeaea;">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <img src="<?php echo $img_src; ?>" class="order-item-img" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                    <div class="order-item-info">
                                                        <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $item['product_id']; ?>" class="order-item-name" style="font-size: 0.8rem; font-weight: 700; color: #1a1a1a; text-decoration: none; display: block;"><?php echo htmlspecialchars($item['name']); ?></a>
                                                        <div class="order-item-meta" style="font-size: 0.7rem; color: #666; margin-top: 2px;">Qty: <?php echo $item['quantity']; ?></div>
                                                    </div>
                                                </div>
                                                <div class="order-item-price-qty" style="font-size: 0.8rem; font-weight: 700; color: #1a1a1a;">
                                                    ₹<?php echo number_format($item['price']); ?>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <div style="background: #ffffff; border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 12px; padding: 14px;">
                                            <div style="color: #1a1a1a; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; border-bottom: 1px solid rgba(0, 0, 0, 0.08); padding-bottom: 6px;">Delivery Details</div>
                                            <div style="margin-bottom: 6px;">
                                                <span style="display: block; font-size: 0.65rem; color: #666; text-transform: uppercase; font-weight: 700;">Shipping Address</span>
                                                <span style="font-size: 0.75rem; color: #1a1a1a; font-weight: 600; line-height: 1.4; display: block; margin-top: 2px;"><?php echo htmlspecialchars($o['shipping_address']); ?></span>
                                            </div>
                                            <div style="margin-bottom: 6px;">
                                                <span style="display: block; font-size: 0.65rem; color: #666; text-transform: uppercase; font-weight: 700;">Payment Method</span>
                                                <span style="font-size: 0.75rem; color: #1a1a1a; font-weight: 600; text-transform: uppercase;"><?php echo htmlspecialchars($o['payment_method']); ?></span>
                                            </div>
                                            <div>
                                                <span style="display: block; font-size: 0.65rem; color: #666; text-transform: uppercase; font-weight: 700;">Payment Status</span>
                                                <span style="font-size: 0.75rem; color: #1a1a1a; font-weight: 600; text-transform: uppercase;"><?php echo htmlspecialchars($o['payment_status']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="order-details-actions" style="margin-top: 14px; display: flex; gap: 10px; flex-wrap: wrap;">
                                    <form method="POST" style="margin: 0;">
                                        <input type="hidden" name="reorder_id" value="<?php echo $order_id; ?>">
                                        <button type="submit" class="btn-black-sm">REORDER ITEMS</button>
                                    </form>
                                    
                                    <?php if ($status === 'pending' || $status === 'processing'): ?>
                                    <form method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        <input type="hidden" name="cancel_order_id" value="<?php echo $order_id; ?>">
                                        <button type="submit" class="btn-black-sm" style="background: transparent !important; color: #dc2626 !important; border-color: #dc2626 !important;">CANCEL ORDER</button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div style="text-align: center; padding: 40px 16px; background: #ffffff; border: 1.5px dashed rgba(0, 0, 0, 0.15); border-radius: 16px; width: 100%; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <i data-lucide="package" style="width: 44px; height: 44px; color: #888888; margin-bottom: 12px;"></i>
                        <p style="color: #1a1a1a; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin: 0 0 16px 0; font-size: 0.8rem; text-align: center; width: 100%;">No orders found in your account</p>
                        <a href="<?php echo BASE_URL; ?>/shop.php" class="btn-black-sm" style="text-decoration: none; display: inline-flex;">Start Shopping</a>
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
