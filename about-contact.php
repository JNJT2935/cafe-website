<?php

//Err Variables
$fnameErr = $lnameErr = $emailErr = $messageErr = "";

//Form Variables
$fname = $lname = $email = $message = "";

//Success Message
$successMsg = "";

//Sanitize Input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//Form Validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //First Name
    if (empty($_POST["fname"])) {
        $fnameErr = "* Field is required";
    } else {
        $fname = test_input($_POST["fname"]);

        if (!preg_match("/^[A-Z][a-zA-Z]+$/", $fname)) {
            $fnameErr = "* Must start with uppercase and contain only letters";
        }

        if (strlen($fname) < 3) {
            $fnameErr = "* Must be at least 3 characters";
        }
    }

    //Last Name
    if (empty($_POST["lname"])) {
        $lnameErr = "* Field is required";
    } else {
        $lname =test_input($_POST["lname"]);

        if (!preg_match("/^[A-Z][a-zA-Z]+$/", $lname)) {
            $lnameErr = "* Must start with uppercase and contain only letters";
        }

        if (strlen($lname) < 3) {
            $lnameErr = "* Must be at least 3 characters";
        }
    }

    //Email
    if (empty($_POST["email"])) {
        $emailErr = "* Field is required";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Enter a valid email";
    } else {
        $email =test_input($_POST["email"]);
    }

    //Message
    if (empty($_POST["message"])) {
        $messageErr = "* Field is required";
    } else {
        $message =test_input($_POST["message"]);
        $message = filter_var($message, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    //Check for input errors
    if ($fnameErr == "" && $lnameErr == "" && $emailErr == "" && $messageErr == "") {

        // Clear form values after submission
        $fname = $lname = $email = $message = "";
    }

}

?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="stylesheet" href="assets/css/contact.css">
        <title>About Us</title>
    </head>
    <body>
        <!-- Header -->
        <?php
        include 'assets/includes/header.php';
        ?>

        <main>
            <!-- Contact / About Section -->
            <section class="contact-page">
                <div class="contact-wrapper">
                    <!-- LEFT SIDE - ABOUT US -->
                    <div class="contact-left">
                        <h2>About Us</h2>

                        <p>Welcome to our Online Coffee Shop — your favourite beverage after long day of work. Our mission is to make quality coffee accessible to everyone, anytime, anywhere. From rich coffee beans to carefully crafted blends, we bring the café experience straight to your home.</p>
                        <p>We believe coffee is more than just a drink — it's a way to connect people. That's why our platform is designed to be simple, secure, and customer-friendly, allowing you to explore, order, and enjoy your favorite coffee with ease.</p>
                        <p>Whether you are a casual coffee lover or a passionate enthusiast, our goal is to serve you with fresh products, reliable service, and a smooth online experience.</p>

                    </div>
                
                    <!-- RIGHT SIDE - CONTACT FORM -->
                    <div class="contact-right">
                        <div class="form-header">
                            <h2>Contact Us</h2>
                        </div>
                        <form class="contact-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fname">First Name <span class="error"><?php echo $fnameErr ?></span></label>
                                    <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($fname ?? ''); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="lname">Last Name <span class="error"><?php echo $lnameErr ?></span></label>
                                    <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($lname ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address <span class="error"><?php echo $emailErr ?></span></label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="message">Your Message <span class="error"><?php echo $messageErr ?></span></label>
                                <textarea id="message" name="message" rows="4"><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                            </div>

                            <button type="submit" class="btn-primary">Send Message</button>
                        </form>
                    </div>

                </div>

            </section>
        
        </main>

        <!-- Footer -->
        <?php
        include 'assets/includes/footer.php';
        ?>
        
    </body>
</html>