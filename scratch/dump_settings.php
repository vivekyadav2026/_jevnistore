<?php
require_once __DIR__ . '/../includes/db.php';
$res = $conn->query("SELECT * FROM settings");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "Key: " . $row['key'] . " | Value: " . $row['value'] . "\n";
    }
} else {
    echo "No settings table or query error.\n";
}
?>
