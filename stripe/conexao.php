<?php
$servername = "localhost";
$username = "";
$password = "";


$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    echo "Error";
    die("Error: " . $conn->connect_error);
}
?>