<?php
require_once 'includes/header.php';

// Quick stats
$users_count = $conn->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetch_row()[0];
$products_count = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$orders_count = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$revenue = $conn->query("SELECT SUM(total_amount) FROM orders WHERE payment_status='paid' OR payment_method='cod'")->fetch_row()[0];
?>

<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; margin-bottom: 0.5rem; letter-spacing: -0.01em; color: #f0f0f0;">Overview</h1>
    <p style="color: #888; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase;">Store Performance Dashboard</p>
</div>

<div class="admin-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <div style="background: #161616; border: 1px solid rgba(255,255,255,0.08); padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <h4 style="color: #888; margin: 0 0 10px 0; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px;">Customers</h4>
        <div class="admin-stats-value" style="font-size: 2.5rem; font-weight: 400; font-family: var(--font-serif); color: #f0f0f0;"><?php echo $users_count; ?></div>
    </div>
    <div style="background: #161616; border: 1px solid rgba(255,255,255,0.08); padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <h4 style="color: #888; margin: 0 0 10px 0; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px;">Products</h4>
        <div class="admin-stats-value" style="font-size: 2.5rem; font-weight: 400; font-family: var(--font-serif); color: #f0f0f0;"><?php echo $products_count; ?></div>
    </div>
    <div style="background: #161616; border: 1px solid rgba(255,255,255,0.08); padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <h4 style="color: #888; margin: 0 0 10px 0; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px;">Orders</h4>
        <div class="admin-stats-value" style="font-size: 2.5rem; font-weight: 400; font-family: var(--font-serif); color: #f0f0f0;"><?php echo $orders_count; ?></div>
    </div>
    <div style="background: #161616; border: 1px solid rgba(255,255,255,0.08); padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <h4 style="color: #888; margin: 0 0 10px 0; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px;">Revenue</h4>
        <div class="admin-stats-value" style="font-size: 2.5rem; font-weight: 400; font-family: var(--font-serif); color: #a78bfa;">₹<?php echo number_format($revenue ?: 0, 2); ?></div>
    </div>
</div>

<div style="background: #161616; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); overflow: hidden;">
    <div style="padding: 24px; border-bottom: 1px solid rgba(255,255,255,0.08);">
        <h3 style="margin: 0; font-size: 1.1rem; letter-spacing: 1px; text-transform: uppercase; color: #f0f0f0;">Recent Orders</h3>
    </div>
    <div class="admin-table-wrap">
        <table class="table" style="margin: 0; width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $orders = $conn->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");
                if ($orders->num_rows > 0) {
                    while ($order = $orders->fetch_assoc()) {
                        $status_bg    = $order['status']=='pending'    ? 'rgba(250,204,21,0.15)' : 'rgba(34,197,94,0.15)';
                        $status_color = $order['status']=='pending'    ? '#facc15' : '#4ade80';
                        echo '<tr>
                            <td style="padding: 16px 24px; font-weight: 600; color: #e0e0e0;">#'.$order['id'].'</td>
                            <td style="padding: 16px 24px; color: #e0e0e0;">'.htmlspecialchars($order['user_name']).'</td>
                            <td style="padding: 16px 24px; color: #e0e0e0;">₹'.number_format($order['total_amount'], 2).'</td>
                            <td style="padding: 16px 24px; font-size: 0.85rem; letter-spacing: 1px; color: #aaa;">'.strtoupper($order['payment_method']).'</td>
                            <td style="padding: 16px 24px;"><span style="padding: 6px 12px; border-radius: 20px; font-size:0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; background: '.$status_bg.'; color: '.$status_color.';">'.ucfirst($order['status']).'</span></td>
                            <td style="padding: 16px 24px; color: #888;">'.date('M d, Y', strtotime($order['created_at'])).'</td>
                        </tr>';
                    }
                } else {
                    echo '<tr><td colspan="6" style="text-align:center; padding: 40px; color: #666;">No recent orders found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
