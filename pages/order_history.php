<?php
session_start();
include "../backend/database/db.php";

// ---------- Basic auth check ----------
$user_id = $_SESSION['user_id'];

$not_logged_in = false;
// Check if user logged in
if (!isset($_SESSION['user_id'])) {
    $not_logged_in = true;
    header("Location: ../pages/home.php");
    exit();
}

//$user_id = $_SESSION['user_id'];

// Fetch orders
$sql = "SELECT o.Order_id, o.Total_paid, o.order_status, o.Order_date
        FROM `order` o
        WHERE o.user_id = ?
        ORDER BY o.Order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>Coffee Shop | Cart</title>

    <!-- CSS link placeholder -->
    <link rel="stylesheet" href="..\assets\css\header.css">
    <link rel="stylesheet" href="..\assets\css\order_history\order_history.css">
    <link rel="stylesheet" href="..\assets\css\cart\cart_footer.css">
</head>

<body>

    <!-- Header -->
    <?php include '..\assets\includes\header.php'; ?>

    <main>
        <h2>My Orders</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($order = $result->fetch_assoc()): ?>
                <?php
                // Fetch items for each order
                $order_id = $order['Order_id'];

                $item_sql = "SELECT p.name, oi.quantity
                             FROM order_item oi
                             JOIN product p ON oi.Product_id = p.Product_id
                             WHERE oi.order_id = ?";

                $item_stmt = $conn->prepare($item_sql);
                $item_stmt->bind_param("i", $order_id);
                $item_stmt->execute();
                $items = $item_stmt->get_result();
                ?>

                <div class="order-card">
                    <div class="order-header">
                        <span>Order #<?= $order_id ?></span>
                        <span class="status <?= strtolower($order['order_status']) ?>">
                            <?= $order['order_status'] ?>
                        </span>
                    </div>

                    <div class="order-items">
                        <?php while ($item = $items->fetch_assoc()): ?>
                            <p><?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?></p>
                        <?php endwhile; ?>
                    </div>

                    <div class="order-footer">
                        <span>Date: <?= date("d M Y, H:i", strtotime($order['Order_date'])) ?></span>
                        <span>Total: Rs <?= $order['Total_paid'] ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="empty">You have no orders yet.</p>
        <?php endif; ?>
    </main>

    <!-- Footer -->
        <?php include '../assets/includes/cart_footer.php'; ?>

</body>

</html>