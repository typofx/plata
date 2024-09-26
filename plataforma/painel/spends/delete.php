<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
// Include the database connection file
include 'conexao.php';

// Check if an ID was provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the input to avoid SQL injection

    // Prepare the SQL statement
    $sql = "DELETE FROM granna80_bdlinks.spends WHERE id = ?";
    
    // Use prepared statements to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id); // Bind the ID parameter

        // Execute the statement
        if ($stmt->execute()) {
            // Record deleted successfully
            echo "Record deleted successfully.";
            echo "<script>window.location.href='index.php';</script>";
        } else {
            // Error in execution
            echo "Error deleting record: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "No ID provided to delete.";
}

// Close the database connection
$conn->close();

// Redirect back to the index page after a delay
echo "<script>window.location.href='index.php';</script>";
exit();
?>
