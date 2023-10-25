<?php
$servername = "localhost";
$username = "granna80_user";
$password = "D72r5vEgt5";


$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    echo "falhou";
    die("Conexao falhou: " . $conn->connect_error);
}
?>

