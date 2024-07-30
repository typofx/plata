<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>

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
    $total_liquidity = 0;
    $total_percentage = 0;
    $total_plata = 0;

    $table_data2 = [];
    $total_data = [];

    while ($row = $result->fetch_assoc()) {
        $walletname = $row['walletname'];
        $plt = $row['balance'] / 10000;
        $liquidity = ($PLTUSD * $plt);
        $percentage = ($plt / $circulating_supply);

        if (stripos($walletname, 'Typo FX') === 0) {
            $exchange = 'Typo FX - Wallets';
        } elseif (stripos($walletname, 'OnlyMoons') === 0) {
            $exchange = 'OnlyMoons';
        } else {
            continue;
        }

        if (!isset($total_data[$exchange])) {
            $total_data[$exchange] = ['liquidity' => 0, 'percentage' => 0, 'plata' => 0];
        }

        $total_data[$exchange]['liquidity'] += $liquidity;
        $total_data[$exchange]['percentage'] += $percentage;
        $total_data[$exchange]['plata'] += $plt;
    }


    $cont = 1;
    foreach ($total_data as $exchange => $data) {
        $table_data2[] = [
            'id' => $cont,
            'exchange' => $exchange,
            'liquidity' => round($data['liquidity'], 2),
            'percentage' => round($data['percentage'], 4),
            'plata' => $data['plata']
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
        if (isset($item['total_plt'])) {
            $cexplt =  $item['total_plt'];
            $total_liquidity = $item['total_plt'] * $PLTUSD;
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
        $dex_liquidity_percentage = round($dex_result / 1000, 2);
        $dex_liquidity_percentage_d = $dex_liquidity_percentage / 100;
        $plt = $circulating_supply * $dex_liquidity_percentage_d;

        $table_data[] = [
            'id' => $cont,
            'exchange' => $exchange,
            'liquidity' => round($liquidity, 2),
            'percentage' =>round($dex_liquidity_percentage / 100,4),
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
        'plata' => $cexplt
    ];



    $cont++;



    // Merge table_data2 into table_data and adjust IDs
    foreach ($table_data2 as $row) {
        $row['id'] = $cont; // Update ID
        $table_data[] = $row; // Add row to the end of table_data
        $cont++;
    }
    $total_percentage_sum = array_sum(array_column($table_data, 'percentage'));
    $total_liquidity_sum = array_sum(array_column($table_data, 'liquidity'));
    $total_plata_sum = array_sum(array_column($table_data, 'plata'));


    $remaining_percentage = 1 - $total_percentage_sum;
    $remaining_liquidity = $remaining_percentage * $marketcap_float;
    $remaining_plt = $remaining_percentage * $circulating_supply;


    $table_data[] = [
        'id' => $cont,
        'exchange' => 'Others',
        'liquidity' => round($remaining_liquidity, 2),
        'percentage' => round($remaining_percentage, 5),
        'plata' => $remaining_plt
    ];

    $cont++;

    // $table_data[] = [
    //     'id' => $cont, 
    //    'exchange' => 'Total',
    //    'liquidity' => round($total_liquidity_sum + $remaining_liquidity, 2),
    //    'percentage' =>$total_percentage_sum + $remaining_percentage,
    //   'plata' => $total_plata_sum + $remaining_plt
    //  ];
    echo "<br><br>";
    echo "<br>Total: ";
    echo  "<br>liquidity: " . round($total_liquidity_sum + $remaining_liquidity, 2);
    echo  "<br>percentage: " . ($total_percentage_sum + $remaining_percentage) * 100 . "%";
    echo  "<br>plata: " . $total_plata_sum + $remaining_plt;

    echo "<br><br>";
    // Save data to JSON file

    usort($table_data, function($a, $b) {
        return $b['liquidity'] <=> $a['liquidity'];
    });

    foreach ($table_data as $index => &$entry) {
        $entry['id'] = $index + 1; 
    }

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