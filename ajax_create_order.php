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
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method, shipping_address) VALUES (?, ?, ?, ?)");
$stmt->bind_param("idss", $user_id, $total, $payment_method, $combined_address);

if ($stmt->execute()) {
    $order_id = $conn->insert_id;
    
    // 3. Insert Order Items
    foreach ($_SESSION['cart'] as $pid => $item) {
        $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $item_stmt->bind_param("iiid", $order_id, $pid, $item['quantity'], $item['price']);
        $item_stmt->execute();
    }
    
    // 4. Clear Cart Session
    unset($_SESSION['cart']);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Order placed successfully',
        'order_id' => $order_id,
        'payment_method' => $payment_method
    ]);
    exit();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create order. Please try again.']);
    exit();
}
?>
