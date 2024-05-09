<?php
$servername = "localhost";
$username = "";
$password = "";


$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    echo "error";
    die("Error: " . $conn->connect_error);
}
?>
