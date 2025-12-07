<!DOCTYPE html>
<header class="main-header">
    <link rel="stylesheet" href="..\assets\css\header.css">
    
    <div class="header-left">
        <!-- LOGO -->
        <a href="../pages/Home_Page.php" class="logo">
            <img src="..\assets\images\header_icon\coffee_logo.svg" alt="Coffee Shop Logo">
            <span>Coffee Shop</span>
        </a>
    </div>
</header>
    <!-- Prototype code for testing -->
<?php

include('../backend/database/database.php');

//image upload
if (isset($_POST['submit'])) {
  $filename = $_FILES["file"]["name"];
  $tempname = $_FILES["file"]["tmp_name"];
  $folder = "../assets/images/productimages/" . $filename;

  if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
    if ($_FILES["file"]["error"] == 4) {
      $filename = '';
    } else {
      die("File upload failed with error code: " . $_FILES["file"]["error"]);
    }
  }

  if (move_uploaded_file($tempname, $folder)) {
    echo "Image uploaded successfully";
  } else {
    echo "Failed to upload image";
  }

  //Sanitize form input
  $category = filter_var($_POST["categories"], FILTER_SANITIZE_NUMBER_INT);
  if ($category == 1){
    $categorytext = "Hot Drinks";
  } elseif ($category == 2){
    $categorytext == "Cold Drinks";
  } elseif ($category == 3){
    $categorytext == "Dessert";
  } elseif ($category == 4){
    $categorytext == "Pastries";
  }  else{
    die("Category not selected/invalid");
  }
  $product_name = filter_var($_POST["pName"], FILTER_SANITIZE_SPECIAL_CHARS);
  $price = filter_var($_POST["pPrice"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $stock = filter_var($_POST["stock"], FILTER_SANITIZE_NUMBER_INT);
  $id = filter_var($_POST["Product_id"], FILTER_SANITIZE_NUMBER_INT);
  $desc = filter_var($_POST["pDescription"], FILTER_SANITIZE_SPECIAL_CHARS);

  $query = "INSERT INTO product (Product_id, name, category, image_source, description, stock_quantity, price) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "issssid", $id, $product_name, $category, $filename, $desc , $stock, $price);
  if (mysqli_stmt_execute($stmt)) {
    header("Location: ../pages/Home_Page.php");
  } else {
    echo "Error occurred while inserting data.";
  }
  mysqli_stmt_close($stmt);
}

?>
<html>

<head>
  <title>Add products</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <form action="" method="POST" enctype="multipart/form-data" >
    <div class="box" style="width:360px;height:500px;border:.5px;margin: 0 auto;">

      &nbsp;<br>*Product Number<br>&nbsp;
      <input name="Product_id" class="input" size="36" style="height:35px" type="text" maxlength="11" required placeholder="0">

      &nbsp;<br>*Product Name<br>&nbsp;
      <input name="pName" class="input" size="36" style="height:35px" type="text" required>

      <br><br>&nbsp;Purchase Price<br>&nbsp;
      <input name="pPrice" class="input" size="36" style="height:35px" min="0" type="float" placeholder="0.00">

      <br><br>&nbsp;Description<br>&nbsp;
      <input name="pDescription" class="input" size="36" style="height:35px" min="0" type="text">

      <br><br>&nbsp;Stock Status<br>&nbsp;
      <input name="stock" class="input" size="36" style="height:35px" min="0" type="int" placeholder="0">

      <br><br>&nbsp;Product Image<br>&nbsp;
      <input name="file" class="input" size="36" style="height:35px" type="file" accept="image/*">

      <br><br>&nbsp;Category<br>&nbsp;
      <select name="categories" id="categories" style="width:254px;background-color:#f1f6fa;border-radius:10px;color:#303841;" required>
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