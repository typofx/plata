<?php
$jsonFilePath = __DIR__ . DIRECTORY_SEPARATOR . "crypto_data.json";

// Verifica se o arquivo existe e é legível
if (file_exists($jsonFilePath) && is_readable($jsonFilePath)) {
    // Obtém o conteúdo do arquivo JSON
    $jsonData = file_get_contents($jsonFilePath);

    // Verifica se a leitura foi bem-sucedida
    if ($jsonData !== false) {
        // Tenta decodificar o JSON
        $data = json_decode($jsonData, true);

        // Verifica se a decodificação foi bem-sucedida
        if ($data !== null) {
            // Imprime o JSON decodificado
            echo json_encode($data);
        } else {
            // Tratamento de erro para falha na decodificação JSON
            echo "Erro ao decodificar JSON.";
        }
    } else {
        // Tratamento de erro para falha na leitura do arquivo
        echo "Erro ao ler o arquivo JSON.";
    }
} else {
    // Tratamento de erro para arquivo inexistente ou não legível
    echo "Arquivo JSON não encontrado ou não legível.";
}
?>
