<?php
require_once 'includes/header.php';
?>

<style>
    /* Styling overrides specifically for cart.php to match product grid layout */
    .cart-page-wrapper {
        padding: 40px 24px 80px;
    }
    
    .cart-header-section {
        margin-bottom: 40px;
        text-align: center;
    }
    
    .cart-header-section h1 {
        font-family: var(--font-primary);
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #1a1a1a;
        margin-bottom: 10px;
    }
    
    .cart-header-section p {
        color: #555555;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 600;
    }
    
    .cart-grid-container {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 60px;
        align-items: start;
    }
    
    /* Elegant Quantity Stepper */
    .cart-qty-stepper {
        display: flex;
        align-items: center;
        border: 1px solid #1a1a1a;
        border-radius: 4px;
        height: 30px;
        width: 90px;
        overflow: hidden;
        background: transparent;
        margin: 0 auto;
    }
    
    .qty-btn {
        width: 28px;
        height: 100%;
        background: none;
        border: none;
        color: #1a1a1a;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
        outline: none;
    }
    
    .qty-btn:hover {
        background: rgba(0, 0, 0, 0.05);
    }
    
    .qty-input {
        flex: 1;
        width: 100%;
        height: 100%;
        border: none;
        background: transparent;
        text-align: center;
        font-size: 0.75rem;
        font-weight: 600;
        color: #1a1a1a;
        outline: none;
        -moz-appearance: textfield;
    }
    
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* Summary Column */
    .cart-summary-column {
        position: sticky;
        top: 120px;
    }
    
    .cart-summary-card {
        background: transparent;
        border: none;
        border-radius: 0;
        padding: 0;
        box-shadow: none;
    }
    
    .summary-title {
        font-family: var(--font-primary);
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #1a1a1a;
        margin-bottom: 24px;
        border-bottom: 1.5px solid #1a1a1a;
        padding-bottom: 12px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.7rem;
        color: #555555;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .summary-row span:last-child {
        font-weight: 700;
        color: #1a1a1a;
    }
    
    .free-shipping {
        color: #16a34a !important;
        font-weight: 700;
    }
    
    .summary-total-divider {
        height: 1.5px;
        background: #1a1a1a;
        margin: 20px 0;
    }
    
    .total-row {
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        color: #1a1a1a !important;
        margin-bottom: 24px;
    }
    
    .total-row span:last-child {
        font-size: 0.85rem;
    }
    
    .tax-info-text {
        font-size: 0.6rem;
        color: #666666;
        line-height: 1.5;
        margin-bottom: 24px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .checkout-submit-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 14px;
        background: #1a1a1a;
        color: #ffffff;
        border: 1px solid #1a1a1a;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .checkout-submit-btn:hover {
        background: transparent;
        color: #1a1a1a;
    }
    
    .continue-shopping-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #555555;
        text-decoration: none;
        margin-top: 20px;
        transition: color 0.2s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .continue-shopping-link:hover {
        color: #1a1a1a;
        opacity: 1;
    }
    
    .continue-shopping-link svg {
        width: 14px;
        height: 14px;
    }
    
    /* Empty State */
    .empty-cart-box {
        text-align: center;
        padding: 80px 40px;
        background: transparent;
        border-radius: 0;
        max-width: 500px;
        margin: 40px auto;
    }
    
    .empty-cart-icon {
        width: 56px;
        height: 56px;
        background: rgba(0, 0, 0, 0.03);
        border: 1px solid #1a1a1a;
        color: #1a1a1a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
    
    .empty-cart-icon svg {
        width: 24px;
        height: 24px;
    }
    
    .empty-cart-title {
        font-family: var(--font-primary);
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a1a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 12px;
    }
    
    .empty-cart-desc {
        color: #666666;
        font-size: 0.8rem;
        line-height: 1.6;
        margin-bottom: 24px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .empty-cart-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 28px;
        background: #1a1a1a;
        color: #ffffff;
        border: 1px solid #1a1a1a;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        transition: all 0.3s;
        text-decoration: none;
    }
    
    .empty-cart-btn:hover {
        background: transparent;
        color: #1a1a1a;
    }
    
    /* Responsive Media Queries */
    @media (max-width: 1024px) {
        .cart-grid-container {
            grid-template-columns: 1fr;
            gap: 50px;
        }
        
        .cart-summary-column {
            position: static;
        }
    }
    
    @media (max-width: 768px) {
        .cart-page-wrapper {
            padding: 24px 16px 60px;
        }
        .cart-grid-container .new-arrivals-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 24px 15px !important;
        }
    }
