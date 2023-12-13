<!DOCTYPE html>

<html>

<body>

<?php

$data = array(
    'clientid' => 'r7qTt7JsMp4NaiKpO6yEQ',
    'clientsecret' => 'OKwcU19AP7YUvH6zynUQ4Qf3KT6a60HwmWpvD9M7H24nleGB8iqmCGd8tvGQcJXY'
        );

$payment = array(
    'fiatAmount' => '1',
    'baseCurrency' => 'USDT',
    'symbol' => 'PLT',
    'tradeType' => '1'
           );

$str = http_build_query($data);
$curl = curl_init();
curl_setopt($curl,CURLOPT_URL, 'https://www.coininn.com/api/v3/papi/spot/PLT');
curl_setopt($curl,CURLOPT_POST, 1);
curl_setopt($curl,CURLOPT_POSTFIELDS, $payment);
curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl,CURLOPT_HEADER, true);
$result = curl_exec($curl);

$url = 'https://www.coininn.com/api/v3/papi/spot/PLT';
$header_size = curl_getinfo($url,CURLINFO_HEADER_SIZE);
$header = substr($result, 0,$header_size);
$body = substr($result, 0,$header_size);


if ($error=curl_error($curl)) {
	http_response_code(400);
	echo json_encode(array('error'=>$error));
} else {

http_response_code(200);
	echo json_encode(array('success'=> 'success ',
				'body'=> $body,
				 'header'=> $header ));
}

?>

</body>
<form method="POST" action="https://www.coininn.com/api/v3/papi/spot/PLT">
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
    
    <input type="submit" value="Trade">
</form>
</html>


