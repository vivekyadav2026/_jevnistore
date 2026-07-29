<?php
/**
 * Set a one-time flash notification (type: success | error | info | warning)
 */
function setFlash($message, $type = 'success') {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['_flash'] = ['msg' => $message, 'type' => $type];
}

/**
 * Retrieve and clear the flash notification (returns null if none)
 */
function getFlash() {
    if (!empty($_SESSION['_flash'])) {
        $flash = $_SESSION['_flash'];
        unset($_SESSION['_flash']);
        return $flash;
    }
    return null;
}

function redirect($url) {
    // Discard buffered output so the Location header is always sent cleanly
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    header("Location: " . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect(BASE_URL . '/login.php');
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect(BASE_URL . '/index.php');
    }
}

function getGoogleClientId() {
    return trim(getSetting('google_client_id', '')) ?: '';
}

function getGoogleClientSecret() {
    return trim(getSetting('google_client_secret', ''));
}

function getCartCount() {
    $count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    return $count;
}

function getCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

/**
 * Resizes an image if it exceeds 1200px and compresses it iteratively to be under 200KB (target_size)
 */
function compressAndSaveImage($tmp_path, $dest_path, $target_size = 204800) {
    $info = getimagesize($tmp_path);
    if ($info === false) {
        return move_uploaded_file($tmp_path, $dest_path);
    }
    
    $mime = $info['mime'];
    
    switch ($mime) {
        case 'image/jpeg':
        case 'image/jpg':
            $image = @imagecreatefromjpeg($tmp_path);
            break;
        case 'image/png':
            $image = @imagecreatefrompng($tmp_path);
            if ($image) {
                imagealphablending($image, false);
                imagesavealpha($image, true);
            }
            break;
        case 'image/webp':
            $image = @imagecreatefromwebp($tmp_path);
            if ($image) {
                imagealphablending($image, false);
                imagesavealpha($image, true);
            }
            break;
        default:
            return move_uploaded_file($tmp_path, $dest_path);
    }
    
    if (!$image) {
        return move_uploaded_file($tmp_path, $dest_path);
    }
    
    // Resize if dimensions are too large (limits to 1200px on the longest side)
    $max_dim = 1200;
    $width = imagesx($image);
    $height = imagesy($image);
    
    if ($width > $max_dim || $height > $max_dim) {
        if ($width > $height) {
            $new_width = $max_dim;
            $new_height = floor($height * ($max_dim / $width));
        } else {
            $new_height = $max_dim;
            $new_width = floor($width * ($max_dim / $height));
        }
        
        $resized = imagecreatetruecolor($new_width, $new_height);
        
        // Retain alpha transparency for PNG/WebP
        if ($mime == 'image/png' || $mime == 'image/webp') {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
            imagefilledrectangle($resized, 0, 0, $new_width, $new_height, $transparent);
        }
        
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagedestroy($image);
        $image = $resized;
    }
    
    // Save image while compressing
    $success = false;
    $quality = 85;
    
    do {
        ob_start();
        if ($mime == 'image/png') {
            $png_quality = round((100 - $quality) / 10); // scale 0-9
            if ($png_quality > 9) $png_quality = 9;
            if ($png_quality < 0) $png_quality = 0;
            imagepng($image, null, $png_quality);
        } elseif ($mime == 'image/webp') {
            imagewebp($image, null, $quality);
        } else {
            imagejpeg($image, null, $quality);
        }
        $data = ob_get_clean();
        $size = strlen($data);
        
        if ($size <= $target_size || $quality <= 30) {
            $success = file_put_contents($dest_path, $data) !== false;
            break;
        }
        $quality -= 10;
    } while ($quality >= 20);
    
    imagedestroy($image);
    return $success;
}

/**
 * Retrieve a setting value from the database settings table.
 */
function getSetting($key, $default = '') {
    global $conn;
    static $settings_cache = null;
    
    if ($settings_cache === null) {
        $settings_cache = [];
        // Check if settings table exists
        $table_check = $conn->query("SHOW TABLES LIKE 'settings'");
        if ($table_check && $table_check->num_rows > 0) {
            $res = $conn->query("SELECT `key`, `value` FROM settings");
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $settings_cache[$row['key']] = $row['value'];
                }
            }
        }
    }
    
    $val = isset($settings_cache[$key]) ? trim($settings_cache[$key]) : '';
    if ($val !== '') {
        return $val;
    }

    if ($default !== '') {
        return $default;
    }

    // Fallback constants
    if ($key === 'razorpay_key_id' && defined('RAZORPAY_KEY_ID')) {
        return RAZORPAY_KEY_ID;
    }
    if ($key === 'razorpay_key_secret' && defined('RAZORPAY_KEY_SECRET')) {
        return RAZORPAY_KEY_SECRET;
    }
    
    return $default;
}

