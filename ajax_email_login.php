<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit();
}

if (empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter your password.']);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $user = $res->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['role']      = $user['role'];
        $_SESSION['user_name'] = $user['name'];

        echo json_encode([
            'status'  => 'success',
            'message' => 'Logged in successfully',
            'user'    => [
                'name'  => $user['name'],
                'email' => $user['email'],
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect password. Please try again.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No account found with that email. Please register first.']);
}
exit();
?>
