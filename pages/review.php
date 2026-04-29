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

    // Fetch all reviews with user name and product name
    // review has no user_id — join through order to get user name
    $all_reviews = $conn->prepare("
        SELECT r.Product_id, r.Order_id, r.Rating, r.Comment,
               u.name AS user_name,
               p.name AS product_name,
               p.image_source AS product_image,
               o.Order_date AS created_at,
               o.user_id
        FROM `review` r
        JOIN `order` o ON r.Order_id = o.Order_id
        JOIN `user`  u ON o.user_id  = u.user_id
        JOIN `product` p ON r.Product_id = p.Product_id
        ORDER BY o.Order_date DESC
    ");
    $all_reviews->execute();
    $reviews = $all_reviews->fetchAll(PDO::FETCH_ASSOC);

    // Fetch products the logged-in user has ordered (for review form dropdown)
    $ordered_products = $conn->prepare("
        SELECT DISTINCT p.Product_id, p.name, p.image_source AS image
        FROM order_item oi
        JOIN `order` o ON oi.Order_id = o.Order_id
        JOIN `product` p ON oi.Product_id = p.Product_id
        WHERE o.user_id = ?
    ");
    $ordered_products->execute([$user_id]);
    $my_products = $ordered_products->fetchAll(PDO::FETCH_ASSOC);

    // Calculate average rating per product
    $avg_ratings = $conn->prepare("
        SELECT Product_id, ROUND(AVG(Rating), 1) AS avg_rating, COUNT(*) AS total
        FROM `review`
        GROUP BY Product_id
    ");
    $avg_ratings->execute();
    $ratings_map = [];
    while ($r = $avg_ratings->fetch(PDO::FETCH_ASSOC)) {
        $ratings_map[$r['Product_id']] = $r;
    }

    // Overall average across all reviews
    $overall = 0;
    if (count($reviews) > 0) {
        $overall = round(array_sum(array_column($reviews, 'Rating')) / count($reviews), 1);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <meta name="description" content="Read and leave reviews for our Coffee Shop products.">
    <title>Coffee Shop - Reviews</title>
    <link rel="stylesheet" href="../assets/css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/review.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/footer.css?v=<?php echo time(); ?>">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../assets/includes/header.php'); ?>

    <div style="height: 80px; width: 100%;"></div>

    <div class="main">

        <!-- ===== PAGE HERO ===== -->
        <section class="review-hero">
            <div class="hero-overlay"></div>
            <div class="hero-text">
                <span class="hero-tag">What Our Customers Say</span>
                <h1>Customer Reviews</h1>
                <p>Honest thoughts from coffee lovers just like you</p>
            </div>
            <!-- Stats bar -->
            <div class="stats-bar">
                <div class="stat">
                    <span class="stat-num"><?php echo count($reviews); ?></span>
                    <span class="stat-label">Total Reviews</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <span class="stat-num"><?php echo $overall; ?></span>
                    <span class="stat-label">Average Rating</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <span class="stat-num"><?php echo count($my_products); ?></span>
                    <span class="stat-label">Products You Can Review</span>
                </div>
            </div>
        </section>

        <!-- ===== LEAVE A REVIEW ===== -->
        <section class="leave-review">
            <div class="section-title">
                <h2>Leave a Review</h2>
                <p>Share your experience with a product you've purchased</p>
            </div>

            <?php if (empty($my_products)): ?>
                <div class="no-products">
                    <i class="bx bx-coffee"></i>
                    <p>You haven't purchased any products yet.</p>
                    <a href="view_product.php" class="btn">Browse Products</a>
                </div>
            <?php else: ?>
            <form action="" method="post" class="review-form" id="reviewForm">

                <!-- Product selector -->
                <div class="form-group">
                    <label>Select a Product <span>*</span></label>
                    <div class="product-selector">
                        <?php foreach ($my_products as $prod): ?>
                        <label class="product-card-radio">
                            <input type="radio" name="product_id" value="<?php echo $prod['Product_id']; ?>" required>
                            <div class="product-card-inner">
                                <img src="../assets/images/productimages/<?php echo $prod['image']; ?>" alt="item image">
                                <span><?php echo htmlspecialchars($prod['name']); ?></span>
                                <?php if (isset($ratings_map[$prod['Product_id']])): ?>
                                    <small>★ <?php echo $ratings_map[$prod['Product_id']]['avg_rating']; ?>
                                    (<?php echo $ratings_map[$prod['Product_id']]['total']; ?> reviews)</small>
                                <?php else: ?>
                                    <small>No reviews yet</small>
                                <?php endif; ?>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Star rating -->
                <div class="form-group">
                    <label>Your Rating <span>*</span></label>
                    <div class="star-rating">
                        <input type="radio" name="rating" id="star5" value="5"><label for="star5"><i class="bx bxs-star"></i></label>
                        <input type="radio" name="rating" id="star4" value="4"><label for="star4"><i class="bx bxs-star"></i></label>
                        <input type="radio" name="rating" id="star3" value="3"><label for="star3"><i class="bx bxs-star"></i></label>
                        <input type="radio" name="rating" id="star2" value="2"><label for="star2"><i class="bx bxs-star"></i></label>
                        <input type="radio" name="rating" id="star1" value="1"><label for="star1"><i class="bx bxs-star"></i></label>
                    </div>
                    <div class="rating-label" id="ratingLabel">Click a star to rate</div>
                </div>

                <!-- Comment -->
                <div class="form-group">
                    <label>Your Review <span>*</span></label>
                    <textarea name="comment" rows="5" maxlength="500"
                        placeholder="Tell others what you think about this product..." required></textarea>
                    <div class="char-count"><span id="charCount">0</span>/500</div>
                </div>

                <button type="submit" name="submit_review" class="btn submit-btn">
                    <i class="bx bx-send"></i> Submit Review
                </button>
            </form>
            <?php endif; ?>
        </section>

        <!-- ===== ALL REVIEWS ===== -->
        <section class="all-reviews">
            <div class="section-title">
                <h2>All Reviews</h2>
                <p>See what our coffee community is saying</p>
            </div>

            <!-- Filter bar -->
            <div class="filter-bar">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="5">★★★★★ 5 Stars</button>
                <button class="filter-btn" data-filter="4">★★★★ 4 Stars</button>
                <button class="filter-btn" data-filter="3">★★★ 3 Stars</button>
                <button class="filter-btn" data-filter="2">★★ 2 Stars</button>
                <button class="filter-btn" data-filter="1">★ 1 Star</button>
            </div>

            <?php if (empty($reviews)): ?>
                <div class="no-reviews">
                    <i class="bx bx-comment-x"></i>
                    <p>No reviews yet. Be the first to share your experience!</p>
                </div>
            <?php else: ?>
            <div class="reviews-grid" id="reviewsGrid">
                <?php foreach ($reviews as $review): ?>
                <div class="review-card" data-rating="<?php echo $review['Rating']; ?>">
                    <div class="review-header">
                        <div class="reviewer-avatar">
                            <?php echo strtoupper(substr($review['user_name'], 0, 1)); ?>
                        </div>
                        <div class="reviewer-info">
                            <h4><?php echo htmlspecialchars($review['user_name']); ?></h4>
                            <span class="review-date">
                                <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                            </span>
                        </div>
                        <!-- Stars display -->
                        <div class="stars-display">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bx bxs-star <?php echo $i <= $review['Rating'] ? 'filled' : 'empty'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Product tag -->
                    <div class="review-product-tag">
                        <i class="bx bxs-coffee"></i>
                        <?php echo htmlspecialchars($review['product_name']); ?>
                    </div>

                    <!-- Comment -->
                    <p class="review-comment">"<?php echo htmlspecialchars($review['Comment']); ?>"</p>

                    <!-- Rating badge -->
                    <div class="rating-badge rating-<?php echo $review['Rating']; ?>">
                        <?php
                            $labels = [1=>'Poor', 2=>'Fair', 3=>'Good', 4=>'Great', 5=>'Excellent'];
                            echo $labels[$review['Rating']];
                        ?>
                    </div>

                    <?php if ($review['user_id'] == $user_id): ?>
                        <div class="your-review-tag"><i class="bx bx-check-circle"></i> Your Review</div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>

    </div><!-- end .main -->

    <?php include('../assets/includes/footer.php'); ?>
    <?php include('../assets/includes/alert.php'); ?>

   <script>
    // ===== STAR RATING LABEL =====
    const ratingLabels = {1:'Poor', 2:'Fair', 3:'Good', 4:'Great', 5:'Excellent'};
    document.querySelectorAll('.star-rating input').forEach(input => {
        input.addEventListener('change', () => {
            document.getElementById('ratingLabel').textContent =
                '★ ' + ratingLabels[input.value] + ' — ' + input.value + '/5';
        });
    });

    // ===== CHARACTER COUNTER =====
    const textarea = document.querySelector('textarea[name="comment"]');
    if (textarea) {
        textarea.addEventListener('input', () => {
            document.getElementById('charCount').textContent = textarea.value.length;
        });
    }

    // ===== FILTER REVIEWS =====
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filter;
            document.querySelectorAll('.review-card').forEach(card => {
                card.style.display =
                    (filter === 'all' || card.dataset.rating === filter) ? 'block' : 'none';
            });
        });
    });

    // FIX 3: JS escaping to prevent XSS
    function escapeHTML(str) {
        return String(str).replace(/[&<>"']/g, tag => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;',
            '"': '&quot;', "'": '&#39;'
        }[tag]));
    }

    // ===== AJAX FORM SUBMISSION =====
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = reviewForm.querySelector('.submit-btn');
            const formData  = new FormData(reviewForm);

            if (!formData.get('product_id')) {
                showToast('Please select a product.', 'warning'); return;
            }
            if (!formData.get('rating')) {
                showToast('Please select a star rating.', 'warning'); return;
            }
            if (!formData.get('comment').trim()) {
                showToast('Please write a review comment.', 'warning'); return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Submitting...';

            // FIX 2: use relative path with ./
            fetch('./submit_review.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    reviewForm.reset();
                    document.getElementById('ratingLabel').textContent = 'Click a star to rate';
                    document.getElementById('charCount').textContent   = '0';

                    addReviewCard(data.review);
                    updateStats();
                } else {
                    showToast(data.message, 'warning');
                }
            })
            .catch(() => showToast('Something went wrong. Please try again.', 'warning'))
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bx bx-send"></i> Submit Review';
            });
        });
    }

    // ===== ADD REVIEW CARD TO GRID =====
    function addReviewCard(review) {
        let grid = document.getElementById('reviewsGrid');

        // If grid doesn't exist yet (no reviews before), create it
        if (!grid) {
            const noReviews = document.querySelector('.no-reviews');
            if (noReviews) noReviews.remove();
            grid = document.createElement('div');
            grid.className = 'reviews-grid';
            grid.id = 'reviewsGrid';
            document.querySelector('.all-reviews').appendChild(grid);
        }

        const stars = Array.from({length: 5}, (_, i) =>
            `<i class="bx bxs-star ${i < review.rating ? 'filled' : 'empty'}"></i>`
        ).join('');

        const labels = {1:'Poor', 2:'Fair', 3:'Good', 4:'Great', 5:'Excellent'};

        const card = document.createElement('div');
        card.className = 'review-card';
        card.dataset.rating = review.rating;
        card.innerHTML = `
            <div class="review-header">
                <div class="reviewer-avatar">${escapeHTML(review.user_name.charAt(0).toUpperCase())}</div>
                <div class="reviewer-info">
                    <h4>${escapeHTML(review.user_name)}</h4>
                    <span class="review-date">${escapeHTML(review.date)}</span>
                </div>
                <div class="stars-display">${stars}</div>
            </div>
            <div class="review-product-tag">
                <i class="bx bxs-coffee"></i> ${escapeHTML(review.product_name)}
            </div>
            <p class="review-comment">"${escapeHTML(review.comment)}"</p>
            <div class="rating-badge rating-${review.rating}">${labels[review.rating]}</div>
            <div class="your-review-tag"><i class="bx bx-check-circle"></i> Your Review</div>
        `;
        grid.prepend(card);
    }

    // FIX 6: Update stats bar dynamically after submission
    function updateStats() {
        const statNums = document.querySelectorAll('.stat-num');

        // Total reviews = first stat
        const totalEl = statNums[0];
        if (totalEl) totalEl.textContent = parseInt(totalEl.textContent) + 1;

        // Recalculate average from all visible cards
        const allRatings = [...document.querySelectorAll('.review-card')]
            .map(c => parseInt(c.dataset.rating));
        if (allRatings.length > 0) {
            const avg = (allRatings.reduce((a, b) => a + b, 0) / allRatings.length).toFixed(1);
            const avgEl = statNums[1];
            if (avgEl) avgEl.textContent = avg;
        }
    }

    // ===== TOAST NOTIFICATION =====
    function showToast(message, type) {
        const existing = document.querySelector('.ajax-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = 'ajax-toast';
        toast.style.cssText = `
            position: fixed; bottom: 30px; right: 30px;
            padding: 14px 24px; border-radius: 10px;
            font-size: 0.95rem; font-weight: 600;
            z-index: 9999; box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            background: ${type === 'success' ? '#d6ffd6' : '#fff3cd'};
            color:       ${type === 'success' ? '#066f06' : '#856404'};
            border: 1px solid ${type === 'success' ? '#83c583' : '#ffc107'};
            transition: opacity 0.4s ease;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }, 3500);
    }
    </script>
</body>
</html>