<?php

header('Content-Type: application/json');

// Valida se a data foi recebida
if (!isset($_GET['price_date']) || empty($_GET['price_date'])) {
    echo json_encode(['success' => false, 'message' => 'A data (Price Date) não foi fornecida.']);
    exit();
}

try {
    $target_date = new DateTime($_GET['price_date']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'O formato da data fornecida é inválido.']);
    exit();
}

// Busca e decodifica os dados do JSON
$json_url = "https://typofx.ie/plataforma/panel/token-historical-data/token_data.json";
$json_data = @file_get_contents($json_url);

if ($json_data === false) {
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar o arquivo de dados históricos.']);
    exit();
}

$historical_data = json_decode($json_data, true);

if (!is_array($historical_data)) {
    echo json_encode(['success' => false, 'message' => 'O conteúdo da URL não é um JSON válido.']);
    exit();
}

// Encontra o registro com a data mais recente em ou antes da data que você enviou
$best_match = null;
$smallest_diff = PHP_INT_MAX; // Um número inteiro muito grande para iniciar a comparação

foreach ($historical_data as $record) {
    if (isset($record['date']) && isset($record['price'])) {
        try {
            $record_date = new DateTime($record['date']);

            // Considera apenas datas ANTERIORES ou IGUAIS à data que você enviou
            if ($record_date <= $target_date) {
                // Calcula a diferença de tempo
                $diff = $target_date->getTimestamp() - $record_date->getTimestamp();

                // Se a diferença atual for menor que a menor diferença encontrada até agora,
                // este passa a ser o melhor resultado.
                if ($diff < $smallest_diff) {
                    $smallest_diff = $diff;
                    $best_match = $record;
                }
            }
        } catch (Exception $e) {
            // Ignora registros com datas inválidas no JSON
            continue;
        }
    }
}

// Retorna o melhor resultado encontrado
if ($best_match !== null) {
    echo json_encode([
        'success' => true,
        'price'   => $best_match['price'],
        'date'    => $best_match['date'] // Retorna a data real do preço encontrado
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Nenhum preço histórico encontrado em ou antes da data selecionada.']);
}

exit();
?>