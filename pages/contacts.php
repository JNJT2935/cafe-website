<?php
include('../assets/includes/connect.php');
session_start();

// Create CSRF token for form submission
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
 
// Err Variables
$fnameErr = $lnameErr = $emailErr = $messageErr = "";

// Field Variables
$fname = $lname = $email = $message = "";

// Basic input cleaning
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

// Handle AJAX form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');
    
    // Read JSON sent by AJAX
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);
    
    // Check JSON structure
    if (!is_array($data)) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid JSON request.",
            "errors" => []
        ]);
        exit();
    }
    
    // Check request action
    if (!isset($data['action']) || $data['action'] !== 'submit_contact') {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid action.",
            "errors" => []
        ]);
        exit();
    }
    
    // Check CSRF token
    if (!isset($data['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $data['csrf_token'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid CSRF token.",
            "errors" => []
        ]);
        exit();
    }
    
    // Validate First Name
    if (empty($data["fname"])) {
        $fnameErr = "* Field is required";
    } else {
        $fname = test_input($data["fname"]);

        if (mb_strlen($fname) < 3 || mb_strlen($fname) > 50) {
            $fnameErr = "* Must be between 3 and 50 characters";
        } elseif (!preg_match("/^[A-Za-z]+(?:[ '’-][A-Za-z]+)*$/u", $fname)) {
            $fnameErr = "* Only letters, spaces, apostrophes and hyphens are allowed";
        }
    }
    
    // Validate Last Name
    if (empty($data["lname"])) {
        $lnameErr = "* Field is required";
    } else {
        $lname = test_input($data["lname"]);

        if (mb_strlen($lname) < 3 || mb_strlen($lname) > 50) {
            $lnameErr = "* Must be between 3 and 50 characters";
        } elseif (!preg_match("/^[A-Za-z]+(?:[ '’-][A-Za-z]+)*$/u", $lname)) {
            $lnameErr = "* Only letters, spaces, apostrophes and hyphens are allowed";
        }
    }
    
    // Validate Email
    if (empty($data["email"])) {
        $emailErr = "* Field is required";
    } else {
        $email = test_input($data["email"]);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "* Enter a valid email address";
        } elseif (mb_strlen($email) > 150) {
            $emailErr = "* Email address is too long";
        }
    }
    
    // Validate Message
    if (empty($data["message"])) {
        $messageErr = "* Field is required";
    } else {
        $message = test_input($data["message"]);

        if (mb_strlen($message) < 10) {
            $messageErr = "* Must be at least 10 characters";
        } elseif (mb_strlen($message) > 500) {
            $messageErr = "* Must not exceed 500 characters";
        }
    }

    if (preg_match("/[\r\n]/", $fname . $lname . $email)) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid input detected.",
            "errors" => []
        ]);
        exit();
    }
    
    if ($fnameErr === "" && $lnameErr === "" && $emailErr === "" && $messageErr === "") {
        try {
            // Save contact message to database
            $stmt = $conn->prepare("
                INSERT INTO contact (firstname, lastname, email, message)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$fname, $lname, $email, $message]);

            echo json_encode([
                "status" => "success",
                "message" => "Your message was successfully received and we will contact you soon.",
                "errors" => []
            ]);
            exit();

        } catch (PDOException $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Database error. Your message could not be saved.",
                "errors" => []
            ]);
            exit();
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Please correct the highlighted fields.",
            "errors" => [
                "fname" => $fnameErr,
                "lname" => $lnameErr,
                "email" => $emailErr,
                "message" => $messageErr
            ]
        ]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/contact.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/footer.css?v=<?php echo time(); ?>">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/js/contact.js?v=<?php echo time(); ?>"></script>
</head>

<body>

<div class="header_background">
    <?php include '../assets/includes/header.php'; ?>
</div>

<main>
    <section class="contact-page">
        <div class="contact-wrapper">
            <!-- ABOUT US -->
            <div class="contact-left">
                <h2>About Us</h2>

                <p>Welcome to our Online Coffee Shop — your favourite beverage after long day of work. Our mission is to make quality coffee accessible to everyone, anytime, anywhere. From rich coffee beans to carefully crafted blends, we bring the café experience straight to your home.</p>
                <p>We believe coffee is more than just a drink — it's a way to connect people. That's why our platform is designed to be simple, secure, and customer-friendly, allowing you to explore, order, and enjoy your favorite coffee with ease.</p>
                <p>Whether you are a casual coffee lover or a passionate enthusiast, our goal is to serve you with fresh products, reliable service, and a smooth online experience.</p>
            </div>
            
            <!-- CONTACT US -->
            <div class="contact-right">
                <div class="form-header">
                    <h2>Contact Us</h2>
                </div>

                <div id="form-message" class="form-message" style="display:none;">
                    <span id="form-message-text"></span>
                    <button type="button" id="close-message" class="close-message-btn" aria-label="Close message">&times;</button>
                </div>

                <form class="contact-form" id="contactForm" action="contacts.php" method="post" novalidate>
                    <input type="hidden" name="action" value="submit_contact">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="fname">First Name <span class="error" id="fname_error"></span></label>
                            <input type="text" id="fname" name="fname">
                        </div>

                        <div class="form-group">
                            <label for="lname">Last Name <span class="error" id="lname_error"></span></label>
                            <input type="text" id="lname" name="lname">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="error" id="email_error"></span></label>
                        <input type="text" id="email" name="email">
                    </div>

                    <div class="form-group">
                        <label for="message">Your Message <span class="error" id="message_error"></span></label>
                        <textarea id="message" name="message" rows="4"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Send Message</button>
                        <button type="reset" class="btn-primary" id="resetFormBtn">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
<div class="footer_background">
    <?php include '../assets/includes/footer.php'; ?>
</div>


</body>
</html>