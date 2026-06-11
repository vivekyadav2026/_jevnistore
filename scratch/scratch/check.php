<?php
$conn = new mysqli("localhost", "root", "", "clothproject_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "--- TABLES ---\n";
$res = $conn->query("SHOW TABLES");
while ($row = $res->fetch_row()) {
    echo $row[0] . "\n";
    
    // Show columns
    $cols = $conn->query("SHOW COLUMNS FROM " . $row[0]);
    while ($col = $cols->fetch_assoc()) {
        echo "  - " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
}

echo "\n--- PRODUCTS & IMAGES ---\n";
$res = $conn->query("SELECT p.id, p.name, p.image, pi.image_path FROM products p LEFT JOIN product_images pi ON p.id = pi.product_id");
while ($row = $res->fetch_assoc()) {
    echo "Product ID {$row['id']}: {$row['name']} | Primary Image: {$row['image']} | Extra Image: {$row['image_path']}\n";
}
?>
