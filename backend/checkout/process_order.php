<?php
session_start();
include "../database/db.php";

// ---------- Basic auth check ----------

$user_id = $_SESSION['user_id'];

$not_logged_in = false;
// Check if user logged in
if (!isset($_SESSION['user_id'])) {
    $not_logged_in = true;
    header("Location: ../pages/home.php");
    exit();
    }

// ---------- Helper: send user back with error ----------
function fail($msg, $redirect = "../../pages/checkout_page.php") {
    $_SESSION['order_error'] = $msg;
    header("Location: $redirect");
    exit;
}

// ---------- Read & sanitize POST ----------
$fulfillment     = isset($_POST['fulfillment']) ? trim($_POST['fulfillment']) : '';
$payment_method  = isset($_POST['payment']) ? trim($_POST['payment']) : '';
$delivery_fee_in = isset($_POST['delivery_fee']) ? (int) $_POST['delivery_fee'] : 0;
$order_datetime  = isset($_POST['order_datetime']) ? trim($_POST['order_datetime']) : ''; // expected "YYYY-MM-DD HH:MM:SS"
$pickup_branch   = isset($_POST['pickup_branch']) ? trim($_POST['pickup_branch']) : '';
$address         = isset($_POST['address']) ? trim($_POST['address']) : '';

// Card placeholders
$card_number = isset($_POST['card_number']) ? preg_replace('/\s+/', '', trim($_POST['card_number'])) : '';
$card_expiry = isset($_POST['card_expiry']) ? trim($_POST['card_expiry']) : '';
$card_cvv    = isset($_POST['card_cvv']) ? trim($_POST['card_cvv']) : '';

// ---------- Validate basic required fields ----------
$allowed_fulfill = ['pickup','delivery'];
if (!in_array($fulfillment, $allowed_fulfill, true)) {
    fail("Invalid delivery method.");
}

// Payment method: accept common names (cash, card, scan)
$allowed_payment = ['cash','card','scan'];
if (!in_array($payment_method, $allowed_payment, true)) {
    fail("Invalid payment method.");
}

// Validate datetime (must be present)
if (empty($order_datetime)) {
    fail("Delivery date and time required.");
}
$dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $order_datetime);
if (!$dateObj) {
    // try alternate common format if needed (e.g. 'Y-m-d\TH:i' from datetime-local)
    $alt = DateTime::createFromFormat('Y-m-d\TH:i', $order_datetime);
    if ($alt) {
        $order_datetime = $alt->format('Y-m-d H:i:00');
        $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $order_datetime);
    } else {
        fail("Invalid date/time format. Expected YYYY-MM-DD HH:MM:SS.");
    }
}

// Validate delivery-specific fields
if ($fulfillment === 'delivery') {
    if (empty($address)) {
        fail("Delivery address is required for delivery.");
    }
} else { // pickup
    if (empty($pickup_branch)) {
        fail("Please select a pickup branch.");
    }
    // For pickup, we store the branch name as delivery_address
    $address = $pickup_branch;
}

// Validate server-side delivery_fee: only allow 0 or 150 (defensive)
$delivery_fee = ($fulfillment === 'delivery') ? 150 : 0;
if (!in_array($delivery_fee_in, [0,150], true)) {
    // ignore client-provided fee and use server rule
    $delivery_fee_in = $delivery_fee;
}
// final fee used (server truth)
$delivery_fee = $delivery_fee_in;

// If payment is card, do validation of card fields
if ($payment_method === 'card') {
    if (empty($card_number) || empty($card_expiry) || empty($card_cvv)) {
        fail("Card details are required for card payments.");
    }
    // basic Luhn-ish length checks
    if (!preg_match('/^\d{12,19}$/', $card_number)) {
        fail("Invalid card number format.");
    }
    if (!preg_match('/^\d{2}\/?\d{2}$/', $card_expiry) && !preg_match('/^\d{2}\-\d{2}$/', $card_expiry) && !preg_match('/^\d{4}$/', $card_expiry)) {
        // allow MM/YY or MM-YY or YYYY (best-effort check)
        // not strict - only basic sanity
    }
    if (!preg_match('/^\d{3,4}$/', $card_cvv)) {
        fail("Invalid CVV.");
    }
}

// ---------- Fetch cart items from DB (and check stock) ----------
$sql = " SELECT
            c.product_id, 
            c.quantity, 
            p.price, 
            p.name AS name, 
            p.stock_quantity
            FROM cart c
            JOIN product p ON c.product_id = p.product_id
            WHERE c.user_id = ? ";

$item_cart = $conn->prepare($sql);

if (!$item_cart) fail("Database error (fetch cart).");

