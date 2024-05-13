<?php
include 'conf/dbconn.php';
session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
  exit;
}

$sessionndp = $_SESSION['ndp'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$bengkel = $_SESSION['bengkel'];
$type = $_SESSION['type'];
$ismpp = $_SESSION['ismpp'];
$jawatan = $_SESSION['jawatan'];
$isvoted = $_SESSION['isvoted'];
$imgsession = $_SESSION['img'];

    $stmt = $conn->prepare("SELECT img FROM user WHERE ndp = ?");
    $stmt->bind_param("s", $sessionndp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
      $imageDataSession = $row['img']; 

      $imageSrcSession = 'data:image/png;base64,' . base64_encode($imageDataSession);
    } else {
      $imageSrcSession = 'assets/img/user.png'; 
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home</title>
<link rel="stylesheet" href="assets/styles/style.css">
<script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
<nav class="navbar">
    <div class="navbar-left">
      <img src="assets/img/adtecmelaka.jpeg" alt="Logo" class="logo">
    </div>
    <div class="navbar-right">
      <ul>
        <li><a href="home.php">Home</a></li>
        <?php
        if ($type === 'staff'){
        ?>
        <li><a href="managempp.php">Manage</a></li>
        <?php
        }
        ?>
        <li><a href="voting_system.php">Voting System</a></li>
        <li><a href="settings.php">Settings</a></li>
        <li><a href="function/logout.php">Log Out</a></li>
      </ul>
    </div>
</nav>
  <div class="hero-section">
    <table>
      <tr rowspan="2">
        <th>WELCOME! </th>
        <th><?php echo $username ?></th>
      </tr>
      <tr>
        <td>
          <img style="border-radius: 30px; display: block; margin: 0 auto;" src="<?php echo $imageSrcSession ?>" alt="User Image" width="100px">
        </td>
        <td>
              <p>Type: <?php echo $type ?></p>
              <p>No Ndp: <?php echo $sessionndp ?></p>
              <p>Course: <?php echo $bengkel ?></p>
        </td>
      </tr>
    </table>
  </div>
  <div class="organization-chart p-5">
  <?php
  $sql_jawatan = "SELECT * FROM jawatan WHERE jenisjawatan <> '-' ORDER BY bil ASC";
  $result_jawatan = $conn->query($sql_jawatan);

  if ($result_jawatan->num_rows > 0) {
    while ($row_jawatan = $result_jawatan->fetch_assoc()) {
      $jenisjawatan = $row_jawatan['jenisjawatan'];

      $sql_users = "SELECT * FROM user WHERE jawatan = '$jenisjawatan'";
      $result_users = $conn->query($sql_users);

      if ($result_users->num_rows > 0) {
        while ($row_user = $result_users->fetch_assoc()) {
          $username = $row_user['username'];
          $type = $row_user['type'];
          $ndp = $row_user['ndp'];
          $bengkel = $row_user['bengkel'];
          $imgProfile = $row_user['img'];
          
          if ($imgProfile) {
            $imageData = base64_encode($imgProfile);
            $imageSrcMpp = 'data:image/png;base64,' . $imageData;
          } else {
            $imageSrcMpp = 'assets/img/user.png'; 
          }

          ?>
          <div class="chart-node">
            <div class="node"><?php echo $jenisjawatan; ?></div>
            <div class="user-card">
            <img src="<?php echo $imageSrcMpp ?>" alt="" width="50px" class="rounded-full mx-auto">
              <h3>Username: <?php echo $username; ?></h3>
              <p>Type: <?php echo $type; ?></p>
              <p>NDP: <?php echo $ndp; ?></p>
              <p>Bengkel: <?php echo $bengkel; ?></p>
            </div>
          </div>
          <?php
        }
      } else {
        ?>
        <div class="chart-node">
          <div class="node"><?php echo $jenisjawatan; ?></div>
          <div class="no-user">No user for this job position yet.</div>
        </div>
        <?php
      }
    }
  } else {
    echo "No job positions found.";
  }
  ?>
</div>

</body>
</html>
