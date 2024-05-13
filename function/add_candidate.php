<?php
include '../conf/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userSelect'])) {
  $selected_ndp = $_POST['userSelect'];

  $update_sql = "UPDATE user SET ismpp = 'Yes', jawatan = '-' WHERE ndp = '$selected_ndp'";

  if ($conn->query($update_sql) === TRUE) {
    echo "<script>alert('Candidate added successfully.'); window.location.href = '../managempp.php';</script>";
  } else {
    echo "Error adding candidate: " . $conn->error;
  }
} else {
  echo "Invalid request.";
}

$conn->close(); 
?>
