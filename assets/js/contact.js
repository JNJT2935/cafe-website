$(document).ready(function () {
    function showFormMessage(type, message) {
        $("#form-message")
            .removeClass("success-message error-message")
            .addClass(type === "success" ? "success-message" : "error-message")
            .show();

        $("#form-message-text").text(message);
    }

    function hideFormMessage() {
        $("#form-message").fadeOut(function () {
            $(this).removeClass("success-message error-message");
            $("#form-message-text").text("");
        });
    }

    function clearFieldErrors() {
        $("#fname_error").text("");
        $("#lname_error").text("");
        $("#email_error").text("");
        $("#message_error").text("");
    }

    $("#close-message").on("click", function () {
        hideFormMessage();
        $("#contactForm")[0].reset();
        clearFieldErrors();
    });

    $("#resetFormBtn").on("click", function () {
        clearFieldErrors();
        hideFormMessage();
    });

    $("#contactForm").on("submit", function (e) {
        e.preventDefault();

        clearFieldErrors();
        hideFormMessage();

        const formData = {
            action: "submit_contact",
            csrf_token: $('input[name="csrf_token"]').val(),
            fname: $("#fname").val(),
            lname: $("#lname").val(),
            email: $("#email").val(),
            message: $("#message").val()
        };

        $.ajax({
            url: "contacts.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(formData),
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    $("#contactForm")[0].reset();
                    showFormMessage("success", response.message);
                } else {
                    showFormMessage("error", response.message);

                    if (response.errors) {
                        $("#fname_error").text(response.errors.fname || "");
                        $("#lname_error").text(response.errors.lname || "");
                        $("#email_error").text(response.errors.email || "");
                        $("#message_error").text(response.errors.message || "");
                    }
                }
            },
            error: function () {
                showFormMessage("error", "AJAX request failed. Please try again.");
            }
        });
    });
});