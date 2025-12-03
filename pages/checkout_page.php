<?php
session_start();
include "../backend/database/db.php";

$user_id = 1;

// Check if cart is empty
$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $_SESSION['cart_warning'] = "Your cart is empty. Add items before checking out.";
    header("Location: cart_page.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- Global + Checkout CSS -->
    <link rel="stylesheet" href="../assets/css/checkout_page.css">
    <link rel="stylesheet" href="..\assets\css\header.css">
    <link rel="stylesheet" href="..\assets\css\cart_footer.css">
</head>

<body>

    <!-- HEADER -->
    <?php include '..\assets\includes\header.php'; ?>

    <main class="page-wrapper">

        <div class="back-link">
            <a href="../pages/cart_page.php">← Back to Cart</a>
        </div>

        <form class="checkout-container" id="checkout-form" action="process_order.php" method="POST" novalidate>

            <!-- LEFT: Order Summary -->
            <section class="checkout-left">

                <h2>Delivery Method</h2>

                <div class="radio-row">
                    <label><input type="radio" name="fulfillment" value="delivery" checked> Delivery</label>
                    <label><input type="radio" name="fulfillment" value="pickup"> Pickup</label>
                </div>

                <!-- DELIVERY FIELDS (visible by default) -->
                <div class="slide-panel" id="delivery-panel" aria-hidden="false">
                    <div class="input-field">
                        <label for="customer-name">Full Name</label>
                        <input id="customer-name" name="customer_name" type="text" required>
                    </div>

                    <div class="input-field">
                        <label for="phone">Phone Number</label>
                        <input id="phone" name="phone" type="tel" required >
                    </div>

                    <div class="input-field">
                        <label for="address">Delivery Address</label>
                        <input id="address" name="address" type="text" placeholder="Street, building, area" required>
                    </div>

                </div>

                <!-- PICKUP FIELDS (hidden by default) -->
                <div class="slide-panel hidden" id="pickup-panel" aria-hidden="true">
                    <div class="input-field">
                        <label for="pickup-branch">Choose pickup branch</label>
                        <select id="pickup-branch" name="pickup_branch">
                            <option value="">-- Select branch --</option>
                            <option value="Port Louis">Port Louis</option>
                            <option value="Curepipe">Curepipe</option>
                            <option value="Rose Hill">Rose Hill</option>
                            <option value="Grand Baie">Grand Baie</option>
                            <option value="Quatre Bornes">Quatre Bornes</option>
                        </select>
                    </div>

                    <!-- Keep phone & name for pickup as well -->
                    

                    <div class="input-field">
                        <label for="pickup-name">Full Name</label>
                        <input id="pickup-name" name="pickup_name" type="text" >
                    </div>
                </div>
                <hr>
                <h2>Payment Method</h2>

                <div class="payment-options">
                    <label><input type="radio" name="payment" value="cash" checked> Cash on Delivery</label>
                    <label><input type="radio" name="payment" value="card"> Debit / Credit Card</label>
                    <label><input type="radio" name="payment" value="paynow"> Pay Now</label>
                </div>

                <div id="card-section" class="card-section hidden" aria-hidden="true">
                    <div class="input-field">
                    <label for="card-number">Card Number</label>
                    <input id="card-number" name="card_number" type="text" inputmode="numeric" maxlength="19" placeholder="1234 5678 9012 3456">
                    </div>

                    <div class="two-col">
                    <div class="input-field">
                        <label for="expiry">Expiry (MM/YY)</label>
                        <input id="expiry" name="card_expiry" type="text" maxlength="5" placeholder="MM/YY">
                    </div>
                    <div class="input-field">
                        <label for="cvv">CVV</label>
                        <input id="cvv" name="card_cvv" type="password" maxlength="4" placeholder="123">
                    </div>
                    </div>
                </div>

            </section>

            <!-- RIGHT: Delivery & Payment -->
             <section class="checkout-right">
                <h2>Order Summary</h2>

                <div class="order-items" id="order-items">
                    <!-- Replace these with PHP loop printing $_SESSION['cart'] -->
                    <div class="order-item">
                    <p>Chocolate Cake × 1</p>
                    <span>Rs 150</span>
                    </div>
                    <div class="order-item">
                    <p>Strawberry Smoothie × 2</p>
                    <span>Rs 200</span>
                    </div>
                </div>

                <div class="promo-field">
                    <input type="text" name="promo" placeholder="Promo code" />
                    <button type="button" id="apply-promo">Apply</button>
                </div>

                <div class="total-box">
                    <p>Subtotal</p>
                    <h3 id="total-amount">Rs 350</h3>
                </div>

                <button type="submit" class="checkout-btn">Place Order</button>

                <div class="small-link">
                    <a href="../pages/cart_page.php" class="cancel-link">Cancel / Back to cart</a>
                </div>
            </section>
            
        </form>
    </main>

    <!-- FOOTER -->
    <?php include '../assets/includes/cart_footer.php'; ?>

    <!--js-->
    <script src="../assets/js/checkout_page.js"></script>

</body>
</html>
