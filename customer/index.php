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

// Wishlist count from session
$wishlist_cnt = isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0;

// Handle profile update
$update_success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    
    $upd = $conn->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
    $upd->bind_param("sssi", $name, $phone, $address, $user_id);
    $upd->execute();
    
    $_SESSION['user_name'] = $name;
    $user['name'] = $name;
    $user['phone'] = $phone;
    $user['address'] = $address;
    $update_success = true;
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

/* Bulletproof CSS Grid (min-width: 0 stops child blowout) */
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
.account-nav-link.danger:hover {
    background: #fee2e2;
    color: #b91c1c;
}

/* Stat Cards */
.stat-cards-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 16px;
    width: 100%;
}
@media (max-width: 480px) {
    .stat-cards-row {
        grid-template-columns: 1fr 1fr !important;
        gap: 10px !important;
    }
}

.stat-card-item {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 14px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    width: 100%;
}
@media (max-width: 480px) {
    .stat-card-item {
        padding: 12px 10px !important;
        gap: 8px !important;
        flex-direction: column !important;
        text-align: center !important;
    }
}

.stat-icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f3f4f6;
    color: #1a1a1a;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: none;
}
.stat-number-val {
    font-size: 1.6rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1;
    margin-bottom: 2px;
}
.stat-label-text {
    font-size: 0.65rem;
    font-weight: 700;
    color: #555555;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Form Panel Card */
.account-panel-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    padding: 24px 20px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    width: 100%;
}
@media (max-width: 600px) {
    .account-panel-card {
        padding: 16px 14px !important;
        border-radius: 14px !important;
    }
}

.panel-card-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-top: 0;
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
}

/* Form Fields */
.form-grid-2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
}
@media (max-width: 640px) {
    .form-grid-2col {
        grid-template-columns: 1fr !important;
        gap: 12px !important;
    }
}

.custom-input-label {
    display: block;
    margin-bottom: 6px;
    font-size: 0.65rem;
    font-weight: 700;
    color: #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.custom-input-field {
    width: 100%;
    padding: 12px 14px;
    border-radius: 8px;
    border: 1.5px solid #e5e7eb;
    font-size: 0.85rem;
    background: #ffffff;
    color: #1a1a1a;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: inherit;
}
.custom-input-field:focus {
    border-color: #000000;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.08);
}
.custom-input-field:disabled {
    background: #f3f4f6;
    color: #777777;
    border-color: #d1d5db;
    cursor: not-allowed;
}

.save-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #1a1a1a;
    color: #ffffff;
    border: 1.5px solid #1a1a1a;
    padding: 12px 28px;
    font-weight: 700;
    font-size: 0.72rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    border-radius: 99px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.save-btn-primary:hover {
    background: #000000;
}
@media (max-width: 480px) {
    .save-btn-primary {
        width: 100% !important;
    }
}
</style>

<div class="customer-page-section">
    <div class="account-container-inner">
        
        <!-- Breadcrumbs & Account Header -->
        <div class="account-header-box">
            <div class="account-breadcrumbs">
                <a href="<?php echo BASE_URL; ?>/index.php">HOME</a>
                <span>/</span>
                <span style="color: #1a1a1a; font-weight: 700;">ACCOUNT</span>
            </div>
            <h1 class="account-title">MY ACCOUNT</h1>
            <p class="account-subtitle">Welcome back, <strong><?php echo htmlspecialchars($user['name']); ?></strong></p>
        </div>

        <div class="account-grid-layout">
            
            <!-- Sidebar Navigation Tabs -->
            <aside>
                <div class="account-nav-list">
                    <a href="index.php" class="account-nav-link active">
                        <i data-lucide="user" style="width: 16px; height: 16px;"></i> Profile Details
                    </a>
                    <a href="order_history.php" class="account-nav-link">
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

            <!-- Main Content Area -->
            <div>
                
                <?php if ($update_success): ?>
                <div style="background: #dcfce7; border: 1px solid #16a34a; color: #15803d; padding: 12px 14px; border-radius: 10px; margin-bottom: 16px; font-weight: 700; font-size: 0.7rem; letter-spacing: 0.5px; text-transform: uppercase; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="check-circle" style="width: 16px; height: 16px;"></i>
                    Profile details updated successfully!
                </div>
                <?php endif; ?>

                <!-- Stats Overview Row -->
                <div class="stat-cards-row">
                    
                    <div class="stat-card-item">
                        <div class="stat-icon-wrapper">
                            <i data-lucide="shopping-bag" style="width: 20px; height: 20px;"></i>
                        </div>
                        <div>
                            <div class="stat-number-val"><?php echo $orders_cnt; ?></div>
                            <div class="stat-label-text">Total Orders</div>
                        </div>
                    </div>

                    <div class="stat-card-item">
                        <div class="stat-icon-wrapper" style="background: #fef2f2; color: #dc2626; border-color: #dc2626;">
                            <i data-lucide="heart" style="width: 20px; height: 20px;"></i>
                        </div>
                        <div>
                            <div class="stat-number-val"><?php echo $wishlist_cnt; ?></div>
                            <div class="stat-label-text">Saved Items</div>
                        </div>
                    </div>

                </div>

                <!-- Profile Details Form Panel -->
                <div class="account-panel-card">
                    <h3 class="panel-card-title">
                        <span>Profile Information</span>
                        <span style="font-size: 0.6rem; color: #15803d; font-weight: 700; background: #dcfce7; border: 1px solid #16a34a; padding: 3px 8px; border-radius: 99px; text-transform: uppercase; letter-spacing: 1px;">
                            Active Member
                        </span>
                    </h3>
                    
                    <form method="POST">
                        <div class="form-grid-2col">
                            <div>
                                <label class="custom-input-label">Full Name</label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required class="custom-input-field" placeholder="Your Name">
                            </div>
                            <div>
                                <label class="custom-input-label">Email Address</label>
                                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled class="custom-input-field" title="Email address cannot be changed">
                            </div>
                        </div>

                        <div style="margin-bottom: 14px;">
                            <label class="custom-input-label">Phone Number</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="custom-input-field" placeholder="+91 9876543210">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label class="custom-input-label">Default Shipping Address</label>
                            <textarea name="address" rows="3" class="custom-input-field" placeholder="Enter your full shipping address (Street, City, Pincode, State)"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" name="update_profile" class="save-btn-primary">
                            <i data-lucide="save" style="width: 15px; height: 15px;"></i>
                            Save Changes
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
