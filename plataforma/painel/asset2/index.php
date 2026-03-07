<?php

include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
include 'conexao.php';

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}


$jsonFile = __DIR__ . '/all_pricesV3.livecoinwatch.json';
$priceData = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
$livePrices = $priceData['prices_vs_usd'] ?? [];
$pltPrices = $priceData['plt_prices'] ?? [];
$usdBrl = $priceData['usd_vs_prices']['USDBRL'] ?? 0;

$priorityPrices = [
    'aWBTC' => $livePrices['WBTCUSD'] ?? 0,
    'WETH' => $livePrices['WETHUSD'] ?? 0,
    'WMATIC' => $livePrices['MATICUSD'] ?? 0,
    'MATIC' => $livePrices['MATICUSD'] ?? 0,
    'BNB' => $livePrices['BNBUSD'] ?? 0,
    'PLT' => $pltPrices['USDPLT'] ?? 0,
    'EUR' => $livePrices['EURUSD'] ?? 0,
    'aEURS' => $livePrices['EURUSD'] ?? 0,
    'EURS' => $livePrices['EURUSD'] ?? 0,
    'BRL' => ($usdBrl != 0) ? (1 / $usdBrl) : ($livePrices['BRLUSD'] ?? 0),
    'USD' => 1.0,
];

$query = "SELECT id, name, icon_path, ticker_symbol, decimal_value, contract_name, pool, network FROM granna80_bdlinks.assets";
$result = $conn->query($query);

$jsonData = [];
$htmlRows = '';
$cont = 1;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ticker = $row['ticker_symbol'];
        $price = $priorityPrices[$ticker] ?? 0.0;
        $network = strtolower($row['network']);
        $contract = $row['contract_name'];
        $id = $row['id'];
        $name = $row['name'];
        $icon = $row['icon_path'];
        $decimal_value = $row['decimal_value'];

        // Formatação diferenciada para o PLT
        if ($ticker === 'PLT') {
            $formattedPrice = number_format((float) $price, 12, '.', '');
        } else {
            $formattedPrice = number_format((float) $price, 8, '.', '');
        }

        $jsonData[] = [
            "id" => $cont,
            "name" => $name,
            "icon" => $icon,
            "ticker" => $ticker,
            "contract" => $contract,
            "decimals" => (int) $decimal_value,
            "network" => $row['network'],
            "price" => $formattedPrice
        ];

        $contractLink = $network === 'polygon' && !empty($contract)
            ? "<a href='https://polygonscan.com/token/{$contract}' target='_blank'>" . substr($contract, 0, 6) . "..." . substr($contract, -4) . "</a>"
            : '...';

        $htmlRows .= "<tr>
            <td>{$id}</td>
            <td>{$name}</td>
            <td>" . (!empty($icon) ? "<img src='/images/assets-icons/" . basename($icon) . "' alt='Asset Icon' style='width:20px; height:20px;'>" : "") . "</td>
            <td>{$ticker}</td>
            <td>{$contractLink}</td>
            <td>{$decimal_value}</td>
            <td>{$row['network']}</td>
            <td>" . $formattedPrice . "</td>
            <td>
                <a href='edit.php?id={$row["id"]}'><i class='fa-solid fa-pen-to-square'></i></a> 
                <a href='delete.php?id={$row["id"]}' onclick='return confirm(\"Tem certeza?\")'><i style='color: red;' class='fa-solid fa-trash'></i></a>
            </td>
        </tr>";
        $cont++;
    }
}
file_put_contents('assets_data.json', json_encode(array_values($jsonData), JSON_PRETTY_PRINT));
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assets List</title>
    <link rel="stylesheet" href="jquery.dataTables.min.css">
    <link rel="stylesheet" href="all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <a href="/plataforma/panel/main.php">[Back]</a>
    <a href="javascript:window.location.reload(true)">[Refresh]</a>
    <a href="add.php">[Add New Record]</a>
    <a href="assets_data.json" target="_blank">[JSON]</a>
    <a href="add_fiduciary_coin.php">[Add fiduciary coin]</a>
    <h1>Assets List</h1>
    <table id="assetsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Icon</th>
                <th>Ticker</th>
                <th>Contract</th>
                <th>Dec</th>
                <th>Network</th>
                <th>Price (USD)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $htmlRows; ?>
        </tbody>
    </table>

    <script src="jquery-3.5.1.min.js"></script>
    <script src="jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#assetsTable').dataTable({
                "lengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
                "pageLength": 50
            });
        });
    </script>
</body>

</html>