<?php
include('../assets/includes/connect.php');
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$warning_msg = [];
$success_msg = [];

if (isset($_POST['submit'])) {

    $id = uniqid("user_id"); 
    $name  = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $pass  = $_POST['pass'];
    $cpass = $_POST['cpass'];

    // Check if email exists
    $check = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $warning_msg[] = "Email already exists";
    } elseif ($pass !== $cpass) {
        $warning_msg[] = "Passwords do not match";
    } else {
        // Secure password hashing
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

        $insert = $conn->prepare("
            INSERT INTO user (user_id, name, email, phone_number, password)
            VALUES (?, ?, ?, ?, ?)
        ");

        $insert->execute([$id, $name, $email, $phone, $hashed_password]);

        $success_msg[] = "Account created! Please login now.";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title> Coffee Shop </title>
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/header.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include('../assets/includes/header.php'); ?>

    <div class="main-container">
        <section class="form-container">
            <div class="title">
                <h1>Register Now</h1>
                <p>Your Journey to Happiness starts with us - Rgister Now!!!</p>
            </div>
            <form action="" method="post">
                <div class="input-field">
                    <p> Your Name <sub>*</sub></p>
                    <input type="text" name="name" pattern="[A-Z[a-z]+([A-Z[a-z]+)*$"required placeholder="Enter Your Name (it should start with a capital letter)" maxlength="16" size="50">
                </div>
                <div class="input-field">
                    <p> Your Email <sub>*</sub></p>
                    <input type="email" name="email" pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$" required placeholder="Enter Your Email Address" maxlength="50" oninput="this.value = this.value.replace(/\s/g,'')">
                </div>
                <div class="input-field">
                    <p> Your Phone Number <sub>*</sub></p>
                    <input type="tel" name="phone" pattern="^(5[0-9]{7}|[246][0-9]{7})$" required placeholder="Enter a valid Mauritian Number (e.g. 52501234 or 2601234)" maxlength="8" oninput="this.value = this.value.replace(/\s/g,'')">
                </div>
                <div class="input-field">
                    <p> Your Password <sub>*</sub></p>
                    <input type="password" name="pass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}" required placeholder="Enter a valid Password" maxlength="50" oninput="this.value = this.value.replace(/\s/g,'')">
                </div>
                <div class="input-field">
                    <p> Confirm Password <sub>*</sub></p>
                    <input type="password" name="cpass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}" required placeholder="Re-enter Your Password" maxlength="50" oninput="this.value = this.value.replace(/\s/g,'')">
                </div>

                <input type="submit" name="submit" value="Register now" class="button">
                <p>already have an account?<a href="login.php">Login Now</a></p>
            </form>
        </section>
    </div>
    <?php include('../assets/includes/alert.php'); ?>
</body>
</html>