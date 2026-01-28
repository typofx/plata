<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'listingplatform.conexao.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM granna80_bdlinks.links WHERE ID = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully.";
        echo "<script>window.location.href='index.php';</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
