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

// Fetch vote counts for the bar chart
$voteStmt = $conn->prepare("SELECT username, votecount FROM user WHERE type = 'pelajar' AND ismpp = 'yes' AND jawatan = '-'");
$voteStmt->execute();
$voteResult = $voteStmt->get_result();

$userVotes = [];
while ($voteRow = $voteResult->fetch_assoc()) {
  $userVotes[] = $voteRow;
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    .container{
        background-color: white;
        margin: auto;
        margin-top: 20px;
        width: 100%;
    }
    h1{
        font-size: 30px;
        font-weight: 600;
        text-align: center;
    }
</style>
<body>
<nav class="navbar">
    <div class="navbar-left">
        <h1>SISTEM E-UNDIAN ADTEC MELAKA</h1>
    </div>
    <div class="navbar-right">
      <ul>
        <li><a href="home.php">Home</a></li>
        <?php
        if ($type === 'pengajar'||$type === 'admin'){
        ?>
        <li><a href="managempp.php">Manage</a></li>
        <li><a href="senaraipelajar.php">Senarai Pelajar</a></li>
        <?php
        }
        ?>
        <li><a href="voting_system.php">Voting System</a></li>
        <li><a href="settings.php">Settings</a></li>
        <li><a href="function/logout.php">Log Out</a></li>
      </ul>
    </div>
</nav>

<div class="container">
    <h1>Voting Chart</h1>
  <canvas id="voteChart"></canvas>
</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const ctx = document.getElementById('voteChart').getContext('2d');
    
    const userVotes = <?php echo json_encode($userVotes); ?>;
    const labels = userVotes.map(user => user.username);
    const data = userVotes.map(user => user.votecount);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Vote Count',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
</body>
</html>
