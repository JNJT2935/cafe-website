<?php
session_start();
// Only allow admins
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}
if (isset($_SESSION['message'])) {
    echo '<p>' . $_SESSION['message'] . '</p>';
    unset($_SESSION['message']);
}
include('../backend/database/db.php');

if (isset($_POST['submit'])) {
  $product_name = filter_var($_POST["pName"], FILTER_SANITIZE_SPECIAL_CHARS);
  $price = filter_var($_POST["pPrice"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $stock = filter_var($_POST["stock"], FILTER_SANITIZE_NUMBER_INT);
  $id = filter_var($_POST["Product_id"], FILTER_SANITIZE_NUMBER_INT);
  $desc = filter_var($_POST["pDescription"], FILTER_SANITIZE_SPECIAL_CHARS);
  $visible = filter_var($_POST["Visible"], FILTER_SANITIZE_NUMBER_INT);
  $category = filter_var($_POST["categories"], FILTER_SANITIZE_NUMBER_INT);

  if ($category == 1){
    $categorytext = "Hot Drinks";
  } elseif ($category == 2){
    $categorytext = "Cold Drinks";
  } elseif ($category == 3){
    $categorytext = "Dessert";
  } elseif ($category == 4){
    $categorytext = "Pastries";
  } elseif ($category == ""){
    $query = "SELECT category FROM product WHERE Product_id = $id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $category = $row["category"];
      }
    }
  }  else{
    die("Category not selected/invalid");
  }
  if ($product_name == ""){
    $query = "SELECT name FROM product WHERE Product_id = $id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $product_name = $row["name"];
      }
    }
  }
  if ($desc == ""){
    $query = "SELECT description FROM product WHERE Product_id = $id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $desc = $row["description"];
      }
    }
  }
  if ($stock == ""){
    $query = "SELECT stock_quantity FROM product WHERE Product_id = $id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $stock = $row["stock_quantity"];
      }
    }
  }
  if ($price == ""){
    $query = "SELECT price FROM product WHERE Product_id = $id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $price = $row["price"];
      }
    }
  }


  $query = "UPDATE product SET name = ?, category = ?, description = ?, stock_quantity = ?, price = ?, Visible_on_website = ? WHERE Product_id = ?;";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "sssidii", $product_name, $category, $desc , $stock, $price, $visible, $id);
  if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    $user_id = $_SESSION['user_id'];
    $modification_status = "Updated";
    $query = "INSERT INTO manage_product (user_id, Product_id, modification_status) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iis", $user_id, $id, $modification_status);
    mysqli_stmt_execute($stmt);
    $_SESSION['message'] = "Success! Your entry has been updated.";
    header("Location: " . $_SERVER['PHP_SELF']);
  } else {
    echo "Error occurred while updating data.";
  }
  mysqli_stmt_close($stmt);
}
?>

<html>

<header class="main-header">
    <link rel="stylesheet" href="..\assets\css\header.css">
    
    <div class="header-left">
        <!-- LOGO -->
        <a href="pages/Home_Page.php" class="logo">
            <img src="..\assets\images\header_icon\coffee_logo.svg" alt="Coffee Shop Logo">
            <span>Coffee Shop</span>
        </a>
    </div>
</header>

<head>
  <title>Update products</title>
  <link rel="stylesheet" href="../assets/css/review.css">
</head>

<body>
  <h1 style="text-align:center;">Write the product number for which the product is updated</h1> 
  <form action="" method="POST" enctype="multipart/form-data" >
    <div class="box" style="width:360px;height:500px;border:.5px;margin: 0 auto;">

      &nbsp;<br>*Product Number<br>&nbsp;
      <input name="Product_id" class="input" size="36" style="height:35px" type="text" maxlength="11" required placeholder="0">

      &nbsp;<br>*Product Name<br>&nbsp;
      <input name="pName" class="input" size="36" style="height:35px" type="text">

      <br><br>&nbsp;Purchase Price<br>&nbsp;
      <input name="pPrice" class="input" size="36" style="height:35px" min="0" type="float" placeholder="0.00">

      <br><br>&nbsp;Description<br>&nbsp;
      <input name="pDescription" class="input" size="36" style="height:35px" min="0" type="text">

      <br><br>&nbsp;Stock Status<br>&nbsp;
      <input name="stock" class="input" size="36" style="height:35px" min="0" type="int" placeholder="0">

      <br><br>&nbsp;Toggle delete<br>&nbsp;
      <select name="Visible" id="Visible" style="width:254px;background-color:#f1f6fa;border-radius:10px;color:#303841;" required>
        <option value=1>Keep listing</option>
        <option value=0>Hide listing</option>
      </select>

      <br><br>&nbsp;Category<br>&nbsp;
      <select name="categories" id="categories" style="width:254px;background-color:#f1f6fa;border-radius:10px;color:#303841;">
        <option value="">Select a category</option>
        <option value=1>Hot Drinks</option>
        <option value=2>Cold Drinks</option>
        <option value=3>Dessert</option>
        <option value=4>Pastries</option>
      </select>

      <br><br>&nbsp;&nbsp;&nbsp;

      <button type="submit" name="submit" class="sbmt"> Submit </button>

      <br><br>
    </div>
  </form>
  <br><br><br><br><br><br>
</body>

</html>