$item_cart->bind_param("i", $user_id);
$item_cart->execute();
$res = $item_cart->get_result();

$cart_items = [];
while ($row = $res->fetch_assoc()) {
    $cart_items[] = $row;
}
$item_cart->close();

if (count($cart_items) === 0) {
    fail("Your cart is empty.");
}

// Calculate total from DB  
$total_paid = 0.0;
foreach ($cart_items as $item) {
    $line_total = ((float)$item['price']) * ((int)$item['quantity']);
    $total_paid += $line_total;
}
// add server-calculated delivery fee
$total_paid += $delivery_fee;

// ---------- Begin DB Transaction ----------
$conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

try {
    // 1) Insert delivery
    $insert_Delivery = $conn->prepare("INSERT INTO delivery 
                                    (delivery_date, delivery_address, delivery_method) VALUES (?, ?, ?)");
    if (!$insert_Delivery) throw new Exception("DB prepare failed (delivery table).");
    $d_date = $order_datetime;
    $d_addr = $address;
    $d_method = $fulfillment;
    $insert_Delivery->bind_param ("sss",$d_date, $d_addr, $d_method);
    if (!$insert_Delivery->execute()) throw new Exception("Failed to insert into delivery table.");
    $delivery_id = $insert_Delivery->insert_id;
    $insert_Delivery->close();

    // 2) Insert payment (mock transaction id)
    $insert_payment = $conn->prepare("INSERT INTO payment 
                                    (payment_method, Transaction_id) VALUES (?, ?)");
    if (!$insert_payment) throw new Exception("DB prepare failed (payment).");
    // mock transaction id
    $transaction_id = bin2hex(random_bytes(8));
    $p_method = $payment_method;
    $insert_payment->bind_param("ss",$p_method, $transaction_id);
    if (!$insert_payment->execute()) throw new Exception("Failed to insert payment.");
    $payment_id = $insert_payment->insert_id;
    $insert_payment->close();

    // 3) Insert order record
    $order_status = "pending";
    $insert_order = $conn->prepare("INSERT INTO `order` 
                                            (user_id, payment_id, delivery_id, order_status, total_paid) VALUES (?, ?, ?, ?, ?)");
    if (!$insert_order) throw new Exception("DB prepare failed (order).");
    $insert_order->bind_param("iiisd",$user_id, $payment_id, $delivery_id, $order_status, $total_paid);
    if (!$insert_order->execute()) throw new Exception("Failed to insert order.");
    $order_id = $insert_order->insert_id;
    $insert_order->close();

    // 4) Insert order_items and update product stock
    $insert_item_order = $conn->prepare("INSERT INTO order_item 
                                                (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    if (!$insert_item_order) throw new Exception("DB prepare failed (order_item).");

    $update_stock = $conn->prepare("UPDATE product
                                    SET stock_quantity = stock_quantity - ? 
                                    WHERE product_id = ? AND stock_quantity >= ?");
    if (!$update_stock) throw new Exception("DB prepare failed (update stock).");

    foreach ($cart_items as $item) {
        $product_id = (int)$item['product_id'];
        $qty = (int)$item['quantity'];
        $unit_price = (float)$item['price'];

        // check stock available
        if ((int)$item['stock_quantity'] < $qty) {
            throw new Exception("Product '{$item['name']}' does not have enough stock.");
        }

        // insert order item (price as unit price * quantity)
        $line_price = $unit_price * $qty;
        $insert_item_order->bind_param("iiid",$order_id, $product_id, $qty, $line_price);
        if (!$insert_item_order->execute()) throw new Exception("Failed to insert order item for product $product_id.");

        // update stock
        $update_stock->bind_param("iii",$qty, $product_id, $qty);
        $update_stock->execute();
        if ($update_stock->affected_rows === 0) {
            throw new Exception("Failed to update stock for product {$product_id} (maybe insufficient stock).");
        }
    }

    $insert_item_order->close();
    $update_stock->close();

    // 5) Clear cart for user
    $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    if (!$clear_cart) throw new Exception("DB prepare failed (clear cart).");
    $clear_cart->bind_param("i", $user_id);
    if (!$clear_cart->execute()) throw new Exception("Failed to clear cart.");
    $clear_cart->close();

    // COMMIT transaction
    $conn->commit();

    // Redirect to confirmation passing order id
    header("Location: confirmation_page.php?order_id=" . urlencode($order_id));
    exit;

} catch (Exception $ex) {
    // ROLLBACK on error and surface message
    $conn->rollback();
    // store a friendly error
    $_SESSION['order_error'] = "Could not process order: " . $ex->getMessage();
    header("Location: ../../pages/checkout_page.php");
    exit;
}
