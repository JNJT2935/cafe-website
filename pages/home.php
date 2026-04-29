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
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "CafeOrCoffeeShop",
                    "@id": "https://www.kofii.mu/#business",
                    "name": "Kofii Shop",
                    "description": "Freshly brewed coffees and baked treats crafted daily and delivered across Mauritius.",
                    "url": "https://www.kofii.mu/",
                    "telephone": "+230-54812369",
                    "priceRange": "Rs 175 – Rs 250",
                    "image": "https://www.kofii.mu/assets/images/hero_image.jpg",
                    "servesCuisine": ["Coffee", "Pastries", "Snacks"],
                    "currenciesAccepted": "MUR",
                    "paymentAccepted": "Cash, Credit Card, Debit Card, PayPal",
                    "address": {
                        "@type": "PostalAddress",
                        "streetAddress": "Royal Road",
                        "addressLocality": "Port Louis",
                        "addressCountry": "MU"
                    },
                    "openingHoursSpecification": [
                        {
                            "@type": "OpeningHoursSpecification",
                            "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
                            "opens": "07:00",
                            "closes": "20:00"
                        },
                        {
                            "@type": "OpeningHoursSpecification",
                            "dayOfWeek": ["Saturday","Sunday"],
                            "opens": "08:00",
                            "closes": "18:00"
                        }
                    ],
                    "hasOfferCatalog": {
                        "@type": "OfferCatalog",
                        "name": "Menu",
                        "url": "https://www.kofii.mu/pages/view_product.php"
                    }
                },
                {
                    "@type": "WebSite",
                    "@id": "https://www.kofii.mu/#website",
                    "url": "https://www.kofii.mu/",
                    "name": "Kofii Shop",
                    "description": "Order premium coffee and snacks online from Kofii Shop, Mauritius.",
                    "potentialAction": {
                        "@type": "SearchAction",
                        "target": {
                            "@type": "EntryPoint",
                            "urlTemplate": "https://www.kofii.mu/pages/view_product.php?search={search_term_string}"
                        },
                        "query-input": "required name=search_term_string"
                    }
                }
            ]
        }
        </script>

    </head>
