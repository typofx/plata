<?php
// Dados da sua conta na Coinbase


// Valores padrão do pagamento
$customerName = '';
$customerEmail = ''; 
$paymentAmount = 1.00;
$paymentCurrency = 'USD';
$paymentDescription = 'Buying Plata Token';
$customerWallet = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualiza os valores do pagamento com os dados do formulário
    $paymentAmount = $_POST['amount'];
    $paymentCurrency = $_POST['currency'];
    $customerName = $_POST['customerName'];
    $customerEmail = $_POST['customerEmail'];
    $web3wallet = $_POST['customerWallet'];
    $PLTwanted = $_POST['PLTwanted'];
    
    
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

         //Cria um botão de pagamento
        //echo '<a href="' . $checkoutUrl . '" target="_blank">Pagar com Coinbase</a>';
        //echo 'Resposta da API: ' . $response;
        //echo 'URL de checkout da Coinbase: ' . $checkoutUrl;
    } else {
        //echo 'Erro ao obter URL de pagamento.';
        //echo 'Resposta da API: ' . $response;
    }
} else {
    //echo 'Erro na requisição para a API da Coinbase. Status: ' . $status;
    //echo 'Resposta da API: ' . $response;
}
?>


<!doctype html>

<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>

    <head>
    <title>Plata Token - Coinbase</title>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta charset="utf-8">
    <meta name="keywords" content="coinbase, coin" />

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/0f8eed42e7.js" crossorigin="anonymous"></script>
    <script>
        function isValidEtherWallet(){
        let address = document.getElementById("customerWallet").value;
        let result = Web3.utils.isAddress(address);
        if (result!= true) document.getElementById("customerWallet").value = "";
        console.log(result);  // => true?
    }  
    
    </script>
    </head>

    <script>
        function currencyConvert() {
            let textCurrency = String(document.getElementById("currency").value);
        
            console.log(textCurrency);
        
            if (textCurrency=='BRL') {
                document.getElementById("PLTwanted").value = (Number(document.getElementById("amount").value) / Number(_PLTBRL)).toFixed(4);
            }else if (textCurrency=='EUR') {
                document.getElementById("PLTwanted").value = (Number(document.getElementById("amount").value) / Number(_PLTEUR)).toFixed(4);
            }else if (textCurrency=='USD') {
                document.getElementById("PLTwanted").value = (Number(document.getElementById("amount").value) / Number(_PLTUSD)).toFixed(4);
            }
    }
        
    </script>

    <body>
        <div id="boxApp" align="center">
        <div id="box" class="box">
        <h3>Coinbase Commerce</h3>
        <div>
        <form method="POST" action="checkout.php">

            <div class="div-label"><label for="User Name">Name</label></div>
            <div>
                <input type="text" id="customerName" name="customerName" value="<?php echo $customerName; ?>" required>
            </div> 

            <div class="div-label"><label for="Email">Email</label></div>
            <div>
                <input type="email" name="customerEmail" id="customerEmail" value="<?php echo $customerEmail; ?>" required>
            </div>

            <div class="div-label"><label for="Web3Wallet">Web3 DEX Polygon(MATIC) Wallet</label></div>
            <div>
                <input type="text" name="customerWallet" size="49" id="customerWallet" placeholder="0x..." value="<?php echo $customerWallet; ?>" onfocusout="isValidEtherWallet()" required>
            </div>

            <div class="amount">
            <div class="div-label"><label for="amount">Payment Amount </label></div>
                <select class="select-list" name="currency" id="currency" onfocus="currencyConvert()" onfocosout="currencyConvert()" focusin="currencyConvert()" onclick="currencyConvert()">
                    <option class="option" value="<?php echo $paymentCurrency = "EUR"; ?>">EUR</option> 
                    <option class="option" value="<?php echo $paymentCurrency = "USD"; ?>">USD</option>
                    <option class="option" value="<?php echo $paymentCurrency = "BRL"; ?>">BRL</option>
                </select>
            <input class="input-amount" type="number" step="0.01" min="0.01" name="amount" id="amount" autocomplete="off" value="<?php echo $paymentAmount; ?>" onkeyup="QUOTEexec()" onkeypress="QUOTEexec()" required>
            </div>
            <div class="div-label"> <label for="PLTwanted">Receiving about Plata Tokens (PLT)</label></div>
            <input lass="input-amount" type="number" id="PLTwanted" name="PLTwanted" size="15" step="0.0001" autocomplete="off" min="0.0001" value="<?php echo $PLTwanted; ?>" onkeyup="PLTexec()" onkeypress="PLTexec()" required>

            <hr width="95%" class="hrline"/>
            <button id="submitButton" name="submit" class="buttonBuyNow"> Pay with Crypto </button>
            <hr width="95%" class="hrline"/>
        </form>
        </div></div></div></div>
        
        
<br>
<center><a id="dappVersion">PlataByCoinbase Dapp Version 0.0.99 (Beta)</a></center>
<?php include '../en/mobile/price.php';?>

<?php
    date_default_timezone_set('UTC');
    echo "<br><center><p>" . $Expdate . "</p></center>";
?>

<script>

    document.getElementById("amount").value = "1.00";
    let _PLTBRL = <?php echo number_format($PLTBRL, 8, '.' , ''); ?>;
    let _PLTEUR = <?php echo number_format($PLTEUR, 8, '.' , ''); ?>;
    let _PLTUSD = <?php echo number_format($PLTUSD, 8, '.' , ''); ?>;

    console.log(_PLTEUR);
    document.getElementById("PLTwanted").value = Number(1/_PLTEUR).toFixed(4);

    document.getElementById("txtCurrencyEnv").innerText = "(EUR)";
    document.getElementById("txtPAIR").innerText = "<?php echo $PLTEUR?>";
    document.getElementById("tr-price").style.visibility = "collapse";

    function QUOTEexec() {
        let textCurrency = String(document.getElementById("currency").value);
        let textAmount = Number(document.getElementById("amount").value);
        
        if (textCurrency=='BRL') {
            document.getElementById("PLTwanted").value = Number(textAmount/_PLTBRL).toFixed(4);
        }else if (textCurrency=='EUR') {
            document.getElementById("PLTwanted").value = Number(textAmount/_PLTEUR).toFixed(4);
        }else  if (textCurrency=='USD') {
            document.getElementById("PLTwanted").value = Number(textAmount/_PLTUSD).toFixed(4);
        }
        
    } QUOTEexec();
    
    function PLTexec() {
        let textCurrency = String(document.getElementById("currency").value);
        let textPLT = Number(document.getElementById("PLTwanted").value);

        if (textCurrency=='BRL') {
            document.getElementById("amount").value = Number(textPLT*_PLTBRL).toFixed(2);
        }else if (textCurrency=='EUR') {
            document.getElementById("amount").value = Number(textPLT*_PLTEUR).toFixed(2);
        }else  if (textCurrency=='USD') {
            document.getElementById("amount").value = Number(textPLT*_PLTUSD).toFixed(2);
        }

    } PLTexec();
    
</script>

    </body>
</html>
