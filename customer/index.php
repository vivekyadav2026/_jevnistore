<?php
require_once __DIR__ . '/../includes/header.php';

requireLogin();

// Fetch user details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Stats
$orders_cnt = $conn->query("SELECT COUNT(*) as c FROM orders WHERE user_id = $user_id")->fetch_assoc()['c'];
$wishlist_cnt = 0; // if we implement wishlist DB

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

<div class="page-header" style="background: var(--bg-secondary); padding: 60px 0; border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 10px; font-size: 2.5rem;">MY ACCOUNT</h1>
        <p style="color: var(--text-secondary); letter-spacing: 2px; font-size: 0.9rem; text-transform: uppercase;">Welcome back, <?php echo htmlspecialchars($user['name']); ?></p>
    </div>
</div>

<div class="container" style="margin-bottom: 8rem; margin-top: 60px;">
    <div class="dashboard-layout">
        
        <aside class="dashboard-nav">
            <a href="index.php" class="active">Profile</a>
            <a href="order_history.php">Order History</a>
            <a href="<?php echo BASE_URL; ?>/wishlist.php">Wishlist</a>
            <a href="<?php echo BASE_URL; ?>/logout.php" style="color: var(--text-secondary);">Logout</a>
        </aside>

        <div>
            <!-- Dashboard Widgets -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 40px;">
                <div class="dashboard-card" style="background: var(--bg-secondary); border-radius: 12px; padding: 30px; text-align: center; border: 1px solid var(--border-color);">
                    <i data-lucide="shopping-bag" style="width: 28px; height: 28px; margin-bottom: 15px; color: var(--accent);"></i>
                    <h2 style="font-size: 2.5rem; margin-bottom: 5px; font-weight: 400; font-family: var(--font-serif); color: var(--text-primary);"><?php echo $orders_cnt; ?></h2>
                    <p style="color: var(--text-secondary); font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase;">Total Orders</p>
                </div>
                <div class="dashboard-card" style="background: var(--bg-secondary); border-radius: 12px; padding: 30px; text-align: center; border: 1px solid var(--border-color);">
                    <i data-lucide="heart" style="width: 28px; height: 28px; margin-bottom: 15px; color: var(--accent);"></i>
                    <h2 style="font-size: 2.5rem; margin-bottom: 5px; font-weight: 400; font-family: var(--font-serif); color: var(--text-primary);"><?php echo $wishlist_cnt; ?></h2>
                    <p style="color: var(--text-secondary); font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase;">Saved Items</p>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="dashboard-card" style="background: var(--bg-secondary); border-radius: 12px; padding: 40px; border: 1px solid var(--border-color);">
                <h3 style="margin-top:0; margin-bottom: 2rem; font-size: 1.1rem; letter-spacing: 2px; text-transform: uppercase; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; color: var(--text-primary);">Profile Details</h3>
                <form method="POST" class="luxury-form">
                    <div class="profile-grid">
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-size: 0.85rem; letter-spacing: 1px; color: var(--text-secondary); text-transform: uppercase;">Full Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required class="form-control" style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 1rem; background: rgba(255,255,255,0.03); color: var(--text-primary); outline: none;">
                        </div>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 8px; font-size: 0.85rem; letter-spacing: 1px; color: var(--text-secondary); text-transform: uppercase;">Email Address</label>
                            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled class="form-control" style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 1rem; background: rgba(0,0,0,0.5); color: #666; cursor: not-allowed; outline: none;">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 0.85rem; letter-spacing: 1px; color: var(--text-secondary); text-transform: uppercase;">Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 1rem; background: rgba(255,255,255,0.03); color: var(--text-primary); outline: none;">
                    </div>
                    <div class="form-group" style="margin-bottom: 3rem;">
                        <label style="display: block; margin-bottom: 8px; font-size: 0.85rem; letter-spacing: 1px; color: var(--text-secondary); text-transform: uppercase;">Default Shipping Address</label>
                        <textarea name="address" rows="3" class="form-control" style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 1rem; background: rgba(255,255,255,0.03); color: var(--text-primary); font-family: inherit; outline: none;"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" name="update_profile" class="btn" style="padding: 16px 40px; font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase; background: var(--accent); color: #000; border: none; border-radius: 8px; cursor: pointer; transition: background 0.3s; font-weight: 600;">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
