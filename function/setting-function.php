<?php
include '../conf/dbconn.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
  exit;
}

if (isset($_POST['update'])) {
  $ndp = $_SESSION['ndp']; 
  $password = $_POST['password'];
  $username = $_POST['username'];
  $bengkel = $_POST['bengkel'];
  $ismpp = $_POST['ismpp'];
  $jawatan = $_POST['jawatan'];

  if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
    $imgData = file_get_contents($_FILES['img']['tmp_name']);
    $imgBase64 = base64_encode($imgData);

    $updateImgQuery = "UPDATE user SET img = '$imgBase64' WHERE ndp = '$ndp'";
    mysqli_query($conn, $updateImgQuery);
  }

  $updateQuery = "UPDATE user SET ndp = '$ndp', password = '$password', username = '$username', bengkel = '$bengkel', ismpp = '$ismpp', jawatan = '$jawatan' WHERE ndp = '$ndp'";
  mysqli_query($conn, $updateQuery);

  echo "<script>alert('Success Update Setting! Please Log in again.'); window.location.href = '../index.php';</script>";
  exit;
} else {
  echo "<script>alert('Request failed, Please try again.'); window.location.href = '../home.php';</script>";
  exit;
}
?>
