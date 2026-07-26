<?php
/**
 * ============================================================================
 * ADMIN DASHBOARD OVERVIEW (admin/index.php)
 * ============================================================================
 * Displays core e-commerce metrics: total customers, products, orders,
 * total store revenue, and recent orders activity log.
 */
require_once 'includes/header.php';

// Quick stats queries
$users_count    = $conn->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetch_row()[0];
$products_count = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$orders_count   = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$revenue        = $conn->query("SELECT SUM(total_amount) FROM orders WHERE payment_status='paid' OR payment_method='cod'")->fetch_row()[0];
?>

<!-- Header Section -->
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.8rem; margin-bottom: 0.4rem; letter-spacing: -0.01em; color: #f8fafc; font-weight: 700;">Dashboard Overview</h1>
    <p style="color: #94a3b8; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; font-weight: 500;">Store Performance & Metrics Summary</p>
</div>

<!-- Stats Cards Grid -->
<div class="admin-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    
    <!-- Total Customers Card -->
    <div style="background: var(--bg-secondary); border: 1px solid rgba(255,255,255,0.1); padding: 24px; border-radius: 12px; transition: transform 0.2s, border-color 0.2s;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h4 style="color: #94a3b8; margin: 0; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">Customers</h4>
            <div style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="users" style="width: 20px; height: 20px;"></i>
            </div>
        </div>
        <div class="admin-stats-value" style="font-size: 2.2rem; font-weight: 700; color: #f8fafc; font-family: inherit;"><?php echo number_format($users_count); ?></div>
        <div style="font-size: 0.78rem; color: #64748b; margin-top: 6px;">Registered shoppers</div>
    </div>
    
    <!-- Total Products Card -->
    <div style="background: var(--bg-secondary); border: 1px solid rgba(255,255,255,0.1); padding: 24px; border-radius: 12px; transition: transform 0.2s, border-color 0.2s;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h4 style="color: #94a3b8; margin: 0; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">Products</h4>
            <div style="background: rgba(168, 85, 247, 0.15); color: #c084fc; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="package" style="width: 20px; height: 20px;"></i>
            </div>
        </div>
        <div class="admin-stats-value" style="font-size: 2.2rem; font-weight: 700; color: #f8fafc; font-family: inherit;"><?php echo number_format($products_count); ?></div>
        <div style="font-size: 0.78rem; color: #64748b; margin-top: 6px;">Catalog items</div>
    </div>
    
    <!-- Total Orders Card -->
    <div style="background: var(--bg-secondary); border: 1px solid rgba(255,255,255,0.1); padding: 24px; border-radius: 12px; transition: transform 0.2s, border-color 0.2s;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h4 style="color: #94a3b8; margin: 0; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">Orders</h4>
            <div style="background: rgba(251, 146, 60, 0.15); color: #fb923c; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="shopping-bag" style="width: 20px; height: 20px;"></i>
            </div>
        </div>
        <div class="admin-stats-value" style="font-size: 2.2rem; font-weight: 700; color: #f8fafc; font-family: inherit;"><?php echo number_format($orders_count); ?></div>
        <div style="font-size: 0.78rem; color: #64748b; margin-top: 6px;">Total placed orders</div>
    </div>
    
    <!-- Total Revenue Card -->
    <div style="background: var(--bg-secondary); border: 1px solid rgba(255,255,255,0.1); padding: 24px; border-radius: 12px; transition: transform 0.2s, border-color 0.2s;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h4 style="color: #94a3b8; margin: 0; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">Revenue</h4>
            <div style="background: rgba(74, 222, 128, 0.15); color: #4ade80; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="indian-rupee" style="width: 20px; height: 20px;"></i>
            </div>
        </div>
        <div class="admin-stats-value" style="font-size: 2.2rem; font-weight: 700; color: #38bdf8; font-family: inherit;">₹<?php echo number_format($revenue ?: 0, 2); ?></div>
        <div style="font-size: 0.78rem; color: #64748b; margin-top: 6px;">Total sales income</div>
    </div>
    
</div>

<!-- Recent Orders Activity Log -->
<div style="background: var(--bg-secondary); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; overflow: hidden;">
    <div style="padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 1rem; letter-spacing: 1px; text-transform: uppercase; color: #f8fafc; font-weight: 700;">Recent Orders</h3>
        <a href="orders.php" style="color: #38bdf8; text-decoration: none; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 4px;">
            View All Orders &rarr;
        </a>
    </div>
    <div class="admin-table-wrap" style="overflow-x: auto;">
        <table class="table" style="margin: 0; width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $orders = $conn->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");
                if ($orders && $orders->num_rows > 0) {
                    while ($order = $orders->fetch_assoc()) {
                        $status_bg    = $order['status']=='pending'    ? 'rgba(250,204,21,0.15)' : ($order['status']=='completed' ? 'rgba(34,197,94,0.15)' : 'rgba(56,189,248,0.15)');
                        $status_color = $order['status']=='pending'    ? '#facc15' : ($order['status']=='completed' ? '#4ade80' : '#38bdf8');
                        echo '<tr>
                            <td style="padding: 16px 24px; font-weight: 700; color: #f8fafc;">#'.$order['id'].'</td>
                            <td style="padding: 16px 24px; color: #cbd5e1; font-weight: 500;">'.htmlspecialchars($order['user_name']).'</td>
                            <td style="padding: 16px 24px; color: #f8fafc; font-weight: 600;">₹'.number_format($order['total_amount'], 2).'</td>
                            <td style="padding: 16px 24px; font-size: 0.82rem; letter-spacing: 1px; color: #94a3b8; font-weight: 600;">'.strtoupper($order['payment_method']).'</td>
                            <td style="padding: 16px 24px;"><span style="padding: 4px 10px; border-radius: 20px; font-size:0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; background: '.$status_bg.'; color: '.$status_color.';">'.ucfirst($order['status']).'</span></td>
                            <td style="padding: 16px 24px; color: #94a3b8; font-size: 0.85rem;">'.date('M d, Y', strtotime($order['created_at'])).'</td>
                        </tr>';
                    }
                } else {
                    echo '<tr><td colspan="6" style="text-align:center; padding: 40px; color: #64748b;">No recent orders found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

