<?php
    include('../assets/includes/connect.php');
    session_start();
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        header('Location: login.php');
        exit();
    }

    // Get product ID from URL
    $product_id = intval($_GET['id'] ?? 0);
    if (!$product_id) {
        header('Location: view_product.php');
        exit();
    }

    $success_msg = [];
    $warning_msg = [];

    // Handle Add to Cart
    if (isset($_POST['add_to_cart'])) {
        $qty = max(1, intval($_POST['quantity'] ?? 1));
        $check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND Product_id = ?");
        $check->execute([$user_id, $product_id]);
        if ($check->rowCount() > 0) {
            $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND Product_id = ?")
                 ->execute([$qty, $user_id, $product_id]);
        } else {
            $conn->prepare("INSERT INTO cart (user_id, Product_id, quantity) VALUES (?, ?, ?)")
                 ->execute([$user_id, $product_id, $qty]);
        }
        $success_msg[] = "Added to cart!";
    }

    // Handle Add to Wishlist
    if (isset($_POST['add_to_wishlist'])) {
        $check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND Product_id = ?");
        $check->execute([$user_id, $product_id]);
        if ($check->rowCount() > 0) {
            $warning_msg[] = "Already in your wishlist.";
        } else {
            $price = floatval($_POST['price']);
            $conn->prepare("INSERT INTO wishlist (user_id, Product_id, price) VALUES (?, ?, ?)")
                 ->execute([$user_id, $product_id, $price]);
            $success_msg[] = "Added to wishlist!";
        }
    }

    // Fetch product
    $stmt = $conn->prepare("SELECT * FROM product WHERE Product_id = ? AND Visible_on_website = 1");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: view_products.php');
        exit();
    }

    // Fetch reviews for this product (join through order)
    $rev_stmt = $conn->prepare("
        SELECT r.Rating, r.Comment, u.name AS reviewer, o.Order_date
        FROM review r
        JOIN `order` o ON r.Order_id = o.Order_id
        JOIN `user`  u ON o.user_id  = u.user_id
        WHERE r.Product_id = ?
        ORDER BY o.Order_date DESC
    ");
    $rev_stmt->execute([$product_id]);
    $reviews = $rev_stmt->fetchAll(PDO::FETCH_ASSOC);

    $avg_rating   = count($reviews) > 0
        ? round(array_sum(array_column($reviews, 'Rating')) / count($reviews), 1)
        : 0;
    $review_count = count($reviews);

    // Related products (same category, excluding current)
    $rel_stmt = $conn->prepare("
        SELECT Product_id, name, price, image_source
        FROM product
        WHERE category = ? AND Product_id != ? AND Visible_on_website = 1
        LIMIT 4
    ");
    $rel_stmt->execute([$product['category'], $product_id]);
    $related = $rel_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($product['name']); ?> — Kofii Shop</title>
    <link rel="stylesheet" href="../assets/css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/product_detail.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/footer.css?v=<?php echo time(); ?>">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../assets/includes/header.php'); ?>
    <div style="height: 80px;"></div>

    <div class="pd-wrapper">

        <!-- ===== PRODUCT CARD ===== -->
        <div class="pd-card">

            <!-- LEFT — dark image panel -->
            <div class="pd-image-panel">
                <a href="view_products.php" class="back-btn"><i class="bx bx-chevron-left"></i></a>
                <div class="img-glow"></div>
                <img src="../assets/images/<?php echo htmlspecialchars($product['image_source']); ?>"
                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                     onerror="this.src='../assets/images/placeholder.jpg'"
                     class="pd-img">
                <!-- Category chip at bottom -->
                <span class="img-category"><?php echo htmlspecialchars($product['category']); ?></span>
            </div>

            <!-- RIGHT — detail panel -->
            <div class="pd-detail-panel">

                <!-- Top row: rating + price -->
                <div class="pd-top-row">
                    <div class="pd-rating-chip">
                        <i class="bx bxs-star"></i>
                        <?php echo $avg_rating > 0 ? $avg_rating : 'New'; ?>
                    </div>
                    <span class="pd-price">Rs <?php echo number_format($product['price'], 2); ?></span>
                </div>

                <!-- Name + qty stepper -->
                <div class="pd-name-row">
                    <h1 class="pd-name"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <form method="POST" id="cartForm">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                        <div class="qty-stepper">
                            <button type="button" class="qty-btn" id="qtyMinus"><i class="bx bx-minus"></i></button>
                            <input type="number" name="quantity" id="qtyInput" value="1" min="1"
                                   max="<?php echo $product['stock_quantity']; ?>" readonly>
                            <button type="button" class="qty-btn" id="qtyPlus"><i class="bx bx-plus"></i></button>
                        </div>
                    </form>
                </div>

                <!-- Description -->
                <p class="pd-desc">
                    <strong><?php echo htmlspecialchars($product['name']); ?></strong> — 
                    <?php echo htmlspecialchars($product['description']); ?>
                </p>

                <!-- Stock indicator -->
                <div class="pd-stock">
                    <?php if ($product['stock_quantity'] == 0): ?>
                        <span class="stock-dot out"></span> Out of stock
                    <?php elseif ($product['stock_quantity'] <= 10): ?>
                        <span class="stock-dot low"></span> Only <?php echo $product['stock_quantity']; ?> left
                    <?php else: ?>
                        <span class="stock-dot in"></span> In stock
                    <?php endif; ?>
                </div>

                <!-- ── Accordion: Ingredients ── -->
                <div class="accordion" id="accIngredients">
                    <button class="accordion-btn active" onclick="toggleAccordion('accIngredients')">
                        <span class="acc-icon open">−</span>
                        <span class="acc-icon closed" style="display:none">+</span>
                        <span>Ingredients</span>
                    </button>
                    <div class="accordion-body open">
                        <ul class="ingredient-list">
                            <?php
                                // Parse description into ingredient-like lines for demo
                                // In production you'd have a separate ingredients table/column
                                $lines = array_filter(array_map('trim', explode('.', $product['description'])));
                                foreach ($lines as $line):
                                    if (empty($line)) continue;
                            ?>
                            <li><i class="bx bx-check-circle"></i> <?php echo htmlspecialchars($line); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- ── Accordion: Reviews ── -->
                <div class="accordion" id="accReviews">
                    <button class="accordion-btn" onclick="toggleAccordion('accReviews')">
                        <span class="acc-icon open" style="display:none">−</span>
                        <span class="acc-icon closed">+</span>
                        <span>Reviews
                            <?php if ($review_count > 0): ?>
                                <em>(<?php echo $review_count; ?>)</em>
                            <?php endif; ?>
                        </span>
                    </button>
                    <div class="accordion-body">
                        <?php if (empty($reviews)): ?>
                            <p class="no-reviews-msg">No reviews yet. <a href="review.php">Be the first!</a></p>
                        <?php else: ?>
                            <?php foreach ($reviews as $rv): ?>
                            <div class="mini-review">
                                <div class="mini-review-top">
                                    <span class="mini-avatar"><?php echo strtoupper(substr($rv['reviewer'],0,1)); ?></span>
                                    <strong><?php echo htmlspecialchars($rv['reviewer']); ?></strong>
                                    <div class="mini-stars">
                                        <?php for ($i=1;$i<=5;$i++): ?>
                                            <i class="bx bxs-star <?php echo $i<=$rv['Rating']?'on':'off'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p>"<?php echo htmlspecialchars($rv['Comment']); ?>"</p>
                            </div>
                            <?php endforeach; ?>
                            <a href="review.php" class="all-reviews-link">See all reviews →</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ── Add-ons row (related products as add-ons) ── -->
                <?php if (!empty($related)): ?>
                <div class="addons-row">
                    <span class="addons-label">You may also like</span>
                    <div class="addons-list">
                        <?php foreach ($related as $rel): ?>
                        <a href="product_detail.php?id=<?php echo $rel['Product_id']; ?>" class="addon-chip" title="<?php echo htmlspecialchars($rel['name']); ?>">
                            <img src="../assets/images/<?php echo htmlspecialchars($rel['image_source']); ?>"
                                 alt="<?php echo htmlspecialchars($rel['name']); ?>"
                                 onerror="this.src='../assets/images/placeholder.jpg'">
                            <i class="bx bx-plus addon-plus"></i>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- ── Action buttons ── -->
                <div class="pd-actions">
                    <?php if ($product['stock_quantity'] > 0): ?>
                    <button type="submit" form="cartForm" class="btn-add-cart">
                        <i class="bx bx-cart-add"></i> Add to Cart
                    </button>
                    <?php else: ?>
                    <button class="btn-add-cart disabled" disabled>Out of Stock</button>
                    <?php endif; ?>

                    <form method="POST" style="display:contents">
                        <input type="hidden" name="add_to_wishlist" value="1">
                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                        <button type="submit" class="btn-wishlist" title="Add to Wishlist">
                            <i class="bx bx-heart"></i>
                        </button>
                    </form>
                </div>

            </div><!-- end pd-detail-panel -->
        </div><!-- end pd-card -->

    </div><!-- end pd-wrapper -->

    <?php include('../assets/includes/footer.php'); ?>
    <?php include('../assets/includes/alert.php'); ?>

    <script>
    // Qty stepper
    const qtyInput = document.getElementById('qtyInput');
    document.getElementById('qtyMinus').addEventListener('click', () => {
        if (parseInt(qtyInput.value) > 1) qtyInput.value--;
    });
    document.getElementById('qtyPlus').addEventListener('click', () => {
        const max = parseInt(qtyInput.max) || 99;
        if (parseInt(qtyInput.value) < max) qtyInput.value++;
    });

    // Accordion toggle
    function toggleAccordion(id) {
        const acc  = document.getElementById(id);
        const body = acc.querySelector('.accordion-body');
        const btn  = acc.querySelector('.accordion-btn');
        const open = acc.querySelectorAll('.acc-icon.open');
        const closed = acc.querySelectorAll('.acc-icon.closed');

        const isOpen = body.classList.contains('open');
        body.classList.toggle('open', !isOpen);
        btn.classList.toggle('active', !isOpen);
        open.forEach(el => el.style.display   = isOpen ? 'none' : 'inline');
        closed.forEach(el => el.style.display = isOpen ? 'inline' : 'none');
    }
    </script>
</body>
</html>