.<?php
$clientID = 'AbtF9ClIC3pdm9VeYet4nSQ6PoINZs34GCwfPq8xz30Me-p84PaodnsDV4ZLPhtck1fB3xVTAtiMIU3H';
$secret = 'EPPdZka2x7kx5AZVadtw5gJpqd0tYdhahynuKOjHa9xzKuK2VU_3nbZ1RwefOlRazNxyhiMQ8DWQvuFM';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, $clientID . ":" . $secret);

$headers = array();
$headers[] = "Accept: application/json";
$headers[] = "Accept-Language: en_US";
$headers[] = "Content-Type: application/x-www-form-urlencoded";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Erro ao obter token de acesso: ' . curl_error($ch);
    exit;
}

$accessToken = json_decode($result)->access_token;

curl_close($ch);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ch = curl_init();

    $amount = $_POST['valor']; // Valor do item
    $currency = $_POST['moeda']; // Moeda
    $description = $_POST['descricao']; // Descrição do item

    $data = '{
        "intent": "sale",
        "payer": {
            "payment_method": "paypal"
        },
        "transactions": [
            {
                "amount": {
                    "total": "' . $amount . '",
                    "currency": "' . $currency . '"
                },
                "description": "' . $description . '"
            }
        ],
        "redirect_urls": {
            "return_url": "https://plata.ie/paypal/sucesso.php",
            "cancel_url": "https://plata.ie/paypal/cancelado.php"
            
        }
    }';

    curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/payments/payment");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $accessToken
    ));

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Erro ao criar a ordem de pagamento: ' . curl_error($ch);
        exit;
    }

    $paymentID = json_decode($result)->id;

    curl_close($ch);

    // Redirecionar o usuário para o PayPal
    $approvalURL = json_decode($result)->links[1]->href;
    header("Location: " . $approvalURL);
    exit;
}

if (isset($_GET['paymentId']) && isset($_GET['PayerID'])) {
    // Processar retorno do PayPal
    $paymentID = $_GET['paymentId'];
    $payerID = $_GET['PayerID'];

    $ch = curl_init();

    $data = '{
        "payer_id": "' . $payerID . '"
    }';

    curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/payments/payment/" . $paymentID . "/execute");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type:application/json",
        "Authorization: Bearer " . $accessToken
    ));

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Erro ao processar o pagamento: ' . curl_error($ch);
        exit;
    }

    $paymentStatus = json_decode($result)->state;

    curl_close($ch);

    if ($paymentStatus == 'approved') {
        // O pagamento foi aprovado, faça o processamento adicional necessário
        echo 'Pagamento aprovado!';
    } else {
        // O pagamento não foi aprovado
        echo 'Pagamento não aprovado.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Formulário de Pagamento PayPal</title>
</head>
<body>
    <h1>Formulário de Pagamento PayPal</h1>
    <form method="POST" action="">
        <label for="valor">Valor:</label>
        <input type="text" name="valor" id="valor" value="50.0" required>
        <br><br>
        <label for="moeda">Moeda:</label>
        <input type="text" name="moeda" id="moeda" value="USD" required>
        <br><br>
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" id="descricao" required>
        <br><br>
        <input type="submit" value="Pagar com PayPal">
    </form>
</body>
</html>