/**
 * Get Shiprocket JWT Authentication Token.
 */
function getShiprocketToken() {
    $email = getSetting('shiprocket_email');
    $password = getSetting('shiprocket_password');

    if (empty($email) || empty($password)) {
        return null;
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Return cached token if valid
    if (isset($_SESSION['shiprocket_token']) && isset($_SESSION['shiprocket_token_expiry']) && $_SESSION['shiprocket_token_expiry'] > time()) {
        return $_SESSION['shiprocket_token'];
    }

    $ch = curl_init('https://apiv2.shiprocket.in/v1/external/auth/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'email' => $email,
        'password' => $password
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if (isset($data['token'])) {
            $_SESSION['shiprocket_token'] = $data['token'];
            // Token is usually valid for 10 days; cache it for 9 days to be safe
            $_SESSION['shiprocket_token_expiry'] = time() + (9 * 24 * 60 * 60);
            return $data['token'];
        }
    }

    return null;
}

/**
 * Automatically Push an Order to Shiprocket Panel.
 */
function pushOrderToShiprocket($order_id) {
    global $conn;

    // Fetch order details
    $stmt = $conn->prepare("SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();

    if (!$order) {
        return ['status' => 'error', 'message' => 'Order not found in database.'];
    }

    // Get order items (including variant column and dimensions)
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

    if (empty($order_items)) {
        return ['status' => 'error', 'message' => 'Order has no items.'];
    }

    // Parse Address
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
        return ['status' => 'error', 'message' => 'Failed to authenticate with Shiprocket API. Check API credentials in admin settings.'];
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
        'billing_phone' => $order['user_phone'] ?: '9999999999',
        'shipping_is_billing' => true,
        'order_items' => $order_items,
        'payment_method' => $payment_method,
        'sub_total' => (float)$order['total_amount'],
        'length' => $max_length ?: (int)getSetting('shiprocket_default_length', '10'),
        'width' => $max_width ?: (int)getSetting('shiprocket_default_width', '10'),
        'height' => $max_height ?: (int)getSetting('shiprocket_default_height', '10'),
        'weight' => $total_weight > 0 ? $total_weight : (float)getSetting('shiprocket_default_weight', '0.5')
    ];

    $ch = curl_init('https://apiv2.shiprocket.in/v2/authorized/orders/create/adhoc');
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

    if ($httpCode === 200 || $httpCode === 201) {
        $resData = json_decode($response, true);
        if (isset($resData['order_id']) && isset($resData['shipment_id'])) {
            $ship_order_id = $resData['order_id'];
            $shipment_id = $resData['shipment_id'];

            $upd = $conn->prepare("UPDATE orders SET shiprocket_order_id = ?, shiprocket_shipment_id = ? WHERE id = ?");
            $upd->bind_param("ssi", $ship_order_id, $shipment_id, $order_id);
            $upd->execute();

            return [
                'status' => 'success',
                'shiprocket_order_id' => $ship_order_id,
                'shiprocket_shipment_id' => $shipment_id
            ];
        }
    }

    $errMsg = 'API Error (HTTP ' . $httpCode . ')';
    if ($response) {
        $resData = json_decode($response, true);
        if (isset($resData['message'])) {
            $errMsg = $resData['message'];
        } elseif (isset($resData['errors'])) {
            $errMsg = json_encode($resData['errors']);
        }
    }

    return ['status' => 'error', 'message' => $errMsg];
}

/**
 * Fetch Live Shipment Tracking Info from Shiprocket
 */
function getShiprocketTrackingInfo($shipment_id) {
    $token = getShiprocketToken();
    if (!$token) return null;
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/courier/track/shipment/" . (int)$shipment_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer " . $token
        ]
    ]);
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if ($httpCode === 200 && $response) {
        return json_decode($response, true);
    }
    
    return null;
}

/**
 * Send an Email via SMTP using PHPMailer.
 */
function sendEmail($to, $subject, $body) {
    $host = getSetting('smtp_host', 'smtp.gmail.com');
    $port = (int)getSetting('smtp_port', '465');
    $encryption = getSetting('smtp_encryption', 'ssl');
    $username = getSetting('smtp_username');
    $password = getSetting('smtp_password');

    if (empty($username) || empty($password)) {
        return false;
    }

    require_once dirname(__DIR__) . '/vendor/autoload.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $username;
        $mail->Password   = $password;
        
        if ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($encryption === 'tls') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPAutoTLS = false;
            $mail->SMTPAuth = false;
        }
        
        $mail->Port       = $port;

        // SSL options to prevent local verification errors
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom($username, getSetting('site_title', 'Nørva Store'));
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
