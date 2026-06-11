<?php
require_once __DIR__ . '/../includes/header.php';

requireLogin();

// Fetch user details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    
    $upd = $conn->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
    $upd->bind_param("sssi", $name, $phone, $address, $user_id);
    $upd->execute();
    
    $_SESSION['user_name'] = $name;
    redirect('index.php');
}
?>

<div class="page-header">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 10px;">MY ACCOUNT</h1>
    </div>
</div>

<div class="container" style="margin-bottom: 8rem;">
    <div class="dashboard-layout">
        
        <aside class="dashboard-nav">
            <a href="index.php" class="active">Profile</a>
            <a href="order_history.php">Order History</a>
            <a href="/wishlist.php">Wishlist</a>
            <a href="/logout.php" style="color: var(--text-secondary);">Logout</a>
        </aside>

        <div>
            <!-- Profile Info -->
            <div class="dashboard-card" style="margin-bottom: 40px;">
                <h3 style="margin-top:0; margin-bottom: 2rem; font-size: 1rem; letter-spacing: 2px; text-transform: uppercase; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">PROFILE DETAILS</h3>
                <form method="POST" class="luxury-form">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled class="form-control" style="color: var(--text-secondary);">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="form-control">
                    </div>
                    <div class="form-group" style="margin-bottom: 3rem;">
                        <label>Shipping Address</label>
                        <textarea name="address" rows="3" class="form-control"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" name="update_profile" class="btn" style="padding: 12px 30px;">UPDATE PROFILE</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
