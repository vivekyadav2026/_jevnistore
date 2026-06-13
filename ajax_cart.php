<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';

$cart_total = 0;
$html = '<div class="cart-header">';
$html .= '<h2 class="cart-title">YOUR CART</h2>';
$html .= '<button class="cart-close" onclick="closeCart()"><i data-lucide="x"></i></button>';
$html .= '</div>';
$html .= '<div class="cart-body" style="padding-bottom: 20px;">';

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        $item_total = $item['price'] * $item['quantity'];
        $cart_total += $item_total;
        $image = $item['image'] ? BASE_URL . '/assets/' . htmlspecialchars($item['image']) : '/assets/product_hoodie.png';
        
        $html .= '
        <div class="cart-item">
            <a href="'.BASE_URL.'/product.php?id='.$id.'" style="display: block; flex-shrink: 0; color: inherit; text-decoration: none;">
                <img src="'.$image.'" alt="'.htmlspecialchars($item['name']).'" class="cart-item-img">
            </a>
            <div class="cart-item-info">
                <div>
                    <a href="'.BASE_URL.'/product.php?id='.$id.'" style="color: inherit; text-decoration: none; display: inline-block;">
                        <h4 class="cart-item-title">'.htmlspecialchars($item['name']).'</h4>
                    </a>
                    <p style="font-size: 0.8rem; color: #555555;">QTY: '.$item['quantity'].'</p>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                    <p class="cart-item-price">₹'.number_format($item['price'], 2).'</p>
                    <form action="'.BASE_URL.'/cart_action.php" method="POST" class="ajax-remove-form" style="display:inline;">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="product_id" value="'.$id.'">
                        <input type="hidden" name="ajax" value="1">
                        <button type="submit" style="background:none;border:none;color:#555555;cursor:pointer;font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;text-decoration:underline;">Remove</button>
                    </form>
                </div>
            </div>
        </div>';
    }
} else {
    $html .= '<p style="text-align: center; color: #555555; margin-top: 50px; letter-spacing: 1px;">YOUR CART IS EMPTY</p>';
}

// Don't Miss Out Section
$html .= '
    <div class="cart-cross-sell" style="margin-top: 30px; border-top: 1px solid var(--border-color); padding-top: 20px;">
        <h4 style="font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; text-align: center; margin-bottom: 15px;">DON\'T MISS OUT</h4>
';
// Fetch one random product for cross-sell
$cs_stmt = $conn->prepare("SELECT id, name, price, image FROM products ORDER BY RAND() LIMIT 1");
$cs_stmt->execute();
$cs_res = $cs_stmt->get_result();
if ($cs_res->num_rows > 0) {
    $cs = $cs_res->fetch_assoc();
    $cs_image = $cs['image'] ? BASE_URL . '/assets/' . htmlspecialchars($cs['image']) : '/assets/product_pants.png';
    $html .= '
        <div style="display: flex; gap: 15px; align-items: center; background: #111; padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); margin-bottom: 10px;">
            <a href="'.BASE_URL.'/product.php?id='.$cs['id'].'" style="display: block; flex-shrink: 0; color: inherit; text-decoration: none;">
                <img src="'.$cs_image.'" style="width: 60px; height: 80px; object-fit: cover; border-radius: 4px;">
            </a>
            <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between; height: 80px;">
                <div>
                    <a href="'.BASE_URL.'/product.php?id='.$cs['id'].'" style="color: inherit; text-decoration: none; display: inline-block;">
                        <h5 style="font-size: 0.85rem; margin-bottom: 4px; color: #ffffff; font-weight: 500; letter-spacing: 0.5px;">'.htmlspecialchars($cs['name']).'</h5>
                    </a>
                    <p style="font-size: 0.8rem; color: #aaaaaa; font-weight: 500;">₹'.number_format($cs['price'], 2).'</p>
                </div>
                <form action="'.BASE_URL.'/cart_action.php" method="POST" class="ajax-cart-form" style="margin-top: 4px;">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="'.$cs['id'].'">
                    <button type="submit" class="btn" style="padding: 6px 14px; font-size: 0.7rem; width: auto; float: right; background-color: #ffffff; color: #000000; border: 1px solid #ffffff; border-radius: 4px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background=\'transparent\'; this.style.color=\'#ffffff\';" onmouseout="this.style.background=\'#ffffff\'; this.style.color=\'#000000\';">ADD</button>
                </form>
            </div>
        </div>
    ';
}
$html .= '</div></div>'; // End cart-cross-sell and cart-body

$html .= '<div class="cart-footer">';
$html .= '<div class="cart-total">';
$html .= '<span>Subtotal</span>';
$html .= '<span>₹'.number_format($cart_total, 2).'</span>';
$html .= '</div>';
$html .= '<p style="text-align: center; font-size: 0.75rem; color: #555555; margin-bottom: 15px;">Or continue shopping</p>';
$html .= '<div style="display: flex; justify-content: center; gap: 15px; margin-bottom: 20px; font-size: 0.7rem; font-weight: 500; letter-spacing: 1px; color: #555555;">';
$html .= '<span>VISA</span>';
$html .= '<span>MASTERCARD</span>';
$html .= '<span>PAYPAL</span>';
$html .= '</div>';
$html .= '<a href="'.BASE_URL.'/checkout.php" class="btn btn-block" style="background: #fff; color: #000;">Checkout</a>';
$html .= '</div>';

echo $html;
?>
