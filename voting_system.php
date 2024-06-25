<?php
include 'conf/dbconn.php';
session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  echo "<script>alert('You must log in first.'); window.location.href = 'index.php';</script>";
  exit;
}

$ndp = $_SESSION['ndp'];
$username = $_SESSION['username'];
$bengkel = $_SESSION['bengkel'];
$type = $_SESSION['type'];
$ismpp = $_SESSION['ismpp'];
$jawatan = $_SESSION['jawatan'];
$isvoted = $_SESSION['isvoted'];

$sql = "SELECT * FROM user WHERE ismpp = 'yes' AND jawatan = '-'";
$result = $conn->query($sql);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    if($isvoted === '0'){
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Voting System</title>
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
        if ($type === 'admin' || $type === 'pengajar'){
        ?>
        <li><a href="managempp.php">Manage</a></li>
        <li><a href="senaraipelajar.php">Senarai Pelajar</a></li>
        <?php
        }
        ?>
        <li><a href="voting_system.php">Voting System</a></li>
        <li><a href="settings.php">Settings</a></li>
        <li><a href="function/logout.php">Logout</a></li>
      </ul>
    </div>
  </nav>
  <h1>Welcome <?php echo $username ?></h1>
  <div class="users-container">
  <?php
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="user-card">
          <h3><?php echo $row['username']; ?></h3>
          <p>Type: <?php echo $row['type']; ?></p>
          <p>NDP: <?php echo $row['ndp']; ?></p>
          <p>Bengkel: <?php echo $row['bengkel']; ?></p>
          <p>Vote: <?php echo $row['votecount']; ?></p>
          <form action="function/vote.php" method="POST">
            <input type="hidden" name="ndp" value="<?php echo $row['ndp']; ?>">
            <button type="submit">Vote</button>
          </form>
        </div>
        <?php
    }
  } else {
    echo "<script>alert('No candidate at this moment.'); window.location.href = 'home.php';</script>";
  }
  ?>
</div>
</body>
</html>
<?php
    }
    else {
      echo "<script>alert('You already vote.'); window.location.href = 'home.php';</script>";
    }
  ?>
