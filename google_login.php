<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// =========================================================================
// GOOGLE OAUTH CREDENTIALS CONFIGURATION (FROM WEB CLIENT 2 SCREENSHOT)
// =========================================================================
$client_id     = getGoogleClientId();
$client_secret = getGoogleClientSecret();
$redirect_uri  = BASE_URL . '/google_login.php';

// Ensure database settings table has these updated credentials
$conn->query("INSERT INTO settings (`key`, `value`) VALUES ('google_client_id', '$client_id') ON DUPLICATE KEY UPDATE `value` = '$client_id'");
$conn->query("INSERT INTO settings (`key`, `value`) VALUES ('google_client_secret', '$client_secret') ON DUPLICATE KEY UPDATE `value` = '$client_secret'");

// 1. Handle Callback from Google OAuth Server
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    if (!empty($client_id) && !empty($client_secret)) {
        $token_url = 'https://oauth2.googleapis.com/token';
        $post_data = [
            'code' => $code,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $redirect_uri,
            'grant_type' => 'authorization_code'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $token_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $token_data = json_decode($response, true);

        if (isset($token_data['access_token'])) {
            $user_info_url = 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $token_data['access_token'];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $user_info_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $user_json = curl_exec($ch);
            curl_close($ch);

            $google_user = json_decode($user_json, true);

            if (isset($google_user['email'])) {
                $email = trim($google_user['email']);
                $name = trim($google_user['name'] ?? 'Google User');

                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $res = $stmt->get_result();

                if ($res->num_rows > 0) {
                    $user = $res->fetch_assoc();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_name'] = $user['name'];
                } else {
                    $rand_pwd = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
                    $ins = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')");
                    $ins->bind_param("sss", $name, $email, $rand_pwd);
                    $ins->execute();

                    $_SESSION['user_id'] = $ins->insert_id;
                    $_SESSION['role'] = 'customer';
                    $_SESSION['user_name'] = $name;
                }

                $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL . '/customer/index.php';
                unset($_SESSION['redirect_after_login']);
                redirect($redirect);
                exit;
            }
        }
    }
}

// 2. Direct Redirect to Official Google OAuth Server (Strict OAuth 2.0 Specs)
$params = [
    'client_id'     => $client_id,
    'redirect_uri'  => $redirect_uri,
    'response_type' => 'code',
    'scope'         => 'openid email profile',
    'access_type'   => 'online',
    'prompt'        => 'select_account'
];

$auth_url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
header("Location: " . $auth_url);
exit;
