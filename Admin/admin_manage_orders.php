<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Manage Orders</title>

<style>
/* ===== RESET & BODY ===== */
html, body { margin:0; padding:0; font-family: Arial, sans-serif; background-color: #BA9D8A; }
* { box-sizing: border-box; }

/* ===== PAGE LAYOUT ===== */
.page-wrapper { padding: 40px 10px; max-width: 1200px; margin:auto; }
.back-link a { color:black; font-size:22px; text-decoration:underline; }
.order-container { display: grid; grid-template-columns:1fr 1fr; gap:25px; margin-top: 20px; }
.order-left, .order-right { background:#fff; padding:22px; border-radius:12px; box-shadow:0 6px 18px rgba(12,12,12,0.06); }
h2 { text-align:center; margin-bottom: 10px; color:#3e2e1f; }

/* ===== TABLE ===== */
.order-table table { width:100%; border-collapse: collapse; }
.order-table th, .order-table td { padding:10px; border-bottom:1px solid #eee2d4; text-align:center; }
.order-table tr:hover { background-color:#fcf4eb; cursor:pointer; }

/* ===== STATUS COLORS ===== */
.status { padding:6px 10px; border-radius:8px; font-size:13px; color:white; }
.pending { background:#c27e00; }
.delivered { background:#137d00; }
.cancelled { background:#c20000; }

.status-select { padding:6px; border-radius:6px; border:1px solid #d5c4b6; }
.status-form { display:flex; gap:6px; justify-content:center; align-items:center; }
.save-btn { padding:6px 12px; border:none; background:#e6a823; border-radius:6px; cursor:pointer; color:white; font-weight:600; }
.save-btn:hover { background:#a86008fd; }

/* ===== DELIVERY DETAILS ===== */
#details-box { margin-top:18px; font-size:15px; line-height:1.7; }

/* ===== SUCCESS MESSAGE ===== */
.success-box { max-width: 1200px; margin: 10px auto; background: #d6ffd6; padding: 12px; color: #066f06; border: 1px solid #83c583; border-radius: 6px; text-align: center; font-weight: bold; }

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) { .order-container { grid-template-columns:1fr; } }
</style>

</head>

<body>

<div class="page-wrapper">

    <div class="back-link">
        <a href="#">← Back to Dashboard</a>
    </div>

    <div class="order-container">

        <!-- LEFT: ORDER TABLE -->
        <div class="order-left">
            <h2>Order List</h2>
            <div class="order-table">
                <table>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                    <!-- SAMPLE DATA -->
                    <tr onclick="showDetails(1)">
                        <td>1</td>
                        <td>John Doe</td>
                        <td>Rs 505</td>
                        <td>01-12-2025</td>
                        <td><span class="status pending">Pending</span></td>
                        <td>
                            <form class="status-form" onsubmit="event.preventDefault(); saveStatus(this)">
                                <select name="status" class="status-select">
                                    <option value="Pending" selected>Pending</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                                <button class="save-btn">Save</button>
                            </form>
                        </td>
                    </tr>

                    <tr onclick="showDetails(2)">
                        <td>2</td>
                        <td>Emily</td>
                        <td>Rs 780</td>
                        <td>02-12-2025</td>
                        <td><span class="status delivered">Delivered</span></td>
                        <td>
                            <form class="status-form" onsubmit="event.preventDefault(); saveStatus(this)">
                                <select name="status" class="status-select">
                                    <option value="Pending">Pending</option>
                                    <option value="Delivered" selected>Delivered</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                                <button class="save-btn">Save</button>
                            </form>
                        </td>
                    </tr>

                </table>
            </div>
        </div>

        <!-- RIGHT: DELIVERY DETAILS -->
        <div class="order-right">
            <h2>Delivery Details</h2>
            <div id="details-box">
                <p>Select an order to view details.</p>
            </div>
        </div>

    </div>

</div>

<script>
// ------------- MOCK ORDER DETAILS WITH ALL FIELDS ----------------
const ordersDetails = {
    1: { 
        name: "John Doe", 
        phone: "5778-1122", 
        email: "john@example.com",
        address: "Rose Hill, Mauritius",
        items: "Cappuccino x2, Coffee Latte x1", 
        total: "Rs 505",
        order_date: "01-12-2025",
        delivery_note: "Leave at door",
        status: "Pending" 
    },
    2: { 
        name: "Emily",
        phone: "5789-2233", 
        email: "emily@example.com",
        address: "Curepipe",
        items: "Mocha x3, Croissant x2", 
        total: "Rs 780",
        order_date: "02-12-2025",
        delivery_note: "Call on arrival",
        status: "Delivered" 
    }
};

// SHOW DETAILS ON CLICK
function showDetails(id) {
    const d = ordersDetails[id];
    document.getElementById("details-box").innerHTML = `
        <p><strong>Order ID:</strong> ${id}</p>
        <p><strong>Name:</strong> ${d.name}</p>
        <p><strong>Email:</strong> ${d.email}</p>
        <p><strong>Phone:</strong> ${d.phone}</p>
        <p><strong>Address:</strong> ${d.address}</p>
        <p><strong>Items Ordered:</strong> ${d.items}</p>
        <p><strong>Total Amount:</strong> ${d.total}</p>
        <p><strong>Order Date:</strong> ${d.order_date}</p>
        <p><strong>Delivery Note:</strong> ${d.delivery_note}</p>
        <p><strong>Status:</strong> ${d.status}</p>
    `;
}

// SAVE STATUS FUNCTION
function saveStatus(form) {
    const select = form.querySelector('select');
    const row = form.closest('tr');
    const statusSpan = row.querySelector('.status');

    // Update table
    statusSpan.textContent = select.value;
    statusSpan.className = "status " + select.value.toLowerCase();

    // Get ID
    const orderId = row.querySelector('td').textContent;

    // Update stored data
    if (ordersDetails[orderId]) {
        ordersDetails[orderId].status = select.value;

        // Update details if visible
        const detailsBox = document.getElementById("details-box");
        if (detailsBox.innerHTML.includes(ordersDetails[orderId].name)) {
            showDetails(orderId);
        }
    }
}
</script>

</body>
</html>


