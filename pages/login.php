<?php
include('../assets/includes/connect.php');
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {

    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: home.php");
    }

    exit();
}

$warning_msg = [];

if (isset($_POST['submit'])) {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass  = $_POST['pass'];

    // Fetch user by email
    $query = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $query->execute([$email]);
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Verify password
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id']    = $row['user_id'];
            $_SESSION['user_name']  = $row['name'];
            $_SESSION['user_email'] = $row['email'];
             $_SESSION['user_type']  = $row['User_type']; 

            // ---- ADMIN OR USER REDIRECT ----
            if ($row['User_type'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: home.php");
            }
            exit();
 
        } else {
            $warning_msg[] = "Incorrect email or password";
        }

    } else {
        $warning_msg[] = "Incorrect email or password";
    }
}
?>

<!DOCTYPE html>
<html lang=en >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coffee Shop Website - Login Page</title>
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
        width: 100% !important;  /* override the 85% from style.css */
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
                <h1>Login Now</h1>
                <p>Login Now and Boost Your Day with Coffee</p>
            </div>
            <form action="" method="post">
                <div class="input-field">
                <p>Your Email <sub>*</sub></p>
                <input type="email" name="email" required placeholder="Enter Your email" maxlength="50" oninput="this.value = this.value.replace(/\s/g,'')">
                </div>
                <div class="input-field">
                    <p> Your Password <sub>*</sub></p>
                    <input type="password" id="passInput" name="pass" required placeholder="Enter Your Password" maxlength="50" oninput="this.value = this.value.replace(/\s/g,'')">
                    <i class="fa-regular fa-eye toggle-password" id="togglePass"></i>
                </div>
                <input type="submit" name="submit" value="Login Now" class="btn">
                <p>do not have an account? <a href="register.php"> Register Now </a></p>

                <div class="social-login">
                    <i class="fab fa-google"></i>
                    <i class="fab fa-facebook"></i>
                    <i class="fab fa-instagram"></i>
                </div>
            </form>
        </section>
    </div>
    <?php include('../assets/includes/alert.php');?>

    <script>
    const togglePass = document.getElementById('togglePass');
    const passInput  = document.getElementById('passInput');

    togglePass.addEventListener('click', function () {
        const isPassword = passInput.type === 'password';
        
        // Toggle input type
        passInput.type = isPassword ? 'text' : 'password';
        
        // Swap the icon
        if (isPassword) {
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
    </script>

</body>
</html>


