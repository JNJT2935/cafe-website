<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Manage Orders</title>

<link rel="stylesheet" href="../assets/css/header.css">

<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<link rel="stylesheet" href="../assets/css/manage_orders.css">

</head>

<body>

<!-- HEADER -->
<header class="main-header">
    <div class="header-left">
        <a href="../pages/home.php" class="logo">
        <img src="../assets/images/header_icon/coffee_logo.svg" alt="Coffee Shop Logo">
        <span>Coffee Shop</span>
        </a>
    </div>
</header>

<div class="page-wrapper">

    <!-- BACK -->
    <div class="back-link">
        <a href="../pages/admin.php" class="back-btn">← Back to Dashboard</a>
    </div>

    <h2>Manage Orders</h2>

    <div class="order-container">

        <!-- LEFT -->
        <div class="order-left">

            <div class="section-title">Order List</div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="orders-body"></tbody>
            </table>

        </div>

        <!-- RIGHT -->
        <div class="order-right">

            <div class="section-title">Delivery Details / Invoice</div>

            <div id="details-box">
                Click an order to view details
            </div>

        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/manage_orders.js"></script>

</body>
</html>
