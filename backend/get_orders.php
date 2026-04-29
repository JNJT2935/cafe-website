<?php
require "../config/db.php";

$stmt = $pdo->query("SELECT * FROM orders ORDER BY order_id ASC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($orders);
?>
