// checkout_page_integrated.js
document.addEventListener("DOMContentLoaded", () => {

  //Panel helpers (slide open/close)
  function openPanel(panel) {
    if (!panel) return;
    panel.classList.remove("hidden");
    panel.setAttribute("aria-hidden", "false");
    const scrollH = panel.scrollHeight;
    panel.style.maxHeight = scrollH + "px";
    panel.style.opacity = "1";
    Array.from(panel.querySelectorAll("input, textarea, select")).forEach(el => el.disabled = false);
  }

  function closePanel(panel) {
    if (!panel) return;
    panel.style.maxHeight = "0px";
    panel.style.opacity = "0";
    panel.setAttribute("aria-hidden", "true");

    const disableAfter = () => {
      Array.from(panel.querySelectorAll("input, textarea, select")).forEach(el => el.disabled = true);
      panel.classList.add("hidden");
      panel.removeEventListener("transitionend", disableAfter);
    };
    panel.addEventListener("transitionend", disableAfter);
  }


  //Elements

  const fulfillmentRadios = Array.from(document.querySelectorAll("input[name='fulfillment']"));
  const deliveryPanel = document.getElementById("delivery-panel");
  const pickupPanel = document.getElementById("pickup-panel");
  const addressInput = document.getElementById("address");

  const paymentRadios = Array.from(document.querySelectorAll("input[name='payment']"));
  const cardSection = document.getElementById("card-section");

  const payNowBox = document.getElementById("paynow_details");

  const deliveryFeeElement = document.getElementById("delivery-fee");
  const finalTotalElement = document.getElementById("final-total");

  const checkoutForm = document.getElementById("checkout-form");

  // Hidden input to send fee to server (created automatically if not present)
  let deliveryFeeInput = document.getElementById("delivery_fee_input");
  if (!deliveryFeeInput && checkoutForm) {
    deliveryFeeInput = document.createElement("input");
    deliveryFeeInput.type = "hidden";
    deliveryFeeInput.id = "delivery_fee_input";
    deliveryFeeInput.name = "delivery_fee";
    deliveryFeeInput.value = "0";
    checkoutForm.appendChild(deliveryFeeInput);
  }
  //constants
  const DELIVERY_FEE = 150;
  const baseTotal = (finalTotalElement && finalTotalElement.dataset && finalTotalElement.dataset.baseTotal)
  ? parseFloat(finalTotalElement.dataset.baseTotal) || 0
  : (finalTotalElement ? parseFloat(finalTotalElement.textContent.replace(/[^\d\.\-]/g,'')) || 0 : 0);
  
  /* ---------------------------
     Initialize panels (Delivery default unless 'pickup' checked)
     --------------------------- */
  function initPanels() {
    const checked = document.querySelector("input[name='fulfillment']:checked");
    if (checked && checked.value === "pickup") {
      // pickup shown, delivery hidden
      if (deliveryPanel) {
        deliveryPanel.classList.add("hidden");
        deliveryPanel.setAttribute("aria-hidden", "true");
        Array.from(deliveryPanel.querySelectorAll("input, textarea, select")).forEach(el => el.disabled = true);
        deliveryPanel.style.maxHeight = "0px";
        deliveryPanel.style.opacity = "0";
      }
      if (pickupPanel) {
        pickupPanel.classList.remove("hidden");
        pickupPanel.setAttribute("aria-hidden", "false");
        Array.from(pickupPanel.querySelectorAll("input, textarea, select")).forEach(el => el.disabled = false);
        pickupPanel.style.maxHeight = pickupPanel.scrollHeight + "px";
        pickupPanel.style.opacity = "1";
      }
      setDeliveryFee(0);
    } else {
      // delivery shown
      if (pickupPanel) {
        pickupPanel.classList.add("hidden");
        pickupPanel.setAttribute("aria-hidden", "true");
        Array.from(pickupPanel.querySelectorAll("input, textarea, select")).forEach(el => el.disabled = true);
        pickupPanel.style.maxHeight = "0px";
        pickupPanel.style.opacity = "0";
      }
      if (deliveryPanel) {
        deliveryPanel.classList.remove("hidden");
        deliveryPanel.setAttribute("aria-hidden", "false");
        Array.from(deliveryPanel.querySelectorAll("input, textarea, select")).forEach(el => el.disabled = false);
        deliveryPanel.style.maxHeight = deliveryPanel.scrollHeight + "px";
        deliveryPanel.style.opacity = "1";
      }
      setDeliveryFee(DELIVERY_FEE);
    }
  }

  initPanels();

  //Fulfillment change handler (delivery <-> pickup)
  fulfillmentRadios.forEach(radio => {
    radio.addEventListener("change", (e) => {
      if (e.target.value === "pickup") {
        // save address if present
        if (addressInput) addressInput.dataset.savedValue = addressInput.value || "";

        closePanel(deliveryPanel);
        openPanel(pickupPanel);
        setDeliveryFee(0);
      } else {
        // restore address value if saved
        if (addressInput && addressInput.dataset.savedValue !== undefined) {
          addressInput.value = addressInput.dataset.savedValue;
        }
        closePanel(pickupPanel);
        openPanel(deliveryPanel);
        setDeliveryFee(DELIVERY_FEE);
      }
    });
  });
  /* ---------------------------
     Delivery fee: update display + hidden input
     returns 0 or 150 for PHP
     --------------------------- */
  function setDeliveryFee(value) {
    if (deliveryFeeElement) deliveryFeeElement.textContent = value;
    if (finalTotalElement) {
      const newTotal = (isNaN(baseTotal) ? 0 : baseTotal) + (isNaN(parseFloat(value)) ? 0 : parseFloat(value));
      // show as integer if original is integer
      finalTotalElement.textContent = Number.isInteger(newTotal) ? newTotal : newTotal.toFixed(2);
    }
    if (deliveryFeeInput) deliveryFeeInput.value = value;
  }

  /* ---------------------------
  - opens card panel when value === 'card'
  - opens paynow panel when value === 'paynow'
  - closes both otherwise
  - uses aria-hidden + hidden class + maxHeight transition
  --------------------------- */
  function initPaymentHandlers() {
  // helper to open a generic panel (cardSection / payNowBox)
  function openPanelGeneric(panel) {
    if (!panel) return;
    panel.classList.remove("hidden");
    panel.setAttribute("aria-hidden", "false");
    // animate by setting maxHeight to scrollHeight
    panel.style.maxHeight = panel.scrollHeight + "px";
    panel.style.opacity = "1";
    Array.from(panel.querySelectorAll("input, textarea, select")).forEach(i => i.disabled = false);
  }

  // helper to close a generic panel
  function closePanelGeneric(panel) {
    if (!panel) return;
    panel.style.maxHeight = "0px";
    panel.style.opacity = "0";
    panel.setAttribute("aria-hidden", "true");
    const after = () => {
      Array.from(panel.querySelectorAll("input, textarea, select")).forEach(i => i.disabled = true);
      panel.classList.add("hidden");
      panel.removeEventListener("transitionend", after);
    };
    panel.addEventListener("transitionend", after);
  }

  // when a payment radio changes, decide which panels to open/close
  paymentRadios.forEach(radio => {
    radio.addEventListener("change", (e) => {
      const SelectedPaymentRadio = e.target.value;

      if (SelectedPaymentRadio === "card") {
        // show card, hide paynow
        openPanelGeneric(cardSection);
        closePanelGeneric(payNowBox);
      } else if (SelectedPaymentRadio === "paynow") {
        // show paynow, hide card
        openPanelGeneric(payNowBox);
        closePanelGeneric(cardSection);
      } else {
        // any other payment method (cash) -> hide both
        closePanelGeneric(cardSection);
        closePanelGeneric(payNowBox);
      }
    });
  });

  // INITIALIZE state on load based on currently checked payment radio
  const checkedPayment = document.querySelector("input[name='payment']:checked");
  if (checkedPayment) {
    if (checkedPayment.value === "card") {
      // show card, hide paynow
      if (cardSection) {
        cardSection.classList.remove("hidden");
        cardSection.setAttribute("aria-hidden", "false");
        cardSection.style.maxHeight = cardSection.scrollHeight + "px";
        cardSection.style.opacity = "1";
        Array.from(cardSection.querySelectorAll("input")).forEach(i => i.disabled = false);
      }
      if (payNowBox) closePanelGeneric(payNowBox);
    } else if (checkedPayment.value === "paynow") {
      // show paynow, hide card
      if (payNowBox) {
        payNowBox.classList.remove("hidden");
        payNowBox.setAttribute("aria-hidden", "false");
        payNowBox.style.maxHeight = payNowBox.scrollHeight + "px";
        payNowBox.style.opacity = "1";
        Array.from(payNowBox.querySelectorAll("input, textarea, select")).forEach(i => i.disabled = false);
      }
      if (cardSection) closePanelGeneric(cardSection);
    } else {
      // none selected or cash
      if (cardSection) closePanelGeneric(cardSection);
      if (payNowBox) closePanelGeneric(payNowBox);
    }
  } else {
    // fallback: hide both
    if (cardSection) closePanelGeneric(cardSection);
    if (payNowBox) closePanelGeneric(payNowBox);
  }
  }
  initPaymentHandlers();
  /* ---------------------------
     Date/time combine on submit (hidden input "order_datetime")
     --------------------------- */
  if (checkoutForm) {
    checkoutForm.addEventListener("submit", function (e) {
      // gather date & time fields (if present)
      const dateEl = document.getElementById("order-date");
      const timeEl = document.getElementById("order-time");

      if (dateEl && timeEl) {
        const date = dateEl.value;   // YYYY-MM-DD
        const time = timeEl.value;   // HH:MM

        if (!date || !time) {
          alert("Please select both date and time.");
          e.preventDefault();
          return;
        }

        const finalDateTime = `${date} ${time}:00`;

        // attach hidden input
        let hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "order_datetime";
        hidden.value = finalDateTime;
        this.appendChild(hidden);
      }

      // ensure delivery_fee is present (already created earlier)
      if (!deliveryFeeInput && this) {
        const fallback = document.createElement("input");
        fallback.type = "hidden";
        fallback.name = "delivery_fee";
        fallback.value = "0";
        this.appendChild(fallback);
      }
    });
  }

  /* ---------------------------
     Keep panel heights correct on resize
     --------------------------- */
  window.addEventListener("resize", () => {
    [deliveryPanel, pickupPanel, cardSection].forEach(panel => {
      if (panel && !panel.classList.contains("hidden")) {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
  });

});
