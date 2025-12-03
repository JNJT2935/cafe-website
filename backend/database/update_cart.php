<?php
session_start();
include "db.php";

$cart_id = $_POST['cart_id'];
$action = $_POST['action'];  // plus, minus, delete

// Fetch current qty
$sql = "SELECT quantity FROM cart WHERE cart_id = $cart_id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$current_qty = $row['quantity'];

// Handle actions
if ($action === "plus") {
    $new_qty = $current_qty + 1;

} elseif ($action === "minus") {
    $new_qty = max(1, $current_qty - 1);  // prevents going below 1

} elseif ($action === "delete") {
    $conn->query("DELETE FROM cart WHERE cart_id = $cart_id");
    echo json_encode(["deleted" => true]);
    exit;
}

$conn->query("UPDATE cart SET quantity = $new_qty WHERE cart_id = $cart_id");

// Return updated totals
echo json_encode(["quantity" => $new_qty]);
?>
