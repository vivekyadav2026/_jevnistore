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
        redirect('/login.php');
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect('/index.php');
    }
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
            break;
        case 'image/webp':
            $image = @imagecreatefromwebp($tmp_path);
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
?>
