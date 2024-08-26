<?php
include 'conexao.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql = "DELETE FROM payout WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "ID not specified!";
    exit();
}

$conn->close();
?>


