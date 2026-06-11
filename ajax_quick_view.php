<?php
require_once 'includes/db.php';

if (!isset($_GET['id'])) exit;
$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) exit;
$p = $res->fetch_assoc();

$image = $p['image'] ? BASE_URL . '/assets/' . htmlspecialchars($p['image']) : '/assets/product_hoodie.png';
?>
<button class="qv-close" onclick="closeQuickView()"><i data-lucide="x"></i></button>
<div class="qv-image-side">
    <img src="<?php echo $image; ?>" alt="">
</div>
<div class="qv-info-side">
    <h3 class="qv-title"><?php echo htmlspecialchars($p['name']); ?></h3>
    <div class="qv-price">RS. <?php echo number_format($p['price'], 2); ?></div>
    
    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:1px; color:#666; margin-bottom:10px;">Hardware Finish</div>
    <div class="qv-size-grid">
        <button type="button" class="qv-size-btn active" onclick="document.querySelectorAll('.qv-size-btn').forEach(b => b.classList.remove('active')); this.classList.add('active'); document.getElementById('qv-size-input').value = 'Silver';">SILVER</button>
        <button type="button" class="qv-size-btn" onclick="document.querySelectorAll('.qv-size-btn').forEach(b => b.classList.remove('active')); this.classList.add('active'); document.getElementById('qv-size-input').value = 'Gold';">GOLD</button>
    </div>
    
    <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" class="ajax-cart-form" style="display: flex; flex-direction: column; gap: 15px;">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
        <input type="hidden" name="size" id="qv-size-input" value="Silver">
        
        <div style="display: flex; gap: 15px; height: 55px; margin-bottom: 5px;">
            <div class="qv-qty-selector" style="margin-bottom: 0; height: 100%;">
                <button type="button" onclick="document.getElementById('qv-qty').value = Math.max(1, parseInt(document.getElementById('qv-qty').value) - 1)">-</button>
                <input type="number" id="qv-qty" name="quantity" value="1" readonly>
                <button type="button" onclick="document.getElementById('qv-qty').value = parseInt(document.getElementById('qv-qty').value) + 1">+</button>
            </div>
            <button type="submit" class="qv-btn" style="margin-bottom: 0; height: 100%; flex: 1;">ADD TO CART</button>
        </div>
        <button type="submit" name="buy_now" value="1" class="qv-btn qv-btn-buy" style="margin-bottom: 0;">BUY IT NOW</button>
    </form>
    
    <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $p['id']; ?>" class="qv-view-details">View details</a>
</div>
<script>
    lucide.createIcons();
    document.querySelectorAll('.qv-size-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            document.querySelectorAll('.qv-size-btn').forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');
        });
    });
</script>
