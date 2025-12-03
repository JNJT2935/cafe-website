document.addEventListener("DOMContentLoaded", () => {

    // PLUS
    document.querySelectorAll(".qty-plus").forEach(btn => {
        btn.addEventListener("click", () => {
            updateCart(btn.dataset.cartId, "plus");
        });
    });

    // MINUS
    document.querySelectorAll(".qty-minus").forEach(btn => {
        btn.addEventListener("click", () => {
            updateCart(btn.dataset.cartId, "minus");
        });
    });

    // DELETE
    document.querySelectorAll(".delete-item").forEach(btn => {
        btn.addEventListener("click", () => {
            updateCart(btn.dataset.cartId, "delete");
        });
    });
});

function updateCart(cartId, action) {
    fetch("../backend/database/update_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `cart_id=${cartId}&action=${action}`
    })
    .then(res => res.json())
    .then(data => {

        if (data.deleted) {
            document.querySelector(`[data-cart-id='${cartId}']`)
                .closest(".cart-item-card")
                .remove();
        } else {
            document.getElementById("qty-" + cartId).textContent = data.quantity;
        }

    });
    // reload
    setTimeout(() => {
        location.reload();
    }, 600)
}

