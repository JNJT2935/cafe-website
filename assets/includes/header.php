<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/connect.php';

if (!isset($conn)) {
    die("Database connection failed in header.php");
}

$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_email = $_SESSION['user_email'] ?? 'No Email';

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../pages/login.php');
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);

$total_cart_items = 0;

if ($user_id) {

    $count_cart_items = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $count_cart_items->execute([$user_id]);
    $total_cart_items = $count_cart_items->rowCount();
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
<?php include(__DIR__ . '/../css/header.css'); ?>
</style>

<header class="main-header">

    <div class="header-left">
        <a href="../pages/home.php" class="logo">
            <img src="../assets/images/logo.svg" alt="Logo">
            <span>Coffee Shop</span>
        </a>
    </div>

    <nav class="navbar">
        <a href="../pages/home.php" class="<?= $current_page == 'home.php' ? 'active' : '' ?>">Home</a>
        <a href="../pages/menu.php" class="<?= $current_page == 'menu.php' ? 'active' : '' ?>">Menu</a>
        <a href="../pages/review.php" class="<?= $current_page == 'review.php' ? 'active' : '' ?>">Review</a>
        <a href="../pages/contacts.php" class="<?= $current_page == 'aboutUs.php' ? 'active' : '' ?>">About Us</a>
    </nav>

    <div class="header-right">

        <div class="user-dropdown">
            <a href="#" class="header-icon">
                <i class="fa-regular fa-user" title="User Status"></i>
            </a>

            <div class="user-box">
                <p>Username: <span><?= htmlspecialchars($user_name); ?></span></p>
                <p>Email: <span><?= htmlspecialchars($user_email); ?></span></p>

                <?php if ($user_id): ?>
                    <form method="post">
                        <button type="submit" name="logout" class="logout-btn">Log Out</button>
                    </form>
                <?php else: ?>
                    <a href="../pages/login.php" class="login-btn">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <a href="../pages/cart_page.php" class="header-icon">
            <i class="fa-solid fa-cart-shopping" title="Cart"></i>
            <sup><?= $total_cart_items ?: '' ?></sup>
        </a>

        <a href="../pages/order_history.php" class="header-icon">
            <i class="fa-solid fa-bag-shopping" title="Order"></i>
        </a>

    </div>

    <script>
    const dropdown = document.querySelector('.user-dropdown');
    const userBox  = document.querySelector('.user-box');

    // Toggle on user icon click
    dropdown.querySelector('a').addEventListener('click', function (e) {
        e.preventDefault();
        userBox.classList.toggle('active');
    });

    // Close when clicking anywhere outside
    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target)) {
            userBox.classList.remove('active');
        }
    });
    
    </script>

</header>
