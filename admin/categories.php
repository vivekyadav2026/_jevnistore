<?php
/**
 * ============================================================================
 * CATEGORIES MANAGEMENT (admin/categories.php)
 * ============================================================================
 * Allows admin to create, edit, delete, and view product categories with pagination.
 */
require_once 'includes/header.php';

// Fetch category for editing if edit query parameter is present
$edit_cat = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_res = $conn->query("SELECT * FROM categories WHERE id = $edit_id");
    if ($edit_res && $edit_res->num_rows > 0) {
        $edit_cat = $edit_res->fetch_assoc();
    }
}

// Handle Add & Edit Category Form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_category'])) {
        $name = trim($_POST['name'] ?? '');
        if (!empty($name)) {
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->bind_param("s", $name);
            $stmt->execute();
            setFlash('Category "' . htmlspecialchars($name) . '" added successfully.', 'success');
            redirect('categories.php');
        }
    } elseif (isset($_POST['edit_category'])) {
        $id = (int)$_POST['id'];
        $name = trim($_POST['name'] ?? '');
        if (!empty($name)) {
            $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->bind_param("si", $name, $id);
            $stmt->execute();
            setFlash('Category updated successfully.', 'success');
            redirect('categories.php');
        }
    }
}

// Handle Category Deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM categories WHERE id = $id");
    setFlash('Category deleted.', 'info');
    redirect('categories.php');
}
?>

<!-- Section Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h3 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #f8fafc; letter-spacing: -0.01em;">Manage Categories</h3>
</div>

<div style="display: flex; gap: 2rem; flex-wrap: wrap;">
    <!-- Add / Edit Category Card Form -->
    <div style="flex: 1; min-width: 300px; background: var(--bg-secondary); padding: 24px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); height: fit-content; color: #f8fafc;">
        <h4 style="margin-top: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.95rem; font-weight: 700; color: var(--accent); border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 12px; margin-bottom: 20px;">
            <?php echo $edit_cat ? 'Edit Category' : 'Add New Category'; ?>
        </h4>
        <form method="POST">
            <?php if ($edit_cat): ?>
                <input type="hidden" name="id" value="<?php echo $edit_cat['id']; ?>">
            <?php endif; ?>
            
            <div style="margin-bottom: 18px;">
                <label style="display:block; margin-bottom:6px; font-size:12px; font-weight:600; color:#cbd5e1; text-transform:uppercase; letter-spacing:0.5px;">Category Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Hoodies, Bags" value="<?php echo $edit_cat ? htmlspecialchars($edit_cat['name']) : ''; ?>" required style="margin-bottom: 0;">
            </div>
            
            <button type="submit" name="<?php echo $edit_cat ? 'edit_category' : 'add_category'; ?>" class="btn" style="padding: 10px 20px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">
                <?php echo $edit_cat ? 'Update Category' : 'Add Category'; ?>
            </button>
            <?php if ($edit_cat): ?>
                <a href="categories.php" class="btn btn-outline" style="padding: 10px 20px; text-decoration: none; display: inline-block; margin-left: 10px; text-transform: uppercase; letter-spacing: 1px;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Category List -->
    <div style="flex: 2; min-width: 400px; background: var(--bg-secondary); padding: 20px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
        <div class="table-responsive">
            <table class="table" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Pagination calculation
                    $per_page = 10;
                    $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
                    $total_res = $conn->query("SELECT COUNT(*) AS total FROM categories");
                    $total_items = $total_res ? (int)$total_res->fetch_assoc()['total'] : 0;
                    $total_pages = ceil($total_items / $per_page);
                    if ($page > $total_pages && $total_pages > 0) $page = $total_pages;
                    $offset = ($page - 1) * $per_page;

                    $cats = $conn->query("SELECT * FROM categories ORDER BY id DESC LIMIT $per_page OFFSET $offset");
                    if ($cats && $cats->num_rows > 0) {
                        while ($c = $cats->fetch_assoc()) {
                            echo '<tr>
                                <td>'.$c['id'].'</td>
                                <td>'.htmlspecialchars($c['name']).'</td>
                                <td style="white-space: nowrap;">
                                    <a href="?edit='.$c['id'].'" style="color: var(--accent); text-decoration: none; margin-right: 15px; display: inline-flex; align-items: center; padding: 5px;" title="Edit Category">
                                        <i data-lucide="edit-3" style="width:18px; height:18px;"></i>
                                    </a>
                                    <a href="?delete='.$c['id'].'" style="color: #ff4444; text-decoration: none; display: inline-flex; align-items: center; padding: 5px;" onclick="return confirm(\'Are you sure you want to delete this category? All products inside this category will have their category cleared.\')" title="Delete Category">
                                        <i data-lucide="trash-2" style="width:18px; height:18px;"></i>
                                    </a>
                                </td>
                            </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="3" style="text-align:center;">No categories found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php renderPagination($page, $total_pages, $total_items, $per_page, 'categories.php'); ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
