<?php
session_start();
include "../backend/database/db.php";

// ---------- Basic auth check ----------
$user_id = ($_SESSION['user_id']);

$not_logged_in = false;
$cart_items = [];
$out_of_stock = [];
$cart_total = 0;
$total_quantity = 0;
$item_count = 0;
$total_quantity = 0;

if (!isset($_SESSION['user_id'])) {
    $not_logged_in = true;
} else {
    // 2. Only get the ID and run SQL if they ARE logged in
    $user_id = $_SESSION['user_id'];
    // Fetch cart items
    $sql = "SELECT 
                c.cart_id,
                c.quantity,
                p.product_id,
                p.name,
                p.description,
                p.price,
                p.image_source,
                p.stock_quantity
            FROM cart c INNER JOIN product p
            ON c.product_id = p.product_id
            WHERE c.user_id = $user_id";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            // Compute item total
            $row['item_total'] = $row['price'] * $row['quantity'];
            // Add to cart array
            $cart_items[] = $row;
            // Add to full cart total
            $cart_total += $row['item_total'];
        }

        //computer number of individual item
        $item_count = count($cart_items);

        // compute the total number if item
        foreach ($cart_items as $item) {
            $total_quantity += $item['quantity'];
        }
        //checking if quantity_cart > quantity_stock 
        foreach ($cart_items as $item) {
        if ($item['quantity'] > $item['stock_quantity']) {
            $out_of_stock[] = $item;
        }
    }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ===== META TAGS ===== -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="referrer" content="no-referrer">
    <meta name="author" content="Noah Trousquin">
    <meta name="description" content="checkout page">

    <!-- Title -->
    <title>Coffee Shop | Cart</title>
 
    <!-- CSS FILES -->
    <link rel="stylesheet" href="..\assets\css\header.css">
    <link rel="stylesheet" href="..\assets\css\cart\cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="..\assets\css\cart\cart_footer.css">
</head>

<!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="..\assets\js\cart.js"></script>

<body>

    <!-- Header -->
    <?php include '..\assets\includes\header.php'; ?>

    <!-- login message -->
    
    <?php if ($not_logged_in): ?>
        <div class="login-overlay">
            <div class="login-modal">
                <h2>Login Required</h2>
                <p>You must log in to view your cart and continue shopping.</p>

                <a href="login.php" class="login-btn">Login</a>
                <a href="register.php" class="register-btn">Create an Account</a>
            </div>
        </div>
    <?php endif; ?>
    <!-- out of stock message -->
    <?php if (!empty($out_of_stock)): ?>
    <div class="cart-quantity-error">
        <strong>Some items exceed available stock:</strong>
        <ul>
            <?php foreach ($out_of_stock as $item): ?>
                <li>
                    <?= htmlspecialchars($item['name']) ?>:
                    requested <?= $item['quantity'] ?>,
                    available <?= $item['stock_quantity'] ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <p>Please reduce the quantity or remove the items before checking out.</p>
    </div>
    <?php endif; ?>


    <!-- main cart contain -->
    <main class="cart-background">
        <div class="cart-container">
            <!-- LEFT SIDE — ITEMS -->
            <section class="cart-left">
                <a href="home.php" class="back-to-shop">← Back to shopping  </a>

                <div class="cart-items">
                    <?php if (empty($cart_items)): ?>
                        <p>Your cart is empty.</p>
                    <?php else: ?>
                        <p>You have <?php echo $item_count; ?> items in your cart</p>

                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item-card" >
                                <img src="../assets/images/productimages/<?php echo $item['image_source']; ?>" alt="item image">

                                    <div class="cart-item-info">
                                        <h3><?php echo $item['name']; ?></h3>
                                        <p><?php echo $item['description']; ?></p>
                                    </div>

                                    <div class="quantity-control">
                                        <button class="qty-minus" data-cart-id="<?php echo $item['cart_id']; ?>" >-</button>
                                        <span class="qty-value" data-cart-id="qty-<?php echo $item['cart_id']; ?>">
                                            <?php echo $item['quantity']; ?>
                                        </span>
                                        <button class="qty-plus" data-cart-id="<?php echo $item['cart_id']; ?>">+</button>
                                    </div>

                                    <div class="item-price"  data-cart-id="<?php echo $item['cart_id']; ?>" >
                                        <strong>Rs <?php echo number_format($item['item_total'], 2); ?></strong>
                                    </div>

                                    <button class="delete-item" data-cart-id="<?php echo $item['cart_id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>       
            <!-- RIGHT SIDE — SUMMARY -->
            <section class="cart-right">
                
                <!-- SUMMARY CARD -->
                <div class="summary-card">
                    <h3 class="summary-title">Order Summary</h3>

                    <div class="summary-row">
                        <span>Number of Individual items</span>
                        <span><?php echo $item_count; ?></span>
                    </div>

                    <hr class="summary-divider">

                    <div class="summary-row">
                        <span>Total Number of items</span>
                        <span><?php echo $total_quantity; ?></span>
                    </div>

                    <hr class="summary-divider">
                    
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span> Rs <?php echo number_format($cart_total, 2); ?></span>
                    </div>

                    <?php if (empty($out_of_stock)): ?>
                        <button class="checkout-btn" onclick="window.location.href='checkout_page.php'">Proceed to Checkout</button>
                    <?php else: ?>
                        <button class="checkout-btn disabled" disabled>Fix Stock Issues First</button>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>
    <!--empty cart message-->
    <?php if (isset($_SESSION['cart_warning'])): ?>
        <div class="toast-warning">
            <?php 
                echo $_SESSION['cart_warning']; 
                unset($_SESSION['cart_warning']);
            ?>
        </div>
    <?php endif; ?>


    <!-- Footer -->
    <?php include '../assets/includes/cart_footer.php'; ?>

    
</body>
</html>
