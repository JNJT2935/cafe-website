<?php
include('../Database/database.php');
session_start();

// --- UPDATE ORDER STATUS ---
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['Order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['order_status']);
    $admin_id = 1; // Set logged-in admin ID here

    // Update order status
    $query = "UPDATE `order` SET order_status=? WHERE Order_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $order_id);
    mysqli_stmt_execute($stmt);

    // Log admin action in manage_order table
    $logQuery = "INSERT INTO manage_order (user_id, Order_id, modification_status) VALUES (?, ?, ?)";
    $log = mysqli_prepare($conn, $logQuery);
    mysqli_stmt_bind_param($log, "iis", $admin_id, $order_id, $new_status);
    mysqli_stmt_execute($log);

    header("Location: admin_manage_orders.php");
    exit;
}

// --- FETCH ALL ORDERS ---
$orderQuery = "
    SELECT o.Order_id, o.user_id, o.Order_date, o.order_status, o.Total_paid,
           u.name, u.email,
           d.delivery_date, d.delivery_address, d.delivery_method
    FROM `order` o
    JOIN user u ON o.user_id = u.user_id
    JOIN delivery d ON o.Delivery_id = d.Delivery_id
    ORDER BY o.Order_id DESC
";

$orders = mysqli_query($conn, $orderQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Orders</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; padding: 10px; }
        th { background: #eee; }
        .btn { padding: 6px 12px; cursor: pointer; border: none; }
        .pending { color: orange; }
        .delivered { color: green; }
        .cancelled { color: red; }
    </style>
</head>

<body>

<h1>Order Management</h1>

<table>
    <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Delivery Details</th>
        <th>Status</th>
        <th>Total Paid</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($orders)) { ?>
        <tr>
            <td><b>#<?= $row['Order_id'] ?></b></td>

            <td>
                <?= $row['name'] ?><br>
                <small><?= $row['email'] ?></small>
            </td>

            <!-- ONLY VIEW DELIVERY DETAILS -->
            <td>
                <b>Date:</b> <?= $row['delivery_date'] ?><br>
                <b>Address:</b> <?= $row['delivery_address'] ?><br>
                <b>Method:</b> <?= $row['delivery_method'] ?>
            </td>

            <td class="<?= strtolower($row['order_status']) ?>">
                <b><?= $row['order_status'] ?></b>
            </td>

            <td>Rs <?= $row['Total_paid'] ?></td>

            <td>

                <!-- VIEW PRODUCTS -->
                <form method="POST" action="view_order_items.php" style="margin-bottom:5px;">
                    <input type="hidden" name="Order_id" value="<?= $row['Order_id'] ?>">
                    <button class="btn" style="background:#d4e3fa;">View Items</button>
                </form>

                <!-- UPDATE ORDER STATUS ONLY -->
                <form method="POST" action="">
                    <input type="hidden" name="Order_id" value="<?= $row['Order_id'] ?>">

                    <select name="order_status" required>
                        <option value="Pending" <?= $row['order_status']=="Pending"?"selected":"" ?>>Pending</option>
                        <option value="Delivered" <?= $row['order_status']=="Delivered"?"selected":"" ?>>Delivered</option>
                        <option value="Cancelled" <?= $row['order_status']=="Cancelled"?"selected":"" ?>>Cancelled</option>
                    </select>

                    <button type="submit" name="update_status" class="btn" style="background:#ffe3a3;">
                        Update
                    </button>
                </form>

            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
