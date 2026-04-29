<?php
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "coffeeshop";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>