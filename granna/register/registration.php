<?php
session_start();


if (!isset($_SESSION['user_email'])) {
    echo "<p style='color: red;'>Session expired. Please come back and enter your email.</p>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_SESSION['user_email'];
    $password = $_POST["password"];
    $name = $_POST["name"];
    $lastName = $_POST["last_name"];
    $birth = $_POST["birth"];
    $ddi = $_POST["ddi"];
    $phone = $_POST["phone"];
    $username = $name . '_' . $lastName;


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

   
    $sql = "INSERT INTO granna80_bdlinks.granna_users (name, last_name, email, birth, ddi, phone, password, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $name, $lastName, $email, $birth, $ddi, $phone, $hashed_password, $username);


    if ($stmt->execute()) {

        echo "<p style='color: green;'>Registration completed successfully!</p>";
        session_unset();  
        session_destroy();  

        //echo "<script>window.location.href = 'welcome.php';</script>";
    } else {
        echo "<p style='color: red;'>Error registering. Please try again.</p>";
    }

    $stmt->close();
    $conn->close();
}
