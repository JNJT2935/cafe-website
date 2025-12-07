<?php
    if (!isset($conn)) {
        include __DIR__ . '/connect.php';

    }

    // Make sure user_id exists
    $user_id = $_SESSION['user_id'] ?? null;

    if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

?>

<style type="text/css">
    <?php include('../assets/css/header.css');?>
</style>


<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Header</title>
    </head>
    <body>
        <header class="header">
            <div class="flex">
                <a href="home.php" class="logo"><img src="../assets/images/logo.svg"></a>
                <nav class="navbar">
                    <a href="home.php">Home</a>
                    <a href="about.php">About Us</a>
                    <a href="menu.php">Menu</a>
                    <a href="products.php">Products</a>
                    <a href="review.php">Reviews</a>
                    <a href="contacts.php">Contacts Us</a>
                    <a href="blogs.php">Blogs</a>
                </nav>
                <div class="icons">
                    <a href="search_page.php" class="search-btn">
                        <img src="../assets/images/bx_bx-search-alt-2.svg" alt="Search" class="search-icon">
                    </a>
                    <i class="bx bxs-user" id="user-btn"></i>
                    <?php
                    $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                    $count_wishlist_items->execute([$user_id]);
                    $total_wishlist_items = $count_wishlist_items->rowCount();
                    ?>
                    <a href="wishlist.php" class="cart-btn">
                        <img src="../assets/images/Heart_Icon.svg" alt="Wishlist" class="wishlist-icon">
                        <sup><?= $total_wishlist_items > 0 ? $total_wishlist_items : '' ?></sup>
                    </a>
                    <?php
                    $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                    $count_cart_items->execute([$user_id]);
                    $total_cart_items = $count_cart_items->rowCount();
                    ?>
                    <a href="cart.php" class="cart-btn">
                        <img src="../assets/images/clarity_shopping-cart-solid.svg" alt="Cart" class="cart-icon">
                        <sup><?= $total_cart_items > 0 ? $total_cart_items : '' ?></sup>
                    </a>
                    <i class="bx bx-list-plus" id="menu-btn" style="font-size: 2rem;"></i>
                </div>
                <div class="user-box">
                    <p>Username : <span><?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';?></span></p>
                    <p>Email : <span><?= isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'No Email'; ?></span></p>

                    <form method="post">
                        <button type="submit" name="logout" class="logout-btn">Log Out</button>
                    </form>
                </div>
            </div>
        </header>
    </body>
</html>