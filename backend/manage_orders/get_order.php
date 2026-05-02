<?php
require "../backend/database/db.php";

if(isset($_GET['order_id'])){
    $id = $_GET['order_id'];

    $stmt = $pdo->prepare("
    SELECT 
        o.Order_id,
        u.name AS customer_name,
        u.email,
        u.phone_number,
        d.delivery_address AS address,
        o.Total_paid AS total,
        o.order_status AS status,
        o.Order_date
    FROM `order` o
    JOIN user u ON o.user_id = u.user_id
    JOIN delivery d ON o.Delivery_id = d.Delivery_id
    WHERE o.Order_id = ?
");


    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($order);
}
?>
