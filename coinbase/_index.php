<?php
// Dados da sua conta na Coinbase
$coinbaseApiKey = '';

// Valores padrão do pagamento
$paymentAmount = 10.0;
$paymentCurrency = 'USD';
$paymentDescription = 'Texto';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualiza os valores do pagamento com os dados do formulário
    $paymentAmount = $_POST['amount'];
    $paymentCurrency = $_POST['currency'];
    $paymentDescription = $_POST['description'];
}

// Configurações da API da Coinbase
$coinbaseApiUrl = 'https://api.commerce.coinbase.com';
$coinbaseApiEndpoint = '/checkouts';

// Cabeçalhos da requisição
$headers = array(
    'Content-Type: application/json',
    'X-CC-Api-Key: ' . $coinbaseApiKey,
    'X-CC-Version: 2018-03-22'
);

// Dados do pagamento no formato JSON
$paymentData = array(
    'name' => 'Cliente Exemplo',
    'description' => $paymentDescription,
    'pricing_type' => 'fixed_price',
    'local_price' => array(
        'amount' => $paymentAmount,
        'currency' => $paymentCurrency
    ),
    'requested_info' => array(
        'name',
        'email'
    ),
    'redirect_url' => 'https://plata.ie/'
);

// Realiza a requisição para criar um novo pagamento na Coinbase
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $coinbaseApiUrl . $coinbaseApiEndpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Obtém o código de status HTTP da resposta
curl_close($ch);

// Processa a resposta da API da Coinbase
if ($status === 201) {
    $responseData = json_decode($response, true);
  
    if (isset($responseData['data']['id'])) {
        $checkoutId = $responseData['data']['id'];
        $checkoutUrl = 'https://commerce.coinbase.com/checkout/' . $checkoutId;

        // Cria um botão de pagamento
        echo '<a href="' . $checkoutUrl . '" target="_blank">Pagar com Coinbase</a>';
        //echo 'Resposta da API: ' . $response;
        //echo 'URL de checkout da Coinbase: ' . $checkoutUrl;
    } else {
        echo 'Erro ao obter URL de pagamento.';
        echo 'Resposta da API: ' . $response;
    }
} else {
    echo 'Erro na requisição para a API da Coinbase. Status: ' . $status;
    echo 'Resposta da API: ' . $response;
}
?>

<form method="POST" action="">
    <label for="amount">Valor do pagamento (USD):</label>
    <input type="number" step="0.01" name="amount" id="amount" value="<?php echo $paymentAmount; ?>" required><br><br>

    <label for="currency">Moeda do pagamento:</label>
    <input type="text" name="currency" id="currency" value="<?php echo $paymentCurrency; ?>" required><br><br>

    <label for="description">Descrição do pagamento:</label>
    <input type="text" name="description" id="description" value="<?php echo $paymentDescription; ?>" required><br><br>

    <input type="submit" value="Atualizar Pagamento">
</form>
