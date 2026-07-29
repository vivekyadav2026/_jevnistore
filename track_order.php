<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$user_id       = $_SESSION['user_id'] ?? 0;
$order_id      = isset($_REQUEST['order_id']) ? (int)$_REQUEST['order_id'] : 0;
$billing_email = strtolower(trim($_REQUEST['email'] ?? ''));

$order         = null;
$order_items   = [];
$error         = '';
$recent_orders = [];

// ── 1. Fetch user's recent orders (if logged in) ──────────────────────────
if ($user_id > 0) {
    $o_stmt = $conn->prepare(
        "SELECT id, total_amount, status, created_at
         FROM orders
         WHERE user_id = ?
         ORDER BY id DESC
         LIMIT 5"
    );
    $o_stmt->bind_param("i", $user_id);
    $o_stmt->execute();
    $o_res = $o_stmt->get_result();
    while ($row = $o_res->fetch_assoc()) {
        $recent_orders[] = $row;
    }
}

// ── 2. Track order on form submit ─────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $order_id > 0) {

    // Case A: Logged-in user — match by order_id + user_id (no email needed)
    if ($user_id > 0) {
        $stmt = $conn->prepare(
            "SELECT o.id, o.shipping_address, o.total_amount, o.status, o.payment_status, o.payment_method, o.created_at,
                    u.name as shipping_name, u.email as shipping_email, u.phone as shipping_phone
             FROM orders o
             JOIN users u ON o.user_id = u.id
             WHERE o.id = ? AND o.user_id = ?
             LIMIT 1"
        );
        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $order = $res->fetch_assoc();
        } else {
            $error = 'No order found with this Order ID in your account.';
        }
    }

    // Case B: Guest — verify by email
    if (!$order && $user_id === 0) {
        if (empty($billing_email)) {
            $error = 'Please enter the Email Address used during checkout.';
        } else {
            $stmt = $conn->prepare(
                "SELECT o.id, o.shipping_address, o.total_amount, o.status, o.payment_status, o.payment_method, o.created_at,
                        u.name as shipping_name, u.email as shipping_email, u.phone as shipping_phone
                 FROM orders o
                 JOIN users u ON o.user_id = u.id
                 WHERE o.id = ?
                 LIMIT 1"
            );
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows > 0) {
                $candidate = $res->fetch_assoc();
                // Verify email matches
                if (strtolower(trim($candidate['shipping_email'])) === $billing_email) {
                    $order = $candidate;
                } else {
                    $error = 'Email does not match this Order ID. Please check and try again.';
                }
            } else {
                $error = 'No order found with Order ID #' . $order_id . '.';
            }
        }
    }

    // Fetch order items if order found
    if ($order) {
        $i_stmt = $conn->prepare(
            "SELECT oi.quantity, oi.price, oi.variant,
                    p.name AS prod_name, p.image AS prod_image
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = ?"
        );
        $i_stmt->bind_param("i", $order['id']);
        $i_stmt->execute();
        $i_res = $i_stmt->get_result();
        while ($row = $i_res->fetch_assoc()) {
            $order_items[] = $row;
        }
    }
}
?>

<style>
/* Make header icons black on track order page */
#main-header .icon-btn,
#main-header .menu-toggle {
    color: #000000 !important;
}

/* ===== TRACK ORDER PAGE ===== */
.track-page {
    min-height: 100vh;
    background: #f5f4f2;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: calc(var(--header-height, 85px) + 30px) 16px 60px;
    box-sizing: border-box;
}

.track-card-wrap {
    width: 100%;
    max-width: 420px;
}

/* ── Main Search Card ── */
.track-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 4px 32px rgba(0,0,0,0.08);
    padding: 32px 28px 28px;
    margin-bottom: 20px;
}

.track-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1a1a1a;
    text-align: center;
    margin: 0 0 6px;
}
.track-subtitle {
    font-size: 0.78rem;
    color: #888;
    text-align: center;
    margin: 0 0 24px;
}

