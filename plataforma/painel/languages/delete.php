<?php  include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
// Include the file for database connection
include 'conexao.php';

// Check if the IDs were passed
if (isset($_GET['id_en']) && isset($_GET['id_es']) && isset($_GET['id_pt'])) {
    // Get the IDs passed via GET
    $id_en = $_GET['id_en'];
    $id_es = $_GET['id_es'];
    $id_pt = $_GET['id_pt'];

    // Delete data from the English table
    $sql_delete_en = "DELETE FROM granna80_bdlinks.plata_texts WHERE id = $id_en";
    $conn->query($sql_delete_en);

    // Delete data from the Spanish table
    $sql_delete_es = "DELETE FROM granna80_bdlinks.plata_texts WHERE id = $id_es";
    $conn->query($sql_delete_es);

    // Delete data from the Portuguese table
    $sql_delete_pt = "DELETE FROM granna80_bdlinks.plata_texts WHERE id = $id_pt";
    $conn->query($sql_delete_pt);

    // Redirect after delete
    echo "<script>window.location.href = 'index.php';</script>"; // Redirect
    exit();
} else {
    echo "Error: Missing IDs";
    exit();
}
?>
