<?php
require 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid access");
}

$item_id    = $_POST['item_id'] ?? null;
$item_name  = $_POST['item_name'] ?? null;
$item_price = $_POST['item_price'] ?? null;

if (!$item_id || !$item_name || !$item_price) {
    die("Invalid item data");
}

$item_id    = intval($item_id);
$item_name  = mysqli_real_escape_string($conn, $item_name);
$item_price = floatval($item_price);

$sql = "INSERT INTO cart (item_id, item_name, price) 
        VALUES ('$item_id', '$item_name', '$item_price')";

if (mysqli_query($conn, $sql)) {
    header("Location: ../../pages/menu/fullmenu.php?added=1");
    exit;
} else {
    echo "Database error: " . mysqli_error($conn);
}
?>