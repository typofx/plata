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

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to delete the payment with the specified ID
    $sql = "DELETE FROM granna80_bdlinks.dex_liquidity WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Clear output buffer
        ob_clean();
        // Redirect back to the home page (index.php)
        echo "<script>window.location.href = 'index.php';</script>";
        exit(); // Ensure script doesn't continue after redirection
    } else {
        echo "Error deleting data: " . $stmt->error;
    }
} else {
    echo "ID not specified";
}

$conn->close();
?>
