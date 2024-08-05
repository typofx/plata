<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
?>

<?php
// Check if a valid ID is provided in the URL
if(isset($_GET['id']) && ($_GET['id'])) {
    $id = base64_decode($_GET['id']);

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
