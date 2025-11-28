<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ===== META TAGS ===== -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Social Media Preview (Optional but professional) -->
    <meta property="og:title" content="Coffee Shop Cart">
    <meta property="og:description" content="Review your items before checkout.">
    <meta property="og:image" content="/assets/images/preview.jpg">
    <meta property="og:type" content="website">

    <!-- Title -->
    <title>Coffee Shop | Cart</title>

    <!-- CSS FILES -->
    <link rel="stylesheet" href="..\assets\css\header.css">
    <link rel="stylesheet" href="..\assets\css\cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <!-- Header -->
    <?php include '..\assets\includes\header.php'; ?>

    <div class="cart-background">
        <div class="cart-container">
            <!-- LEFT SIDE — ITEMS -->
            <div class="cart-left">
                <a href="menu.php" class="back-to-shop">← Back to shopping  </a>
                <div class="cart-items">
                    <!-- ITEM CARD (Repeat using PHP later) -->
                    <div class="cart-item-card">
                        <img src="assets/images/sample-item.jpg" alt="Item">

                        <div class="cart-item-info">
                            <h3>Product Name</h3>
                            <p>Short description goes here</p>
                        </div>
                        <div class="quantity-control">
                            <button>-</button>
                            <span>1</span>
                            <button>+</button>
                        </div>
                        <div class="item-price">
                            <strong>Rs 120</strong>
                        </div>
                        <button class="remove-item-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="cart-item-card">
                        <img src="assets/images/sample-item.jpg" alt="Item">

                        <div class="cart-item-info">
                            <h3>Product Name</h3>
                            <p>Short description goes here</p>
                        </div>
                        <div class="quantity-control">
                            <button>-</button>
                            <span>1</span>
                            <button>+</button>
                        </div>
                        <div class="item-price">
                            <strong>Rs 80</strong>
                        </div>
                        <button class="remove-item-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    
                </div>
            </div>       
            <!-- RIGHT SIDE — SUMMARY -->
            <div class="cart-right">
                
                <!-- SUMMARY CARD -->
                <div class="summary-card">
                    <h3 class="summary-title">Order Summary</h3>

                    <div class="summary-row">
                        <span>Number of Items</span>
                        <span>3</span>
                    </div>

                    <hr class="summary-divider">

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rs 200</span>
                    </div>

                    <hr class="summary-divider">

                    <div class="summary-row">
                        <span>Discount</span>
                        <span>Rs 0</span>
                    </div>

                    <hr class="summary-divider">

                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span>Rs 200</span>
                    </div>

                    <button class="checkout-btn" onclick="window.location.href='checkout.php'">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- JS -->
    <script src="/js/cart.js"></script>
</body>
</html>
