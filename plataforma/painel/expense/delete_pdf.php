<?php
include "conexao.php"; 


if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql = "SELECT pdf_path FROM granna80_bdlinks.spends WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pdf_path = $row['pdf_path'];

    
        $sql = "UPDATE granna80_bdlinks.spends SET pdf_path = NULL WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "PDF deleted successfully!";

        
            if (file_exists($pdf_path)) {
                unlink($pdf_path); 
            }

            echo "<script>window.location.href='edit.php?id=" . $id . "';</script>";
           
            exit;
        } else {
            echo "Error deleting PDF: " . $conn->error;
        }
    } else {
        echo "Record not found!";
    }
} else {
    echo "Invalid request!";
}

$conn->close();
?>
