<?php
// Dados da sua conta na Coinbase
$coinbaseApiKey = 'd683db11-168d-4e2e-adb9-e48e670d0f92';

// Valores padrão do pagamento
$customerName = '';
$customerEmail = ''; 
$paymentAmount = 1.00;
$paymentCurrency = 'USD';
$paymentDescription = 'Buying Plata Token';
$customerWallet = '0x';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualiza os valores do pagamento com os dados do formulário
    $paymentAmount = $_POST['amount'];
    $paymentCurrency = $_POST['currency'];
    $customerName = $_POST['customerName'];
    $customerEmail = $_POST['customerEmail'];
    $web3wallet = $_POST['customerWallet'];
    
    //$paymentDescription = $_POST['description'];
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
    'name' => '$PLT Plata Token',
    'description' => 'Buying Plata Token with Coinbase',
    'pricing_type' => 'fixed_price',
        'local_price' => array(
            'amount' => $paymentAmount,
            'currency' => $paymentCurrency,
        ),
    'requested_info' => array(
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
        //echo '<a href="' . $checkoutUrl . '" target="_blank">Pagar com Coinbase</a>';
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

<form method="POST" action="checkout.php">
    <label for="User Name">User Name</label>
    <input type="text" name="customerName" id="customerName" value="<?php echo $customerName; ?>" required><br><br>

    <label for="Email">Email</label>
    <input type="email" name="customerEmail" id="customerEmail" value="<?php echo $customerEmail; ?>" required><br><br>
    
    <label for="Web3Wallet">Web3 DEX Polygon Wallet</label>
    <input type="text" name="customerWallet" size="49" id="customerWallet" value="<?php echo $customerWallet; ?>" required><br><br>

    <label for="amount">Valor do pagamento:</label>
    <select name="currency" id="currency">
    <option value="<?php echo $paymentCurrency = "EUR"; ?>">EUR</option>
    <option value="<?php echo $paymentCurrency = "USD"; ?>">USD</option>
    <option value="<?php echo $paymentCurrency = "BRL"; ?>">BRL</option>
    </select>

    <input type="number" step="0.01" min="1" name="amount" id="amount" value="<?php echo $paymentAmount; ?>" required><br><br>
    



    <input type="submit" value="Pay with Coinbase">
</form>
