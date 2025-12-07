<?php
    if (isset($_POST['sub-btn'])) {
        $email = $_POST['subemail'];
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $select_subscribers = $conn->prepare("SELECT * FROM `subscribe` WHERE user_id = ?");
        $select_subscribers->execute([$user_id]);

        if ($select_subscribers->rowCount() > 0) {
            $warning_msg[] = "You are already subscribed!";
        } else {
            $insert_subscribers = $conn->prepare("INSERT INTO `subscribe` (user_id, email) VALUES (?, ?)");
            $insert_subscribers->execute([$user_id, $email]);
            $success_msg[] = "Thank you for subscribing";
        }
    }
?>

<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Footer</title>
</head>
<body>
    <div class="top-footer">
        <h2><i class="bx bx-envelope"></i>Sign Up For Newsletters</h2>
        <form action="" method="post">
            <div class="input-field">
                <input type="email" name="subemail" required placeholder="Enter Your Email" maxlength="38"
                oninput="this.value = this.value.replace(/\s/g, '')">
                <button type="submit" name="sub-btn" class="btn">Subscribe</button>
            </div>
        </form>
    </div>

    <footer>
        <div class="overlay"></div>
            <div class="footer-content">
                <div class="inner-footer">
                    <div class="card">
                        <h3>About Us</h3>
                        <ul>
                            <li>About Us</li>
                            <li>Our Differences</li>
                            <li>Community Matters</li>
                            <li>Press</li>
                            <li>Blog</li>
                            <li>Video Gallery</li>
                        </ul>
                    </div>
                    <div class="card">
                        <h3>Services</h3>
                        <ul>
                            <li>Orders</li>
                            <li>Help Center</li>
                            <li>Shipping</li>
                            <li>Term of Use</li>
                            <li>Account Details</li>
                            <li>My Account</li>
                        </ul>
                    </div>
                    <div class="card">
                        <h3>Locations</h3>
                        <ul>
                            <li>Terre Rouge, TR</li>
                            <li>Port-Louis, PL</li>
                            <li>Curepipe, CR</li>
                            <li>Rose-Hill, RH</li>
                            <li>Flacq, FQ</li>
                        </ul>
                    </div>
                    <div class="card">
                        <h3>Connect With Us</h3>
                        <p>Follow us on social media for updates and special offers</p>
                        <div class="social-links">
                            <i class="bx bxl-instagram"></i>
                            <i class="bx bxl-twitter"></i>
                            <i class="bx bxl-facebook"></i>
                            <i class="bx bxl-whatsapp"></i>
                        </div>
                    </div>
                </div>
                <div class="bottom-footer">
                    <p>&copy; 2025 All Rights Reserved - Coffee</p>
                </div>
            </div>
    </footer>

</body>
</html>
