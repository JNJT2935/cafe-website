<!DOCTYPE html>
<header class="main-header">
    <link rel="stylesheet" href="\assets\css\header.css">
    
    <div class="header-left">
        <!-- LOGO -->
        <a href="pages/Home_Page.php" class="logo">
            <img src="..\assets\images\header_icon\coffee_logo.svg" alt="Coffee Shop Logo">
            <span>Coffee Shop</span>
        </a>
    </div>

    <!-- Prototype code for testing -->
<?php
//temporary database implementation
include('./Database/database.php');

//image upload
if (isset($_POST['submit'])) {
  $filename = $_FILES["file"]["name"];
  $tempname = $_FILES["file"]["tmp_name"];
  $folder = "../images/productimages" . $filename;

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
  $category = filter_var($_POST["categories"], FILTER_SANITIZE_SPECIAL_CHARS);
  if ($category !== "Hot Drinks") or ($category !=="Cold Drinks") or ($category !=="Dessert") or ($category !=="Pastries"){
    die("Category not selected/invalid");
  }
  $product_name = filter_var($_POST["pName"], FILTER_SANITIZE_SPECIAL_CHARS);
  $sale_price = filter_var($_POST["pPrice"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $stock = filter_var($_POST["stock"], FILTER_SANITIZE_NUMBER_INT);
  $id = filter_var($_POST["Product_id"], FILTER_SANITIZE_NUMBER_INT);
  $desc = filter_var($_POST["pDescription"], FILTER_SANITIZE_SPECIAL_CHARS);

  $query = "INSERT INTO products (Product_id, pName, Visible_on_website, category, productImage, pDescription, stock_quantity) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "isdddsiii", $id, $product_name, FALSE, $category, $filename, $desc , $stock);

  if (mysqli_stmt_execute($stmt)) {
    header("Location: listing.php");
  } else {
    echo "Error occurred while inserting data.";
  }
  mysqli_stmt_close($stmt);
}

?>
<html>

<head>
  <title>products</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <form action="" method="POST" enctype="multipart/form-data" >
    <div class="box" style="width:360px;height:500px;border:.5px;margin: 0 auto;">

      &nbsp;<br>*Product Number<br>&nbsp;
      <input name="Product_id" class="input" size="36" style="height:35px" type="text" maxlength="40" required placeholder="0">

      &nbsp;<br>*Product Name<br>&nbsp;
      <input name="pName" class="input" size="36" style="height:35px" type="text" maxlength="40" required>

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
        <option value="Hot Drinks">Hot Drinks</option>
        <option value="Cold Drinks">Cold Drinks</option>
        <option value="Dessert">Dessert</option>
        <option value="Pastries">Pastries</option>
      </select>

      <br><br>&nbsp;&nbsp;&nbsp;

      <button type="submit" name="submit" class="sbmt"> Submit </button>

      <br><br>
    </div>
  </form>
  <br><br><br><br><br><br>
</body>

</html>