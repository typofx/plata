<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tokenomics</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <a href="liquidity_data.json" target="_blank">[JSON]</a>
    <a href="https://plata.ie/plataforma/painel/menu.php">[Control Panel]</a>
    <a href="javascript:window.location.reload(true)">[Refresh]</a>
    <a href="menu.php">[Add manually]</a>
    <!-- PHP -->

    <?php
    ob_start();
    include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
    ob_end_clean();

    echo "<h2>Market Capitalization: " . $PLTmarketcap . "</h2>";
    echo "PLTUSD: " . $PLTUSD;

    $circulating_supply = 11299000992;

    include 'conexao.php';

    $sql = "SELECT walletname, balance FROM granna80_bdlinks.tokenomics";
    $result = $conn->query($sql);

    // Initialize an array to store liquidity by exchange
    $liquidity_per_exchange = [];

    // Fetch data from the database
    $table_data2 = [];
    while ($row = $result->fetch_assoc()) {
        $exchange = $row['walletname'];
        $plt = $row['balance'] / 10000;
        $liquidity = ($PLTUSD * $plt);
        $percentage = number_format(($plt / $circulating_supply), 4, '.', ',');

        $table_data2[] = [
            'exchange' => $exchange,
            'liquidity' => $liquidity,
            'percentage' => floatval($percentage),
            'plata' => $plt
        ];
    }

    $json_url = 'https://plata.ie/plataforma/painel/lp-contracts/lp_contracts.json';

    // Fetch JSON data from the URL
    $json_data = file_get_contents($json_url);

    // Decode JSON data into an associative array
    $data = json_decode($json_data, true);

    // Process JSON data
    foreach ($data as $item) {
        if (isset($item['exchange']) && isset($item['liquidity'])) {
            $exchange = $item['exchange'];
            $liquidity = $item['liquidity'];

            // Group exchanges if needed
            if ($exchange === 'SushiSwap V3') {
                $exchange = 'SushiSwap';
            }
            if ($exchange === 'QuickSwap V3') {
                $exchange = 'QuickSwap';
            }
            if ($exchange === 'Uniswap V3') {
                $exchange = 'Uniswap';
            }
          


            if (!isset($liquidity_per_exchange[$exchange])) {
                $liquidity_per_exchange[$exchange] = 0;
            }

            $liquidity_per_exchange[$exchange] += $liquidity;
        }
    }

    $json_url = 'https://plata.ie/plataforma/painel/order-book/order_book_data.json';

    // Fetch JSON data from the URL
    $json_data = file_get_contents($json_url);

    // Decode JSON data into an associative array
    $data = json_decode($json_data, true);

    // Initialize variable to store total liquidity
    $total_liquidity = 0;

    // Process JSON data
    foreach ($data as $item) {
        if (isset($item['total_liquidity'])) {
            $total_liquidity = $item['total_liquidity'];
            break;
        }
    }

    // Calculate CEX total liquidity percentage
    $marketcap_float = floatval($PLTmarketcap);
    $liquidity_float = floatval($total_liquidity);
    $cex_result = ($liquidity_float / $marketcap_float) * 100;
    $cex_liquidity_percentage = round($cex_result / 1000, 2);

    // Prepare data for DataTables
    $table_data = [];
    $cont = 1;
    foreach ($liquidity_per_exchange as $exchange => $liquidity) {
        $marketcap_float = floatval($PLTmarketcap);
        $liquidity_float = floatval($liquidity);
        $dex_result = ($liquidity_float / $marketcap_float) * 100;
        $dex_liquidity_percentage = round( $dex_result / 1000, 2);
        $dex_liquidity_percentage_d = $dex_liquidity_percentage / 100;
        $plt = $circulating_supply * $dex_liquidity_percentage_d;

        $table_data[] = [
            'id' => $cont,
            'exchange' => $exchange,
            'liquidity' => round($liquidity, 2),
            'percentage' => $dex_liquidity_percentage / 100,
            'plata' => $plt
        ];
        $cont++;
    }
    $cex_liquidity_percentage_d = $cex_liquidity_percentage / 100;
    $plt = $circulating_supply * $cex_liquidity_percentage_d;

    // Add row for total CEX liquidity
    $table_data[] = [
        'id' => $cont,
        'exchange' => 'Centralized Exchanges (CEX)',
        'liquidity' => round($total_liquidity, 2),
        'percentage' => $cex_liquidity_percentage / 100,
        'plata' => $plt
    ];

    $cont++;

    // Merge table_data2 into table_data and adjust IDs
    foreach ($table_data2 as $row) {
        $row['id'] = $cont; // Update ID
        $table_data[] = $row; // Add row to the end of table_data
        $cont++;
    }

    // Save data to JSON file
    file_put_contents('liquidity_data.json', json_encode($table_data));

    // Display HTML table with static data
    echo "
<table id='liquidityTable' class='display'>
    <thead>
        <tr>
      
            <th>Exchange</th>
            <th>Liquidity</th>
            <th>Percentage</th>
            <th>Plata Token (PLT)</th>
        </tr>
    </thead>
    <tbody>";

    foreach ($table_data as $row) {
        echo "<tr>
    
        <td>{$row['exchange']}</td>
        <td>" . number_format($row['liquidity'], 2, '.', ',') . "</td>
        <td>{$row['percentage']}</td>
        <td>" . number_format($row['plata'], 4, '.', ',') . "</td>
      </tr>";
    }

    echo "</tbody>
</table>";
    ?>

    <script>
        $(document).ready(function() {
            $('#liquidityTable').DataTable({
                "paging": true,
                "searching": true,
                "info": true,
                "order": [
                    [1, 'desc']
                ]
            });
        });
    </script>
</body>

</html>
