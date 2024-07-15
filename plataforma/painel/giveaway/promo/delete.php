<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Delete Promo Code</title>
</head>
<body>
<?php
// Include database connection file
include 'conexao.php';

// Check if ID is passed via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to delete promo code based on ID
    $sql = "DELETE FROM granna80_bdlinks.promo_codes WHERE id = $id";
    
    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        // Redirect to the homepage after deletion
        echo '<script>window.location.href = "index.php";</script>';
        exit();
    } else {
        echo "Error deleting promo code: " . $conn->error;
    }
} else {
    echo "Promo code ID not specified.";
}

// Close database connection
$conn->close();
?>
</body>
</html>
