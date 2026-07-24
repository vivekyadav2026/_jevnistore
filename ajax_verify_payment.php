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

$razorpay_payment_id = trim($_POST['razorpay_payment_id'] ?? '');
$razorpay_order_id = trim($_POST['razorpay_order_id'] ?? '');
$razorpay_signature = trim($_POST['razorpay_signature'] ?? '');
$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;

if (empty($razorpay_payment_id) || empty($razorpay_order_id) || empty($razorpay_signature) || $order_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Missing verification parameters']);
    exit();
}

// Fetch Key Secret from Settings
$key_secret = getSetting('razorpay_key_secret');
if (empty($key_secret)) {
    echo json_encode(['status' => 'error', 'message' => 'Razorpay credentials not configured on server.']);
    exit();
}

// Calculate expected signature
$generated_signature = hash_hmac('sha256', $razorpay_order_id . '|' . $razorpay_payment_id, $key_secret);

if (hash_equals($generated_signature, $razorpay_signature)) {
    // Payment verified!
    // Update local order payment details
    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid', payment_id = ? WHERE id = ?");
    $stmt->bind_param("si", $razorpay_payment_id, $order_id);
    
    if ($stmt->execute()) {
        // Clear cart
        unset($_SESSION['cart']);
        if (isset($_SESSION['pending_order_id'])) {
            unset($_SESSION['pending_order_id']);
        }

        // Auto-push order to Shiprocket
        $shiprocket_res = pushOrderToShiprocket($order_id);

        echo json_encode([
            'status' => 'success',
            'message' => 'Payment verified and order successfully completed.',
            'shiprocket' => $shiprocket_res
        ]);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Payment verified, but failed to update local database. Please contact support.']);
        exit();
    }
} else {
    // Log failure or attempt to notify admin
    error_log("Razorpay signature mismatch for Order #" . $order_id);
    echo json_encode(['status' => 'error', 'message' => 'Payment signature verification failed. The transaction might be invalid.']);
    exit();
}
?>
