<?php
 include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
include "conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta o ID do serviço a ser deletado
    $id = $_POST['id'];

    // Deleta o serviço do banco de dados
    $query = "DELETE FROM granna80_bdlinks.typofx_services WHERE id='$id'";
    if ($conn->query($query)) {
        echo "Serviço deletado com sucesso!";
    } else {
        echo "Erro ao deletar o serviço: " . $conn->error;
    }
}
?>