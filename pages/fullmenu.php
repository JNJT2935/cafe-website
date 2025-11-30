<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <title>Coffee Shop Home Page</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/menu.css">
</head>

<body>
<h1 class="menu-title">Our Coffee Menu</h1>

<?php if (isset($_GET['added'])): ?>
    <div style="
        background: #d4edda; 
        color:#155724; 
        padding:10px; 
        width:90%; 
        margin:10px auto; 
        border-radius:5px; 
        text-align:center;
        border:1px solid #c3e6cb;">
        Item added to cart!
    </div>
<?php endif; ?>

<div class="menu-container">

    
        <div class="menu-card">
            <img src="/assets/images/menu/espresso.jpg" alt="Espresso">
        
            <h3>ESPRESSO</h3>
            <h4>AVAILABLE</h4>
            <p1>Espresso 100%</p1>
            <p2>Bold in flavor, small in size. The essence of every coffee drink is this potent, fragrant burst of pure coffee bliss. Ideal for the true coffee purist or for a quick pick-me-up.</p2>
            <span class="price">Rs 100</span>

            <form action="../../../backend/database/addtocart.php" method="POST">
                <input type="hidden" name="item_id" value="1" required>
                <input type="hidden" name="item_name" value="Espresso" required>
                <input type="hidden" name="item_price" value="100" required>

                <button type="submit" class="add-btn">Add to Cart</button>
            </form>
        </div>

    <div class="menu-item">
        <div class="menu-card">
            <img src="/assets/images/menu/macchiato.jpg" alt="Machiato">
        
            <h3>MACHIATO</h3>
            <h4>AVAILABLE</h4>
            <p1>Espresso 85% | Milk Foam 15%</p1>
            <p2>A strong espresso kiss. Every sip of the Macchiato, a potent espresso shot that is delicately "stained" with creamy milk foam, strikes the ideal balance between strength and softness.</p2>
            <span class="price">Rs 160</span>
            
            <form action="../../../backend/database/addtocart.php" method="POST">
                <input type="hidden" name="item_id" value="2" required>
                <input type="hidden" name="item_name" value="Machiato" required>
                <input type="hidden" name="item_price" value="160" required>

                <button type="submit" class="add-btn">Add to Cart</button>
            </form>
        </div>
    </div>

    <div class="menu-item">
        <div class="menu-card">
            <img src="/assets/images/menu/americano.jpg" alt="Americano">
        
            <h3>AMERICANO</h3>
            <h4>AVAILABLE</h4>
            <p1>Espresso 30% | Hot Water 70%</p1>
            <p2>It's bold, smooth, and never bitter. For those who enjoy the depth of espresso with the simplicity of a traditional brew, the Americano combines the richness of espresso with hot water to create a lighter, longer drink.</p2>
            <span class="price">Rs 110</span>

            <form action="../../../backend/database/addtocart.php" method="POST">
                <input type="hidden" name="item_id" value="3" required>
                <input type="hidden" name="item_name" value="Americano" required>
                <input type="hidden" name="item_price" value="110" required>

                <button type="submit" class="add-btn">Add to Cart</button>
            </form>
        </div>
    </div>

    <div class="menu-item">
        <div class="menu-card">
            <img src="/assets/images/menu/coffeelatte.jpg" alt="Coffee Latte">
        
            <h3>COFFEE LATTE</h3>
            <h4>AVAILABLE</h4>
            <p1>Espresso 15% | Steamed Milk 70% | Milk Foam 15%</p1>
            <p2><wrapped-text>Gentle, creamy, and exquisitely soothing. The Latte, the preferred beverage for people who prefer smooth and calming drinks, combines velvety steamed milk with a shot of espresso and is topped with a light foam.</p2>
            <span class="price">Rs 175</span>

            <form action="../../../backend/database/addtocart.php" method="POST">
                <input type="hidden" name="item_id" value="4" required>
                <input type="hidden" name="item_name" value="Coffee Latte" required>
                <input type="hidden" name="item_price" value="175" required>

                <button type="submit" class="add-btn">Add to Cart</button>
            </form>
        </div>
    </div>

    <div class="menu-item">
        <div class="menu-card">
            <img src="/assets/images/menu/capuccino.jpg" alt="Cappuccino">
        
            <h3>CAPPUCCINO</h3>
            <h4>AVAILABLE</h4>
            <p1>Espresso 25% | Steamed Milk 30% | Milk Foam 45%</p1>
            <p2>Character-rich, fluffy, and creamy. Body, texture, and heart are all expertly balanced in the Cappuccino, which is a cloud of rich milk foam over strong espresso.</p2>
            <span class="price">Rs 165</span>

            <form action="../../../backend/database/addtocart.php" method="POST">
                <input type="hidden" name="item_id" value="5" required>
                <input type="hidden" name="item_name" value="Cappuccino" required>
                <input type="hidden" name="item_price" value="165" required>

                <button type="submit" class="add-btn">Add to Cart</button>
            </form>
        </div>
    </div>

    <div class="menu-item">
        <div class="menu-card">
            <img src="/assets/images/menu/affogato.jpeg" alt="Affogato">
        
            <h3>AFFOGATO</h3>
            <h4>AVAILABLE</h4>
            <p1>Espresso 35% | Ice Cream 65%</p1>
            <p2><wrapped-text>Coffee meets dessert. The Affogato, which consists of a scoop of cold, creamy vanilla ice cream and a shot of hot espresso, is decadent, spectacular, and completely unforgettable.</p2>
            <span class="price">Rs 150</span>

            <form action="../../../backend/database/addtocart.php" method="POST">
                <input type="hidden" name="item_id" value="6" required>
                <input type="hidden" name="item_name" value="Affogato" required>
                <input type="hidden" name="item_price" value="150" required>

                <button type="submit" class="add-btn">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>