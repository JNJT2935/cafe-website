$(document).ready(function(){
    loadOrders();
});

let currentOrder = null;

/* =========================
   LOAD ORDERS
========================= */
function loadOrders(){

    $.get("../backend/manage_orders/get_orders.php", function(data){

        let html = "";

        data.forEach(order => {

            html += `
            <tr data-id="${order.order_id}">

                <td>${order.order_id}</td>
                <td>${order.customer_name}</td>
                <td>${formatDate(order.order_date)}</td>
                <td>Rs ${parseFloat(order.total).toFixed(0)}</td>

                <td>
                    <span class="status ${order.status.toLowerCase()}">
                        ${order.status}
                    </span>
                </td>

                <td>
                    <select class="status-dropdown">
                        <option value="Pending" ${order.status=="Pending"?"selected":""}>Pending</option>
                        <option value="Delivered" ${order.status=="Delivered"?"selected":""}>Delivered</option>
                        <option value="Cancelled" ${order.status=="Cancelled"?"selected":""}>Cancelled</option>
                    </select>

                    <button class="save-btn" data-id="${order.order_id}">
                        Save
                    </button>
                </td>

            </tr>`;
        });

        $("#orders-body").html(html);

    }, "json");
}

/* =========================
   CLICK ANY ROW → VIEW INVOICE
========================= */
$(document).on("click", "tr", function(e){

    if($(e.target).is("button") || $(e.target).is("select")){
        return;
    }

    let id = $(this).data("id");
    if(!id) return;

    $.get("../backend/manage_orders/get_order.php", { order_id: id }, function(data){

        currentOrder = data;

        $("#details-box").html(`
            <div id="invoice">

                <h3>🧾 Invoice #${data.order_id}</h3>
                <hr>

                <p><strong>Name:</strong> ${data.customer_name}</p>
                <p><strong>Email:</strong> ${data.email}</p>
                <p><strong>Phone:</strong> ${data.phone}</p>
                <p><strong>Address:</strong> ${data.address}</p>

                <hr>

                <p><strong>Status:</strong> 
                    <span id="invoice-status">${data.status}</span>
                </p>

                <hr>

                <p><strong>Items:</strong></p>
                <ul>
                    ${(data.items || "")
                        .split(",")
                        .map(i => `<li>${i.trim()}</li>`)
                        .join("")
                    }
                </ul>

                <hr>

                <p><strong>Total:</strong> Rs ${parseFloat(data.total).toFixed(0)}</p>
                <p><strong>Date:</strong> ${formatDate(data.order_date)}</p>

                <br>

                <button onclick="downloadPDF()" class="save-btn">
                    📥 Download PDF
                </button>

            </div>
        `);

    }, "json");
});

/* =========================
   SAVE STATUS UPDATE
========================= */
$(document).on("click", ".save-btn", function(e){

    e.stopPropagation(); // IMPORTANT

    let row = $(this).closest("tr");

    let id = $(this).data("id");
    let status = row.find(".status-dropdown").val();

    $.post("../backend/manage_orders/update_order.php", {
        id: id,
        status: status
    }, function(){

        loadOrders(); // refresh table

        // update invoice live if open
        $("#invoice-status").text(status);

        alert("Status updated!");

    }, "json");
});

/* =========================
   PDF DOWNLOAD (A4)
========================= */
function downloadPDF(){

    if(!currentOrder){
        alert("Select an order first!");
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF("p", "mm", "a4");

    let y = 20;

    doc.setFontSize(18);
    doc.text("COFFEE SHOP INVOICE", 55, y);

    y += 10;

    doc.setFontSize(11);
    doc.text("Invoice ID: " + currentOrder.order_id, 10, y); y += 7;
    doc.text("Date: " + formatDate(currentOrder.order_date), 10, y); y += 7;
    doc.text("Status: " + currentOrder.status, 10, y); y += 10;

    doc.text("Customer Details:", 10, y);
    y += 7;

    doc.text("Name: " + currentOrder.customer_name, 10, y); y += 6;
    doc.text("Email: " + currentOrder.email, 10, y); y += 6;
    doc.text("Phone: " + currentOrder.phone, 10, y); y += 6;
    doc.text("Address: " + currentOrder.address, 10, y); y += 10;

    doc.text("Items:", 10, y);
    y += 7;

    (currentOrder.items || "").split(",").forEach(item => {
        doc.text("- " + item.trim(), 15, y);
        y += 6;
    });

    y += 5;

    doc.setFontSize(13);
    doc.text("Total: Rs " + parseFloat(currentOrder.total).toFixed(0), 10, y);

    doc.save("invoice_" + currentOrder.order_id + ".pdf");
}

/* =========================
   FORMAT DATE
========================= */
function formatDate(d){
    let date = new Date(d);
    return date.toLocaleDateString("en-GB");
}
