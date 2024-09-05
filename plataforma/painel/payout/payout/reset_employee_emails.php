<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';


if (isset($_GET['employee_id'])) {
    $employee_id = intval($_GET['employee_id']); 


    $update_sql = "UPDATE granna80_bdlinks.work_weeks SET email_sent = 0 WHERE employee_id = ?";
    $update_stmt = $conn->prepare($update_sql);

    if ($update_stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $update_stmt->bind_param('i', $employee_id);

    if ($update_stmt->execute()) {
        echo 'Coluna email_sent reiniciada com sucesso para o employee_id ' . $employee_id;
        echo "<script>window.location.href='work_weeks.php?employee_id=" . $employee_id . "';</script>";
    } else {
        echo 'Erro ao reiniciar a coluna email_sent: ' . htmlspecialchars($update_stmt->error);
    }

    $update_stmt->close();
} else {
    echo 'Nenhum employee_id fornecido.';
}

$conn->close();
?>
