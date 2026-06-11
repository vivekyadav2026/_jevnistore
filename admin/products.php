<?php
require_once 'includes/header.php';

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $price = $_POST['price'] ?? 0;
    $compare_at_price = !empty($_POST['compare_at_price']) ? (float)$_POST['compare_at_price'] : null;
    $description = $_POST['description'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    
    // Handle primary image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = uniqid() . '_' . basename($_FILES['image']['name']);
        compressAndSaveImage($_FILES['image']['tmp_name'], '../assets/' . $image);
    }

    // Handle secondary (hover) image upload
    $image2 = '';
    if (isset($_FILES['image2']) && $_FILES['image2']['error'] == 0) {
        $image2 = uniqid() . '_' . basename($_FILES['image2']['name']);
        compressAndSaveImage($_FILES['image2']['tmp_name'], '../assets/' . $image2);
    }

    $stmt = $conn->prepare("INSERT INTO products (category_id, name, description, price, compare_at_price, stock, image, image2) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issddiss", $category_id, $name, $description, $price, $compare_at_price, $stock, $image, $image2);
    $stmt->execute();
    $product_id = $conn->insert_id;

    // Insert primary and secondary images into product_images gallery
    if ($image) {
        $conn->query("INSERT INTO product_images (product_id, image_path, sort_order) VALUES ($product_id, '$image', 0)");
    }
    if ($image2) {
        $conn->query("INSERT INTO product_images (product_id, image_path, sort_order) VALUES ($product_id, '$image2', 1)");
    }

    // Handle multiple gallery images
    if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
        $sort_order = 2;
        foreach ($_FILES['gallery']['name'] as $key => $gallery_name) {
            if ($_FILES['gallery']['error'][$key] == 0) {
                $gal_image = uniqid() . '_' . basename($gallery_name);
                compressAndSaveImage($_FILES['gallery']['tmp_name'][$key], '../assets/' . $gal_image);
                
                $stmt_gal = $conn->prepare("INSERT INTO product_images (product_id, image_path, sort_order) VALUES (?, ?, ?)");
                $stmt_gal->bind_param("isi", $product_id, $gal_image, $sort_order);
                $stmt_gal->execute();
                $sort_order++;
            }
        }
    }

    setFlash('Product added successfully.', 'success');
    redirect('products.php');
}

// Handle Edit Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_product'])) {
    $product_id = (int)$_POST['product_id'];
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $price = $_POST['price'] ?? 0;
    $compare_at_price = !empty($_POST['compare_at_price']) ? (float)$_POST['compare_at_price'] : null;
    $description = $_POST['description'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    
    // Get existing product images
    $p_stmt = $conn->prepare("SELECT image, image2 FROM products WHERE id = ?");
    $p_stmt->bind_param("i", $product_id);
    $p_stmt->execute();
    $existing = $p_stmt->get_result()->fetch_assoc();
    
    $image = $existing['image'];
    $image2 = $existing['image2'];
    
    // Handle primary image update
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = uniqid() . '_' . basename($_FILES['image']['name']);
        compressAndSaveImage($_FILES['image']['tmp_name'], '../assets/' . $image);
        
        $conn->query("DELETE FROM product_images WHERE product_id = $product_id AND sort_order = 0");
        $conn->query("INSERT INTO product_images (product_id, image_path, sort_order) VALUES ($product_id, '$image', 0)");
    }

    // Handle secondary image update
    if (isset($_FILES['image2']) && $_FILES['image2']['error'] == 0) {
        $image2 = uniqid() . '_' . basename($_FILES['image2']['name']);
        compressAndSaveImage($_FILES['image2']['tmp_name'], '../assets/' . $image2);
        
        $conn->query("DELETE FROM product_images WHERE product_id = $product_id AND sort_order = 1");
        $conn->query("INSERT INTO product_images (product_id, image_path, sort_order) VALUES ($product_id, '$image2', 1)");
    }
    
    $stmt = $conn->prepare("UPDATE products SET category_id = ?, name = ?, description = ?, price = ?, compare_at_price = ?, stock = ?, image = ?, image2 = ? WHERE id = ?");
    $stmt->bind_param("issddissi", $category_id, $name, $description, $price, $compare_at_price, $stock, $image, $image2, $product_id);
    $stmt->execute();
    
    // Handle multiple new gallery images
    if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
        // Find next sort order
        $so_query = $conn->query("SELECT MAX(sort_order) as max_so FROM product_images WHERE product_id = $product_id");
        $sort_order = ($so_query && $so_row = $so_query->fetch_assoc()) ? (int)$so_row['max_so'] + 1 : 2;
        if ($sort_order < 2) $sort_order = 2;
        
        foreach ($_FILES['gallery']['name'] as $key => $gallery_name) {
            if ($_FILES['gallery']['error'][$key] == 0) {
                $gal_image = uniqid() . '_' . basename($gallery_name);
                compressAndSaveImage($_FILES['gallery']['tmp_name'][$key], '../assets/' . $gal_image);
                
                $stmt_gal = $conn->prepare("INSERT INTO product_images (product_id, image_path, sort_order) VALUES (?, ?, ?)");
                $stmt_gal->bind_param("isi", $product_id, $gal_image, $sort_order);
                $stmt_gal->execute();
                $sort_order++;
            }
        }
    }
    
    setFlash('Product updated successfully.', 'success');
    redirect('products.php');
}

