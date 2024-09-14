<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();
include 'conexao.php';




if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}


if (!function_exists('tokenbalance')) {
    function tokenbalance($url)
    {
        $jsonfile = file_get_contents($url);
        $data = json_decode($jsonfile);
        return round($data->{'result'});
    }
}

function calculatePrice($contract, $pool, $decimal_value)
{
    $json_url = 'https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=ETH&api_key=6023fb8068e6f17fe63800ce08f15fb6bd88d7b3b825600d58736973a6aafd98';
    $ar_data = json_decode( file_get_contents($json_url) );
    $ETHUSD = 1 / $ar_data->{'ETH'}; 
    
    $weth_contract = '0x7ceb23fd6bc0add59e62ac25578270cff1b9f619';
    $json_token_pool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=' . $contract . '&address=' . $pool . '&tag=latest&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';
    $json_weth_pool  = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=' . $weth_contract . '&address=' . $pool . '&tag=latest&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';
    $token_pool_value = tokenbalance($json_token_pool) / 10 ** $decimal_value;
    $weth_pool_value = tokenbalance($json_weth_pool) / 10 ** 18;


    $USDToken = $token_pool_value / ($weth_pool_value * $ETHUSD);
    return sprintf('%.10f', 1 / $USDToken);
}



$query = "SELECT id, name, ticker_symbol, decimal_value, contract_name, pool, network FROM granna80_bdlinks.assets";
$result = $conn->query($query);


$jsonData = [];
$htmlRows = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $name = $row['name'];
        $ticker_symbol = $row['ticker_symbol'];
        $decimal_value = $row['decimal_value'];
        $contract = $row['contract_name'];
        $network = $row['network'];
        $pool = $row['pool'];


        if (empty($pool) || $pool == '0') {
           floatval($price = 0.0) ;
        } else {
            $price = calculatePrice($contract, $pool, $decimal_value);
        }

        if ($ticker_symbol == "aWBTC") {
            $BTCUSD = (float)str_replace(',', '', $BTCUSD);
            $price = $BTCUSD;
        } else if ($ticker_symbol == "USDC.e") {
            $price = 0;
        } else if ($ticker_symbol == "WETH") {
            $price = $ETHUSD;
        } else if ($ticker_symbol == "WMATIC") {
            $price = $MATICUSD;
        } else if ($ticker_symbol == "BRZ") {
            $price = $BRZUSD;
        } else if ($ticker_symbol == "USDC") {
            $price = $USDCUSD;
        } else if ($ticker_symbol == "USDT") {
            $price = $USDTUSD; 
        } else if ($ticker_symbol == "DAI") {
            $price = $DAIUSD; 
        } else if ($ticker_symbol == "BUSD") {
            $price = $BUSDUSD; 
        } else if ($ticker_symbol == "BNB") {
            $price = $BNBUSD; 
        } else if ($ticker_symbol == "MATIC") {
            $price = $MATICUSD; }



            $jsonData[$id] = [
                "name" => $name,
                "ticker" => $ticker_symbol,
                "contract" => $contract,
                "decimals" => (int)$decimal_value,
                "network" => $network,
                "price" => $ticker_symbol === 'PLT' ? floatval($price) : round(floatval($price), 4)
            ];
            


        $htmlRows .= "<tr>
        <td>$id</td>
        <td>$name</td>
        <td>$ticker_symbol</td>
        <td><a href='https://polygonscan.com/token/" . $contract . "'>" . substr($contract, 0, 6) . "..." . substr($contract, -4) . "</a></td>
        <td>$decimal_value</td>
        <td>$network</td>
        <td>" . ($ticker_symbol === 'PLT' ? $price : number_format((float)str_replace(',', '', $price), 4)) . "</td>
        <td><a href='edit.php?id=" . $row["id"] . "'><i class='fa-solid fa-pen-to-square'></i></a> 
            <a href='#' onclick='confirmDelete(" . $row["id"] . ")'><i style='color: red;' class='fa-solid fa-trash'></i></a>
        </td>
    </tr>";
    
    
    }
}


$jsonData['timestamp'] = date('c'); // ISO 8601 timestamp


file_put_contents('assets_data.json', json_encode($jsonData, ));


$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tokens</title>


    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }

        table,
        th,
        td {
            text-align: center;
        }

        .highlight {
            background-color: yellow;
            color: black;
            padding: 2px 4px;
            border-radius: 3px;
        }
    </style>

</head>

<body>

    <a href="https://plata.ie/plataforma/painel/menu.php">[Control Panel]</a>
    <a href="javascript:window.location.reload(true)">[Refresh]</a>
    <a href="add.php">[Add New Record]</a>
    <a href="assets_data.json">[JSON]</a>
    <h1>Assets List</h1>
    <table id="assetsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Ticker</th>
                <th>Contract</th>
                <th>Decimals</th>
                <th>Network</th>
                <th>Price (USD)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $htmlRows; ?>
        </tbody>
    </table>


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#assetsTable').dataTable({
                "lengthMenu": [
                    [25, 50, 75, -1],
                    [25, 50, 75, "All"]
                ],
                "pageLength": 50
            });
        });
    </script>

</body>

</html>