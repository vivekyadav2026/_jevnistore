<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $credential = $_POST['credential'] ?? '';
    
    if (!empty($credential)) {
        // Decode JWT ID Token payload
        $parts = explode('.', $credential);
        if (count($parts) === 3) {
            $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);
            
            if ($payload && isset($payload['email'])) {
                $email = trim($payload['email']);
                $name = trim($payload['name'] ?? 'Google User');

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

                echo json_encode([
                    'status' => 'success',
                    'redirect' => $redirect
                ]);
                exit;
            }
        }
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid Google credential']);