// Handle Delete Product
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Fetch and remove associated images from filesystem
    $imgs = $conn->query("SELECT image_path FROM product_images WHERE product_id = $id");
    while ($img_row = $imgs->fetch_assoc()) {
        @unlink('../assets/' . $img_row['image_path']);
    }
    
    $conn->query("DELETE FROM product_images WHERE product_id = $id");
    $conn->query("DELETE FROM products WHERE id = $id");
    setFlash('Product deleted successfully.', 'info');
    redirect('products.php');
}

// Handle Delete Gallery Image (during Edit)
if (isset($_GET['delete_gallery_image_id'])) {
    $img_id = (int)$_GET['delete_gallery_image_id'];
    $prod_id = (int)($_GET['product_id'] ?? 0);
    
    $img_query = $conn->query("SELECT image_path FROM product_images WHERE id = $img_id");
    if ($img_query && $img_row = $img_query->fetch_assoc()) {
        @unlink('../assets/' . $img_row['image_path']);
    }
    $conn->query("DELETE FROM product_images WHERE id = $img_id");
    
    setFlash('Gallery image removed.', 'info');
    redirect('products.php?edit_id=' . $prod_id);
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h3 style="margin: 0;">Manage Products</h3>
    <button onclick="openAddModal()" class="btn" style="padding: 8px 16px;">+ Add Product</button>
</div>

<!-- Add Modal -->
<div id="add-modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.85); z-index:100; padding: 40px 20px; overflow-y: auto;">
    <div style="background:#111; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 8px; border: 1px solid #333; color: white;">
        <div style="display:flex; justify-content:space-between; margin-bottom: 20px; border-bottom: 1px solid #222; padding-bottom: 10px;">
            <h4 style="margin:0; font-size:1.1rem; text-transform:uppercase; letter-spacing:1px;">Add Product</h4>
            <button onclick="closeAddModal()" style="background:none; border:none; color:white; cursor:pointer;"><i data-lucide="x"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" class="form-control" placeholder="Product Name" required>
            <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                <?php
                $cats = $conn->query("SELECT * FROM categories");
                while($c = $cats->fetch_assoc()) echo "<option value='{$c['id']}'>{$c['name']}</option>";
                ?>
            </select>
            <input type="number" step="0.01" name="price" class="form-control" placeholder="Price (INR)" required>
            <input type="number" step="0.01" name="compare_at_price" class="form-control" placeholder="Compare-At Price (Original Price, optional)">
            <input type="number" name="stock" class="form-control" placeholder="Stock Quantity" required>
            <textarea name="description" id="add-description" class="form-control" placeholder="Description" rows="4"></textarea>
            
            <div style="margin-top: 15px;">
                <label style="display:block; margin-bottom:5px; font-size: 13px; color: #888;">Primary Image (Front) - Max 200KB compressed</label>
                <input type="file" name="image" id="add-image" class="form-control" accept="image/*" required>
                <div id="add-image-preview" style="margin-top: 5px; display: none;"></div>
            </div>
            
            <div style="margin-top: 15px;">
                <label style="display:block; margin-bottom:5px; font-size: 13px; color: #888;">Secondary Image (Hover / Flip) - Max 200KB compressed</label>
                <input type="file" name="image2" id="add-image2" class="form-control" accept="image/*">
                <div id="add-image2-preview" style="margin-top: 5px; display: none;"></div>
            </div>
            
            <div style="margin-top: 15px;">
                <label style="display:block; margin-bottom:5px; font-size: 13px; color: #888;">Additional Gallery Images - Max 200KB compressed each</label>
                <input type="file" name="gallery[]" id="add-gallery" class="form-control" accept="image/*" multiple>
                <div id="add-gallery-preview" style="margin-top: 5px; display: flex; gap: 10px; flex-wrap: wrap;"></div>
            </div>
            
            <button type="submit" name="add_product" class="btn btn-block" style="margin-top: 25px; padding: 12px;">Save Product</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.85); z-index:100; padding: 40px 20px; overflow-y: auto;">
    <div style="background:#111; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 8px; border: 1px solid #333; color: white;">
        <div style="display:flex; justify-content:space-between; margin-bottom: 20px; border-bottom: 1px solid #222; padding-bottom: 10px;">
            <h4 style="margin:0; font-size:1.1rem; text-transform:uppercase; letter-spacing:1px;">Edit Product</h4>
            <button onclick="closeEditModal()" style="background:none; border:none; color:white; cursor:pointer;"><i data-lucide="x"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" id="edit-product-id">
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:13px; color:#888;">Product Name</label>
                <input type="text" name="name" id="edit-name" class="form-control" required>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:13px; color:#888;">Category</label>
                <select name="category_id" id="edit-category-id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php
                    $cats = $conn->query("SELECT * FROM categories");
                    while($c = $cats->fetch_assoc()) echo "<option value='{$c['id']}'>{$c['name']}</option>";
                    ?>
                </select>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:13px; color:#888;">Price (₹)</label>
                <input type="number" step="0.01" name="price" id="edit-price" class="form-control" required>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:13px; color:#888;">Compare-At Price (Original Price, optional)</label>
                <input type="number" step="0.01" name="compare_at_price" id="edit-compare-at-price" class="form-control">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:13px; color:#888;">Stock</label>
                <input type="number" name="stock" id="edit-stock" class="form-control" required>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:13px; color:#888;">Description</label>
                <textarea name="description" id="edit-description" class="form-control" rows="4"></textarea>
            </div>
            
            <!-- Image uploads + previews -->
            <div style="margin-top: 20px; border-top: 1px solid #222; padding-top: 15px;">
                <label style="display:block; margin-bottom:5px; font-size: 13px; color: #888;">Primary Image (Upload to Replace)</label>
                <input type="file" name="image" id="edit-image" class="form-control" accept="image/*">
                <div id="edit-image-current" style="margin-top: 5px;"></div>
                <div id="edit-image-preview" style="margin-top: 5px; display: none;"></div>
            </div>
            
            <div style="margin-top: 20px;">
                <label style="display:block; margin-bottom:5px; font-size: 13px; color: #888;">Secondary Image (Upload to Replace)</label>
                <input type="file" name="image2" id="edit-image2" class="form-control" accept="image/*">
                <div id="edit-image2-current" style="margin-top: 5px;"></div>
                <div id="edit-image2-preview" style="margin-top: 5px; display: none;"></div>
            </div>
            
            <div style="margin-top: 20px;">
                <label style="display:block; margin-bottom:5px; font-size: 13px; color: #888;">Upload Additional Gallery Images</label>
                <input type="file" name="gallery[]" id="edit-gallery" class="form-control" accept="image/*" multiple>
                <div id="edit-gallery-preview" style="margin-top: 5px; display: flex; gap: 10px; flex-wrap: wrap;"></div>
            </div>
            
            <!-- Current Additional Gallery Images -->
            <div style="margin-top: 20px; border-top: 1px solid #222; padding-top: 15px;">
                <label style="display:block; margin-bottom:10px; font-size: 13px; color: #888;">Current Additional Gallery Images (Click Trash to Remove)</label>
                <div id="edit-gallery-current" style="display:flex; gap:10px; flex-wrap:wrap;"></div>
            </div>
            
            <button type="submit" name="edit_product" class="btn btn-block" style="margin-top: 25px; padding: 12px;">Update Product</button>
        </form>
    </div>
