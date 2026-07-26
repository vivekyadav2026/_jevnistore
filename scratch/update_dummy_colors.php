<?php
require_once __DIR__ . '/includes/db.php';

// Update existing products with dummy color variations
$colors = 'Black, Red, Blue, Green, White, Beige';

$stmt = $conn->prepare("UPDATE products SET has_variants = 1, variant_name = 'Color', variants_list = ? WHERE has_variants = 0 OR variant_name IS NULL OR variant_name = '' OR variant_name = 'Color'");
$stmt->bind_param("s", $colors);
if ($stmt->execute()) {
    echo "Successfully updated " . $stmt->affected_rows . " products with dummy Color variations (" . $colors . ").\n";
} else {
    echo "Error updating products: " . $conn->error . "\n";
}

// Display updated products
$res = $conn->query("SELECT id, name, variant_name, variants_list FROM products");
while ($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Name: " . $row['name'] . " | Variant: " . $row['variant_name'] . " (" . $row['variants_list'] . ")\n";
}
