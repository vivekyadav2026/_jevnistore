<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/db.php';
require_once 'includes/functions.php';

header('Content-Type: text/plain');

$order_id = 5; // Target Order ID

echo "--- Shiprocket Order #$order_id Debugging Tool ---\n";

// 1. Fetch Order
$stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "ERROR: Order #$order_id not found in database.\n";
    exit();
}

// 2. Fetch Items
$item_stmt = $conn->prepare("SELECT oi.*, p.name as prod_name, p.weight, p.length, p.width, p.height FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$items_res = $item_stmt->get_result();

$order_items = [];
$total_weight = 0;
$max_length = 0;
$max_width = 0;
$max_height = 0;

while ($item = $items_res->fetch_assoc()) {
    $wgt = (float)$item['weight'] > 0 ? (float)$item['weight'] : (float)getSetting('shiprocket_default_weight', '0.5');
    $total_weight += $wgt * (int)$item['quantity'];
    
    $l = (int)$item['length'] > 0 ? (int)$item['length'] : (int)getSetting('shiprocket_default_length', '10');
    $w = (int)$item['width'] > 0 ? (int)$item['width'] : (int)getSetting('shiprocket_default_width', '10');
    $h = (int)$item['height'] > 0 ? (int)$item['height'] : (int)getSetting('shiprocket_default_height', '10');
    
    if ($l > $max_length) $max_length = $l;
    if ($w > $max_width) $max_width = $w;
    if ($h > $max_height) $max_height = $h;
    
    $variant_suffix = !empty($item['variant']) ? ' (' . $item['variant'] . ')' : '';
    $sku_suffix = !empty($item['variant']) ? '_' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $item['variant']), 0, 8)) : '';
    
    $order_items[] = [
        'name' => $item['prod_name'] . $variant_suffix,
        'sku' => 'PROD_' . $item['product_id'] . $sku_suffix,
        'units' => (int)$item['quantity'],
        'selling_price' => (float)$item['price']
    ];
}

// 3. Format Address
$address = $order['shipping_address'];
$pincode = '';
$state = '';
$city = '';
$address1 = '';
$address2 = '';

if (preg_match('/(\d{6})/', $address, $matches)) {
    $pincode = $matches[1];
}

$clean_addr = preg_replace('/-\s*\d{6}/', '', $address);
$clean_addr = preg_replace('/\((Home|Work)\)/i', '', $clean_addr);
$parts = array_map('trim', explode(',', $clean_addr));

if (count($parts) >= 1) $state = array_pop($parts);
if (count($parts) >= 1) $city = array_pop($parts);
if (count($parts) >= 1) $address2 = array_pop($parts);
$address1 = implode(', ', $parts);

if (empty($address1)) {
    $address1 = $address2;
    $address2 = '';
}
if (empty($address1)) {
    $address1 = $clean_addr;
}

$token = getShiprocketToken();
if (!$token) {
    echo "ERROR: Failed to authenticate with Shiprocket API.\n";
    exit();
}

$payment_method = ($order['payment_method'] === 'cod') ? 'COD' : 'Prepaid';

$payload = [
    'order_id' => 'JEVNI_' . $order['id'],
    'order_date' => date('Y-m-d H:i', strtotime($order['created_at'])),
    'pickup_location' => getSetting('shiprocket_pickup_location', 'Primary'),
    'billing_customer_name' => $order['user_name'],
    'billing_last_name' => '',
    'billing_address' => $address1,
    'billing_address_2' => $address2,
    'billing_city' => $city,
    'billing_pincode' => $pincode,
    'billing_state' => $state,
    'billing_country' => 'India',
    'billing_email' => $order['user_email'],
    'billing_phone' => preg_replace('/[^0-9]/', '', $order['user_phone']) ? substr(preg_replace('/[^0-9]/', '', $order['user_phone']), -10) : '9999999999',
    'shipping_is_billing' => true,
    'order_items' => $order_items,
    'payment_method' => $payment_method,
    'sub_total' => (float)$order['total_amount'],
    'length' => $max_length > 0 ? $max_length : (float)getSetting('shiprocket_default_length', '10'),
    'width' => $max_width > 0 ? $max_width : (float)getSetting('shiprocket_default_width', '10'),
    'height' => $max_height > 0 ? $max_height : (float)getSetting('shiprocket_default_height', '10'),
    'weight' => $total_weight > 0 ? $total_weight : (float)getSetting('shiprocket_default_weight', '0.5')
];

echo "PAYLOAD:\n" . json_encode($payload, JSON_PRETTY_PRINT) . "\n\n";

echo "Attempting to push order to Shiprocket...\n";

$ch = curl_init('https://apiv2.shiprocket.in/v1/external/orders/create/adhoc');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "API Response:\n";
echo $response . "\n";
echo "---------------------------------\n";
?>
