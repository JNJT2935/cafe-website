<?php
include('../assets/includes/connect.php');
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$warning_msg = [];
$success_msg = [];

if (isset($_POST['submit'])) {

    $name  = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST['phone']);
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
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        $salt = bin2hex(random_bytes(8)); // generates a 16-char salt

        $insert = $conn->prepare("
            INSERT INTO user (name, email, phone_number, password, salt, User_type)
            VALUES (?, ?, ?, ?, ?, 'user')
        ");

        if ($insert->execute([$name, $email, $phone, $hashed_password, $salt])) {
            header("Location: login.php");
            exit();
        } else {
            $warning_msg[] = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Coffee Shop - Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/header.css?v=<?php echo time(); ?>">

    <style>
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .password-wrapper input {
            width: 100% !important;
            padding-right: 42px !important;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 16px;
            user-select: none;
            z-index: 10;
        }
        .toggle-password:hover {
            color: #e8670b;
        }
    </style>
</head>

<body>
    <?php include('../assets/includes/header.php'); ?>

    <div class="main-container">
        <section class="form-container">
            <div class="title">
                <h1>Register Now</h1>
                <p>Your Journey to Happiness starts with us - Register Now!</p>
            </div>
            <form action="" method="post">

                <div class="input-field">
                    <p>Your Name <sub>*</sub></p>
                    <input type="text" name="name" required
                           placeholder="Enter Your Name"
                           maxlength="50">
                </div>

                <div class="input-field">
                    <p>Your Email <sub>*</sub></p>
                    <input type="email" name="email" required
                           placeholder="Enter Your Email Address"
                           maxlength="50"
                           oninput="this.value = this.value.replace(/\s/g,'')">
                </div>

                <div class="input-field">
                    <p>Your Phone Number <sub>*</sub></p>
                    <input type="tel" name="phone"
                           pattern="^(5[0-9]{7}|[246][0-9]{7})$"
                           required
                           placeholder="e.g. 52501234"
                           maxlength="8"
                           oninput="this.value = this.value.replace(/\s/g,'')">
                </div>

                <div class="input-field">
                    <p>Your Password <sub>*</sub></p>
                    <div class="password-wrapper">
                        <input type="password" id="passInput" name="pass"
                               pattern="(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&amp;*?]).{8,}"
                               required placeholder="Min 8 chars, upper, lower, number, symbol"
                               maxlength="50"
                               oninput="this.value = this.value.replace(/\s/g,'')">
                        <i class="fa-regular fa-eye toggle-password" id="togglePass"></i>
                    </div>
                </div>

                <div class="input-field">
                    <p>Confirm Password <sub>*</sub></p>
                    <div class="password-wrapper">
                        <input type="password" id="cpassInput" name="cpass"
                               pattern="(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&amp;*?]).{8,}"
                               required placeholder="Re-enter Your Password"
                               maxlength="50"
                               oninput="this.value = this.value.replace(/\s/g,'')">
                        <i class="fa-regular fa-eye toggle-password" id="toggleCpass"></i>
                    </div>
                </div>

                <input type="submit" name="submit" value="Register Now" class="button">
                <p>Already have an account? <a href="login.php">Login Now</a></p>

            </form>
        </section>
    </div>

    <?php include('../assets/includes/alert.php'); ?>

    <script>
        function toggleVisibility(toggleId, inputId) {
            const toggle = document.getElementById(toggleId);
            const input  = document.getElementById(inputId);

            toggle.addEventListener('click', function () {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                if (isPassword) {
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        }

        toggleVisibility('togglePass',  'passInput');
        toggleVisibility('toggleCpass', 'cpassInput');
    </script>
</body>
</html>