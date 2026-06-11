<?php
require_once 'includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 10px;">YOUR BAG</h1>
    </div>
</div>

<div class="container" style="max-width: 1200px; margin-bottom: 8rem;">
    <?php if (empty($_SESSION['cart'])): ?>
        <div style="text-align: center; padding: 5rem 0;">
            <p style="color: var(--text-secondary); letter-spacing: 2px; text-transform: uppercase;">Your cart is empty</p>
            <a href="<?php echo BASE_URL; ?>/shop.php" class="btn" style="margin-top: 2rem;">CONTINUE SHOPPING</a>
        </div>
    <?php else: ?>
        <table class="minimal-table" style="margin-bottom: 4rem;">
            <thead>
                <tr>
                    <th>Product</th>
                    <th style="text-align: center;">Quantity</th>
                    <th style="text-align: right;">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $id => $item): 
                    $img = $item['image'] ? BASE_URL . '/assets/' . htmlspecialchars($item['image']) : '/assets/product_pants.png';
                ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 2rem;">
                                <img src="<?php echo $img; ?>" style="width: 100px; aspect-ratio: 3/4; object-fit: cover;">
                                <div>
                                    <h4 style="margin: 0; font-size: 1rem; text-transform: uppercase; font-weight: 500;"><a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $id; ?>"><?php echo htmlspecialchars($item['name']); ?></a></h4>
                                    <p style="color: var(--text-secondary); margin: 5px 0 0 0; font-size: 0.9rem;">₹<?php echo $item['price']; ?></p>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" style="display:flex; justify-content:center; align-items:center; gap:10px;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <div class="qv-qty-selector" style="margin-bottom: 0; width: 120px; height: 40px;">
                                    <button type="button" onclick="const i = this.nextElementSibling; i.value = Math.max(1, parseInt(i.value) - 1); i.form.submit();">-</button>
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" readonly>
                                    <button type="button" onclick="const i = this.previousElementSibling; i.value = parseInt(i.value) + 1; i.form.submit();">+</button>
                                </div>
                            </form>
                        </td>
                        <td style="text-align: right; font-weight: 500;">
                            ₹<?php echo $item['price'] * $item['quantity']; ?>
                        </td>
                        <td style="text-align: right;">
                            <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <button type="submit" style="background: none; border: none; color: var(--text-secondary); cursor: pointer;"><i data-lucide="x"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="display: flex; justify-content: flex-end;">
            <div style="width: 350px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 1.2rem; font-weight: 500; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
                    <span>SUBTOTAL</span>
                    <span>₹<?php echo getCartTotal(); ?></span>
                </div>
                <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 2rem; letter-spacing: 1px; text-transform: uppercase;">Shipping & taxes calculated at checkout</p>
                <a href="<?php echo BASE_URL; ?>/checkout.php" class="btn btn-block">CHECKOUT</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
