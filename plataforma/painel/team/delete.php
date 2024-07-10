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
// Check if a valid ID is provided in the URL
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Include database configuration file
    include "conexao.php";

    // SQL query to delete the team member with the provided ID
    $sql = "DELETE FROM granna80_bdlinks.team WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
      
      echo '<script>window.location.href="index.php";</script>'; 

    } else {
        echo "Error deleting team member: " . $conn->error;
    }

    // Close database connection
    $conn->close();
} else {
    echo "Invalid member ID.";
}
?>
