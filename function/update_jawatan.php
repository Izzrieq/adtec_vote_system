<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../conf/dbconn.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $ndp = $_POST['ndp'];

  if (isset($_POST['update_jawatan'])) {
    $new_jawatan_id = $_POST['jawatan']; // Assuming this is the job position ID

    // Check if the user is authorized (you can modify this condition based on your requirements)
    if ($_SESSION['type'] === 'staff') {
      // Verify that the new jawatan_id exists in the jawatan table
      $verify_sql = "SELECT * FROM jawatan WHERE jenisjawatan = '$new_jawatan_id'";
      $verify_result = $conn->query($verify_sql);

      if ($verify_result->num_rows > 0) {
        // Update job position ID in the database
        $update_sql = "UPDATE user SET jawatan = '$new_jawatan_id' WHERE ndp = '$ndp'";
        if ($conn->query($update_sql) === TRUE) {
          echo "<script>alert('Job position updated successfully.'); window.location.href = '../home.php';</script>";
        } else {
          echo "Error updating job position: " . $conn->error;
        }
      } else {
        echo "Invalid job position ID.";
      }
    } else {
      echo "You are not authorized to update job positions.";
    }
  } elseif (isset($_POST['revoke_jawatan'])) {
    // Revoke the job position and set ismpp to 'No' and jawatan to '-'
    $revoke_sql = "UPDATE user SET ismpp = 'No', jawatan = '-' WHERE ndp = '$ndp'";
    if ($conn->query($revoke_sql) === TRUE) {
      echo "<script>alert('Job position revoked successfully.'); window.location.href = '../home.php';</script>";
    } else {
      echo "Error revoking job position: " . $conn->error;
    }
  } else {
    echo "Invalid request.";
  }
} else {
  echo "Invalid request.";
}

$conn->close(); // Close the database connection
?>
