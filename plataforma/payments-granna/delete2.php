<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
?>

<?php
include 'conexao.php';

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Emails a serem excluídos
$emails = ["uarloque@live.com", "softgamebr4@gmail.com"];

// Prepara a declaração SQL com placeholders
$stmt = $conn->prepare("DELETE FROM granna80_bdlinks.granna_payments WHERE email = ?");

// Verifica se a preparação da declaração foi bem-sucedida
if ($stmt === false) {
    die("Erro na preparação da declaração: " . $conn->error);
}

// Liga o parâmetro e executa a declaração para cada email
foreach ($emails as $email) {
    $stmt->bind_param("s", $email);
    if ($stmt->execute() === false) {
        echo "Erro ao executar para o email $email: " . $stmt->error . "\n";
    } else {
        echo "Registro com o email $email excluído com sucesso.\n";
    }
}

// Fecha a declaração e a conexão
$stmt->close();
$conn->close();

// Redireciona para index.php após a execução
echo "<script>window.location.href = 'index.php';</script>";
exit();
?>

