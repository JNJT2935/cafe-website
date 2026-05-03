<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop Home Page</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/menu_home_page.css">
    <link rel="stylesheet" href="..\assets\css\header.css">
    <link rel="stylesheet" href="../assets/css/footer.css?v=<?php echo time(); ?>">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <?php include '..\assets\includes\header.php'; ?>

    <section class="menu-home">
        <div class="menu-text">
            <h2>DISCOVER</h2>
            <h1>OUR MENU</h1>
            <p>
                You've come to the right place whether your goal is for a boost, procrastinate or just act like you're working.
            </p>
            <a href="fullmenu.php" class="menu-btn">View Full Menu</a>
        </div>

        <div class="menu-images">
            <img src="..\assets\images\menu\affogato.jpeg" alt="">
            <img src="..\assets\images\menu\americano.jpg" alt="">
            <img src="..\assets\images\menu\capuccino.jpg" alt="">
            <img src="..\assets\images\menu\espresso.jpg" alt="">
        </div>
    </section>

</body>
</html>