<?php
session_start();
include "../conf/dbconn.php"; 

$ndp = $_POST['ndp'];
$password = $_POST['password'];

$ndp = mysqli_real_escape_string($conn, $ndp);
$password = mysqli_real_escape_string($conn, $password);

$sql = "SELECT * FROM user WHERE ndp = '$ndp'";
$result = $conn->query($sql);

if ($result === false) {
    echo "Query error: " . $conn->error;
    exit;
}

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    if ($password == $row['password']) { 
        $_SESSION['logged_in'] = true;
        $_SESSION['password'] = $row['password'];
        $_SESSION['ndp'] = $row['ndp'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['type'] = $row['type'];
        $_SESSION['bengkel'] = $row['bengkel'];
        $_SESSION['ismpp'] = $row['ismpp'];
        $_SESSION['jawatan'] = $row['jawatan'];
        $_SESSION['isvoted'] = $row['isvoted'];
        $_SESSION['img'] = $row['img'];

        // Determine redirect URL based on user type
        $redirectUrl = "../home.php";
        if ($row['type'] == 'pelajar' || $row['type'] == 'pengajar' || $row['type'] == 'admin') {
            echo "<script>
                    alert('Login successful!');
                    window.location.href = '$redirectUrl';
                  </script>";
        } else {
            echo "<script>
                    alert('Invalid user type.');
                    window.location.href = '../index.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Invalid login credentials.');
                window.location.href = '../index.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid login credentials.');
            window.location.href = '../index.php';
          </script>";
}
?>
