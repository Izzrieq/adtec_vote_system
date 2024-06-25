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

$limit = 10; 
if (isset($_GET["page"])) {
  $page  = $_GET["page"]; 
} else { 
  $page = 1; 
};  
$start_from = ($page - 1) * $limit;  

$userStmt = $conn->prepare("SELECT * FROM user WHERE type = 'pelajar' ORDER BY bengkel LIMIT ?, ?");
$userStmt->bind_param("ii", $start_from, $limit);
$userStmt->execute();
$userResult = $userStmt->get_result();

$totalRecordsStmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE type = 'pelajar'");
$totalRecordsStmt->execute();
$totalRecordsResult = $totalRecordsStmt->get_result();
$totalRecords = $totalRecordsResult->fetch_array()[0];
$totalPages = ceil($totalRecords / $limit);
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
<style>
.container {
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

    .table th, .table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #f2f2f2;
    }

    .pagination {
        margin: 20px 0;
        display: flex;
        justify-content: center;
    }

    .pagination a {
        margin: 0 5px;
        padding: 10px 15px;
        text-decoration: none;
        color: #007bff;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .pagination a.active {
        background-color: #007bff;
        color: white;
        border: 1px solid #007bff;
    }

    .pagination a:hover {
        background-color: #ddd;
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
        <?php if ($type === 'pengajar'||$type === 'admin'): ?>
        <li><a href="managempp.php">Manage</a></li>
        <li><a href="senaraipelajar.php">Senarai Pelajar</a></li>
        <?php endif; ?>
        <li><a href="voting_system.php">Voting System</a></li>
        <li><a href="settings.php">Settings</a></li>
        <li><a href="function/logout.php">Log Out</a></li>
      </ul>
    </div>
</nav>

<div class="container">
    <h2 style="background-color: white; color: black; padding: 10px;">Senarai Pelajar</h2>
    <table class="table">
        <thead>
            <tr>
                <th>NDP</th>
                <th>Username</th>
                <th>Bengkel</th>
                <th>Type</th>
                <th>Jawatan</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($userRow = $userResult->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($userRow['ndp']); ?></td>
                <td><?php echo htmlspecialchars($userRow['username']); ?></td>
                <td><?php echo htmlspecialchars($userRow['bengkel']); ?></td>
                <td><?php echo htmlspecialchars($userRow['type']); ?></td>
                <td><?php echo htmlspecialchars($userRow['jawatan']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="senaraipelajar.php?page=<?php echo $i; ?>" class="<?php if ($page == $i) { echo 'active'; } ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</div>

</body>
</html>
