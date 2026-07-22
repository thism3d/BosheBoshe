<?php


$servername = "localhost";
$username = "bosheboshe_udtxasd";
$password = "@RGYhjfasdtU1245";
$dbname = "bosheboshe_userdatabase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

    header('Location: ' . $serverhost .'/logout.php');
    exit();
}

//echo "Connected";
?>
