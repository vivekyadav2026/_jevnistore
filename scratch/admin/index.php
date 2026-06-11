<?php
require_once 'includes/header.php';

// Quick stats
$users_count = $conn->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetch_row()[0];
$products_count = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$orders_count = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$revenue = $conn->query("SELECT SUM(total_amount) FROM orders WHERE payment_status='paid' OR payment_method='cod'")->fetch_row()[0];
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <div style="background: #111; border: 1px solid #333; padding: 20px; border-radius: 8px;">
        <h4 style="color: var(--text-secondary); margin: 0 0 10px 0; font-size: 0.9rem; text-transform: uppercase;">Total Customers</h4>
        <div style="font-size: 2rem; font-weight: 700;"><?php echo $users_count; ?></div>
    </div>
    <div style="background: #111; border: 1px solid #333; padding: 20px; border-radius: 8px;">
        <h4 style="color: var(--text-secondary); margin: 0 0 10px 0; font-size: 0.9rem; text-transform: uppercase;">Total Products</h4>
        <div style="font-size: 2rem; font-weight: 700;"><?php echo $products_count; ?></div>
    </div>
    <div style="background: #111; border: 1px solid #333; padding: 20px; border-radius: 8px;">
        <h4 style="color: var(--text-secondary); margin: 0 0 10px 0; font-size: 0.9rem; text-transform: uppercase;">Total Orders</h4>
        <div style="font-size: 2rem; font-weight: 700;"><?php echo $orders_count; ?></div>
    </div>
    <div style="background: #111; border: 1px solid #333; padding: 20px; border-radius: 8px;">
        <h4 style="color: var(--text-secondary); margin: 0 0 10px 0; font-size: 0.9rem; text-transform: uppercase;">Est. Revenue</h4>
        <div style="font-size: 2rem; font-weight: 700; color: var(--accent);">₹<?php echo number_format($revenue ?: 0, 2); ?></div>
    </div>
</div>

<h3 style="margin-bottom: 1rem; border-bottom: 1px solid #333; padding-bottom: 10px;">Recent Orders</h3>
<table class="table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>User</th>
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
                echo '<tr>
                    <td>#'.$order['id'].'</td>
                    <td>'.htmlspecialchars($order['user_name']).'</td>
                    <td>₹'.$order['total_amount'].'</td>
                    <td>'.strtoupper($order['payment_method']).'</td>
                    <td><span style="padding: 3px 8px; border-radius: 12px; font-size:0.8rem; background: #222;">'.ucfirst($order['status']).'</span></td>
                    <td>'.date('M d, Y', strtotime($order['created_at'])).'</td>
                </tr>';
            }
        } else {
            echo '<tr><td colspan="6" style="text-align:center; padding: 20px;">No recent orders found.</td></tr>';
        }
        ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
