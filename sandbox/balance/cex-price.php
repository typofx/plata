<?php






ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();


$jsonUrl = "https://www.coininn.com/api/v3/papi/spot/depth?symbol=PLT&baseCurrency=USDT";


function fetchJsonFromUrl($url)
{
    $json = file_get_contents($url);
    if ($json === false) {
        throw new Exception("Falha ao obter o JSON da URL: $url");
    }
    return $json;
}


function normalizeAsks($asks)
{
    $normalizedAsks = [];
    foreach ($asks as $ask) {
        if (isset($ask['price']) && isset($ask['amount'])) {
      
            $normalizedAsks[] = [
                'price' => convertScientificToFloat($ask['price']), 
                'amount' => $ask['amount']
            ];
        } elseif (is_array($ask) && count($ask) === 2) {
      
            $normalizedAsks[] = [
                'price' => convertScientificToFloat($ask[0]), 
                'amount' => $ask[1]
            ];
        }
    }
    return $normalizedAsks;
}


function convertScientificToFloat($value)
{
    return floatval($value); 

try {

    $json = fetchJsonFromUrl($jsonUrl);


    $data = json_decode($json, true);


    $asks = isset($data['asks']) ? normalizeAsks($data['asks']) : [];

    $bids = isset($data['bids']) ? normalizeAsks($data['bids']) : [];


    $totalPrice = 0;
    $totalAmount = 0;
    $totalPriceBids = 0;
    $totalAmountBids = 0;


    foreach ($bids as $bid) {
        $totalPriceBids += $bid['price']; 
        $totalAmountBids += $bid['amount']; 
    
    foreach ($asks as $ask) {
        $totalPrice += $ask['price']; 
        $totalAmount += $ask['amount']; 
    }
} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage();
    die();
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela de JSON</title>



</head>

<body>

    <h3>Market Depth</h3>

    <h3>Asks</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Price</th>
                <th>Amount</th>

            </tr>
        </thead>
        <tbody>
            <?php $cont = 1; ?>
            <?php foreach ($asks as $ask) : ?>
                <?php if ($cont > 5) break; ?>

                <tr>
                    <td>
                        <center><?php echo $cont ?></center>
                    </td>
                    <td>
                        <center><?php echo number_format($ask['price'], 8); ?></center>
                    </td>
                    <td>
                        <center><?php echo $ask['amount'] ?></center>
                    </td>
                </tr>
                <?php $cont++ ?>
            <?php endforeach; ?>


            <tr>
                <td><strong>
                    
                    </strong></td>
                <td><strong>
                        <center><?php // echo number_format($totalPrice, 8); ?> </center>
                    </strong></td>
            </tr>

        </tbody>
    </table>


    
    <p>Plata Token (PLT) Price: <?php echo $PLTUSD ?> USDT</p>
    <br>
    <strong>Total Amount ASK (PLT):</strong>
    <strong><?php echo $totalAmountF = number_format($totalAmount, 4, '.', ','); ?></strong>
    <br>
    <strong>Total ASK (USDT):</strong>
  <?php $totalPLTUSD = ($PLTUSD * $totalAmount)  ?>
    <strong><?php echo $totalPLTUSDF = number_format($totalPLTUSD, 2, '.', ','); ?></strong>
    <br>
    <strong>
       Total BID (USDT):
      <?php echo $totalAmountBids ?>
    </strong>
 


<?php 

include 'conexao.php';

$sql = "SELECT id, value, value1, value2 FROM granna80_bdlinks.order_book";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
 

    while ($row = $result->fetch_assoc()) {







?>




<br>
<br>
   <p> Claimed ASK (PLT) : <strong><?php echo  number_format($row["value"], 4, '.', ',') ; ?></strong> (Self Reported)</p>
   
    <p> Claimed ASK (USDT) : <strong><?php echo  number_format($row["value1"], 2, '.', ',') ; ?></strong> (Self Reported)</p>
    
    <p> Claimed BID (USDT) : <strong><?php echo  number_format($row["value2"], 2, '.', ',') ; ?></strong> (Self Reported)</p>



    <?php  
    }

 
} else {
    echo "0 results";
}
    ?>
</body>

</html>