<?php
require "../config/db.php";

try {

    $stmt = $pdo->query("
   SELECT 
    o.Order_id AS order_id,
    u.name AS customer_name,
    o.Order_date AS order_date,
    o.Total_paid AS total,

    CASE 
        WHEN o.order_status IN ('done','completed') THEN 'Delivered'
        WHEN o.order_status = 'pending' THEN 'Pending'
        ELSE 'Pending'
    END AS status

FROM `order` o
JOIN user u ON o.user_id = u.user_id
ORDER BY o.Order_id DESC



    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($orders);

} catch (PDOException $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
