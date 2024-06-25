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

$sql = "SELECT * FROM user WHERE ismpp = 'yes'";
$result = $conn->query($sql);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Voting System</title>
<link rel="stylesheet" href="assets/styles/style.css">
<script src="https://kit.fontawesome.com/8f1ca98d75.js" crossorigin="anonymous"></script>
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
        if ($type === 'staff' || $type === 'admin'){
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
  <div class="users-container">
  <?php
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $imgUrl = $row['img'];
      if ($imgUrl) {
        $imageData = base64_encode($imgUrl);
        $imageSrc = 'data:image/png;base64,' . $imageData;
      } else {
        $imageSrc = 'assets/img/user.png'; 
      }
      ?>
      <form action="function/update_jawatan.php" method="POST">
        <div class="user-card">
          <img src="<?php echo $imageSrc ?>" alt="" width="100px">
          <div class="user-card-info">
            <h3 class="text-xl"><?php echo $row['username']; ?></h3>
            <input type="text"  class="px-3 py-3 my-2 placeholder-black text-sm" 
            placeholder="Type: <?php echo $row['type']; ?>" readonly> 
            <input type="text"  class="px-3 py-3 my-2 placeholder-black text-sm" 
            placeholder="Ndp: <?php echo $row['ndp']; ?>" readonly> 
            <input type="text"  class="px-3 py-3 my-2 placeholder-black text-sm" 
            placeholder="Bengkel: <?php echo $row['bengkel']; ?>" readonly> 
            <input type="text"  class="px-3 py-3 my-2 placeholder-black text-sm" 
            placeholder="Vote Count: <?php echo $row['votecount']; ?>" readonly> 
            <input type="text"  class="px-3 py-3 placeholder-black text-sm" 
            placeholder="Jawatan: <?php echo $row['jawatan']; ?>" readonly> 
            <select name="jawatan" id="jawatan" class="border-0 px-3 py-3 my-3 placeholder-blueGray-300 
            text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full 
            ease-linear transition-all duration-150">
            <option value="#" disabled selected>Ubah Jawatan</option>
            <?php
            $sql_jawatan = "SELECT * FROM jawatan";
            $result_jawatan = $conn->query($sql_jawatan);
            if ($result_jawatan->num_rows > 0) {
              while ($row_jawatan = $result_jawatan->fetch_assoc()) {
                echo '<option value="' . $row_jawatan['jenisjawatan'] . '">' . $row_jawatan['jenisjawatan'] . '</option>';
              }
            }
            ?>
            </select>
            <button type="submit" name="update_jawatan" class="text-white bg-blue-700 hover:bg-blue-800 
            focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 
            dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none 
            dark:focus:ring-blue-800">Update Jawatan</button>
            <button type="submit" name="revoke_jawatan" class="text-white bg-red-700 hover:bg-red-800 
            focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 
            dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none 
            dark:focus:ring-red-800">Revoke Jawatan</button>
            <input type="hidden" name="ndp" value="<?php echo $row['ndp']; ?>">
          </div>
        </div>
      </form>
      <?php
    }
  } else {
    echo "<p style='text-align: center; color: red;'>No users with Mpp</p>";
  }
  ?>
</div>
<button class="floating-button-chart">
  <span class="floating-button-icon"><a href="chart.php">
    <i class="fa fa-bar-chart"></i>
  </a></span>
</button>
<button class="floating-button" onclick="openModal()">
  <span class="floating-button-icon">+</span>
</button>

<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <form action="function/add_candidate.php" method="POST">
      <h2>Choose User</h2>
      <label for="userSelect">Select User:</label>
      <select id="userSelect" name="userSelect">
        <option value="" disabled selected>Please Select</option>
        <?php
        $sql_users = "SELECT * FROM user WHERE ismpp = 'No' AND type = 'pelajar' And jawatan = '-'";
        $result_users = $conn->query($sql_users);
        if ($result_users->num_rows > 0) {
          while ($row_user = $result_users->fetch_assoc()) {
            echo '<option value="' . $row_user['ndp'] . '">' . $row_user['username'] . '</option>';
          }
        } else {
          echo '<option value="">No candidate at the moment</option>';
        }
        ?>
      </select>
      <button type="submit">Add Candidate</button>
    </form>
  </div>
</div>

  <script>
    function openModal() {
      document.getElementById('myModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('myModal').style.display = 'none';
    }
  </script>
</body>
</html>
