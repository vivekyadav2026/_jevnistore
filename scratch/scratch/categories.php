<?php
$conn = new mysqli("localhost", "root", "", "clothproject_db");
$res = $conn->query("SELECT * FROM categories");
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Name: {$row['name']}\n";
}
?>