</div>

<div style="background: #111; padding: 20px; border-radius: 8px; border: 1px solid #333; overflow-x: auto;">
    <table class="table" style="margin-top: 0;">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $prods = $conn->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
            if ($prods->num_rows > 0) {
                while ($p = $prods->fetch_assoc()) {
                    $img = $p['image'] ? BASE_URL . '/assets/' . htmlspecialchars($p['image']) : '/assets/product_pants.png';
                    
                    // Get gallery images for this product (excluding primary/secondary at sort_order 0 & 1)
                    $g_res = $conn->query("SELECT id, image_path FROM product_images WHERE product_id = {$p['id']} AND sort_order >= 2 ORDER BY sort_order ASC");
                    $gallery = [];
                    while($g_row = $g_res->fetch_assoc()) {
                        $gallery[] = $g_row;
                    }
                    
                    $p_json = json_encode($p);
                    $gallery_json = json_encode($gallery);
                    
                    $price_html = '₹' . number_format($p['price'], 2);
                    if (!empty($p['compare_at_price']) && $p['compare_at_price'] > $p['price']) {
                        $discount_pct = round((($p['compare_at_price'] - $p['price']) / $p['compare_at_price']) * 100);
                        $price_html = '
                            <div style="display:flex; flex-direction:column; gap:4px;">
                                <div>
                                    <span style="color:#ef4444; font-weight:600;">₹' . number_format($p['price'], 2) . '</span>
                                    <span style="text-decoration:line-through; color:#666; font-size:0.8rem; margin-left:6px;">₹' . number_format($p['compare_at_price'], 2) . '</span>
                                </div>
                                <div>
                                    <span style="background:#dc2626; color:white; font-size:0.7rem; font-weight:600; padding:2px 6px; border-radius:3px; text-transform:uppercase;">Save ' . $discount_pct . '%</span>
                                </div>
                            </div>
                        ';
                    }
                    
                    echo '<tr>
                        <td><img src="'.$img.'" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #222;"></td>
                        <td>'.htmlspecialchars($p['name']).'</td>
                        <td>'.htmlspecialchars($p['cat_name']).'</td>
                        <td>'.$price_html.'</td>
                        <td>'.$p['stock'].'</td>
                        <td style="white-space: nowrap;">
                            <button class="btn btn-outline" onclick=\'openEditModal('.$p_json.', '.$gallery_json.')\' style="padding: 5px 10px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 5px; cursor: pointer; border-color: #555; margin-right: 10px;">
                                <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i> Edit
                            </button>
                            <a href="?delete='.$p['id'].'" style="color: #ff4444; text-decoration: none; display: inline-flex; align-items: center; padding: 5px;" onclick="return confirm(\'Are you sure you want to delete this product?\')" title="Delete Product">
                                <i data-lucide="trash-2" style="width:18px; height:18px;"></i>
                            </a>
                        </td>
                    </tr>';
                }
            } else {
                echo '<tr><td colspan="6" style="text-align:center;">No products found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<style>
.ck-editor__editable_inline {
    min-height: 250px !important;
    color: #000 !important; /* Ensure text is visible on the default white editor background */
    background-color: #fff !important;
}
/* Ensure the toolbar text and icons are properly visible too in case they inherit dark mode styles */
.ck.ck-toolbar {
    background-color: #f8f9fa !important;
    border-color: #ddd !important;
}
.ck.ck-toolbar .ck-button {
    color: #333 !important;
}
.ck.ck-dropdown__panel {
    background: #fff !important;
}
.ck.ck-list__item__button {
    color: #333 !important;
}
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
let addDescEditor, editDescEditor;

document.addEventListener('DOMContentLoaded', () => {
    ClassicEditor
        .create(document.querySelector('#add-description'))
        .then(editor => { addDescEditor = editor; })
        .catch(error => { console.error(error); });
        
    ClassicEditor
        .create(document.querySelector('#edit-description'))
        .then(editor => { editDescEditor = editor; })
        .catch(error => { console.error(error); });
});

// File preview helper for single files
function bindImagePreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const previewDiv = document.getElementById(previewId);
    if (!input || !previewDiv) return;
    
    input.addEventListener('change', function() {
        previewDiv.innerHTML = '';
        if (this.files && this.files[0]) {
            previewDiv.style.display = 'block';
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.objectFit = 'cover';
                img.style.borderRadius = '4px';
                img.style.border = '1px solid #333';
                img.style.maxWidth = '100px';
                img.style.maxHeight = '100px';
                previewDiv.appendChild(img);
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            previewDiv.style.display = 'none';
        }
    });
}

