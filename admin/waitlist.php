<?php
/**
 * ============================================================================
 * WAITLIST REQUESTS (admin/waitlist.php)
 * ============================================================================
 * Displays pre-order & out-of-stock product waitlist requests from customers,
 * including product details, user contact details, and pagination.
 */
require_once 'includes/header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM waitlists WHERE id = $id");
    setFlash('Waitlist entry deleted successfully.', 'info');
    redirect('waitlist.php');
}

// Pagination calculation
$per_page = 10;
$page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$total_res = $conn->query("SELECT COUNT(*) AS total FROM waitlists");
$total_items = $total_res ? (int)$total_res->fetch_assoc()['total'] : 0;
$total_pages = ceil($total_items / $per_page);
if ($page > $total_pages && $total_pages > 0) $page = $total_pages;
$offset = ($page - 1) * $per_page;

$query = "
    SELECT w.*, p.name as product_name 
    FROM waitlists w 
    JOIN products p ON w.product_id = p.id 
    ORDER BY w.created_at DESC
    LIMIT $per_page OFFSET $offset
";
$result = $conn->query($query);
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h3 style="margin: 0;">Waitlist Requests</h3>
</div>

<div style="background: var(--bg-secondary); padding: 20px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); overflow-x: auto;">
    <table class="table" style="margin-top: 0;">
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('M j, Y g:i A', strtotime($row['created_at'])); ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" style="color: #38bdf8; text-decoration: none;"><?php echo htmlspecialchars($row['email']); ?></a></td>
                        <td><?php echo htmlspecialchars($row['phone'] ? $row['phone'] : '-'); ?></td>
                        <td>
                            <a href="?delete=<?php echo $row['id']; ?>" style="color: #ff4444; text-decoration: none; display: inline-flex; align-items: center; padding: 5px;" onclick="return confirm('Are you sure you want to delete this waitlist entry?')" title="Delete Entry">
                                <i data-lucide="trash-2" style="width:18px; height:18px;"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No waitlist requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php renderPagination($page, $total_pages, $total_items, $per_page, 'waitlist.php'); ?>
</div>

<?php require_once 'includes/footer.php'; ?>
