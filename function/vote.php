<?php
session_start(); 
include '../conf/dbconn.php'; 

if (!isset($_SESSION['ndp'])) {
  header("Location: ../login.php");
  exit();
}

$sessionndp = $_SESSION['ndp'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ndp'])) {
  $ndp = $_POST['ndp'];

  $sql = "SELECT * FROM user WHERE ndp='$ndp'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    $user_data = $result->fetch_assoc();

    if ($user_data['ismpp'] == 'Yes') {
      $update_sql = "UPDATE user SET votecount = votecount + 1 WHERE ndp = '$ndp'";
      if ($conn->query($update_sql) === TRUE) {
        $update_vote = "UPDATE user SET isvoted = 1 WHERE ndp = '$sessionndp'";
        if ($conn->query($update_vote) === TRUE) {
          echo "<script>alert('Vote Successfully! Please Sign in again'); window.location.href = 'logout.php';</script>";
        } else {
          echo "Error updating vote status: " . $conn->error;
        }
      } else {
        echo "Error updating vote count: " . $conn->error;
      }
    } else {
      echo "You cannot vote for this user.";
    }
  } else {
    echo "User not found.";
  }
} else {
  echo "Invalid request.";
}

$conn->close();
?>
