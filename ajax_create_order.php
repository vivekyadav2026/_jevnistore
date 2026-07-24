<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'User is not logged in. Please verify your phone number.']);
    exit();
}

if (empty($_SESSION['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Your cart is empty']);
    exit();
}

// Validate cart items
foreach ($_SESSION['cart'] as $pid => $item) {
    $product_id = isset($item['product_id']) ? (int)$item['product_id'] : (int)$pid;
    $stmt = $conn->prepare("SELECT is_waitlist FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $product = $res->fetch_assoc();
        if (isset($product['is_waitlist']) && $product['is_waitlist'] == 1) {
            echo json_encode(['status' => 'error', 'message' => 'One or more items in your cart are currently on waitlist and cannot be ordered. Please remove them from your cart to proceed.']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'An item in your cart is no longer available.']);
        exit();
    }
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pincode = trim($_POST['pincode'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$flat = trim($_POST['flat'] ?? '');
$area = trim($_POST['area'] ?? '');
$address_type = trim($_POST['address_type'] ?? 'Home');
$payment_method = trim($_POST['payment_method'] ?? 'cod');

if (empty($name) || empty($email) || empty($pincode) || empty($city) || empty($state) || empty($flat) || empty($area)) {
    echo json_encode(['status' => 'error', 'message' => 'All delivery fields are required']);
    exit();
}

// Combine address components
$combined_address = $flat . ", " . $area . ", " . $city . ", " . $state . " - " . $pincode . " (" . $address_type . ")";
$total = getCartTotal();

// 1. Update user info (check if the email is already taken by another user first)
$chk_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$chk_stmt->bind_param("si", $email, $user_id);
$chk_stmt->execute();
$chk_res = $chk_stmt->get_result();

if ($chk_res->num_rows > 0) {
    // Email belongs to someone else. Update name and address only to prevent UNIQUE constraint crash
    $upd_stmt = $conn->prepare("UPDATE users SET name = ?, address = ? WHERE id = ?");
    $upd_stmt->bind_param("ssi", $name, $combined_address, $user_id);
} else {
    // Update name, email, and address
    $upd_stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, address = ? WHERE id = ?");
    $upd_stmt->bind_param("sssi", $name, $email, $combined_address, $user_id);
}

$upd_stmt->execute();

// Update session user name in case it changed
$_SESSION['user_name'] = $name;

// 2. Create Order
$payment_status = 'pending';
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method, payment_status, shipping_address) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("idsss", $user_id, $total, $payment_method, $payment_status, $combined_address);

if ($stmt->execute()) {
    $order_id = $conn->insert_id;
    
    // 3. Insert Order Items
    foreach ($_SESSION['cart'] as $pid => $item) {
        $product_id = isset($item['product_id']) ? (int)$item['product_id'] : (int)$pid;
        $variant = $item['variant'] ?? '';
        $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, variant) VALUES (?, ?, ?, ?, ?)");
        $item_stmt->bind_param("iiids", $order_id, $product_id, $item['quantity'], $item['price'], $variant);
        $item_stmt->execute();
    }
    
    if ($payment_method === 'razorpay') {
        // Initialize Razorpay Order via REST API
        $key_id = getSetting('razorpay_key_id');
        $key_secret = getSetting('razorpay_key_secret');

        if (empty($key_id) || empty($key_secret)) {
            // Rollback order
            $conn->query("DELETE FROM order_items WHERE order_id = " . $order_id);
            $conn->query("DELETE FROM orders WHERE id = " . $order_id);
            echo json_encode(['status' => 'error', 'message' => 'Razorpay payment gateway credentials are not configured. Please contact the administrator.']);
            exit();
        }

        $amount_in_paise = round($total * 100);

        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'amount' => $amount_in_paise,
            'currency' => 'INR',
            'receipt' => 'order_rcpt_' . $order_id
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 201) {
            $rzp_order = json_decode($response, true);
            if (isset($rzp_order['id'])) {
                $_SESSION['pending_order_id'] = $order_id;
                
                echo json_encode([
                    'status' => 'success',
                    'payment_method' => 'razorpay',
                    'order_id' => $order_id,
                    'razorpay_order_id' => $rzp_order['id'],
                    'razorpay_key' => $key_id,
                    'amount' => $amount_in_paise
                ]);
                exit();
            }
        }

        // Clean up and error out if Razorpay order creation failed
        $conn->query("DELETE FROM order_items WHERE order_id = " . $order_id);
        $conn->query("DELETE FROM orders WHERE id = " . $order_id);
        
        $errMsg = 'Failed to create payment order with Razorpay.';
        if ($response) {
            $rzp_err = json_decode($response, true);
            if (isset($rzp_err['error']['description'])) {
                $errMsg .= ' ' . $rzp_err['error']['description'];
            }
        }
        
        echo json_encode(['status' => 'error', 'message' => $errMsg]);
        exit();
        
    } else {
        // COD Order - Clear Cart and Push to Shiprocket automatically
        unset($_SESSION['cart']);
        
        // Auto-push order to Shiprocket
        $shiprocket_res = pushOrderToShiprocket($order_id);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Order placed successfully',
            'order_id' => $order_id,
            'payment_method' => $payment_method,
            'shiprocket' => $shiprocket_res
        ]);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create order. Please try again.']);
    exit();
}
?>
