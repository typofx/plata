<?php
 include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
// Inclui o arquivo de conexão com o banco de dados
include "conexao.php";

// Função para registrar logs de erro
function logError($message) {
    $logFile = __DIR__ . '/error_log.txt'; // Arquivo de log no mesmo diretório do script
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $id = $_POST['id'];
    $name = $_POST['name'];
    $link = $_POST['link'];
    $date = $_POST['date'];
    $created_by = $_POST['created_by'];
    $visible = $_POST['visible'];

    // Verifica se uma nova imagem foi enviada
    $logo = null; // Inicializa a variável para o nome da imagem
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        // Configurações do upload
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-services/';
        $target_file = $upload_dir . basename($_FILES["logo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verifica se o diretório de upload existe
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                logError("Erro: Não foi possível criar o diretório de upload: $upload_dir");
                die("Erro: Não foi possível criar o diretório de upload.");
            }
        }

        // Permite apenas arquivos JPEG, PNG e SVG
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'svg'])) {
            logError("Erro: Apenas arquivos JPG, JPEG, PNG e SVG são permitidos. Tipo enviado: $imageFileType");
            die("Erro: Apenas arquivos JPG, JPEG, PNG e SVG são permitidos.");
        }

        // Verifica se o arquivo é uma imagem (exceto SVG)
        if ($imageFileType !== 'svg') {
            $check = getimagesize($_FILES["logo"]["tmp_name"]);
            if ($check === false) {
                logError("Erro: O arquivo enviado não é uma imagem válida.");
                die("Erro: O arquivo enviado não é uma imagem válida.");
            }
        }

        // Move o arquivo para o diretório de uploads
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
            $logo = basename($_FILES["logo"]["name"]);
            logError("Sucesso: Arquivo $logo movido para $target_file.");
        } else {
            $error = error_get_last();
            logError("Erro ao mover o arquivo: " . $error['message']);
            die("Erro: Falha ao mover o arquivo para o diretório de upload.");
        }
    }

    // Monta a query de atualização
    if ($logo) {
        // Se uma nova imagem foi enviada, atualiza o campo `logo`
        $query = "UPDATE granna80_bdlinks.typofx_services 
                  SET name='$name', logo='$logo', link='$link', date='$date', created_by='$created_by', visible='$visible' 
                  WHERE id='$id'";
    } else {
        // Se nenhuma nova imagem foi enviada, mantém a imagem atual
        $query = "UPDATE granna80_bdlinks.typofx_services 
                  SET name='$name', link='$link', date='$date', created_by='$created_by', visible='$visible' 
                  WHERE id='$id'";
    }

    // Executa a query
    if ($conn->query($query)) {
        echo "Serviço atualizado com sucesso!";
    } else {
        logError("Erro ao executar a query: " . $conn->error);
        echo "Erro ao atualizar o serviço: " . $conn->error;
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>