<?php
require "../config/db.php";

if(isset($_POST['id']) && isset($_POST['status'])){

    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE order_id=?");
    $stmt->execute([$status, $id]);

    echo json_encode(["success" => true]);
}
?>
