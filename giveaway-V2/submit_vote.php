<?php
session_start(); // Iniciar a sessão
include "conexao.php"; 
ob_start(); include '../en/mobile/price.php'; ob_end_clean();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $_SESSION['error'] = "Access denied.";
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

if (!isset($_POST['evm_wallet']) || !isset($_POST['vote_number'])) {
    $_SESSION['error'] = "Missing form data.";
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

if ($conn->connect_error) {
    $_SESSION['error'] = "Connection failed: " . $conn->connect_error;
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

$evm_wallet = $_POST['evm_wallet'];
$vote_number = $_POST['vote_number'];

if (!preg_match('/^0x[a-fA-F0-9]{40}$/', $evm_wallet)) {
    $_SESSION['error'] = "Invalid EVM address.";
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

$check_sql = "SELECT COUNT(*) AS count FROM granna80_bdlinks.votes WHERE evm_wallet = '$evm_wallet'";
$result = $conn->query($check_sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row["count"] > 0) {
        $_SESSION['error'] = "This Ethereum wallet address has already been registered.";
        echo "<script>window.location.href='index.php';</script>";
        exit();
    }
} else {
    $_SESSION['error'] = "Error checking database for existing wallet address.";
   echo "<script>window.location.href='index.php';</script>";
    exit();
}

if ($vote_number != 0) {
    $check_vote_sql = "SELECT COUNT(*) AS count FROM granna80_bdlinks.votes WHERE vote_number = '$vote_number'";
    $vote_result = $conn->query($check_vote_sql);
    if ($vote_result && $vote_result->num_rows > 0) {
        $vote_row = $vote_result->fetch_assoc();
        if ($vote_row["count"] > 0) {
            $_SESSION['error'] = "A vote with the same number already exists.";
           echo "<script>window.location.href='index.php';</script>";
            exit();
        }
    } else {
        $_SESSION['error'] = "Error checking database for existing vote.";
       echo "<script>window.location.href='index.php';</script>";
        exit();
    }
}

$target_dir = "uploads/";
$imageFileType = strtolower(pathinfo($_FILES["vote_image"]["name"], PATHINFO_EXTENSION));
$unique_filename = uniqid('PLATA_', true) . '_' . rand(1000, 9999) . '.' . $imageFileType;
$target_file = $target_dir . $unique_filename;

if (!isset($_FILES["vote_image"]) || $_FILES["vote_image"]["size"] == 0) {
    $target_file = "platatoken400px.png";
} else {
    $check = getimagesize($_FILES["vote_image"]["tmp_name"]);
    if($check === false) {
        $_SESSION['error'] = "File is not an image.";
       echo "<script>window.location.href='index.php';</script>";
        exit();
    }
    if ($_FILES["vote_image"]["size"] > 10000000) { // 10MB
        $_SESSION['error'] = "Sorry, your file is too large.";
       echo "<script>window.location.href='index.php';</script>";
        exit();
    }
    if (!move_uploaded_file($_FILES["vote_image"]["tmp_name"], $target_file)) {
        $_SESSION['error'] = "Sorry, there was an error uploading your file.";
       echo "<script>window.location.href='index.php';</script>";
        exit();
    }
}

$sql = "INSERT INTO granna80_bdlinks.votes (evm_wallet, vote_image, vote_number, MATICPLT, MATICUSD) VALUES ('$evm_wallet', '$target_file', '$vote_number', '$MATICPLT', '$MATICUSD')";

if ($conn->query($sql) === TRUE) {
    $_SESSION['success'] = "New record created successfully";
} else {
    $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: index.php");
exit();
?>
