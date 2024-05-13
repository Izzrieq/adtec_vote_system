<?php
error_reporting(0);
include "conf/dbconn.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$ndp = $username = $password = $confirm_password = $type = $bengkel = "";
$ndp_err = $username_err = $password_err = $confirm_password_err = $type_err = $bengkel_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["ndp"]))) {
        $ndp_err = "Please enter a NDP.";
    } else {
        $ndp = trim($_POST["ndp"]);
    }
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a Name.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "<p style='color: red;'>Password must have at least 6 characters *</p>";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "<p style='color: red;'>Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "<p style='color: red;'>Password did not match.";
        }
    }

    if (empty(trim($_POST["bengkel"]))) {
        $bengkel_err = "<p style='color: red;'>Please choose a bengkel.";
    } else {
        $bengkel = trim($_POST["bengkel"]);
    }

    if (empty($ndp_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($type_err) && empty($bengkel_err)) {
        if (substr($ndp, 0, 2) == '00') {
            $type = 'staff'; 
        } else {
            $type = 'pelajar'; 
        }

        $id_query = "SELECT MAX(id) AS max_id FROM user";
        $result = mysqli_query($conn, $id_query);
        $row = mysqli_fetch_assoc($result);
        $new_id = $row['max_id'] + 1;

        $ismpp = 'No';
        $jawatan = '-';
        $votecount = 0;
        $isvoted = 0;
        $img = '';

        $insert_query = "INSERT INTO user (id, ndp, username, password, type, bengkel, ismpp, jawatan, votecount, isvoted, img) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($insert_stmt = mysqli_prepare($conn, $insert_query)) {
            mysqli_stmt_bind_param($insert_stmt, 'isssssssiss', $new_id, $ndp, $username, $password, $type, $bengkel, $ismpp, $jawatan, $votecount, $isvoted, $img);

            if (mysqli_stmt_execute($insert_stmt)) {
                echo "<script>alert('User registered successfully.'); window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Error registering user. Error: " . mysqli_error($conn) . "'); window.location.href = 'register.php';</script>";
            }

            mysqli_stmt_close($insert_stmt);
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href = 'register.php';</script>";
        }


    }
}

$bengkel_query = "SELECT * FROM bengkel";
$bengkel_result = mysqli_query($conn, $bengkel_query);
$bengkel = mysqli_fetch_all($bengkel_result, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New User</title>
    <script defer src="https://unpkg.com/alpinejs@3.2.3/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-neutral-50 m-0 p-0 font-large font-sans">
    <header class="d-flex justify-content-between bg-white ">
        <div class="w-25 p-0 h-25 d-inline-block">
            <a href="index.php">
                <img class="w-full m-0 h-100 d-inline-block" src="assets/img/adtecmelaka.jpeg" alt="logo">
            </a>
        </div>
        <div class="p-0">
            <h1 class="m-3 text-primary">ADTEC MPP E-VOTE SYSTEM</h1>
        </div>
    </header>

    <div class="container-box" style="display: flex; justify-content:center;">
        <div class="bg-white px-6 py-3 text-black" style="width: 45%;">
            <h2 class="text-2xl text-center">Register New User</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div>
                    <label>NDP:</label>
                    <input type="text" name="ndp" class="block border border-grey-light w-full p-2 rounded mb-2" value="<?php echo $ndp; ?>" oninput="checkNdpType(this)">
                    <span><?php echo $ndp_err; ?></span>
                </div>
                <div>
                    <label>Type:</label>
                    <input type="text" name="type" id="type" class="block border border-grey-light w-full p-2 rounded mb-2" value="<?php echo $type; ?>" readonly>
                    <span><?php echo $type_err; ?></span>
                </div>
                <div>
                    <label>User Name:</label>
                    <input type="text" name="username" class="block border border-grey-light w-full p-2 rounded mb-2" value="<?php echo $username; ?>">
                    <span><?php echo $username_err; ?></span>
                </div>
                <div>
                    <label>Password:</label>
                    <input type="password" name="password" class="block border border-grey-light w-full p-2 rounded mb-2" value="<?php echo $password; ?>">
                    <span><?php echo $password_err; ?></span>
                </div>
                <div>
                    <label>Confirm Password:</label>
                    <input type="password" name="confirm_password" class="block border border-grey-light w-full p-2 rounded mb-2" value="<?php echo $confirm_password; ?>">
                    <span><?php echo $confirm_password_err; ?></span>
                </div>
                <div>
                    <label>bengkel:</label>
                    <select name="bengkel" class="block border border-grey-light w-full p-2 rounded mb-2">
                        <?php foreach ($bengkel as $beng) { ?>
                            <option value="<?php echo $beng['jenisbengkel']; ?>" <?php echo ($bengkel == $beng['jenisbengkel']) ? 'selected' : ''; ?>>
                                <?php echo $beng['jenisbengkel']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <span><?php echo $bengkel_err; ?></span>
                </div>
                <div class="w-full px-6 py-3 text-black">
                    <input type="submit" value="Register" class="text-white w-full bg-sky-400 hover:bg-opacity-75 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:ring-offset-2 p-2">
                </div>
            </form>
        </div>
    </div>
    <script>
        function checkNdpType(input) {
            var ndpValue = input.value.trim();
            var typeInput = document.getElementById('type');

            if (ndpValue.startsWith('00')) {
                typeInput.value = 'staff'; 
            } else {
                typeInput.value = 'pelajar'; 
            }
        }
    </script>

</body>

</html>