</style>

<div class="container cart-page-container cart-page-wrapper" style="max-width: 1200px;">
    
    <div class="cart-header-section">
        <h1>Your Bag</h1>
        <p><?php echo !empty($_SESSION['cart']) ? count($_SESSION['cart']) . ' ' . (count($_SESSION['cart']) === 1 ? 'item' : 'items') : '0 items'; ?></p>
    </div>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="empty-cart-box">
            <div class="empty-cart-icon">
                <i data-lucide="shopping-bag"></i>
            </div>
            <h2 class="empty-cart-title">Your bag is empty</h2>
            <p class="empty-cart-desc">Looks like you haven't added any premium bags to your bag yet.</p>
            <a href="<?php echo BASE_URL; ?>/shop.php" class="empty-cart-btn">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-grid-container">
            <!-- Left Side: Grid of Product Cards identical to other pages -->
            <div class="new-arrivals-grid" style="grid-template-columns: repeat(2, 1fr); gap: 40px 30px;">
                <?php foreach ($_SESSION['cart'] as $id => $item): 
                    $img = $item['image'] ? BASE_URL . '/assets/' . htmlspecialchars($item['image']) : '/assets/product_pants.png';
                ?>
                    <div class="product-card-min" style="position: relative;">
                        <!-- Product Image inside white border box -->
                        <a href="<?php echo BASE_URL; ?>/product.php?id=<?php echo $id; ?>" class="product-img-box">
                            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        </a>
                        
                        <!-- Title & Single Price -->
                        <div class="product-min-title"><?php echo htmlspecialchars($item['name']); ?></div>
                        <div class="product-min-price">₹<?php echo number_format($item['price']); ?></div>
                        
                        <!-- Stepper -->
                        <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST" style="margin-bottom: 12px;">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <div class="cart-qty-stepper">
                                <button type="button" class="qty-btn" onclick="const i = this.nextElementSibling; i.value = Math.max(1, parseInt(i.value) - 1); i.form.submit();">-</button>
                                <input type="number" name="quantity" class="qty-input" value="<?php echo $item['quantity']; ?>" min="1" readonly>
                                <button type="button" class="qty-btn" onclick="const i = this.previousElementSibling; i.value = parseInt(i.value) + 1; i.form.submit();">+</button>
                            </div>
                        </form>
                        
                        <!-- Item Total Price -->
                        <div style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; color: #1a1a1a;">
                            Total: ₹<?php echo number_format($item['price'] * $item['quantity']); ?>
                        </div>

                        <!-- Remove Link (matching wishlist style) -->
                        <form action="<?php echo BASE_URL; ?>/cart_action.php" method="POST">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <button type="submit" class="wishlist-remove-link" style="background: none; border: none; font-size: 0.55rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #555555; cursor: pointer; border-bottom: 1px solid rgba(0, 0, 0, 0.2); padding-bottom: 1px; display: inline-block;">REMOVE</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Right Side: Order Summary -->
            <div class="cart-summary-column">
                <div class="cart-summary-card">
                    <h2 class="summary-title">Order Summary</h2>
                    
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>₹<?php echo number_format(getCartTotal()); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span class="free-shipping">Free Delivery</span>
                    </div>
                    
                    <div class="summary-total-divider"></div>
                    
                    <div class="summary-row total-row">
                        <span>Total</span>
                        <span>₹<?php echo number_format(getCartTotal()); ?></span>
                    </div>
                    
                    <p class="tax-info-text">Duties, taxes, and shipping are included in the total price.</p>
                    
                    <a href="<?php echo BASE_URL; ?>/checkout.php" class="checkout-submit-btn">Proceed To Checkout</a>
                    
                    <a href="<?php echo BASE_URL; ?>/shop.php" class="continue-shopping-link">
                        <i data-lucide="arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
