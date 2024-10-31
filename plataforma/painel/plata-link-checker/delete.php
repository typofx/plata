<?php
// Verifica se o usuário está logado
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';

// Include database connection file
include 'conexao.php';

// Check if an ID was passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the record
    $deleteQuery = "DELETE FROM granna80_bdlinks.plata_link_checker WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        // Redirect to index page after deletion
        echo "<script>window.location.href='index.php?msg=Record deleted successfully.'</script>";
        exit();
    } else {
        // Redirect to index page with an error message if deletion failed
        echo "<script>window.location.href='index.php?msg=Error deleting record: " . mysqli_error($conn) . "'</script>";
        exit();
    }
} else {
    // Redirect to index page if no ID is specified
    echo "<script>window.location.href='index.php?msg=Invalid request.'</script>";
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
