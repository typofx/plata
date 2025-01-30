<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include "conexao.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];


    if (is_numeric($id)) {

        $query = "DELETE FROM granna80_bdlinks.plata_footer WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($result) {

            echo "<script>window.location.href='index.php';</script>";
            exit();
        } else {
 
            die("Error deleting item: " . mysqli_error($conn));
        }
    } else {

        die("Invalid ID.");
    }
} else {
  
    die("ID not specified.");
}
?>