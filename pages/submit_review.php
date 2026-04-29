<?php
session_start();
include('../assets/includes/connect.php');

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit();
}

$product_id = intval($_POST['product_id'] ?? 0);
$rating     = intval($_POST['rating'] ?? 0);
$comment    = htmlspecialchars(trim($_POST['comment'] ?? ''));

if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Please select a rating between 1 and 5 stars.']);
    exit();
}
if (empty($comment)) {
    echo json_encode(['success' => false, 'message' => 'Please write a review comment.']);
    exit();
}

// Check if user has ordered this product
$order_check = $conn->prepare("
    SELECT o.Order_id
    FROM order_item oi
    JOIN `order` o ON oi.Order_id = o.Order_id
    WHERE o.user_id = ? AND oi.Product_id = ?
    LIMIT 1
");
$order_check->execute([$user_id, $product_id]);
$order_row = $order_check->fetch(PDO::FETCH_ASSOC);

if (!$order_row) {
    echo json_encode(['success' => false, 'message' => 'You can only review products you have purchased.']);
    exit();
}

// FIX 4: Check uniqueness per USER + PRODUCT (not per order)
// This prevents reviewing the same product multiple times across different orders
$check = $conn->prepare("
    SELECT r.*
    FROM `review` r
    JOIN `order` o ON r.Order_id = o.Order_id
    WHERE o.user_id = ? AND r.Product_id = ?
");
$check->execute([$user_id, $product_id]);

if ($check->rowCount() > 0) {
    echo json_encode(['success' => false, 'message' => 'You have already reviewed this product.']);
    exit();
}

$order_id = $order_row['Order_id'];

$insert = $conn->prepare("
    INSERT INTO `review` (Product_id, Order_id, Rating, Comment)
    VALUES (?, ?, ?, ?)
");
$insert->execute([$product_id, $order_id, $rating, $comment]);

// Fetch names for instant DOM update
$user_stmt = $conn->prepare("SELECT name FROM `user` WHERE user_id = ?");
$user_stmt->execute([$user_id]);
$user_row = $user_stmt->fetch(PDO::FETCH_ASSOC);

$product_stmt = $conn->prepare("SELECT name FROM `product` WHERE Product_id = ?");
$product_stmt->execute([$product_id]);
$product_row = $product_stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'message' => 'Your review has been submitted. Thank you!',
    'review'  => [
        'user_name'    => $user_row['name'],
        'product_name' => $product_row['name'],
        'rating'       => $rating,
        'comment'      => $comment,
        'date'         => date('M d, Y'),
    ]
]);
exit();
?>