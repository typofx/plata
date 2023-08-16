<?php
session_start(); // Iniciar a sessão

// Verificar se o usuário está autenticado
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // O usuário não está autenticado, redirecionar de volta para a página de login
    header("Location: index.php");
    exit();
}
include '../conexao.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM granna80_bdlinks.links WHERE ID = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
