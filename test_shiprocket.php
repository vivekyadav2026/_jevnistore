<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

header('Content-Type: text/plain');

$email = getSetting('shiprocket_email');
$password = getSetting('shiprocket_password');

echo "--- Shiprocket Debugging Tool ---\n";
echo "Saved Email: " . ($email ? $email : "[EMPTY]") . "\n";
echo "Saved Password Length: " . strlen($password) . "\n\n";

if (!$email || !$password) {
    echo "ERROR: Credentials are not saved in Admin Settings.\n";
    exit();
}

echo "Attempting to authenticate with Shiprocket API...\n";

$payload = [
    'email' => $email,
    'password' => $password
];

$ch = curl_init('https://apiv2.shiprocket.in/v1/external/auth/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
if ($curlError) {
    echo "cURL Error: " . $curlError . "\n";
}

echo "API Response:\n";
echo $response . "\n";
echo "---------------------------------\n";
?>