/* Input fields */
.track-input-wrap {
    position: relative;
    margin-bottom: 12px;
}
.track-input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    pointer-events: none;
    line-height: 1;
}
.track-input {
    width: 100%;
    padding: 14px 14px 14px 42px;
    border: 1.5px solid #e8e6e3;
    border-radius: 12px;
    font-family: inherit;
    font-size: 0.88rem;
    color: #1a1a1a;
    background: #fafaf9;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}
.track-input:focus {
    border-color: #e8175d;
    box-shadow: 0 0 0 3px rgba(232,23,93,0.08);
    background: #fff;
}
.track-input::placeholder { color: #bbb; }

/* Track button */
.track-btn {
    width: 100%;
    padding: 15px;
    background: #e8175d;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-family: inherit;
    font-size: 0.92rem;
    font-weight: 700;
    cursor: pointer;
    letter-spacing: 0.3px;
    margin-top: 6px;
    transition: background 0.2s, transform 0.15s;
}
.track-btn:hover  { background: #c91050; transform: translateY(-1px); }
.track-btn:active { transform: translateY(0); }

/* Error alert */
.track-error {
    background: #fff0f3;
    border: 1.5px solid #e8175d;
    color: #c91050;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 0.82rem;
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ── Recent Orders (logged-in) ── */
.recent-title {
    font-size: 0.7rem;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 12px;
}
.recent-chip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border-radius: 14px;
    padding: 12px 16px;
    margin-bottom: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.chip-id   { font-size: 0.88rem; font-weight: 700; color: #1a1a1a; }
.chip-date { font-size: 0.72rem; color: #888; margin-top: 2px; }
.chip-track {
    font-size: 0.72rem;
    font-weight: 700;
    color: #e8175d;
    text-decoration: none;
    background: #fff0f3;
    padding: 6px 14px;
    border-radius: 99px;
    transition: background 0.2s;
    white-space: nowrap;
}
.chip-track:hover { background: #ffd6e4; }

/* Status pills */
.status-pill {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 99px;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    white-space: nowrap;
}
.status-pending    { background:#fef3c7; color:#d97706; }
.status-processing { background:#dbeafe; color:#2563eb; }
.status-shipped    { background:#ede9fe; color:#7c3aed; }
.status-completed,
.status-delivered  { background:#dcfce7; color:#16a34a; }
.status-cancelled,
.status-failed     { background:#fee2e2; color:#dc2626; }

/* ── Order Result Card ── */
.result-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 4px 32px rgba(0,0,0,0.08);
    padding: 24px;
    margin-top: 16px;
}
.result-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    padding-bottom: 16px;
    border-bottom: 1.5px solid #f0efed;
}
.result-order-label { font-size:0.7rem; color:#888; text-transform:uppercase; letter-spacing:0.5px; font-weight:600; display:block; }
.result-order-num   { font-size:1.1rem; font-weight:700; color:#1a1a1a; }

.result-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 20px;
}
.meta-label { font-size:0.68rem; color:#888; text-transform:uppercase; letter-spacing:0.4px; font-weight:600; margin-bottom:3px; }
.meta-val   { font-size:0.84rem; font-weight:600; color:#1a1a1a; }

/* Shipping address box */
.ship-box {
    background: #fafaf9;
    border: 1.5px solid #f0efed;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 0.82rem;
    color: #555;
    line-height: 1.6;
    margin-bottom: 20px;
}

/* Items */
.item-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f5f4f2;
    gap: 10px;
}
.item-img  { width:44px; height:44px; object-fit:cover; border-radius:8px; border:1.5px solid #f0efed; flex-shrink:0; }
.item-name { font-size:0.82rem; font-weight:600; color:#1a1a1a; }
.item-variant { font-size:0.7rem; color:#888; margin-top:2px; }
.item-price   { font-size:0.82rem; font-weight:700; color:#1a1a1a; white-space:nowrap; }
.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 14px;
    border-top: 1.5px solid #f0efed;
    font-weight: 700;
    font-size: 0.9rem;
}
.total-amt { font-size:1.05rem; color:#1a1a1a; }

/* Order status progress */
.progress-wrap { margin: 20px 0; }
.progress-steps {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}
.progress-steps::before {
    content: '';
    position: absolute;
    top: 14px;
    left: 14px;
    right: 14px;
    height: 2px;
    background: #e8e6e3;
    z-index: 0;
}
.progress-fill {
    position: absolute;
    top: 14px;
    left: 14px;
    height: 2px;
    background: #e8175d;
    z-index: 1;
    transition: width 0.4s;
}
.step-dot-wrap { display:flex; flex-direction:column; align-items:center; gap:6px; z-index:2; }
.step-dot {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: #e8e6e3;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.65rem; font-weight: 700; color: #aaa;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e8e6e3;
}
.step-dot.done  { background:#e8175d; color:#fff; box-shadow: 0 0 0 2px #e8175d; }
.step-dot.active{ background:#fff; color:#e8175d; box-shadow: 0 0 0 2px #e8175d; }
.step-label { font-size:0.6rem; color:#aaa; font-weight:600; text-align:center; max-width:55px; }
.step-label.done, .step-label.active { color:#1a1a1a; }

@media (max-width: 480px) {
    .track-card { padding:24px 18px 20px; }
    .result-meta { grid-template-columns:1fr; gap:10px; }
    .step-label { font-size:0.55rem; max-width:44px; }
}
</style>

<div class="track-page">
<div class="track-card-wrap">

    <!-- ── Main Search Card ─────────────────────────── -->
    <div class="track-card">
        <h1 class="track-title">Track Order</h1>
        <p class="track-subtitle">Enter your Order ID &amp; Email to track</p>

        <?php if (!empty($error)): ?>
        <div class="track-error">
            <i data-lucide="alert-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="track_order.php" id="order-tracking-form">
            <!-- Order ID -->
            <div class="track-input-wrap">
                <span class="track-input-icon">#</span>
                <input type="number" name="order_id" class="track-input"
                       placeholder="Order ID (e.g. 4084)"
                       value="<?php echo $order_id > 0 ? $order_id : ''; ?>"
                       required>
            </div>

            <!-- Email (not required if logged in) -->
            <div class="track-input-wrap">
                <span class="track-input-icon">
                    <i data-lucide="mail" style="width:15px;height:15px;"></i>
                </span>
                <input type="email" name="email" class="track-input"
                       placeholder="Email address used at checkout"
                       value="<?php echo htmlspecialchars($billing_email); ?>"
                       <?php echo $user_id > 0 ? '' : 'required'; ?>>
            </div>

            <?php if ($user_id > 0): ?>
            <p style="font-size:0.72rem;color:#888;margin:0 0 12px;text-align:center;">
                Logged in — only Order ID needed.
            </p>
            <?php endif; ?>

            <button type="submit" class="track-btn">Track Order</button>
        </form>
    </div>

    <!-- ── Recent Orders (logged-in users) ───────────── -->
    <?php if ($user_id > 0 && !empty($recent_orders) && !$order): ?>
    <div style="margin-top:4px;">
        <div class="recent-title">Your Recent Orders</div>
        <?php foreach ($recent_orders as $ro): ?>
        <div class="recent-chip">
            <div>
                <div class="chip-id">#<?php echo $ro['id']; ?></div>
                <div class="chip-date">
                    <?php echo date('d M Y', strtotime($ro['created_at'])); ?>
                    · ₹<?php echo number_format($ro['total_amount']); ?>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="status-pill status-<?php echo strtolower($ro['status']); ?>">
                    <?php echo htmlspecialchars($ro['status']); ?>
                </span>
                <a href="track_order.php" onclick="fillForm(<?php echo $ro['id']; ?>);return false;" class="chip-track">Track</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <script>
    function fillForm(id) {
        const trackingForm = document.getElementById('order-tracking-form');
        if (trackingForm) {
            trackingForm.querySelector('input[name="order_id"]').value = id;
            trackingForm.submit();
        }
    }
    </script>
    <?php endif; ?>

    <!-- ── Order Result ────────────────────────────── -->
    <?php if ($order): ?>
    <div class="result-card" id="tracking-results">

        <!-- Header -->
        <div class="result-header">
            <div>
                <span class="result-order-label">Order</span>
                <span class="result-order-num">#<?php echo $order['id']; ?></span>
            </div>
            <span class="status-pill status-<?php echo strtolower($order['status']); ?>">
                <?php echo htmlspecialchars(ucfirst($order['status'])); ?>
            </span>
        </div>

        <!-- Progress bar -->
        <?php
        $steps = ['pending','processing','shipped','delivered'];
        $cur   = strtolower($order['status']);
        $cur_i = array_search($cur, $steps);
        if ($cur_i === false) $cur_i = ($cur === 'completed') ? 3 : 0;
        $is_fully_done = ($cur === 'completed' || $cur === 'delivered');
        $fill_pct = $cur_i > 0 ? ($cur_i / (count($steps)-1)) * 100 : 0;
        ?>
        <div class="progress-wrap">
            <div class="progress-steps">
                <div class="progress-fill" style="width:<?php echo $fill_pct; ?>%;"></div>
                <?php foreach ($steps as $si => $sl): 
                    $dot_class = ($si < $cur_i || ($si === $cur_i && $is_fully_done)) ? 'done' : (($si === $cur_i) ? 'active' : '');
                    $lbl_class = ($si < $cur_i || ($si === $cur_i && $is_fully_done)) ? 'done' : (($si === $cur_i) ? 'active' : '');
                ?>
                <div class="step-dot-wrap">
                    <div class="step-dot <?php echo $dot_class; ?>">
                        <?php if ($si < $cur_i || ($si === $cur_i && $is_fully_done)): ?>✓<?php else: echo $si+1; endif; ?>
                    </div>
                    <div class="step-label <?php echo $lbl_class; ?>"><?php echo ucfirst($sl); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Meta info -->
        <div class="result-meta">
            <div>
                <div class="meta-label">Customer</div>
                <div class="meta-val"><?php echo htmlspecialchars($order['shipping_name']); ?></div>
            </div>
            <div>
                <div class="meta-label">Order Date</div>
                <div class="meta-val"><?php echo date('d M Y', strtotime($order['created_at'])); ?></div>
            </div>
            <div>
                <div class="meta-label">Payment</div>
                <div class="meta-val" style="text-transform:uppercase;">
                    <?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?>
                </div>
            </div>
            <div>
                <div class="meta-label">Pay Status</div>
                <div class="meta-val status-pill status-<?php echo strtolower($order['payment_status']); ?>" style="padding:3px 8px;">
                    <?php echo htmlspecialchars(ucfirst($order['payment_status'] ?? 'N/A')); ?>
                </div>
            </div>
        </div>

        <!-- Shipping address -->
        <?php if (!empty($order['shipping_address'])): ?>
        <div class="meta-label" style="margin-bottom:6px;">Shipping To</div>
        <div class="ship-box">
            <?php echo nl2br(htmlspecialchars(
                $order['shipping_name'] . "\n" .
                $order['shipping_address']
            )); ?>
        </div>
        <?php endif; ?>

        <!-- Items -->
        <?php if (!empty($order_items)): ?>
        <div class="meta-label" style="margin-bottom:10px;">Items Ordered</div>
        <div>
            <?php foreach ($order_items as $item):
                $img = !empty($item['prod_image'])
                    ? BASE_URL . '/assets/' . htmlspecialchars($item['prod_image'])
                    : BASE_URL . '/assets/product_pants.png';
            ?>
            <div class="item-row">
                <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                    <img src="<?php echo $img; ?>" class="item-img" alt="">
                    <div style="min-width:0;">
                        <div class="item-name"><?php echo htmlspecialchars($item['prod_name']); ?></div>
                        <?php if (!empty($item['variant'])): ?>
                        <div class="item-variant"><?php echo htmlspecialchars($item['variant']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="item-price">
                    <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price']); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="total-row">
            <span>Total Paid</span>
            <span class="total-amt">₹<?php echo number_format($order['total_amount']); ?></span>
        </div>
        <?php endif; ?>

        <!-- Processing note if no shipment -->
        <div style="display:flex;align-items:flex-start;gap:10px;margin-top:20px;padding-top:16px;border-top:1.5px solid #f0efed;">
            <i data-lucide="clock" style="color:#e8175d;width:18px;height:18px;flex-shrink:0;margin-top:1px;"></i>
            <p style="font-size:0.8rem;color:#888;margin:0;line-height:1.6;">
                <?php if ($cur === 'shipped' || $cur === 'delivered'): ?>
                Your order has been shipped. Contact support for live courier tracking details.
                <?php else: ?>
                Your order is being processed at our warehouse. You will be notified once it is shipped.
                <?php endif; ?>
            </p>
        </div>

    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('tracking-results');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
    </script>
    <?php endif; ?>

</div><!-- /.track-card-wrap -->
</div><!-- /.track-page -->

<?php require_once 'includes/footer.php'; ?>
