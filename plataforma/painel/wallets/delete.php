<?php
// Include the database connection file
include 'conexao.php';

// Check if 'id' is passed via URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convert 'id' to an integer to prevent SQL injection

    // Prepare the SQL query to delete the wallet by ID
    $sql = "DELETE FROM granna80_bdlinks.wallets WHERE id = ?";

    // Initialize prepared statement
    $stmt = $conn->prepare($sql);

    // Bind the 'id' parameter
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to the wallets list with a success message
  echo 'Wallet deleted successfully';
        echo "<script>window.location.href='index.php';</script>";
        exit;
    } else {
        // In case of failure, show an error
        echo "Error deleting wallet: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // If 'id' is not provided, redirect back with an error message
    header("Location: index.php?message=No wallet ID provided");
    exit;
}

// Close the database connection
$conn->close();
?>
