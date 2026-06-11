<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

    if ($product_id > 0) {
        if ($action == 'add') {
            if (!in_array($product_id, $_SESSION['wishlist'])) {
                $_SESSION['wishlist'][] = $product_id;
            }
        } elseif ($action == 'remove') {
            if (($key = array_search($product_id, $_SESSION['wishlist'])) !== false) {
                unset($_SESSION['wishlist'][$key]);
                // Reindex
                $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
            }
        }
    }
}

// Return JSON for AJAX requests
if (isset($_POST['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';
    $msg = $action === 'add' ? 'Added to wishlist!' : 'Removed from wishlist.';
    $type = $action === 'add' ? 'success' : 'info';
    echo json_encode([
        'status'   => 'success',
        'count'    => count($_SESSION['wishlist']),
        'wishlist' => $_SESSION['wishlist'],
        'message'  => $msg,
        'type'     => $type
    ]);
    exit();
}

// Redirect back
$referer = $_SERVER['HTTP_REFERER'] ?? 'wishlist.php';
redirect($referer);
