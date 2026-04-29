<?php
session_start();
 include ('../assets/includes/connect.php'); // use the PDO connection

// ---------- Basic auth check ----------
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/home.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders
$sql = "SELECT o.Order_id, o.Total_paid, o.order_status, o.Order_date
        FROM `order` o
        WHERE o.user_id = ?
        ORDER BY o.Order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bindParam(1, $user_id, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>Coffee Shop | Order History</title>

    <!-- CSS link placeholder -->
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/order_history/order_history.css">
    <link rel="stylesheet" href="../assets/css/cart/cart_footer.css">
</head>

<body>

    <!-- Header -->
    <?php include '../assets/includes/header.php'; ?>

    <main>
        <h2>My Orders</h2>

        <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>
                <?php
                $order_id = $order['Order_id'];

                $item_sql = "SELECT p.name, oi.quantity
                             FROM order_item oi
                             JOIN product p ON oi.Product_id = p.Product_id
                             WHERE oi.order_id = ?";

                $item_stmt = $conn->prepare($item_sql);
                $item_stmt->bindParam(1, $order_id, PDO::PARAM_INT);
                $item_stmt->execute();
                $items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <div class="order-card">
                    <div class="order-header">
                        <span>Order #<?= $order_id ?></span>
                        <span class="status <?= strtolower($order['order_status']) ?>">
                            <?= htmlspecialchars($order['order_status']) ?>
                        </span>
                    </div>

                    <div class="order-items">
                        <?php foreach ($items as $item): ?>
                            <p><?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?></p>
                        <?php endforeach; ?>
                    </div>

                    <div class="order-footer">
                        <span>Date: <?= date("d M Y, H:i", strtotime($order['Order_date'])) ?></span>
                        <span>Total: Rs <?= $order['Total_paid'] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">You have no orders yet.</p>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <?php include '../assets/includes/cart_footer.php'; ?>

</body>
</html>
