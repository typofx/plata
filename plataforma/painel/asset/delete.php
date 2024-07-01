<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../../index.php");
    exit();
}

// Check if the id parameter is passed in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Include the database connection file
    include 'conexao.php';

    // Filter the id to prevent SQL injection
    $id = $_GET['id'];

    // Prepare the SQL query to delete the record
    $sql = "DELETE FROM granna80_bdlinks.assets WHERE id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        trigger_error($conn->error, E_USER_ERROR);
    }

    // Bind parameters and execute the statement
    $stmt->bind_param('i', $id);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        // Redirect back to the payments page
        echo "<script>window.location.href = 'index.php';</script>";
    } else {
        echo "Error deleting record.";
    }

    // Close the statement
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid or unspecified ID.";
}
?>
