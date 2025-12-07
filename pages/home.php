<?php
    include ('../assets/includes/connect.php');
    session_start();
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        header('Location: login.php');
        exit();
    }
    
    if (isset($_POST['logout'])) {
        session_destroy();
        header('location: login.php');
    }
?>

<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <title>Coffee Shop - Home Page</title>
        <link rel="stylesheet" href="../assets/css/header.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../assets/css/home.css?v=<?php echo time(); ?>">
        <link rel="stylesheet" href="../assets/css/footer.css?v=<?php echo time(); ?>">
    </head>
<body>
    <?php include ('../assets/includes/header.php');?>

    <div class="main">
        <section class="home-section">
            <div class="left-arrow"><i class="bx bxs-left-arrow"></i></div>
            <div class="right-arrow"><i class="bx bxs-right-arrow"></i></div>

            <div class="slider__slider Mody">
                <div class="Mody">
                    <h1>Fresh Coffee in the morning</h1>
                    <p>Start your day the right way at Coffee Shop. From rich espresso to smooth lattes and freshly baked pastries, we serve the perfect blend of flavor and comfort. Whether you’re grabbing a cup on the go or settling in with friends, our cozy space and ethically sourced beans make every sip special..</p>
                </div>
                <div class="slider__slider slider1">
                    <div class="slider-detail">
                        <h1>Pure Green Coffee</h1>
                        <p>Experience premium unroasted beans packed with antioxidants and natural vitality.</p>
                        <a href="../pages/view_product.php" class="btn">Shop Now</a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>
                </div>
                <div class="slider__slider slider2">
                    <div class="slider-detail">
                        <h1>Nature's Morning Boost</h1>
                        <p>Embrace sustainable energy with our ethically sourced green coffee collection.</p>
                        <a href="../pages/view_products" class="btn">Shop Now</a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>                  
                </div>
                    <div class="slider__slider slider3">
                    <div class="slider-detail">
                        <h1>Wellness in Every Sip</h1>
                        <p>Discover how coffee supports metabolism and overall vitality naturally</p>
                        <a href="../pages/view_products" class="btn">Shop Now</a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>                  
                </div>
                    <div class="slider__slider slider4">
                    <div class="slider-detail">
                        <h1>Farm to cup perfection</h1>
                        <p>Taste the difference of carefully selcted, premium black coffee beans</p>
                        <a href="../pages/view_products" class="btn">Shop Now</a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>                  
                </div>
                    <div class="slider__slider slider5">
                    <div class="slider-detail">
                        <h1>Elevate Every Cup</h1>
                        <p>Join thousands who've discovered the remarkable benefits of black coffee</p>
                        <a href="../pages/view_products" class="btn">Shop Now</a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>                  
                </div>               
            </div>
            
            <div class="slider-progess">
                <span class="dot active" data-slider="0"></span>
                <span class="dot" data-slider="1"></span>
                <span class="dot" data-slider="2"></span>
                <span class="dot" data-slider="3"></span>
                <span class="dot" data-slider="4"></span>
                <span class="dot" data-slider="5"></span>
            </div>
        </section>
        <!-- Home slider end -->
        <!-- Ingredients section -->
        <section class="ingredients-section">
            <div class="project-image">
                <img src="img/container.webp" alt="coffee product">
            </div>
            <div class="content">
                <h2>FINEST INGREDIENTS</h2>
                <p class="intro-text">
                    This is the perfect place to find a nice and cozy spot to sip some. You'll find the Java jungle, Coffee Bean and more.
                </p>
                <div class="feature">
                    <i class="bx bxs-coffee icon"></i>
                    <div class="feature-content">
                        <h3>Coffeemaker</h3>
                        <p>Receive incoming calls or speed dial contacts without reching aniesn.</p>
                    </div>
                </div>
                <div class="feature">
                    <i class="bx bxs-coffee-bean icon"></i>
                    <div class="feature-content">
                        <h3>Coffee Grinder</h3>
                        <p>Calls & Calendar management personal contacts without reaching edfes</p>
                    </div>
                </div>
                <div class="feature">
                    <i class="bx bxs-coffee-togo icon"></i>
                    <div class="feature-content">
                        <h3>Coffee Cup</h3>
                        <p>Controls management personal contacts without reaching phones offer</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Shop section -->
        <section class="shop">
            <div class="title">
                <img src="/img/download.png" alt="">
                <h3>Popular Products</h3>
            </div>
            <div class="hero-banners">
                <img src="/img/hero_image.jpg" alt="">
                <div class="hero-content">
                    <h3>Discover the Power of Pure Black Coffee</h3>
                    <p>Freshly roasted, ethically sourced beans that bring rich flavor and natural energy to every cup. Experience coffee the way nature intended.</p>
                    <a href="view_products.php" class="btn">Shop Now</a>
                </div>
            </div>

            <div class="box-container">

                <div class="box">
                    <img src="./images/image5.svg" alt="">
                    <a href="view_products.php" class="btn">Shop Now</a>
                </div>

                <div class="box">
                    <img src="../images/image6.svg" alt="">
                    <a href="view_products.php" class="btn">Shop Now</a>
                </div>

                <div class="box">
                    <img src="../images/image7.svg" alt="">
                    <a href="view_products.php" class="btn">Shop Now</a>
                </div>

                <div class="box">
                    <img src="../images/image8.svg" alt="">
                    <a href="view_products.php" class="btn">Shop Now</a>
                </div>

            </div>
        </section>

        <!-- Shop category section -->
        <section class="shop-category">
            <div class="box-container">
                <div class="box">
                    <img src="/img/left.jpg" alt="">
                    <div class="detail">
                        <span>BIG OFFERS</span>
                        <h1>Extra 15% off</h1>
                        <a href="/dev_products.php" class="btn">Shop Now</a>
                    </div>
                </div>
                <div class="box">
                    <img src="/img/right.jpg" alt="">
                    <div class="detail">
                        <span>Now In Taste</span>
                        <h1>Coffee House</h1>
                        <a href="/dev_products.php" class="btn">Shop Now</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="services">
            <div class="box-container">

                <div class="box">
                    <img src="./img/icon2.png" alt="">
                    <div class="detail">
                        <h3>Great Savings</h3>
                        <p>Save big on every order</p>
                    </div>
                </div>

                <div class="box">
                    <img src="./img/icon1.png" alt="">
                    <div class="detail">
                        <h3>24/7 Support</h3>
                        <p>one-on-one support</p>
                    </div>
                </div>

                <div class="box">
                    <img src="./img/icon2.png" alt="">
                    <div class="detail">
                        <h3>Gift Vouchers</h3>
                        <p>Vouchers on every festivals</p>
                    </div>
                </div>

                <div class="box">
                    <img src="./img/icon2.png" alt="">
                    <div class="detail">
                        <h3>Cash on delivery</h3>
                        <p>all MU Delivery</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Brand section -->
        <section class="brand">
            <div class="title">
                <h2>Our Coffee Brands</h2>
                <p>Explore the signature blends that make every cup unforgettable.</p>
            </div>

            <div class="box-container">
                <div class="box">
                    <img src="/img/img-3.webp" alt="">
                    <h3>Mocha</h3>
                </div>
                <div class="box">
                    <img src="/img/img-2.webp" alt="">
                    <h3>Caramel Macchiato</h3>
                </div>
                <div class="box">
                    <img src="/img/img-9.webp" alt="">
                    <h3>Premium Roast</h3>
                </div>
                <div class="box">
                    <img src="/img/img-1.webp" alt="">
                    <h3>Latte</h3>
                </div>
            </div>
        </section>
    
    </div>

    <?php include ("../assets/includes/footer.php");?>
    <?php include('../assets/includes/alert.php');?>

    
</body>
</html>


