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

$phone = $_POST['phone'] ?? '';
$otp = $_POST['otp'] ?? '';

// Basic validations
$phone = preg_replace('/[^0-9]/', '', $phone);
if (strlen($phone) !== 10) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid 10-digit mobile number']);
    exit();
}

if (strlen($otp) !== 4) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a 4-digit OTP']);
    exit();
}

// For demo purposes, we will accept any 4-digit OTP (e.g. 1234)
// Let's check if the user already exists in the database
$stmt = $conn->prepare("SELECT * FROM users WHERE phone = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // Existing user found - Log them in
    $user = $res->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['user_name'] = $user['name'];
    
    // Clean guest name/email placeholders so they are not prefilled
    $clean_name = ($user['name'] !== 'Guest Customer') ? $user['name'] : '';
    $clean_email = (strpos($user['email'], 'guest_') === 0) ? '' : $user['email'];
    
    echo json_encode([
        'status' => 'success', 
        'message' => 'Logged in successfully',
        'user' => [
            'name' => $clean_name,
            'email' => $clean_email
        ]
    ]);
    exit();
} else {
    // New user - Auto-register and log them in
    $name = 'Guest Customer';
    // Create a unique placeholder email based on phone number to satisfy the DB constraint
    $email = 'guest_' . $phone . '@jevani.com';
    $role = 'customer';
    
    // Hash a random password for security
    $random_password = bin2hex(random_bytes(8));
    $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);
    
    $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
    $insert_stmt->bind_param("sssss", $name, $email, $hashed_password, $phone, $role);
    
    if ($insert_stmt->execute()) {
        $new_user_id = $conn->insert_id;
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['role'] = $role;
        $_SESSION['user_name'] = $name;
        
        echo json_encode(['status' => 'success', 'message' => 'Account created and logged in successfully']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create guest account. Please try again.']);
        exit();
    }
}
?>
