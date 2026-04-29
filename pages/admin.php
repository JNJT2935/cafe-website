<?php
session_start();
include('../assets/includes/connect.php');

// Only allow admins
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}

$admin_name = $_SESSION['user_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f4e8d8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: #2d1810;
            color: #fff;
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.3rem;
            font-weight: 700;
            color: #d4a373;
            text-decoration: none;
        }

        .topbar .logo i { font-size: 1.6rem; }

        .topbar .admin-info {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 0.95rem;
            color: rgba(255,255,255,0.8);
        }

        .topbar .admin-info span { color: #d4a373; font-weight: 600; }

        .logout-btn {
            background: #c44536;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            transition: background 0.2s;
        }

        .logout-btn:hover { background: #a33; }

        /* ===== MAIN ===== */
        .dashboard {
            flex: 1;
            max-width: 1000px;
            margin: 3rem auto;
            padding: 0 20px;
            width: 100%;
        }

        .welcome {
            text-align: center;
            margin-bottom: 3rem;
        }

        .welcome h1 {
            font-size: 2rem;
            color: #2d1810;
            margin-bottom: 0.5rem;
        }

        .welcome p {
            color: #6b4423;
            font-size: 1rem;
        }

        /* ===== CARDS ===== */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.8rem;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            text-align: center;
            text-decoration: none;
            color: #2d1810;
            box-shadow: 0 4px 20px rgba(107,68,35,0.12);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 35px rgba(107,68,35,0.2);
            border-color: #d4a373;
        }

        .card .icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #fff;
        }

        .card.orders  .icon { background: #6b4423; }
        .card.add     .icon { background: #137d00; }
        .card.update  .icon { background: #c27e00; }

        .card h3 {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .card p {
            font-size: 0.88rem;
            color: #888;
            line-height: 1.6;
        }

        .card .arrow {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: #d4a373;
            font-weight: 600;
        }

        /* ===== FOOTER ===== */
        .dash-footer {
            text-align: center;
            padding: 1.5rem;
            color: #aaa;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <!-- Top Bar -->
    <div class="topbar">
        <a href="../pages/home.php" class="logo">
            <i class="fa-solid fa-mug-hot"></i> Coffee Shop
        </a>
        <div class="admin-info">
            Welcome, <span><?php echo htmlspecialchars($admin_name); ?></span>
            <a href="../pages/logout.php" class="logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>

    <!-- Dashboard -->
    <div class="dashboard">

        <div class="welcome">
            <h1>Admin Dashboard</h1>
            <p>Manage your coffee shop from here.</p>
        </div>

        <div class="cards">

            <a href="admin_manage_order.php" class="card orders">
                <div class="icon"><i class="fa-solid fa-box"></i></div>
                <h3>Manage Orders</h3>
                <p>View, update, and track all customer orders and delivery statuses.</p>
                <span class="arrow">Go to Orders →</span>
            </a>

            <a href="addproducts.php" class="card add">
                <div class="icon"><i class="fa-solid fa-plus"></i></div>
                <h3>Add Product</h3>
                <p>Add new coffee drinks, snacks, or other items to the menu.</p>
                <span class="arrow">Add Product →</span>
            </a>

            <a href="updateproduct.php" class="card update">
                <div class="icon"><i class="fa-solid fa-pen-to-square"></i></div>
                <h3>Update Product</h3>
                <p>Edit prices, stock, descriptions, or hide existing products.</p>
                <span class="arrow">Update Product →</span>
            </a>

        </div>
    </div>

    <div class="dash-footer">
        &copy; <?php echo date('Y'); ?> Coffee Shop Admin Panel
    </div>

</body>
</html>