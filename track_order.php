<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$user_id = $_SESSION['user_id'] ?? 0;
$order_id = isset($_REQUEST['order_id']) ? (int)$_REQUEST['order_id'] : 0;
$billing_email = trim($_REQUEST['email'] ?? '');
$billing_phone = trim($_REQUEST['phone'] ?? '');

$order = null;
$error = '';
$tracking_info = null;
$recent_orders = [];

// Fetch user's recent orders if logged in
if ($user_id > 0) {
    $o_stmt = $conn->prepare("SELECT id, total_amount, status, created_at FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 5");
    $o_stmt->bind_param("i", $user_id);
    $o_stmt->execute();
    $o_res = $o_stmt->get_result();
    while ($o_row = $o_res->fetch_assoc()) {
        $recent_orders[] = $o_row;
    }
}

// Order lookup request
if ($order_id > 0) {
    // If user is logged in, check if the order belongs to them to skip verification
    if ($user_id > 0) {
        $stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ? AND o.user_id = ?");
        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $order = $res->fetch_assoc();
        }
    }
    
    // If not loaded yet (or not logged in), perform email/phone verification lookup
    if (!$order) {
        if (empty($billing_email) && empty($billing_phone)) {
            $error = 'Please enter your Billing Email or Phone Number for verification.';
        } else {
            $stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $res = $stmt->get_result();
            
            if ($res->num_rows > 0) {
                $candidate = $res->fetch_assoc();
                
                $verify_email = strtolower(trim($billing_email));
                $verify_phone = preg_replace('/[^0-9]/', '', $billing_phone);
                
                $db_email = strtolower(trim($candidate['user_email']));
                $db_phone = preg_replace('/[^0-9]/', '', $candidate['user_phone']);
                
                $email_match = !empty($verify_email) && ($db_email === $verify_email);
                $phone_match = !empty($verify_phone) && ($db_phone === $verify_phone);
                
                if ($email_match || $phone_match) {
                    $order = $candidate;
                } else {
                    $error = 'Verification failed. The Email or Phone Number provided does not match this Order ID.';
                }
            } else {
                $error = 'No order found with the provided Order ID.';
            }
        }
    }

    // Fetch live tracking if order is loaded and has a Shiprocket Shipment ID
    if ($order && !empty($order['shiprocket_shipment_id'])) {
        $tracking_info = getShiprocketTrackingInfo($order['shiprocket_shipment_id']);
    }
}
?>

