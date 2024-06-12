<?php 
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

    // Select the database
    $dbname = 'granna80_bdlinks';
    if (!$conn->select_db($dbname)) {
        die("Failed to select database: " . $conn->error);
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete the payment with the specified ID
        $sqlDelete = "DELETE FROM payments_trash WHERE trash_id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $id);

        if (!$stmtDelete->execute()) {
            throw new Exception("Error deleting payment: " . $stmtDelete->error);
        }

        // Commit the transaction
        $conn->commit();

        // Clear output buffer
        ob_clean();
        // Redirect back to the home page (index.php)
        echo "<script>window.location.href = 'index.php';</script>";
        exit(); // Ensure script doesn't continue after redirection

    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }

} else {
    echo "ID not specified";
}

$conn->close();
?>
