<?php
require 'includes/db.php';
$res = $conn->query('SHOW COLUMNS FROM waitlists');
while($row = $res->fetch_assoc()){ print_r($row); }
