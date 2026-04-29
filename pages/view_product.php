<?php
    include('../assets/includes/connect.php');
    session_start();
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        header('Location: login.php');
        exit();
    }

    $success_msg = [];
    $warning_msg = [];

    // Handle Add to Cart
    if (isset($_POST['add_to_cart'])) {
        $product_id = intval($_POST['product_id']);
        $quantity   = max(1, intval($_POST['quantity'] ?? 1));

        // Check if already in cart
        $check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND Product_id = ?");
        $check->execute([$user_id, $product_id]);

        if ($check->rowCount() > 0) {
            // Update quantity
            $update = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND Product_id = ?");
            $update->execute([$quantity, $user_id, $product_id]);
        } else {
            $insert = $conn->prepare("INSERT INTO cart (user_id, Product_id, quantity) VALUES (?, ?, ?)");
            $insert->execute([$user_id, $product_id, $quantity]);
        }
        $success_msg[] = "Product added to cart!";
    }

    // Handle Add to Wishlist
    if (isset($_POST['add_to_wishlist'])) {
        $product_id = intval($_POST['product_id']);
        $price      = floatval($_POST['price']);

        $check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND Product_id = ?");
        $check->execute([$user_id, $product_id]);

        if ($check->rowCount() > 0) {
            $warning_msg[] = "Already in your wishlist.";
        } else {
            $insert = $conn->prepare("INSERT INTO wishlist (user_id, Product_id, price) VALUES (?, ?, ?)");
            $insert->execute([$user_id, $product_id, $price]);
            $success_msg[] = "Added to wishlist!";
        }
    }

    // Filters
    $category = $_GET['category'] ?? 'all';
    $sort     = $_GET['sort']     ?? 'default';
    $search   = trim($_GET['search'] ?? '');

    // Build query
    $where  = ["p.Visible_on_website = 1"];
    $params = [];

    if ($category !== 'all') {
        $where[]  = "p.category = ?";
        $params[] = $category;
    }
    if ($search !== '') {
        $where[]  = "(p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $order_by = match($sort) {
        'price_asc'  => 'p.price ASC',
        'price_desc' => 'p.price DESC',
        'name_asc'   => 'p.name ASC',
        'rating'     => 'avg_rating DESC',
        default      => 'p.Product_id ASC',
    };

    $sql = "
        SELECT p.*,
               ROUND(AVG(r.Rating), 1) AS avg_rating,
               COUNT(r.Rating)         AS review_count
        FROM product p
        LEFT JOIN review r ON r.Product_id = p.Product_id
        WHERE " . implode(' AND ', $where) . "
        GROUP BY p.Product_id
        ORDER BY $order_by
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch distinct categories
    $cat_stmt = $conn->query("SELECT DISTINCT category FROM product WHERE Visible_on_website = 1 ORDER BY category");
    $categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

    // Cart item count
    $cart_count = 0;
    if ($user_id) {
        $cc = $conn->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
        $cc->execute([$user_id]);
        $cart_count = (int)$cc->fetchColumn();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Browse our full menu of coffees and snacks.">
    <title>Kofii Shop — Menu</title>
    <link rel="stylesheet" href="../assets/css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/view_product.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/footer.css?v=<?php echo time(); ?>">
    <img src="../assets/images/productimages/<?php echo $item['image_source']; ?>" alt="item image">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../assets/includes/header.php'); ?>

    <div style="height: 80px; width: 100%;"></div>

    <div class="main">

        <!-- ===== HERO ===== -->
        <section class="products-hero">
            <div class="hero-overlay"></div>
            <div class="hero-text">
                <span class="hero-tag">Freshly Crafted Daily</span>
                <h1>Our Menu</h1>
                <p>Discover our hand-picked selection of coffees &amp; baked treats</p>
            </div>
            <div class="stats-bar">
                <div class="stat">
                    <span class="stat-num"><?php echo count($products); ?></span>
                    <span class="stat-label">Products</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <span class="stat-num"><?php echo count($categories); ?></span>
                    <span class="stat-label">Categories</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <a href="cart.php" class="stat-cart">
                        <i class="bx bx-cart"></i>
                        <span class="stat-num"><?php echo $cart_count; ?></span>
                    </a>
                    <span class="stat-label">In Your Cart</span>
                </div>
            </div>
        </section>

        <!-- ===== FILTERS ===== -->
        <section class="filters-section">

            <!-- Search -->
            <form method="GET" class="search-bar" id="filterForm">
                <div class="search-wrap">
                    <i class="bx bx-search"></i>
                    <input type="text" name="search" placeholder="Search products…"
                           value="<?php echo htmlspecialchars($search); ?>">
                    <?php if ($search): ?>
                        <a href="?" class="clear-search"><i class="bx bx-x"></i></a>
                    <?php endif; ?>
                </div>

                <!-- Category pills -->
                <div class="category-pills">
                    <a href="?category=all&sort=<?php echo $sort; ?>&search=<?php echo urlencode($search); ?>"
                       class="pill <?php echo $category === 'all' ? 'active' : ''; ?>">
                        <i class="bx bx-grid-alt"></i> All
                    </a>
                    <?php foreach ($categories as $cat): ?>
                    <a href="?category=<?php echo urlencode($cat); ?>&sort=<?php echo $sort; ?>&search=<?php echo urlencode($search); ?>"
                       class="pill <?php echo $category === $cat ? 'active' : ''; ?>">
                        <?php
                            $icons = ['Beverage' => 'bx-coffee', 'Snack' => 'bx-cookie', 'Food' => 'bx-dish'];
                            $icon  = $icons[$cat] ?? 'bx-tag';
                        ?>
                        <i class="bx <?php echo $icon; ?>"></i>
                        <?php echo htmlspecialchars($cat); ?>
                    </a>
                    <?php endforeach; ?>
                </div>

                <!-- Sort -->
                <div class="sort-wrap">
                    <label><i class="bx bx-sort-alt-2"></i> Sort:</label>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="default"    <?php echo $sort==='default'    ? 'selected':''; ?>>Default</option>
                        <option value="price_asc"  <?php echo $sort==='price_asc'  ? 'selected':''; ?>>Price: Low → High</option>
                        <option value="price_desc" <?php echo $sort==='price_desc' ? 'selected':''; ?>>Price: High → Low</option>
                        <option value="name_asc"   <?php echo $sort==='name_asc'   ? 'selected':''; ?>>Name A–Z</option>
                        <option value="rating"     <?php echo $sort==='rating'     ? 'selected':''; ?>>Top Rated</option>
                    </select>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                </div>
            </form>
        </section>

        <!-- ===== PRODUCTS GRID ===== -->
        <section class="products-section">

            <?php if (empty($products)): ?>
            <div class="no-products">
                <i class="bx bx-search-alt"></i>
                <h3>No products found</h3>
                <p>Try a different search or category.</p>
                <a href="view_product.php" class="btn">Clear Filters</a>
            </div>

            <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $p):
                    $stars      = round($p['avg_rating'] ?? 0);
                    $avg        = $p['avg_rating'] ? number_format($p['avg_rating'], 1) : '–';
                    $low_stock  = $p['stock_quantity'] <= 10;
                    $out        = $p['stock_quantity'] == 0;
                ?>
                <div class="product-card <?php echo $out ? 'out-of-stock' : ''; ?>">

                    <!-- Image -->
                    <div class="card-image">
                        <img src="../assets/images/<?php echo htmlspecialchars($p['image_source']); ?>"
                             alt="<?php echo htmlspecialchars($p['name']); ?>"
                             onerror="this.src='../assets/images/placeholder.jpg'">

                        <!-- Category badge -->
                        <span class="category-badge"><?php echo htmlspecialchars($p['category']); ?></span>

                        <?php if ($out): ?>
                            <div class="stock-overlay">Out of Stock</div>
                        <?php elseif ($low_stock): ?>
                            <span class="low-stock-badge">Only <?php echo $p['stock_quantity']; ?> left</span>
                        <?php endif; ?>

                        <!-- Quick actions overlay -->
                        <div class="card-actions-overlay">
                            <?php if (!$out): ?>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="product_id" value="<?php echo $p['Product_id']; ?>">
                                <input type="hidden" name="price"      value="<?php echo $p['price']; ?>">
                                <button type="submit" name="add_to_wishlist" class="action-btn wishlist-btn" title="Add to Wishlist">
                                    <i class="bx bx-heart"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            <a href="review.php" class="action-btn review-btn" title="Reviews">
                                <i class="bx bx-star"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <a href="product_detail.php?id=<?php echo $p['Product_id']; ?>" class="card-title-link">
                        <h3 class="card-title"><?php echo htmlspecialchars($p['name']); ?></h3>
                    </a>
                        <p class="card-desc"><?php echo htmlspecialchars($p['description']); ?></p>

                        <!-- Rating -->
                        <div class="card-rating">
                            <div class="stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bx bxs-star <?php echo $i <= $stars ? 'filled' : 'empty'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="rating-text">
                                <?php echo $avg; ?>
                                <?php if ($p['review_count'] > 0): ?>
                                    <span class="review-count">(<?php echo $p['review_count']; ?>)</span>
                                <?php endif; ?>
                            </span>
                        </div>

                        <!-- Price row -->
                        <div class="card-footer">
                            <span class="price">Rs <?php echo number_format($p['price'], 2); ?></span>

                            <?php if (!$out): ?>
                            <form method="POST" class="add-cart-form">
                                <input type="hidden" name="product_id" value="<?php echo $p['Product_id']; ?>">
                                <div class="qty-wrap">
                                    <button type="button" class="qty-btn" onclick="changeQty(this,-1)">−</button>
                                    <input type="number" name="quantity" value="1" min="1"
                                           max="<?php echo $p['stock_quantity']; ?>" class="qty-input" readonly>
                                    <button type="button" class="qty-btn" onclick="changeQty(this,1)">+</button>
                                </div>
                                <button type="submit" name="add_to_cart" class="btn add-btn">
                                    <i class="bx bx-cart-add"></i> Add
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="out-label">Unavailable</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </section>

    </div><!-- end .main -->

    <?php include('../assets/includes/footer.php'); ?>
    <?php include('../assets/includes/alert.php'); ?>

    <script>
    function changeQty(btn, delta) {
        const input = btn.parentElement.querySelector('.qty-input');
        const max   = parseInt(input.max) || 99;
        let val = parseInt(input.value) + delta;
        if (val < 1)   val = 1;
        if (val > max) val = max;
        input.value = val;
    }
    </script>
</body>
</html>