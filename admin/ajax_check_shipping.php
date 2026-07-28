<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($order_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid order ID']);
    exit();
}

// 1. Fetch Order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo json_encode(['status' => 'error', 'message' => 'Order not found']);
    exit();
}

// 2. Extract Delivery Pincode
$address = $order['shipping_address'];
$delivery_postcode = '';
if (preg_match('/(\d{6})/', $address, $matches)) {
    $delivery_postcode = $matches[1];
}

if (empty($delivery_postcode)) {
    echo json_encode(['status' => 'error', 'message' => 'Could not extract 6-digit pincode from shipping address.']);
    exit();
}

// 3. Calculate Total Weight
$item_stmt = $conn->prepare("SELECT oi.quantity, p.weight FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$items_res = $item_stmt->get_result();

$total_weight = 0;
while ($item = $items_res->fetch_assoc()) {
    $w = (float)$item['weight'] > 0 ? (float)$item['weight'] : (float)getSetting('shiprocket_default_weight', '0.5');
    $total_weight += $w * (int)$item['quantity'];
}
if ($total_weight <= 0) {
    $total_weight = (float)getSetting('shiprocket_default_weight', '0.5');
}

// 4. Get Pickup Pincode
// Ideally we should know the pickup pincode. Shiprocket's pickup location is usually a string name (e.g. "Primary").
// For the serviceability API, we need the 6-digit pickup pincode.
// We can try to use a setting `shiprocket_pickup_pincode`, but since we only have `shiprocket_pickup_location` right now,
// we might need to add it, or just query Shiprocket for pickup locations.
// A simpler alternative is to ask the user to provide the pickup pincode in settings.
// For now, let's fetch it from a setting, fallback to empty (which might cause an error).
$pickup_postcode = getSetting('shiprocket_pickup_pincode', '');

// Wait, the Shiprocket API requires pickup_postcode OR pickup_location (depending on API version, but standard serviceability wants pickup_postcode).
// Actually, in Shiprocket `/v1/external/courier/serviceability/` takes `pickup_postcode` and `delivery_postcode`.
// Let's check if the admin has saved a `shiprocket_pickup_pincode`. If not, we prompt them.

if (empty($pickup_postcode)) {
    echo json_encode(['status' => 'error', 'message' => 'Pickup Pincode is not configured. Please add it in Settings.']);
    exit();
}

// 5. Call Shiprocket API
$token = getShiprocketToken();
if (!$token) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to authenticate with Shiprocket API. Check credentials.']);
    exit();
}

$is_cod = ($order['payment_method'] === 'cod') ? 1 : 0;

$url = "https://apiv2.shiprocket.in/v1/external/courier/serviceability/?pickup_postcode={$pickup_postcode}&delivery_postcode={$delivery_postcode}&weight={$total_weight}&cod={$is_cod}";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200 && $response) {
    $data = json_decode($response, true);
    
    if (isset($data['status']) && $data['status'] == 404) {
        echo json_encode(['status' => 'error', 'message' => 'Serviceability not found for these pincodes.']);
        exit();
    }
    
    if (isset($data['data']['available_courier_companies']) && is_array($data['data']['available_courier_companies'])) {
        $couriers = $data['data']['available_courier_companies'];
        if (count($couriers) > 0) {
            // Find the lowest rate
            $lowest_rate = -1;
            $courier_name = '';
            
            foreach ($couriers as $c) {
                $rate = (float)$c['rate'];
                if ($lowest_rate < 0 || $rate < $lowest_rate) {
                    $lowest_rate = $rate;
                    $courier_name = $c['courier_name'];
                }
            }
            
            echo json_encode([
                'status' => 'success',
                'weight' => $total_weight,
                'rate' => $lowest_rate,
                'courier_name' => $courier_name,
                'pickup_pincode' => $pickup_postcode,
                'delivery_pincode' => $delivery_postcode
            ]);
            exit();
        }
    }
    
    echo json_encode(['status' => 'error', 'message' => 'No couriers available.']);
} else {
    echo json_encode(['status' => 'error', 'message' => "Shiprocket API Error ($httpCode)"]);
}
