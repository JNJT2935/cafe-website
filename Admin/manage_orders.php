<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Manage Orders</title>

<link rel="stylesheet" href="../assets/css/header.css">

<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>

/* ===== BODY ===== */
html, body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #BA9D8A;
}

/* ===== HEADER ===== */
.main-header {
    background: #3e2e1f;
    padding: 10px 20px;
    display: flex;
    align-items: center;
}

.header-left .logo {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: white;
    font-weight: bold;
}

.header-left img {
    height: 40px;
}

/* ===== BACK BUTTON ===== */
.back-link {
    margin: 15px 0;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #3e2e1f;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: 0.2s;
}

.back-btn:hover {
    transform: translateX(-3px);
    background: #2b1f15;
}

/* ===== PAGE WRAPPER ===== */
.page-wrapper {
    max-width: 1200px;
    margin: auto;
    padding: 30px 10px;
}

h2 {
    text-align: center;
    color: #3e2e1f;
}

/* ===== LAYOUT ===== */
.order-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

/* LEFT */
.order-left {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    overflow-x: auto;
}

/* RIGHT */
.order-right {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    position: sticky;
    top: 20px;
}

/* SECTION TITLE */
.section-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
}

/* ===== TABLE ===== */
table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #222;
    color: white;
    padding: 12px;
}

td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

tr:hover {
    background: #f7f7f7;
}

/* ===== STATUS ===== */
.status {
    padding: 5px 10px;
    border-radius: 6px;
    color: white;
    font-size: 12px;
}

.pending { background: orange; }
.delivered { background: green; }
.cancelled { background: red; }

/* ===== BUTTONS ===== */
.view-btn {
    background: #007bff;
    color: white;
    border: none;
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
}

.save-btn {
    background: #28a745;
    color: white;
    border: none;
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
}

/* ===== INVOICE ===== */
#invoice {
    font-size: 14px;
    line-height: 1.6;
}

#invoice ul {
    padding-left: 18px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .order-container {
        grid-template-columns: 1fr;
    }
}

</style>
</head>

<body>

<!-- HEADER -->
<header class="main-header">
    <div class="header-left">
        <a href="../pages/Home_Page.php" class="logo">
        <img src="../assets/images/header_icon/coffee_logo.svg" alt="Coffee Shop Logo">
        <span>Coffee Shop</span>
        </a>
    </div>
</header>

<div class="page-wrapper">

    <!-- BACK -->
    <div class="back-link">
        <a href="../pages/Home_Page.php" class="back-btn">← Back to Dashboard</a>
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
<script src="../assets/js/orders.js"></script>

</body>
</html>
