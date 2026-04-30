<?php
include('../backend/database/dbconnect.php');

//sanitise form ionput

if (isset($_POST['submit'])) {
  $product_name = filter_var($_POST["pName"], FILTER_SANITIZE_SPECIAL_CHARS);
  $price = filter_var($_POST["pPrice"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $id = filter_var($_POST["item_id"], FILTER_SANITIZE_NUMBER_INT);
  $desc = filter_var($_POST["pDescription"], FILTER_SANITIZE_SPECIAL_CHARS);

  //Make sure that leaving spaces blank will not erase fields

  if ($product_name == ""){
    $query = "SELECT item_name FROM menu_items WHERE item_id = $id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $product_name = $row["item_name"];
      }
    }
  }
  if ($desc == ""){
    $query = "SELECT description FROM menu_items WHERE item_id = $id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $desc = $row["description"];
      }
    }
  }
  if ($price == ""){
    $query = "SELECT price FROM menu_items WHERE item_id = $id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $price = $row["price"];
      }
    }
  }


  $query = "UPDATE menu_items SET item_name = ?, description = ?, price = ? WHERE item_id = ?;";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "ssdi", $product_name, $desc, $price, $id);
  if (mysqli_stmt_execute($stmt)) {
    echo "Record updated successfully";
  } else {
    echo "Error occurred while updating data.";
  }
  mysqli_stmt_close($stmt);
}
?>

<html>

<header class="main-header">
    <link rel="stylesheet" href="..\assets\css\review.css">
    
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
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <h1 style="text-align:center;">Write the product number for which the product is to be updated</h1> 
  <form action="" method="POST" enctype="multipart/form-data" >
    <div class="box" style="width:360px;height:500px;border:.5px;margin: 0 auto;">

      &nbsp;<br>*Product Number<br>&nbsp;
      <input name="item_id" class="input" size="36" style="height:35px" type="text" maxlength="11" required placeholder="0">

      &nbsp;<br>Product Name<br>&nbsp;
      <input name="pName" class="input" size="36" style="height:35px" type="text">

      <br><br>&nbsp;Purchase Price<br>&nbsp;
      <input name="pPrice" class="input" size="36" style="height:35px" min="0" type="float" placeholder="0.00">

      <br><br>&nbsp;Description<br>&nbsp;
      <input name="pDescription" class="input" size="36" style="height:35px" min="0" type="text">


      <br><br>&nbsp;&nbsp;&nbsp;

      <button type="submit" name="submit" class="sbmt"> Submit </button>

      <br><br>
    </div>
  </form>
  <br><br><br><br><br><br>
</body>

</html>
