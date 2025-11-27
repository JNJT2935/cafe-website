<header class="main-header">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <div class="header-left">
        <!-- LOGO -->
        <a href="pages/Home_Page.php" class="logo">
            <img src="..\assets\images\header_icon\coffee_logo.svg" alt="Coffee Shop Logo">
            <span>Coffee Shop</span>
        </a>
    </div>

    <?php 
    $current_page = basename($_SERVER['PHP_SELF']); 
    ?>

    <!-- NAVIGATION LINKS -->
    <nav class="navbar">
        <a href="" class="<?= $current_page == 'home.php' ? 'active' : '' ?>">Home</a>
        <a href="" class="<?= $current_page == 'menu.php' ? 'active' : '' ?>">Menu</a>
        <a href="" class="<?= $current_page == 'review.php' ? 'active' : '' ?>">Review</a>
        <a href="" class="<?= $current_page == 'aboutUs.php' ? 'active' : '' ?>">About us</a>
    </nav>

    <div class="header-right">
        <!-- Icons -->
        <a href="" class="header-icon">
            <i class="fa-regular fa-user"></i>
        </a>

        <a href="..\..\pages\cart_page.php" class="header-icon">
            <i class="fa-solid fa-cart-shopping"></i>
        </a>
        <a href="" class="header-icon">
            <i class="fa-regular fa-heart"></i>
        </a>

    </div>

</header>