// Global trackers for interactive multiple gallery selections
let addGalleryFiles = [];
let editGalleryFiles = [];

function setupInteractiveGallery(inputId, previewId, filesArray) {
    const input = document.getElementById(inputId);
    const previewDiv = document.getElementById(previewId);
    if (!input || !previewDiv) return;
    
    input.addEventListener('change', function() {
        if (this.files) {
            Array.from(this.files).forEach(file => {
                // Prevent duplicating the exact same file in selection
                if (!filesArray.some(f => f.name === file.name && f.size === file.size)) {
                    filesArray.push(file);
                }
            });
            syncFileInput(input, filesArray);
            renderGalleryPreviews(input, previewDiv, filesArray);
        }
    });
}

function syncFileInput(input, filesArray) {
    const dt = new DataTransfer();
    filesArray.forEach(file => dt.items.add(file));
    input.files = dt.files;
}

function renderGalleryPreviews(input, previewDiv, filesArray) {
    previewDiv.innerHTML = '';
    if (filesArray.length > 0) {
        previewDiv.style.display = 'flex';
        previewDiv.style.gap = '10px';
        previewDiv.style.flexWrap = 'wrap';
        
        filesArray.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const item = document.createElement('div');
                item.style.position = 'relative';
                item.style.width = '60px';
                item.style.height = '60px';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '4px';
                img.style.border = '1px solid #333';
                
                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.innerHTML = '&times;';
                deleteBtn.style.position = 'absolute';
                deleteBtn.style.top = '-5px';
                deleteBtn.style.right = '-5px';
                deleteBtn.style.background = '#ef4444';
                deleteBtn.style.color = 'white';
                deleteBtn.style.border = 'none';
                deleteBtn.style.borderRadius = '50%';
                deleteBtn.style.width = '18px';
                deleteBtn.style.height = '18px';
                deleteBtn.style.display = 'flex';
                deleteBtn.style.alignItems = 'center';
                deleteBtn.style.justifyContent = 'center';
                deleteBtn.style.cursor = 'pointer';
                deleteBtn.style.fontSize = '12px';
                deleteBtn.style.fontWeight = 'bold';
                
                deleteBtn.addEventListener('click', (ev) => {
                    ev.stopPropagation();
                    filesArray.splice(idx, 1);
                    syncFileInput(input, filesArray);
                    renderGalleryPreviews(input, previewDiv, filesArray);
                });
                
                item.appendChild(img);
                item.appendChild(deleteBtn);
                previewDiv.appendChild(item);
            };
            reader.readAsDataURL(file);
        });
    } else {
        previewDiv.style.display = 'none';
    }
}

