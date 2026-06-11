<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'clothproject_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Razorpay config (dummy keys for now)
define('RAZORPAY_KEY_ID', 'rzp_test_YOUR_KEY_ID');
define('RAZORPAY_KEY_SECRET', 'YOUR_KEY_SECRET');
?>
