<!DOCTYPE html>
<header class="main-header">
    <link rel="stylesheet" href="..\assets\css\header.css">
    
    <div class="header-left">
        <!-- LOGO -->
        <a href="../pages/home.php" class="logo">
            <img src="..\assets\images\header_icon\coffee_logo.svg" alt="Coffee Shop Logo">
            <span>Coffee Shop</span>
        </a>
    </div>
</header>
    <!-- Prototype code for testing -->
<?php
//admin authentication
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}

$admin_name = $_SESSION['user_name'] ?? 'Admin';

include('../backend/database/dbconnect.php');

//image upload
if (isset($_POST['submit'])) {
  $filename = $_FILES["file"]["name"];
  $tempname = $_FILES["file"]["tmp_name"];
  $folder = "../assets/images/menu/" . $filename;

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

  $item_name = filter_var($_POST["pName"], FILTER_SANITIZE_SPECIAL_CHARS);
  $price = filter_var($_POST["pPrice"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $desc = filter_var($_POST["pDescription"], FILTER_SANITIZE_SPECIAL_CHARS);

  $query = "INSERT INTO menu_items (item_name, image, description, price) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "sssd", $item_name, $filename, $desc , $price);
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
  <link rel="stylesheet" href="../assets/css/review.css">
</head>

<body>
  <form action="" method="POST" enctype="multipart/form-data" >
    <div class="box" style="width:360px;height:500px;border:.5px;margin: 0 auto;">

      &nbsp;<br>*Product Name<br>&nbsp;
      <input name="pName" class="input" size="36" style="height:35px" type="text" required>

      <br><br>&nbsp;Purchase Price<br>&nbsp;
      <input name="pPrice" class="input" size="36" style="height:35px" min="0" type="float" placeholder="0.00">

      <br><br>&nbsp;Description<br>&nbsp;
      <input name="pDescription" class="input" size="36" style="height:35px" min="0" type="text">

      <br><br>&nbsp;Product Image<br>&nbsp;
      <input name="file" class="input" size="36" style="height:35px" type="file" accept="image/*">

      <br><br>&nbsp;&nbsp;&nbsp;

      <button type="submit" name="submit" class="sbmt"> Submit </button>

      <br><br>
    </div>
  </form>
  <br><br><br><br><br><br>
</body>

</html>