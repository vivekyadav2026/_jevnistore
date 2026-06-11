<?php
require_once 'includes/header.php';

// Handle Order Status Update
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    setFlash('Order #' . $id . ' status updated to ' . ucfirst($status) . '.', 'success');
    redirect('orders.php');
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h3 style="margin: 0;">Manage Orders</h3>
</div>

<div style="background: #111; padding: 20px; border-radius: 8px; border: 1px solid #333; overflow-x: auto;">
    <table class="table" style="margin-top: 0;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $orders = $conn->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC");
            if ($orders->num_rows > 0) {
                while ($o = $orders->fetch_assoc()) {
                    echo '<tr>
                        <td>#'.$o['id'].'</td>
                        <td>'.htmlspecialchars($o['user_name']).'</td>
                        <td>₹'.number_format($o['total_amount'], 2).'</td>
                        <td>'.strtoupper($o['payment_method']).' - '.ucfirst($o['payment_status']).'</td>
                        <td><span style="padding: 3px 8px; border-radius: 12px; font-size:0.8rem; background: #222;">'.ucfirst($o['status']).'</span></td>
                        <td>'.date('M d, Y', strtotime($o['created_at'])).'</td>
                        <td style="white-space: nowrap;">
                            <!-- Edit Status -->
                            <select onchange="window.location.href=\'?id='.$o['id'].'&status=\'+this.value" style="background:#222; color:white; border:none; padding:6px 10px; border-radius:4px; font-size: 0.85rem; margin-right: 15px; cursor: pointer;">
                                <option value="pending" '.($o['status']=='pending'?'selected':'').'>Pending</option>
                                <option value="processing" '.($o['status']=='processing'?'selected':'').'>Processing</option>
                                <option value="completed" '.($o['status']=='completed'?'selected':'').'>Completed</option>
                                <option value="cancelled" '.($o['status']=='cancelled'?'selected':'').'>Cancelled</option>
                            </select>
                            
                            <!-- View Details Eye Icon -->
                            <button onclick="viewOrder('.$o['id'].')" style="background: none; border: none; color: var(--accent); cursor: pointer; display: inline-flex; align-items: center; justify-content: center; padding: 5px;" title="View Details">
                                <i data-lucide="eye" style="width: 20px; height: 20px;"></i>
                            </button>
                        </td>
                    </tr>';
                }
            } else {
                echo '<tr><td colspan="7" style="text-align:center;">No orders found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Order Details Modal -->
<div id="view-modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.85); z-index:100; padding: 40px 20px; overflow-y: auto;">
    <div style="background:#111; max-width: 700px; margin: 0 auto; padding: 30px; border-radius: 8px; border: 1px solid #333; color: white;">
        <div style="display:flex; justify-content:space-between; margin-bottom: 20px; border-bottom: 1px solid #222; padding-bottom: 10px;">
            <h4 style="margin:0; font-size:1.1rem; text-transform:uppercase; letter-spacing:1px; color: var(--accent);">Order Details</h4>
            <button onclick="closeViewModal()" style="background:none; border:none; color:white; cursor:pointer;"><i data-lucide="x"></i></button>
        </div>
        <div id="order-details-content">
            <!-- Loaded via AJAX -->
        </div>
    </div>
</div>

<script>
function viewOrder(id) {
    const contentDiv = document.getElementById('order-details-content');
    contentDiv.innerHTML = '<div style="padding: 50px; text-align: center; color: #888;">Loading Order Details...</div>';
    document.getElementById('view-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    fetch('ajax_order_details.php?id=' + id)
        .then(res => res.text())
        .then(html => {
            contentDiv.innerHTML = html;
            if (window.lucide) {
                lucide.createIcons();
            }
        })
        .catch(err => {
            contentDiv.innerHTML = '<div style="padding: 20px; color: #ff4444;">Error loading order details</div>';
            console.error('AJAX error:', err);
        });
}

function closeViewModal() {
    document.getElementById('view-modal').style.display = 'none';
    document.body.style.overflow = '';
}
</script>

<?php require_once 'includes/footer.php'; ?>
