<?php

require 'vendor/autoload.php'; // Carrega a biblioteca Guzzle

$clientId = '';
$clientSecret = '';

$accessToken = ''; // Você precisa obter um access token

// Autenticação com a API da SumUp para obter um access token
$client = new GuzzleHttp\Client();
$response = $client->post('https://api.sumup.com/token', [
    'form_params' => [
        'grant_type' => 'client_credentials',
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
    ],
]);

$data = json_decode($response->getBody());
$accessToken = $data->access_token;

// Realiza um pagamento
$response = $client->post('https://api.sumup.com/v0.1/checkouts', [
    'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
    ],
    'json' => [
        'checkout_request' => [
            'total_amount' => 1000, // Valor em centavos
            'currency' => 'BRL',
        ],
    ],
]);

$data = json_decode($response->getBody());

// Processa a resposta e exibe o resultado
if ($data->status === 'success') {
    echo 'Pagamento realizado com sucesso. ID da transação: ' . $data->checkout->id;
} else {
    echo 'Erro ao processar o pagamento: ' . $data->status;
}
