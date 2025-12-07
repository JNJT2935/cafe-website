<?php
session_start();
include "../database/db.php";

// Must receive order_id
if (!isset($_GET['order_id'])) {
    die("No order ID provided.");
}

$order_id = (int)$_GET['order_id'];

// ====== Fetch Order + Payment + Delivery + User Info ======
$sql = "SELECT o.order_id, o.total_paid, o.order_date, o.order_status,
           u.name AS username, u.phone_number,
           d.delivery_address, d.delivery_method, d.delivery_date,
           p.payment_method, p.Transaction_id
        FROM `order` o
        JOIN user u ON o.user_id = u.user_id
        JOIN delivery d ON o.delivery_id = d.delivery_id
        JOIN payment p ON o.payment_id = p.payment_id
        WHERE o.order_id = ? ";
$order_info = $conn->prepare($sql);
$order_info->bind_param("i", $order_id);
$order_info->execute();
$order = $order_info->get_result()->fetch_assoc();
$order_info->close();

if (!$order) {
    die("Order not found.");
}

// ====== Fetch Ordered Items ======
$sql="SELECT oi.product_id, oi.quantity, oi.price,
           p.name AS product_name
        FROM order_item oi
        JOIN product p ON oi.product_id = p.product_id
        WHERE oi.order_id = ?";
$order_item = $conn->prepare($sql);
$order_item->bind_param("i", $order_id);
$order_item->execute();
$items_result = $order_item->get_result();
$items = $items_result->fetch_all(MYSQLI_ASSOC);
$order_item->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ===== META TAGS ===== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="referrer" content="no-referrer">
    <meta name="author" content="Noah Trousquin">
    <meta name="description" content="checkout page">
    <title>Order Confirmation</title>

    <link rel="stylesheet" href="..\..\assets\css\checkout\confirmation_page.css">
</head>

<body>


<main>
    <div class="confirmation-container">
        <h2>Order Confirmed!</h2>

        <div class="info-block">
            <p><strong>Order ID:</strong> <?= $order['order_id'] ?></p>
            <p><strong>Date:</strong> <?= $order['order_date'] ?></p>
            <p><strong>Status:</strong> <?= ucfirst($order['order_status']) ?></p>
        </div>

        <div class="info-block">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($order['username']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone_number']) ?></p>
        </div>

        <div class="info-block">
            <h3>Delivery Information</h3>
            <p><strong>Method:</strong> <?= ucfirst($order['delivery_method']) ?></p>

            <?php if ($order['delivery_method'] === "delivery"): ?>
                <p><strong>Address:</strong> <?= htmlspecialchars($order['delivery_address']) ?></p>
            <?php else: ?>
                <p><strong>Pickup Branch:</strong> <?= htmlspecialchars($order['delivery_address']) ?></p>
            <?php endif; ?>

            <p><strong>Date & Time:</strong> <?= $order['delivery_date'] ?></p>
        </div>

        <div class="info-block">
            <h3>Payment Information</h3>
            <p><strong>Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
            <p><strong>Transaction ID:</strong> <?= $order['Transaction_id'] ?></p>
        </div>

        <div class="info-block">
            <h3>Items Purchased</h3>

            <div class="items-list">
                <?php foreach ($items as $item): ?>
                    <div class="item-row">
                        <span><?= htmlspecialchars($item['product_name']) ?> × <?= $item['quantity'] ?></span>
                        <span>Rs <?= number_format($item['price'], 2) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="total-line">
                Total Paid: Rs <?= number_format($order['total_paid'], 2) ?>
            </div>
        </div>

        <a href="../../pages/cart_pages" class="back-link">Back to Shop</a>
    </div>
</main>

</body>
</html>
