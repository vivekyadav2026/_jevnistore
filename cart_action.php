<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = (int)($_POST['product_id'] ?? 0);
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($action == 'add' && $product_id > 0) {
        $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        $stmt = $conn->prepare("SELECT name, price, image FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows > 0) {
            $product = $res->fetch_assoc();
            
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $qty;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => $qty
                ];
            }
        }
    } elseif ($action == 'bulk_add') {
        $pids = $_POST['product_ids'] ?? [];
        if (is_array($pids)) {
            foreach ($pids as $pid) {
                $pid = (int)$pid;
                if ($pid > 0) {
                    $stmt = $conn->prepare("SELECT name, price, image FROM products WHERE id = ?");
                    $stmt->bind_param("i", $pid);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    if ($res->num_rows > 0) {
                        $product = $res->fetch_assoc();
                        if (isset($_SESSION['cart'][$pid])) {
                            $_SESSION['cart'][$pid]['quantity'] += 1;
                        } else {
                            $_SESSION['cart'][$pid] = [
                                'name' => $product['name'],
                                'price' => $product['price'],
                                'image' => $product['image'],
                                'quantity' => 1
                            ];
                        }
                    }
                }
            }
        }
    } elseif ($action == 'remove' && $product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    } elseif ($action == 'update' && $product_id > 0) {
        $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($qty > 0 && isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $qty;
        } elseif ($qty <= 0) {
            unset($_SESSION['cart'][$product_id]);
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