// Bind handlers when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Single image bindings
    bindImagePreview('add-image', 'add-image-preview');
    bindImagePreview('add-image2', 'add-image2-preview');
    bindImagePreview('edit-image', 'edit-image-preview');
    bindImagePreview('edit-image2', 'edit-image2-preview');
    
    // Interactive multiple gallery bindings
    setupInteractiveGallery('add-gallery', 'add-gallery-preview', addGalleryFiles);
    setupInteractiveGallery('edit-gallery', 'edit-gallery-preview', editGalleryFiles);
});

function openAddModal() {
    addGalleryFiles.length = 0; // Reset selected files array
    const input = document.getElementById('add-gallery');
    if (input) input.value = '';
    const previewDiv = document.getElementById('add-gallery-preview');
    if (previewDiv) {
        previewDiv.innerHTML = '';
        previewDiv.style.display = 'none';
    }
    
    document.getElementById('add-image').value = '';
    document.getElementById('add-image2').value = '';
    document.getElementById('add-image-preview').style.display = 'none';
    document.getElementById('add-image2-preview').style.display = 'none';
    
    document.getElementById('add-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAddModal() {
    document.getElementById('add-modal').style.display = 'none';
    document.body.style.overflow = '';
}

function openEditModal(product, gallery) {
    editGalleryFiles.length = 0; // Reset selected files array
    const input = document.getElementById('edit-gallery');
    if (input) input.value = '';
    const previewDiv = document.getElementById('edit-gallery-preview');
    if (previewDiv) {
        previewDiv.innerHTML = '';
        previewDiv.style.display = 'none';
    }
    
    document.getElementById('edit-product-id').value = product.id;
    document.getElementById('edit-name').value = product.name;
    document.getElementById('edit-category-id').value = product.category_id;
    document.getElementById('edit-price').value = product.price;
    document.getElementById('edit-compare-at-price').value = product.compare_at_price || '';
    document.getElementById('edit-stock').value = product.stock;
    document.getElementById('edit-description').value = product.description || '';
    if (editDescEditor) {
        editDescEditor.setData(product.description || '');
    }
    
    // Clear dynamic file fields and previews
    document.getElementById('edit-image').value = '';
    document.getElementById('edit-image2').value = '';
    document.getElementById('edit-image-preview').style.display = 'none';
    document.getElementById('edit-image2-preview').style.display = 'none';
    
    // Display current primary image
    const curImg = document.getElementById('edit-image-current');
    if (product.image) {
        curImg.innerHTML = `<img src="<?php echo BASE_URL; ?>/assets/${product.image}" style="max-width:80px; max-height:80px; border-radius:4px; border:1px solid #333;"> <span style="font-size:12px; color:#666;">(${product.image})</span>`;
    } else {
        curImg.innerHTML = '<span style="font-size:12px; color:#555;">No current image</span>';
    }
    
    // Display current secondary image
    const curImg2 = document.getElementById('edit-image2-current');
    if (product.image2) {
        curImg2.innerHTML = `<img src="<?php echo BASE_URL; ?>/assets/${product.image2}" style="max-width:80px; max-height:80px; border-radius:4px; border:1px solid #333;"> <span style="font-size:12px; color:#666;">(${product.image2})</span>`;
    } else {
        curImg2.innerHTML = '<span style="font-size:12px; color:#555;">No current image</span>';
    }
    
    // Display current gallery images with delete triggers
    const curGallery = document.getElementById('edit-gallery-current');
    curGallery.innerHTML = '';
    if (gallery && gallery.length > 0) {
        gallery.forEach(img => {
            const item = document.createElement('div');
            item.style.position = 'relative';
            item.style.width = '60px';
            item.style.height = '60px';
            item.innerHTML = `
                <img src="<?php echo BASE_URL; ?>/assets/${img.image_path}" style="width:100%; height:100%; object-fit:cover; border-radius:4px; border:1px solid #333;">
                <a href="?delete_gallery_image_id=${img.id}&product_id=${product.id}" 
                   style="position:absolute; top:-5px; right:-5px; background:#ef4444; color:white; border-radius:50%; width:18px; height:18px; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:10px; font-weight:bold;"
                   onclick="return confirm('Delete this gallery image?')">
                   &times;
                </a>
            `;
            curGallery.appendChild(item);
        });
    } else {
        curGallery.innerHTML = '<span style="font-size:12px; color:#555;">No additional gallery images</span>';
    }
    
    document.getElementById('edit-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('edit-modal').style.display = 'none';
    document.body.style.overflow = '';
}

// Automatically trigger edit modal if edit_id is passed in URL (redirected from gallery delete action)
window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('edit_id');
    if (editId) {
        // Find edit button for this product ID and click it
        // We can find the product row or re-query it. To make it extremely simple, we can simulate click on the edit button in the table
        const buttons = document.querySelectorAll('button[onclick*="openEditModal"]');
        buttons.forEach(btn => {
            if (btn.getAttribute('onclick').includes('"id":"' + editId + '"') || btn.getAttribute('onclick').includes('"id":' + editId + ',')) {
                btn.click();
            }
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
