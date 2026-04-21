<?php
require 'dbconnect.php';

if (
    !isset($_POST['item_id']) ||
    !isset($_POST['item_name']) ||
    !isset($_POST['item_price'])
) {
    echo "Invalid form submission";
    exit();
}

$item_id = intval($_POST['item_id']);
$item_name = $_POST['item_name'];
$item_price = floatval($_POST['item_price']);

if ($item_id <= 0 || $item_price <= 0) {
    echo "Invalid item data";
    exit();
}

$stmt = $conn->prepare("INSERT INTO cart (item_id, item_name, price) VALUES (?, ?, ?)");
$stmt->bind_param("isd", $item_id, $item_name, $item_price);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "DB error: " . $stmt->error;
}

$stmt->close();
?>