<?php
ob_start(); // Buffer all output so header() calls always work
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'clothproject_db';
// $user = 'u478569362_jevni_Store';
// $password = 'jevni_Store2@';
// $dbname = 'u478569362_jevni';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define BASE_URL for absolute pathing based on directory structure
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$dir = str_replace('\\', '/', dirname(__DIR__));
$base_url = str_replace($doc_root, '', $dir);
define('BASE_URL', $base_url);

// Razorpay config (dummy keys for now)
define('RAZORPAY_KEY_ID', 'rzp_test_YOUR_KEY_ID');
define('RAZORPAY_KEY_SECRET', 'YOUR_KEY_SECRET');
?>
