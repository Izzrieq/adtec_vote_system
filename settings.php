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

$ndp = $_SESSION['ndp'];
$result = mysqli_query($conn, "SELECT * FROM user WHERE ndp = '$ndp'");
if ($r = mysqli_fetch_array($result)) {
  $password = $_SESSION['password'];
  $ndp = $_SESSION['ndp'];
  $username = $_SESSION['username'];
  $bengkel = $_SESSION['bengkel'];
  $type = $_SESSION['type'];
  $ismpp = $_SESSION['ismpp'];
  $jawatan = $_SESSION['jawatan'];
  $isvoted = $_SESSION['isvoted'];

  $stmt = $conn->prepare("SELECT img FROM user WHERE ndp = ?");
  $stmt->bind_param("s", $ndp);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $imageDataSession = $row['img']; 

    $imageSrcSession = 'data:image/png;base64,' . base64_encode($imageDataSession);
  } else {
    $imageSrcSession = 'assets/img/user.png'; 
  }
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
        <li><a href="function/logout.php">Logout</a></li>
      </ul>
    </div>
  </nav>
  <form action="function/setting-function.php" method="POST" enctype="multipart/form-data">
    <div class="container-box" style="display: flex; justify-content:center;">
      <div class="bg-white rounded-lg min-h-screen pt-0 my-0 mb-12">
        <div class="container mx-auto">
          <div class="inputs w-full max-w-xl px-6 py-3">
            <div class='flex items-center justify-between mt-2'>
              <div class="personal w-full">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Profile</h2>
                <div class="col-span-full">
                  <div class="mt-2 flex items-center gap-x-3">
                    <img class="rounded-image h-12 w-12 rounded-full" src="<?php echo $imageSrcSession ?>" alt="User Image"
                      width="50px">
                    <input type="file" name="img" id="fileInput" style="display: none;">
                    <button id="changeImageButton" type="button"
                      class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                      onclick="uploadFile()">
                      Change Profile Picture
                    </button>
                  </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                  <div class='w-full md:w-1/2 px-3 mb-3'>
                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>STAFF
                      NDP
                    </label>
                    <input name="ndp" type="text"
                      class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-3 leading-tight focus:outline-none focus:border-gray-500'
                      value="<?php echo $ndp; ?>" />
                  </div>
                  <div class='w-full md:w-1/2 px-3 mb-3'>
                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Username
                    </label>
                    <input name="username" type="text"
                      class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-3 leading-tight focus:outline-none focus:border-gray-500'
                      value="<?php echo $username; ?>" />
                  </div>
                </div>
                <div class='w-full md:w-full px-3 mb-3'>
                  <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Password
                  </label>
                  <input name="password" type="text"
                    class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-3 leading-tight focus:outline-none focus:border-gray-500'
                    value="<?php echo $password; ?>" />
                </div>
                <div class='w-full md:w-full px-3 mb-3'>
                  <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Bengkel
                  </label>
                  <input name="bengkel" type="text" readonly
                    class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-3 leading-tight focus:outline-none focus:border-gray-500'
                    value="<?php echo $bengkel; ?>" />
                </div>
                <div class='w-full md:w-full px-3 mb-3'>
                  <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Mpp
                  </label>
                  <input name="ismpp" type="text" readonly
                    class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-3 leading-tight focus:outline-none focus:border-gray-500'
                    value="<?php echo $ismpp; ?>" />
                </div>
                <div class="flex items-center justify-between mt-4">
                  <div class='w-full md:w-1/2 px-3 mb-3'>
                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Jawatan
                    </label>
                    <input name="jawatan" type="text" readonly
                      class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-3 leading-tight focus:outline-none focus:border-gray-500'
                      value="<?php echo $jawatan; ?>" />
                  </div>
                </div>
                <button type="submit" name="update" class="rounded-md bg-green-700 text-white p-2 mt-2">SAVE CHANGES
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
  </form>


</body>
<script>
  function uploadFile() {
    document.getElementById('fileInput').click();

    document.getElementById('fileInput').addEventListener('change', function () {
      var file = this.files[0];

      if (file) {
        console.log('Selected file:', file.name);
      }
    });
  }
</script>

</html>