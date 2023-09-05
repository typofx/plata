<?php
session_start(); // Start the session

// Check if the user is authenticated
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // User is not authenticated, redirect back to the login page
    header("Location: index.php");
    exit();
}
include '../conexao.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM granna80_bdlinks.links WHERE ID = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
