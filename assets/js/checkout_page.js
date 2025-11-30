document.addEventListener("DOMContentLoaded", () => {
  // Elements
  const fulfillmentRadios = document.querySelectorAll("input[name='fulfillment']");
  const deliveryPanel = document.getElementById("delivery-panel");
  const pickupPanel = document.getElementById("pickup-panel");
  const notesField = document.getElementById("notes-field");

  const addressInput = document.getElementById("address");
  const pickupSelect = document.getElementById("pickup-branch");

  // Payment elements
  const paymentRadios = document.querySelectorAll("input[name='payment']");
  const cardSection = document.getElementById("card-section");

  // Helper: slide open element
  function openPanel(panel) {
    panel.classList.remove("hidden");
    panel.setAttribute("aria-hidden", "false");
    // let it measure scrollHeight and set maxHeight for smooth animation
    const scrollH = panel.scrollHeight;
    panel.style.maxHeight = scrollH + "px";
    panel.style.opacity = "1";
    // enable inputs inside
    Array.from(panel.querySelectorAll("input, textarea, select")).forEach(el => {
      el.disabled = false;
    });
  }

  // Helper: slide close element
  function closePanel(panel) {
    panel.style.maxHeight = "0px";
    panel.style.opacity = "0";
    panel.setAttribute("aria-hidden", "true");
    // disable inputs after transition end to prevent submission
    const disableAfter = () => {
      Array.from(panel.querySelectorAll("input, textarea, select")).forEach(el => {
        el.disabled = true;
      });
      panel.classList.add("hidden");
      panel.removeEventListener("transitionend", disableAfter);
    };
    panel.addEventListener("transitionend", disableAfter);
  }

  // Initialize: ensure panels are in correct initial state
  function initPanels() {
    if (document.querySelector("input[name='fulfillment']:checked").value === "pickup") {
      // show pickup, hide delivery
      deliveryPanel.classList.add("hidden");
      deliveryPanel.setAttribute("aria-hidden", "true");
      Array.from(deliveryPanel.querySelectorAll("input, textarea, select")).forEach(el => el.disabled = true);
      pickupPanel.classList.remove("hidden");
      pickupPanel.setAttribute("aria-hidden", "false");
      Array.from(pickupPanel.querySelectorAll("input, textarea, select")).forEach(el => el.disabled = false);
      pickupPanel.style.maxHeight = pickupPanel.scrollHeight + "px";
      pickupPanel.style.opacity = "1";
      deliveryPanel.style.maxHeight = "0px";
      deliveryPanel.style.opacity = "0";
      // notes hidden for pickup
      if (notesField) notesField.style.display = "none";
    } else {
      // delivery default
      pickupPanel.classList.add("hidden");
      pickupPanel.setAttribute("aria-hidden", "true");
      Array.from(pickupPanel.querySelectorAll("input, textarea, select")).forEach(el => el.disabled = true);
      deliveryPanel.classList.remove("hidden");
      deliveryPanel.setAttribute("aria-hidden", "false");
      Array.from(deliveryPanel.querySelectorAll("input, textarea, select")).forEach(el => el.disabled = false);
      deliveryPanel.style.maxHeight = deliveryPanel.scrollHeight + "px";
      deliveryPanel.style.opacity = "1";
      pickupPanel.style.maxHeight = "0px";
      pickupPanel.style.opacity = "0";
      if (notesField) notesField.style.display = "block";
    }
  }

  initPanels();

  // Listen to fulfillment changes (delivery / pickup)
  fulfillmentRadios.forEach(radio => {
    radio.addEventListener("change", (e) => {
      if (e.target.value === "pickup") {
        // when switching to pickup: keep address value saved in data attr, then close delivery and open pickup
        if (addressInput) {
          addressInput.dataset.savedValue = addressInput.value || "";
        }
        closePanel(deliveryPanel);
        openPanel(pickupPanel);
        if (notesField) notesField.style.display = "none";
      } else {
        // switching to delivery: restore saved address if any
        if (addressInput && addressInput.dataset.savedValue !== undefined) {
          addressInput.value = addressInput.dataset.savedValue;
        }
        closePanel(pickupPanel);
        openPanel(deliveryPanel);
        if (notesField) notesField.style.display = "block";
      }
    });
  });

  // Payment method toggling (card)
  function initPayment() {
    paymentRadios.forEach(radio => {
      radio.addEventListener("change", (e) => {
        if (e.target.value === "card") {
          // show
          cardSection.classList.remove("hidden");
          cardSection.setAttribute("aria-hidden", "false");
          cardSection.style.maxHeight = cardSection.scrollHeight + "px";
          cardSection.style.opacity = "1";
          Array.from(cardSection.querySelectorAll("input")).forEach(i => i.disabled = false);
        } else {
          // hide
          cardSection.style.maxHeight = "0px";
          cardSection.style.opacity = "0";
          cardSection.setAttribute("aria-hidden", "true");
          const after = () => {
            Array.from(cardSection.querySelectorAll("input")).forEach(i => i.disabled = true);
            cardSection.classList.add("hidden");
            cardSection.removeEventListener("transitionend", after);
          };
          cardSection.addEventListener("transitionend", after);
        }
      });
    });

    // initialize card section disabled state
    if (!document.querySelector("input[name='payment']:checked") || 
        document.querySelector("input[name='payment']:checked").value !== "card") {
      Array.from(cardSection.querySelectorAll("input")).forEach(i => i.disabled = true);
      cardSection.classList.add("hidden");
      cardSection.style.maxHeight = "0px";
      cardSection.style.opacity = "0";
    } else {
      cardSection.classList.remove("hidden");
      cardSection.style.maxHeight = cardSection.scrollHeight + "px";
      cardSection.style.opacity = "1";
      Array.from(cardSection.querySelectorAll("input")).forEach(i => i.disabled = false);
    }
  }

  initPayment();

  // Accessibility: ensure panels have correct maxHeight after window resize (to keep animation consistent)
  window.addEventListener("resize", () => {
    [deliveryPanel, pickupPanel, cardSection].forEach(panel => {
      if (!panel.classList.contains("hidden")) {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
  });

});
