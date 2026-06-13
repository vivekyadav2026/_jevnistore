<?php
include 'includes/db.php';
$res = $conn->query('SELECT * FROM categories');
while($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . " - Name: " . $row['name'] . "\n";
}
?>
