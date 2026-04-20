$(document).ready(function () {
  // PLUS
  $(".qty-plus").on("click", function () {
    updateCart($(this).data("cartId"), "plus");
  });

  // MINUS
  $(".qty-minus").on("click", function () {
    updateCart($(this).data("cartId"), "minus");
  });

  // DELETE
  $(".delete-item").on("click", function () {
    updateCart($(this).data("cartId"), "delete");
  });
});

function updateCart(cartId, action) {
  $.ajax({
    url: "../backend/checkout/update_cart.php",
    type: "POST",
    data: { cart_id: cartId, action: action },
    dataType: "json",
    success: function (data) {
      if (data.deleted) {
        $("[data-cart-id='" + cartId + "']")
          .closest(".cart-item-card")
          .remove();
      } else {
        $("#qty-" + cartId).text(data.quantity);
      }
    },
  });

  // reload
  setTimeout(function () {
    location.reload();
  }, 600);
}
