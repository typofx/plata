<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}
?>

<?php
include 'conexao.php';

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Emails to be deleted
$emails = ["uarloque@live.com", "softgamebr4@gmail.com"];

// Prepare the SQL statement with placeholders
$stmt = $conn->prepare("DELETE FROM granna80_bdlinks.payments WHERE email = ?");

// Check if the statement preparation was successful
if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error);
}

// Bind the parameter and execute the statement for each email
foreach ($emails as $email) {
    $stmt->bind_param("s", $email);
    if ($stmt->execute() === false) {
        echo "Error executing for email $email: " . $stmt->error . "\n";
    } else {
        echo "Record with email $email deleted successfully.\n";
    }
}

// Close the statement and the connection
$stmt->close();
$conn->close();

// Redirect to index.php after execution
echo "<script>window.location.href = 'index.php';</script>";
exit();
?>
