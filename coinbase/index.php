<?php

// Your Coinbase account data

// Default payment values
//$paymentAmount = 10.0;
//$paymentCurrency = 'USD';
//$paymentDescription = 'Text';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update payment values with form data
    $paymentAmount = $_POST['amount'];
    $paymentCurrency = $_POST['currency'];
    $customerName = $_POST['customerName'];
    $customerEmail = $_POST['customerEmail'];
    $web3wallet = $_POST['customerWallet'];
    $PLTwanted = $_POST['PLTwanted'];
    $checkoutUrl = $_POST['checkoutUrl'];
    
    //$paymentDescription = $_POST['description'];
}

// Coinbase API configurations
$coinbaseApiUrl = 'https://api.commerce.coinbase.com';
$coinbaseApiEndpoint = '/checkouts';

// Request headers
$headers = array(
    'Content-Type: application/json',
    'X-CC-Api-Key: ' . $coinbaseApiKey,
    'X-CC-Version: 2018-03-22'
);

// Payment data in JSON format
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

// Make a request to create a new payment on Coinbase
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $coinbaseApiUrl . $coinbaseApiEndpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get the HTTP status code of the response
curl_close($ch);

// Process the response from the Coinbase API
if ($status === 201) {
    $responseData = json_decode($response, true);
  
    if (isset($responseData['data']['id'])) {
        $checkoutId = $responseData['data']['id'];
        $checkoutUrl = 'https://commerce.coinbase.com/checkout/' . $checkoutId;
        $checkoutMail = 'onclick="emailToCustomer()"';
        
        //echo $checkoutMail;
        
        //echo 'API response: ' . $response;
        //echo 'Coinbase checkout URL: ' . $checkoutUrl;
    } else {
        //echo 'Error getting payment URL.';
        //echo 'API response: ' . $response;
        header("Location: https://www.plata.ie/coinbase/");
        exit();
    }
} else {
    //echo 'Error in request to Coinbase API. Status: ' . $status;
    //echo 'API response: ' . $response;
    header("Location: https://www.plata.ie/coinbase/");
    exit();
}

?>
<link rel="stylesheet" href="https://www.plata.ie/coinbase/style.css">
<?php

// Create a payment button

?>

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
        function showFullWallet(){
            alert(WalletAddress);
        }
    </script>
    <div id="boxApp" align="center">
        <div id="box" class="box">
            <h3>Purchase Confirmation</h3>
            <h4>Coinbase Commerce</h4>
            <div>
                <form action="<?php echo $checkoutUrl; ?>" >
                    <div class="div-label"><label for="User Name">Name :</label></div>
                    <div>
                        <b><?php echo $customerName; ?></b>
                    </div> 
                    <br>
                    <div class="div-label"><label for="Email">Email :</label></div>
                    <div>
                        <b><?php echo $customerEmail; ?></b>
                    </div>
                    <br>
                    <div class="div-label"><label for="Web3Wallet">Web3 DEX Polygon(MATIC) Wallet :</label></div>
                    <div>
                        <b><a id="WalletToGetPlata" onclick="showFullWallet()"><?php echo $web3wallet; ?></a></b>
                    </div>
                    <br>
                    <div class="div-label"><label for="Amount">Amount To Be Spent :</label></div>
                    <div>
                        <b><?php echo $paymentAmount; ?> (<?php echo $paymentCurrency; ?>)</b>
                    </div>
                    <br>
                    <div class="div-label"><label for="PLTwanted">Plata Tokens (Quote) :</label></div>
                    <b><?php echo $PLTwanted; ?> (PLT)</b>
                    <br>
                    <hr width="95%" class="hrline"/>
                    <button class='buttonBuyNow' onclick="emailToCustomer()" >Checkout</button>
                    <hr width="95%" class="hrline"/>
                </form>
            </div>
        </div>
    </div>
    <br>
    <center><a id="dappVersion">PlataByCoinbase Dapp Version 0.0.99 (Beta)</a></center>

<?php
    date_default_timezone_set('UTC');
    echo "<br><center><p>" . $Expdate . "</p></center>";
?>

<script>
    let WalletAddress = document.getElementById("WalletToGetPlata").innerText + " ";
    
    function emailToCustomer() {
        var link = document.createElement("a")
        link.href = "mailto.php?customerName=<?php echo $customerName;?>&customerEmail=<?php echo $customerEmail;?>&customerWallet=<?php echo $web3wallet;?>&amount=<?php echo $paymentAmount;?>&currency=<?php echo $paymentCurrency;?>&PLTwanted=<?php echo $PLTwanted;?>";
        link.target = "_blank"
        link.click()
    }

    function ReducedStringNameWalletAddress() {
        document.getElementById("WalletToGetPlata").innerText = ( WalletAddress.slice(0,6) + "..." + WalletAddress.slice(-5,-1));
    } ReducedStringNameWalletAddress();
    
</script>
