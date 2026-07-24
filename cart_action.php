<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = (int)($_POST['product_id'] ?? 0);
    $variant = isset($_POST['variant']) ? trim($_POST['variant']) : '';
    $cart_key_post = $_POST['cart_key'] ?? '';
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($action == 'add' && $product_id > 0) {
        $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        $stmt = $conn->prepare("SELECT name, price, image, is_waitlist, stock FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows > 0) {
            $product = $res->fetch_assoc();
            
            if (isset($product['is_waitlist']) && $product['is_waitlist'] == 1) {
                // Reject adding waitlist item to cart
                if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
                    echo json_encode(['status' => 'error', 'message' => 'This item is on waitlist and cannot be added to cart.']);
                    exit();
                } else {
                    setFlash('This item is on waitlist and cannot be added to cart.', 'error');
                    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
                    exit();
                }
            } elseif (isset($product['stock']) && $product['stock'] <= 0) {
                // Reject adding out of stock item to cart
                if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
                    echo json_encode(['status' => 'error', 'message' => 'This item is currently out of stock.']);
                    exit();
                } else {
                    setFlash('This item is currently out of stock.', 'error');
                    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
                    exit();
                }
            }
            
            // Build composite cart key
            $cart_key = $product_id;
            if ($variant !== '') {
                $cart_key = $product_id . '_' . md5($variant);
            }
            
            if (isset($_SESSION['cart'][$cart_key])) {
                $_SESSION['cart'][$cart_key]['quantity'] += $qty;
            } else {
                $_SESSION['cart'][$cart_key] = [
                    'product_id' => $product_id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => $qty,
                    'variant' => $variant
                ];
            }
        }
    } elseif ($action == 'bulk_add') {
        $pids = $_POST['product_ids'] ?? [];
        if (is_array($pids)) {
            foreach ($pids as $pid) {
                $pid = (int)$pid;
                if ($pid > 0) {
                    $stmt = $conn->prepare("SELECT name, price, image, is_waitlist FROM products WHERE id = ?");
                    $stmt->bind_param("i", $pid);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    if ($res->num_rows > 0) {
                        $product = $res->fetch_assoc();
                        
                        // Skip if waitlist
                        if (isset($product['is_waitlist']) && $product['is_waitlist'] == 1) {
                            continue;
                        }
                        
                        // Default bulk add has no variant
                        $cart_key = $pid;
                        
                        if (isset($_SESSION['cart'][$cart_key])) {
                            $_SESSION['cart'][$cart_key]['quantity'] += 1;
                        } else {
                            $_SESSION['cart'][$cart_key] = [
                                'product_id' => $pid,
                                'name' => $product['name'],
                                'price' => $product['price'],
                                'image' => $product['image'],
                                'quantity' => 1,
                                'variant' => ''
                            ];
                        }
                    }
                }
            }
        }
    } elseif ($action == 'remove') {
        $remove_key = !empty($cart_key_post) ? $cart_key_post : $product_id;
        if (isset($_SESSION['cart'][$remove_key])) {
            unset($_SESSION['cart'][$remove_key]);
        }
    } elseif ($action == 'update') {
        $update_key = !empty($cart_key_post) ? $cart_key_post : $product_id;
        $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($qty > 0 && isset($_SESSION['cart'][$update_key])) {
            $_SESSION['cart'][$update_key]['quantity'] = $qty;
        } elseif ($qty <= 0 && isset($_SESSION['cart'][$update_key])) {
            unset($_SESSION['cart'][$update_key]);
        }
    }
}

if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
    $count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
    $action = $_POST['action'] ?? '';
    $msg = 'Cart updated.';
    if ($action === 'add') $msg = 'Item added to cart!';
    elseif ($action === 'remove') $msg = 'Item removed from cart.';
    elseif ($action === 'update') $msg = 'Cart quantity updated.';
    elseif ($action === 'bulk_add') $msg = 'Items added to cart!';
    echo json_encode(['status' => 'success', 'count' => $count, 'message' => $msg]);
    exit();
}

// Redirect back to where user came from, or cart
$referer = $_SERVER['HTTP_REFERER'] ?? 'cart.php';

if (isset($_POST['buy_now']) && $_POST['buy_now'] == '1') {
    header("Location: checkout.php");
} else {
    header("Location: $referer");
}
exit();
?>
