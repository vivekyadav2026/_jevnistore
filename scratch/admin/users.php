<?php
require_once 'includes/header.php';

$current_admin_id = $_SESSION['user_id'] ?? 0;

// Handle Role Toggle
if (isset($_GET['toggle_role'])) {
    $target_user_id = (int)$_GET['toggle_role'];
    
    // Prevent self-lockout
    if ($target_user_id === $current_admin_id) {
        $_SESSION['error_msg'] = "You cannot modify your own role.";
    } else {
        // Fetch current role
        $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $target_user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows > 0) {
            $user = $res->fetch_assoc();
            $new_role = $user['role'] === 'admin' ? 'customer' : 'admin';
            
            $upd = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
            $upd->bind_param("si", $new_role, $target_user_id);
            $upd->execute();
        }
    }
    redirect('users.php');
}

// Handle User Deletion
if (isset($_GET['delete'])) {
    $target_user_id = (int)$_GET['delete'];
    
    // Prevent self-deletion
    if ($target_user_id === $current_admin_id) {
        $_SESSION['error_msg'] = "You cannot delete your own admin account.";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $target_user_id);
        $stmt->execute();
    }
    redirect('users.php');
}

$error_msg = $_SESSION['error_msg'] ?? '';
unset($_SESSION['error_msg']);
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h3 style="margin: 0;">Manage Users</h3>
</div>

<?php if ($error_msg): ?>
    <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #ef4444; padding: 15px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem;">
        <?php echo htmlspecialchars($error_msg); ?>
    </div>
<?php endif; ?>

<div style="background: #111; padding: 20px; border-radius: 8px; border: 1px solid #333; overflow-x: auto;">
    <table class="table" style="margin-top: 0;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Date Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $users = $conn->query("SELECT * FROM users ORDER BY id DESC");
            if ($users->num_rows > 0) {
                while ($u = $users->fetch_assoc()) {
                    $is_self = $u['id'] === $current_admin_id;
                    $role_badge = $u['role'] === 'admin' ? '<span style="color:#eab308; background:rgba(234,179,8,0.15); padding: 3px 8px; border-radius: 12px; font-size: 0.75rem; font-weight:600; text-transform:uppercase;">Admin</span>' : '<span style="color:#38bdf8; background:rgba(56,189,248,0.15); padding: 3px 8px; border-radius: 12px; font-size: 0.75rem; font-weight:600; text-transform:uppercase;">Customer</span>';
                    
                    echo '<tr>
                        <td>'.$u['id'].'</td>
                        <td>'.htmlspecialchars($u['name']).'</td>
                        <td>'.htmlspecialchars($u['email']).'</td>
                        <td>'.htmlspecialchars($u['phone'] ?: 'N/A').'</td>
                        <td>'.$role_badge.'</td>
                        <td>'.date('M d, Y', strtotime($u['created_at'])).'</td>
                        <td style="white-space: nowrap;">';
                    
                    if ($is_self) {
                        echo '<span style="color:#666; font-size: 0.85rem; font-style:italic;">Your Session</span>';
                    } else {
                        // Change Role trigger
                        $btn_text = $u['role'] === 'admin' ? 'Make Customer' : 'Make Admin';
                        $btn_style = $u['role'] === 'admin' ? 'color:#38bdf8; border-color:#38bdf8;' : 'color:#eab308; border-color:#eab308;';
                        echo '
                        <a href="?toggle_role='.$u['id'].'" class="btn btn-outline" style="padding: 4px 8px; font-size: 0.75rem; '.$btn_style.' text-transform: uppercase; margin-right: 15px; cursor: pointer; text-decoration: none;">
                            '.$btn_text.'
                        </a>
                        
                        <!-- Delete User -->
                        <a href="?delete='.$u['id'].'" style="color: #ff4444; text-decoration: none; display: inline-flex; align-items: center; padding: 5px;" onclick="return confirm(\'Are you sure you want to delete this user? All their order records will also be removed.\')" title="Delete User">
                            <i data-lucide="trash-2" style="width:18px; height:18px;"></i>
                        </a>';
                    }
                    
                    echo '</td>
                    </tr>';
                }
            } else {
                echo '<tr><td colspan="7" style="text-align:center;">No users found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
