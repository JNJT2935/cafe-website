$(document).ready(function () {
  // Panel helpers
  function openPanel(panel) {
    if (!panel) return;
    $(panel)
      .removeClass("hidden")
      .attr("aria-hidden", "false")
      .css({ maxHeight: panel.scrollHeight + "px", opacity: "1" })
      .find("input, textarea, select")
      .prop("disabled", false);
  }

  function closePanel(panel) {
    if (!panel) return;
    $(panel)
      .css({ maxHeight: "0px", opacity: "0" })
      .attr("aria-hidden", "true");
    $(panel).one("transitionend", function () {
      $(panel).find("input, textarea, select").prop("disabled", true);
      $(panel).addClass("hidden");
    });
  }

  // Elements
  const $fulfillmentRadios = $("input[name='fulfillment']");
  const $deliveryPanel = $("#delivery-panel");
  const $pickupPanel = $("#pickup-panel");
  const $addressInput = $("#address");

  const $paymentRadios = $("input[name='payment']");
  const $cardSection = $("#card-section");
  const $payNowBox = $("#paynow_details");

  const $deliveryFeeElement = $("#delivery-fee");
  const $finalTotalElement = $("#final-total");
  const $checkoutForm = $("#checkout-form");

  // Hidden input
  let $deliveryFeeInput = $("#delivery_fee_input");
  if ($deliveryFeeInput.length === 0 && $checkoutForm.length) {
    $deliveryFeeInput = $("<input>", {
      type: "hidden",
      id: "delivery_fee_input",
      name: "delivery_fee",
      value: "0",
    }).appendTo($checkoutForm);
  }

  const DELIVERY_FEE = 150;
  const baseTotal = $finalTotalElement.data("baseTotal")
    ? parseFloat($finalTotalElement.data("baseTotal")) || 0
    : parseFloat($finalTotalElement.text().replace(/[^\d\.\-]/g, "")) || 0;

  // Initialize panels
  function initPanels() {
    const checked = $("input[name='fulfillment']:checked").val();
    if (checked === "pickup") {
      closePanel($deliveryPanel[0]);
      openPanel($pickupPanel[0]);
      setDeliveryFee(0);
    } else {
      closePanel($pickupPanel[0]);
      openPanel($deliveryPanel[0]);
      setDeliveryFee(DELIVERY_FEE);
    }
  }
  initPanels();

  // Fulfillment change
  $fulfillmentRadios.on("change", function () {
    if (this.value === "pickup") {
      if ($addressInput.length)
        $addressInput.data("savedValue", $addressInput.val() || "");
      closePanel($deliveryPanel[0]);
      openPanel($pickupPanel[0]);
      setDeliveryFee(0);
    } else {
      if (
        $addressInput.length &&
        $addressInput.data("savedValue") !== undefined
      ) {
        $addressInput.val($addressInput.data("savedValue"));
      }
      closePanel($pickupPanel[0]);
      openPanel($deliveryPanel[0]);
      setDeliveryFee(DELIVERY_FEE);
    }
  });

  // Delivery fee
  function setDeliveryFee(value) {
    $deliveryFeeElement.text(value);
    const newTotal = baseTotal + parseFloat(value) || 0;
    $finalTotalElement.text(
      Number.isInteger(newTotal) ? newTotal : newTotal.toFixed(2),
    );
    $deliveryFeeInput.val(value);
  }

  // Payment handlers
  function initPaymentHandlers() {
    function openPanelGeneric(panel) {
      if (!panel) return;
      $(panel)
        .removeClass("hidden")
        .attr("aria-hidden", "false")
        .css({ maxHeight: panel.scrollHeight + "px", opacity: "1" })
        .find("input, textarea, select")
        .prop("disabled", false);
    }
    function closePanelGeneric(panel) {
      if (!panel) return;
      $(panel)
        .css({ maxHeight: "0px", opacity: "0" })
        .attr("aria-hidden", "true")
        .one("transitionend", function () {
          $(panel).find("input, textarea, select").prop("disabled", true);
          $(panel).addClass("hidden");
        });
    }

    $paymentRadios.on("change", function () {
      const v = this.value;
      if (v === "card") {
        openPanelGeneric($cardSection[0]);
        closePanelGeneric($payNowBox[0]);
      } else if (v === "scan") {
        openPanelGeneric($payNowBox[0]);
        closePanelGeneric($cardSection[0]);
      } else {
        closePanelGeneric($cardSection[0]);
        closePanelGeneric($payNowBox[0]);
      }
    });

    const checkedPayment = $(
      "input[name='payment']:checked, input[name='payment_method']:checked",
    ).val();
    if (checkedPayment === "card") {
      openPanelGeneric($cardSection[0]);
      closePanelGeneric($payNowBox[0]);
    } else if (checkedPayment === "paynow") {
      openPanelGeneric($payNowBox[0]);
      closePanelGeneric($cardSection[0]);
    } else {
      closePanelGeneric($cardSection[0]);
      closePanelGeneric($payNowBox[0]);
    }
  }
  initPaymentHandlers();

  // Form submit
  $checkoutForm.on("submit", function (e) {
    const date = $("#order-date").val();
    const time = $("#order-time").val();
    if (date && time) {
      $("<input>", {
        type: "hidden",
        name: "order_datetime",
        value: `${date} ${time}:00`,
      }).appendTo(this);
    } else if ($("#order-date").length && $("#order-time").length) {
      alert("Please select both date and time.");
      e.preventDefault();
      return;
    }
    if ($deliveryFeeInput.length === 0) {
      $("<input>", {
        type: "hidden",
        name: "delivery_fee",
        value: "0",
      }).appendTo(this);
    }
  });

  // Resize handler
  $(window).on("resize", function () {
    [$deliveryPanel[0], $pickupPanel[0], $cardSection[0]].forEach((panel) => {
      if (panel && !$(panel).hasClass("hidden")) {
        $(panel).css("maxHeight", panel.scrollHeight + "px");
      }
    });
  });
});
