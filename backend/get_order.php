<?php
require "../config/db.php";

if(isset($_GET['order_id'])){
    $id = $_GET['order_id'];

    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id=?");
    $stmt->execute([$id]);

    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($order);
}
?>
