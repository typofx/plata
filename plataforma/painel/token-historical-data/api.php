<?php
// Dados da requisição
$data = json_encode(array('currency' => 'USD', 'code' => '______PLT', 'meta' => true));

// Configurações do contexto da requisição
$context_options = array (
    'http' => array (
        'method' => 'POST',
        'header' => "Content-type: application/json\r\n" .
                    "x-api-key: 135b0af8-e18a-42a4-bce7-ed193b2932e6\r\n",  // Remover os "<>" da chave de API
        'content' => $data
    )
);

// Cria o contexto
$context = stream_context_create($context_options);

// Faz a requisição à API
$response = file_get_contents('https://api.livecoinwatch.com/coins/single', false, $context);

if ($response === FALSE) {
    // Tratamento de erro aqui
    echo "Ocorreu um erro ao fazer a requisição.";
} else {
    // Decodifica a resposta JSON
    $json_data = json_decode($response, true);
    
    // Pega o volume
    if (isset($json_data['volume'])) {
        $volume = $json_data['volume'];
        echo  number_format($volume, 2, '.', ',');
    } else {
        echo "Campo 'volume' não encontrado na resposta da API.";
    }
}
?>