<body>
    <?php include ('../assets/includes/header.php');?>

    <div style="height: 80px; width: 100%;"></div>

    <div class="main">
        
    <section class="home-section">
        <div class="left-arrow"><i class="bx bxs-left-arrow"></i></div>
        <div class="right-arrow"><i class="bx bxs-right-arrow"></i></div>

        <div class="slider">
            <div class="slider__slide slider0">
                <div class="slider-detail">
                    <h1>Fresh Coffee in the morning</h1>
                    <p>Start your day the right way at Coffee Shop. From rich espresso to smooth lattes and freshly baked pastries.</p>
                    <a href="../pages/product_detail.php" class="btn">Shop Now</a>
                </div>
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>
            </div>
            <div class="slider__slide slider1">
                <div class="slider-detail">
                    <h1>Pure Green Coffee</h1>
                    <p>Experience premium unroasted beans packed with antioxidants and natural vitality.</p>
                    <a href="../pages/view_product.php" class="btn">Shop Now</a>
                </div>
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>
            </div>
            <div class="slider__slide slider2">
                <div class="slider-detail">
                    <h1>Nature's Morning Boost</h1>
                    <p>Embrace sustainable energy with our ethically sourced green coffee collection.</p>
                    <a href="../pages/view_product.php" class="btn">Shop Now</a>
                </div>
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>
            </div>
            <div class="slider__slide slider3">
                <div class="slider-detail">
                    <h1>Wellness in Every Sip</h1>
                    <p>Discover how coffee supports metabolism and overall vitality naturally.</p>
                    <a href="../pages/view_product.php" class="btn">Shop Now</a>
                </div>
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>
            </div>
            <div class="slider__slide slider4">
                <div class="slider-detail">
                    <h1>Farm to Cup Perfection</h1>
                    <p>Taste the difference of carefully selected, premium black coffee beans.</p>
                    <a href="../pages/view_product.php" class="btn">Shop Now</a>
                </div>
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>
            </div>
            <div class="slider__slide slider5">
                <div class="slider-detail">
                    <h1>Elevate Every Cup</h1>
                    <p>Join thousands who've discovered the remarkable benefits of black coffee.</p>
                    <a href="../pages/view_product.php" class="btn">Shop Now</a>
                </div>
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>
            </div>
        </div>

        <div class="slider-progress">
            <span class="dot active" data-slider="0"></span>
            <span class="dot" data-slider="1"></span>
            <span class="dot" data-slider="2"></span>
            <span class="dot" data-slider="3"></span>
            <span class="dot" data-slider="4"></span>
            <span class="dot" data-slider="5"></span>
        </div>
    </section>
    
        <!-- Ingredients section -->
        <section class="ingredients-section">
            <div class="project-image">
                <img src="../assets/images/container.webp" alt="coffee product">
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
                <img src="../assets/images/download.png" alt="">
                <h3>Popular Products</h3>
            </div>
            <div class="hero-banners">
                <img src="../assets/images/hero_image.jpg" alt="">
                <div class="hero-content">
                    <h2>Discover the Power of Pure Black Coffee</h2>
                    <p>Freshly roasted, ethically sourced beans that bring rich flavor and natural energy to every cup. Experience coffee the way nature intended.</p>
                    <a href="view_product.php" class="btn">Shop Now</a>
                </div>
            </div>

            <div class="box-container">

                <div class="box">
                    <img src="../assets/images/image5.svg" alt="">
                    <a href="view_product.php" class="btn">Shop Now</a>
                </div>

                <div class="box">
                    <img src="../assets/images/image6.svg" alt="">
                    <a href="view_product.php" class="btn">Shop Now</a>
                </div>

                <div class="box">
                    <img src="../assets/images/image7.svg" alt="">
                    <a href="view_product.php" class="btn">Shop Now</a>
                </div>

                <div class="box">
                    <img src="../assets/images/image8.svg" alt="">
                    <a href="view_product.php" class="btn">Shop Now</a>
                </div>

            </div>
        </section>

        <!-- Shop category section -->
        <section class="shop-category">
            <div class="box-container">
                <div class="box">
                    <img src="../assets/images/left.jpg" alt="">
                    <div class="detail">
                        <span>BIG OFFERS</span>
                        <h1>Extra 15% off</h1>
                        <a href="/dev_products.php" class="btn">Shop Now</a>
                    </div>
                </div>
                <div class="box">
                    <img src="../assets/images/right.jpg" alt="">
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
                    <img src="../assets/images/icon2.png" alt="">
                    <div class="detail">
                        <h3>Great Savings</h3>
                        <p>Save big on every order</p>
                    </div>
                </div>

                <div class="box">
                    <img src="../assets/images/icon1.png" alt="">
                    <div class="detail">
                        <h3>24/7 Support</h3>
                        <p>one-on-one support</p>
                    </div>
                </div>

                <div class="box">
                    <img src="../assets/images/icon0.png" alt="">
                    <div class="detail">
                        <h3>Gift Vouchers</h3>
                        <p>Vouchers on every festivals</p>
                    </div>
                </div>

                <div class="box">
                    <img src="../assets/images/icon.png" alt="">
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
                    <img src="../assets/images/image10.webp" alt="">
                    <h3>Mocha</h3>
                </div>
                <div class="box">
                    <img src="../assets/images/image9.webp" alt="">
                    <h3>Caramel Macchiato</h3>
                </div>
                <div class="box">
                    <img src="../assets/images/image12.webp" alt="">
                    <h3>Premium Roast</h3>
                </div>
                <div class="box">
                    <img src="../assets/images/image11.webp" alt="">
                    <h3>Latte</h3>
                </div>
            </div>
        </section>
    
    </div>

    <?php include ("../assets/includes/footer.php");?>
    <?php include('../assets/includes/alert.php');?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.querySelector('.slider');
        const leftArrow = document.querySelector('.left-arrow');
        const rightArrow = document.querySelector('.right-arrow');
        const dots = document.querySelectorAll('.dot');
        const slides = document.querySelectorAll('.slider__slide');
        
        let currentIndex = 0;
        const slideCount = slides.length;
        let autoTimer    = null;
        const AUTO_DELAY = 4500;
        
        // ---- scroll to a slide by index ----
        function goToSlide(index) {
            // wrap around
            if (index < 0)           index = slideCount - 1;
            if (index >= slideCount) index = 0;
 
            const slideWidth = slides[0].clientWidth;
            slider.scrollTo({ left: index * slideWidth, behavior: 'smooth' });
 
            currentIndex = index;
            updateDots(index);
        }
 
        // ---- update active dot ----
        function updateDots(index) {
            dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
        }
 
        // ---- read current index from scroll position ----
        function getScrollIndex() {
            const slideWidth = slides[0].clientWidth;
            return Math.round(slider.scrollLeft / slideWidth);
        }
 
        // ---- auto-play ----
        function startAuto() {
            stopAuto();
            autoTimer = setInterval(() => goToSlide(currentIndex + 1), AUTO_DELAY);
        }
 
        function stopAuto() {
            if (autoTimer) { clearInterval(autoTimer); autoTimer = null; }
        }
 
        // ---- events ----
        rightArrow.addEventListener('click', () => { goToSlide(currentIndex + 1); startAuto(); });
        leftArrow.addEventListener('click',  () => { goToSlide(currentIndex - 1); startAuto(); });
 
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => { goToSlide(i); startAuto(); });
        });
 
        // sync dots while user drags/scrolls manually
        slider.addEventListener('scroll', () => {
            currentIndex = getScrollIndex();
            updateDots(currentIndex);
        });
 
        // pause on hover
        slider.addEventListener('mouseenter', stopAuto);
        slider.addEventListener('mouseleave', startAuto);
 
        // keyboard support
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft')  { goToSlide(currentIndex - 1); startAuto(); }
            if (e.key === 'ArrowRight') { goToSlide(currentIndex + 1); startAuto(); }
        });
 
        // touch / swipe support
        let touchStartX = 0;
        slider.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        slider.addEventListener('touchend', (e) => {
            const diff = touchStartX - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 50) {
                diff > 0 ? goToSlide(currentIndex + 1) : goToSlide(currentIndex - 1);
                startAuto();
            }
        }, { passive: true });
 
        // fix scroll position after resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => goToSlide(currentIndex), 250);
        });
 
        // ---- init ----
        goToSlide(0);
        startAuto();
    });
    </script>
</body>
</html>


