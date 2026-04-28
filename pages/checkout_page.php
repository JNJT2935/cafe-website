<?php
session_start();
include "../backend/database/db.php";

// ---------- Basic auth check ----------
$$user_id = $_SESSION['user_id'];

$not_logged_in = false;
// Check if user logged in
if (!isset($_SESSION['user_id'])) {
    $not_logged_in = true;
    header("Location: ../pages/home.php");
    exit();
    }

// Check if cart is empty before giving access
$is_empty = $conn->prepare("SELECT COUNT(*) AS count FROM cart WHERE user_id = ?");
$is_empty->bind_param("i", $user_id);
$is_empty->execute();
$result = $is_empty->get_result();
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    $_SESSION['cart_warning'] = "Your cart is empty. Add items before checking out.";
    header("Location: cart_page.php");
    exit;
}
//fetch items cart
$sql = "SELECT 
            c.user_id,
            c.quantity,
            p.product_id,
            p.name AS product_name,
            p.price,
            u.name AS user_name,
            u.phone_number
        FROM cart c
        INNER JOIN product p ON c.product_id = p.product_id
        INNER JOIN user u ON c.user_id = u.user_id
        WHERE c.user_id = $user_id";

$result = $conn->query($sql);

$cart_items = [];
$cart_total = 0;
$user_name = "";
$user_phone_number = "";

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        // Compute item total
        $row['item_total'] = $row['price'] * $row['quantity'];
        // Add to cart array
        $cart_items[] = $row;
        // Add to full cart total
        $cart_total += $row['item_total'];
        //name
        $user_name = $row['user_name'];
        //phone number
        $user_phone_number = $row['phone_number'];
    }
}

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
    <title>Checkout</title>

    <!-- Global + Checkout CSS -->
    <link rel="stylesheet" href="..\assets\css\checkout\checkout_page.css">
    <link rel="stylesheet" href="..\assets\css\header.css">
    <link rel="stylesheet" href="..\assets\css\cart\cart_footer.css">
</head>

<body>

    <!-- HEADER -->
    <?php include '..\assets\includes\header.php'; ?>

    <main class="page-wrapper">

        <?php
        if (isset($_SESSION['order_error'])) {
            echo '<div style="
                background:#ffdddd;
                padding:15px;
                margin:20px 0;
                border-left:5px solid #d8000c;
                color:#a70000;
                font-size:16px;
                border-radius:5px;
            ">' . htmlspecialchars($_SESSION['order_error']) . '</div>';

            // clear it so it does not persist
            unset($_SESSION['order_error']);
        }
        ?>


        <div class="back-link">
            <a href="../pages/cart_page.php">← Back to Cart</a>
        </div>

        <form class="checkout-container" id="checkout-form" action="..\backend\checkout\process_order.php" method="POST">
            <!-- LEFT: Order Summary -->
            <section class="checkout-left">

                <h2>Delivery Method</h2>

                <div class="radio-row">
                    <label><input type="radio" name="fulfillment" value="delivery" id="delivery" checked> Delivery</label>
                    <label><input type="radio" name="fulfillment" value="pickup" id="pickup"> Pickup</label>
                </div>

                <!-- DELIVERY FIELDS (visible by default) -->
                <div class="slide-panel" id="delivery-panel" aria-hidden="false">
                    <div class="user-info">
                        <div class="info-block">
                            <H3>Full Name</H3>
                            <p><?php echo $user_name; ?></p>
                        </div>
                        <div class="info-block">
                            <H3>Phone Number</H3>
                            <p><?php echo $user_phone_number; ?></p>
                        </div>
                    </div>

                    <div class="input-field">
                        <label for="address">Delivery Address</label>
                        <input id="address" name="address" type="text" placeholder="Street, building, area" required>
                    </div>

                </div>
                
                <!-- DELIVARY DATE FIELDS (visible by default) -->
                <div class="datetime-section">
                    <div class="datetime-field">
                        <label for="order-date">Select Date</label>
                        <input type="date" id="order-date" name="order_date" required>
                    </div>

                    <div class="datetime-field">
                        <label for="order-time">Select Time</label>
                        <input type="time" id="order-time" name="order_time" required>
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
                        <!-- Keep phone & name for pickup as well -->
                        <div class="user-info">
                            <div class="info-block">
                                <H3>Full Name</H3>
                                <p><?php echo $user_name; ?></p>
                            </div>
                            <div class="info-block">
                                <H3>Phone Number</H3>
                                <p><?php echo $user_phone_number; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h2>Payment Method</h2>

                <div class="payment-options">
                    <label><input type="radio" name="payment" value="cash" checked> Cash on Delivery</label>
                    <label><input type="radio" name="payment" value="card"> Debit / Credit Card</label>
                    <label><input type="radio" name="payment" value="scan"> Scan to pay (Juice/My.t Money/Paypal)</label>
                </div>
                <!-- card details expandable (hidden by default) -->
                <div id="card-section" class="card-section hidden" aria-hidden="true">
                    <div class="input-field">
                    <label for="card-number">Card Number</label>
                    <input id="card-number" name="card_number" type="text" inputmode="numeric" maxlength="19" placeholder="1234 5678 9012 3456">
                    </div>

                    <div class="two-col">
                    <div class="input-field">
                        <label for="expiry">Expiry (MM/YY)</label>
                        <input id="expiry" name="card_expiry" type="text" maxlength="4" placeholder="MM/YY">
                    </div>
                    <div class="input-field">
                        <label for="cvv">CVV</label>
                        <input id="cvv" name="card_cvv" type="password" maxlength="4" placeholder="123">
                    </div>
                    </div>
                </div>

                <!-- Hidden expandable Pay Now section -->
                <div id="paynow_details" class="paynow-box hidden" aria-hidden="true">
                    <p><strong>Scan the QR Code to Pay:</strong></p>

                    <div class="qr-container" id="pay_code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=FakePayment12345"
                            alt="Payment QR Code">
                    </div>

                    <p style="font-size: 14px; color: #555;">
                        (This QR is auto-generated and for assignment/demo purposes only.)
                    </p>
                </div>


            </section>

            <!-- RIGHT: Delivery & Payment -->
             <section class="checkout-right">
                <h2>Order Summary</h2>

                <?php foreach ($cart_items as $item): ?>
                    <div class="order-item">
                            <p><?php echo $item['product_name']; ?> × <?php echo $item['quantity']; ?></p>
                            <span>Rs <?php echo $item['item_total']; ?></span>
                    </div>
                <?php endforeach; ?>

                <div class="order-item">
                    <p>Delivery Fee </p>
                    <p>Rs <span id="delivery-fee"></span></p>
                </div>

                <div class="total-box">
                    <strong>TOTAL :</strong>
                    <h3 id="final-total" data-base-total="<?php echo $cart_total; ?>"><?php echo $cart_total; ?></h3>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/js/checkout_page.js"></script>

</body>
</html>
