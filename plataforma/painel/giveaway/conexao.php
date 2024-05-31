<?php
$servername = "localhost";
$username = "";
$password = "";


$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    echo "ERROR";
    die("ERROR" . $conn->connect_error);
}
?>

