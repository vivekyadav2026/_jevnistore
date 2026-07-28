<?php
require_once __DIR__ . '/../includes/db.php';

$queries = [
    "ALTER TABLE products ADD COLUMN weight DECIMAL(10,3) DEFAULT 0.5 AFTER stock",
    "ALTER TABLE products ADD COLUMN length INT DEFAULT 10 AFTER weight",
    "ALTER TABLE products ADD COLUMN width INT DEFAULT 10 AFTER length",
    "ALTER TABLE products ADD COLUMN height INT DEFAULT 10 AFTER width"
];

foreach ($queries as $query) {
    if ($conn->query($query)) {
        echo "Success: $query\n";
    } else {
        echo "Error: " . $conn->error . " -> $query\n";
    }
}
echo "Done.\n";
?>