<style>
    .track-container {
        max-width: 800px;
        margin: 60px auto 100px;
        padding: 0 24px;
    }
    
    .track-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .track-header h1 {
        font-family: var(--font-primary);
        font-size: 2.2rem;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #ffffff;
        margin-bottom: 12px;
    }
    
    .track-header p {
        font-size: 0.85rem;
        color: #a1a1aa;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 500;
    }
    
    .track-card {
        background: #ffffff;
        border: 1.5px solid #1a1a1a;
        border-radius: 4px;
        padding: 30px;
        margin-bottom: 30px;
    }
    
    .track-form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .track-form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    
    .track-input {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #d1d5db;
        border-radius: 4px;
        font-family: inherit;
        font-size: 0.85rem;
        outline: none;
        transition: border-color 0.2s;
    }
    
    .track-input:focus {
        border-color: #1a1a1a;
    }
    
    .track-btn {
        background: #1a1a1a;
        color: #ffffff;
        border: 1.5px solid #1a1a1a;
        border-radius: 4px;
        width: 100%;
        padding: 14px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 10px;
    }
    
    .track-btn:hover {
        background: transparent;
        color: #1a1a1a;
    }
    
    .track-error {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        color: #b91c1c;
        padding: 12px 16px;
        border-radius: 4px;
        font-size: 0.8rem;
        margin-bottom: 24px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
    }
    
    /* Order Details Styling */
    .order-status-badge {
        display: inline-block;
        padding: 4px 10px;
        font-size: 0.65rem;
        font-weight: 700;
        border-radius: 3px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .badge-pending { background: #fef3c7; color: #d97706; }
    .badge-processing { background: #dbeafe; color: #2563eb; }
    .badge-completed { background: #dcfce7; color: #16a34a; }
    .badge-cancelled { background: #fee2e2; color: #dc2626; }
    
    .order-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        border-bottom: 1.5px solid #1a1a1a;
        padding-bottom: 20px;
        margin-bottom: 24px;
    }
    
    @media (max-width: 600px) {
        .order-meta-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .meta-item-title {
        font-size: 0.65rem;
        color: #666666;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    
    .meta-item-value {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    /* Scans Timeline */
    .timeline-wrapper {
        margin-top: 20px;
        border-left: 2px solid #1a1a1a;
        padding-left: 20px;
    }
    
    .timeline-event {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-event::before {
        content: '';
        position: absolute;
        left: -27px;
        top: 2px;
        width: 12px;
        height: 12px;
        background: #1a1a1a;
        border-radius: 50%;
        border: 2px solid #ffffff;
    }
    
    .timeline-event.active::before {
        background: #16a34a;
    }
    
    .event-time {
        font-size: 0.65rem;
        font-weight: 700;
        color: #666666;
        text-transform: uppercase;
    }
    
    .event-desc {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 4px 0;
    }
    
    .event-loc {
        font-size: 0.7rem;
        color: #555555;
        font-style: italic;
    }
    
    .recent-orders-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    
    .recent-orders-table th, 
    .recent-orders-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eaeaea;
        font-size: 0.8rem;
        color: #1a1a1a;
    }
    
    .recent-orders-table th {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #666;
        border-bottom: 1.5px solid #1a1a1a;
    }
    
    .track-mini-btn {
        background: #1a1a1a;
        color: white;
        border: none;
        padding: 6px 12px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 3px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    
    .track-mini-btn:hover {
        background: #444;
    }

    @media (max-width: 600px) {
        .track-card {
            padding: 20px 16px;
        }
        .track-container {
            margin: 30px auto 80px;
            padding: 0 16px;
        }
        .recent-orders-table th,
        .recent-orders-table td {
            padding: 10px 8px;
            font-size: 0.75rem;
            white-space: nowrap;
        }
        .recent-orders-table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -16px;
            padding: 0 16px;
        }
    }
</style>

<div class="container track-container">
    <div class="track-header">
        <h1>Track Your Order</h1>
        <p>Real-time delivery status & shipping tracker</p>
    </div>
    
    <?php if (!empty($error)): ?>
        <div class="track-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Recent Orders (Only if logged in) -->
    <?php if ($user_id > 0 && !empty($recent_orders)): ?>
        <div class="track-card">
            <h2 style="font-family: var(--font-primary); font-size: 1.1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 15px; color: #1a1a1a;">Your Recent Orders</h2>
            <div class="recent-orders-table-wrapper" style="overflow-x: auto;">
                <table class="recent-orders-table" style="min-width: 480px;">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $ro): ?>
                            <tr>
                                <td style="font-weight: 700;">#<?php echo $ro['id']; ?></td>
                                <td><?php echo date('d M Y', strtotime($ro['created_at'])); ?></td>
                                <td style="font-weight: 600;">₹<?php echo number_format($ro['total_amount']); ?></td>
                                <td>
                                    <span class="order-status-badge badge-<?php echo strtolower($ro['status']); ?>" style="padding: 2px 6px; font-size: 0.55rem;">
                                        <?php echo htmlspecialchars($ro['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="track_order.php?order_id=<?php echo $ro['id']; ?>" class="track-mini-btn">Track</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Lookup Form -->
    <div class="track-card">
        <h2 style="font-family: var(--font-primary); font-size: 1.1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 20px; color: #1a1a1a;">Lookup Order</h2>
        <form method="POST" action="track_order.php">
            <div class="track-form-grid">
                <div>
                    <label class="track-form-label">Order ID</label>
                    <input type="number" name="order_id" required class="track-input" placeholder="e.g. 10042" value="<?php echo $order_id > 0 ? $order_id : ''; ?>">
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label class="track-form-label">Billing Email</label>
                        <input type="email" name="email" class="track-input" placeholder="name@domain.com" value="<?php echo htmlspecialchars($billing_email); ?>">
                    </div>
                    <div>
                        <label class="track-form-label">Billing Phone</label>
                        <input type="text" name="phone" class="track-input" placeholder="e.g. 9871234567" value="<?php echo htmlspecialchars($billing_phone); ?>">
                    </div>
                </div>
                
                <p style="font-size: 0.65rem; color: #666; text-transform: uppercase; letter-spacing: 0.5px; margin: 0; line-height: 1.4;">
                    * If not logged in, please verify by entering the Email Address or Phone Number used during checkout to track the order.
                </p>
                
                <button type="submit" class="track-btn">Track Shipment</button>
            </div>
        </form>
    </div>

    <!-- Display Results -->
    <?php if ($order !== null): ?>
        <div class="track-card" id="tracking-results">
            <h2 style="font-family: var(--font-primary); font-size: 1.1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 1.5px solid #1a1a1a; padding-bottom: 12px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                <span>Tracking Order #<?php echo $order['id']; ?></span>
                <span class="order-status-badge badge-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span>
            </h2>
            
            <div class="order-meta-grid">
                <div>
                    <div class="meta-item-title">Customer Name</div>
                    <div class="meta-item-value"><?php echo htmlspecialchars($order['user_name']); ?></div>
                </div>
                <div>
                    <div class="meta-item-title">Order Date</div>
                    <div class="meta-item-value"><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></div>
                </div>
                <div>
                    <div class="meta-item-title">Payment Method</div>
                    <div class="meta-item-value" style="text-transform: uppercase;"><?php echo htmlspecialchars($order['payment_method']); ?></div>
                </div>
                <div>
                    <div class="meta-item-title">Payment Status</div>
                    <div class="meta-item-value" style="text-transform: uppercase;"><?php echo htmlspecialchars($order['payment_status']); ?></div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div style="margin-bottom: 24px;">
                <div class="meta-item-title">Shipping Destination</div>
                <div class="meta-item-value" style="font-family: monospace; white-space: pre-line; line-height: 1.5; font-size: 0.8rem; background: #fafafa; padding: 12px; border-radius: 4px; border: 1.5px dashed #d1d5db; margin-top: 6px;">
                    <?php echo htmlspecialchars($order['shipping_address']); ?>
                </div>
            </div>

            <!-- Items -->
            <div style="margin-bottom: 24px;">
                <div class="meta-item-title">Line Items</div>
                <div style="margin-top: 6px;">
                    <?php 
                    $items_stmt = $conn->prepare("SELECT oi.*, p.name as prod_name, p.image as prod_image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                    $items_stmt->bind_param("i", $order['id']);
                    $items_stmt->execute();
                    $items = $items_stmt->get_result();
                    
                    while($item = $items->fetch_assoc()):
                        $p_image = $item['prod_image'] ? BASE_URL . '/assets/' . htmlspecialchars($item['prod_image']) : '/assets/product_pants.png';
                    ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eaeaea; padding: 10px 0;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="<?php echo $p_image; ?>" style="width: 45px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                <div>
                                    <div style="font-size: 0.8rem; font-weight: 600; color: #1a1a1a;"><?php echo htmlspecialchars($item['prod_name']); ?></div>
                                    <?php if (!empty($item['variant'])): ?>
                                        <div style="font-size: 0.65rem; color: #888; text-transform: uppercase; font-weight: 700; margin-top: 2px;">Model: <?php echo htmlspecialchars($item['variant']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div style="text-align: right; font-size: 0.8rem; font-weight: 700;">
                                <?php echo $item['quantity']; ?> x ₹<?php echo number_format($item['price']); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; font-weight: 700; font-size: 0.9rem;">
                    <span>Total Paid</span>
                    <span style="font-size: 1rem; color: #1a1a1a;">₹<?php echo number_format($order['total_amount']); ?></span>
                </div>
            </div>

            <!-- Shiprocket Live Tracking -->
            <?php if (!empty($order['shiprocket_shipment_id'])): ?>
                <div style="border-top: 1.5px solid #1a1a1a; padding-top: 20px; margin-top: 20px;">
                    <div class="meta-item-title">Delivery Status (Shiprocket Tracker)</div>
                    
                    <?php 
                    $scans = [];
                    $awb = '';
                    $courier = '';
                    $current_status = 'Pending Pickup';
                    
                    if ($tracking_info && isset($tracking_info['tracking_data']['shipment_track'][0])) {
                        $track = $tracking_info['tracking_data']['shipment_track'][0];
                        $awb = $track['awb_code'] ?? '';
                        $courier = $track['courier_name'] ?? '';
                        $current_status = $track['current_status'] ?? 'Shipped / Label Generated';
                        $scans = $track['scans'] ?? [];
                    }
                    ?>

                    <div style="background: #f9fafb; padding: 20px; border-radius: 4px; border: 1.5px solid #eaeaea; margin-top: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                            <div>
                                <span style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: #666; display: block;">Current Location status</span>
                                <span style="font-size: 1.1rem; font-weight: 700; color: #16a34a; text-transform: uppercase;"><?php echo htmlspecialchars($current_status); ?></span>
                            </div>
                            <?php if (!empty($awb)): ?>
                                <div style="text-align: right;">
                                    <span style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: #666; display: block;">Tracking AWB (<?php echo htmlspecialchars($courier); ?>)</span>
                                    <span style="font-size: 0.9rem; font-family: monospace; font-weight: 700; color: #1a1a1a;"><?php echo htmlspecialchars($awb); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Scans Timeline -->
                        <?php if (!empty($scans)): ?>
                            <div class="timeline-wrapper">
                                <?php foreach ($scans as $idx => $scan): ?>
                                    <div class="timeline-event <?php echo $idx === 0 ? 'active' : ''; ?>">
                                        <div class="event-time"><?php echo date('d M Y, h:i A', strtotime($scan['date'])); ?></div>
                                        <div class="event-desc"><?php echo htmlspecialchars($scan['activity']); ?></div>
                                        <?php if (!empty($scan['location'])): ?>
                                            <div class="event-loc">Location: <?php echo htmlspecialchars($scan['location']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="font-size: 0.8rem; color: #555555; display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                                <i data-lucide="info" style="width: 16px; height: 16px; color: #d97706;"></i>
                                <span>The package has been synced with Shiprocket. Once the courier picks up the parcel, live scan details will appear here.</span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($awb)): ?>
                            <a href="https://www.shiprocket.in/shipment-tracking/" target="_blank" class="track-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; margin-top: 20px;">
                                <i data-lucide="external-link" style="width: 14px; height: 14px;"></i> Track via Shiprocket Portal
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div style="border-top: 1.5px solid #1a1a1a; padding-top: 20px; margin-top: 20px; display: flex; align-items: center; gap: 12px;">
                    <i data-lucide="clock" style="color: #d97706; flex-shrink:0;"></i>
                    <p style="font-size: 0.8rem; color: #555555; margin: 0; line-height: 1.5;">
                        Your order is being processed at our headquarters and is awaiting carrier pickup. Once shipped, live tracking parameters will automatically activate here.
                    </p>
                </div>
            <?php endif; ?>
        </div>
        
        <script>
            // Auto scroll to results
            document.addEventListener('DOMContentLoaded', () => {
                const el = document.getElementById('tracking-results');
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth' });
                }
            });
        </script>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
