<?php
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables Example</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <a href="liquidity_data.json">[JSON]</a>
    <a href="https://plata.ie/plataforma/painel/menu.php">[Control Panel]</a>
    <a href="javascript:window.location.reload(true)">[Refresh]</a>
    <!-- PHP -->

    <?php
    ob_start();
    include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
    ob_end_clean();

    echo "<h2>Market Capitalization: " . $PLTmarketcap . "</h2>";


    // JSON URL for exchange liquidity
    $json_url = 'https://plata.ie/plataforma/painel/lp-contracts/lp_contracts.json'; // replace with actual URL

    // Fetch JSON data from the URL
    $json_data = file_get_contents($json_url);

    // Decode JSON data into an associative array
    $data = json_decode($json_data, true);

    // Initialize an array to store liquidity by exchange
    $liquidity_per_exchange = [];

    // Process JSON data
    foreach ($data as $item) {
        if (isset($item['exchange']) && isset($item['liquidity'])) {
            $exchange = $item['exchange'];
            $liquidity = $item['liquidity'];

            // Group SushiSwap and SushiSwap V3
            if ($exchange === 'SushiSwap V3') {
                $exchange = 'SushiSwap';
            }

            // Group QuickSwap and QuickSwap V3
            if ($exchange === 'QuickSwap V3') {
                $exchange = 'QuickSwap';
            }

            // Group Uniswap and Uniswap V3
            if ($exchange === 'Uniswap V3') {
                $exchange = 'Uniswap';
            }

            if (!isset($liquidity_per_exchange[$exchange])) {
                $liquidity_per_exchange[$exchange] = 0;
            }

            $liquidity_per_exchange[$exchange] += $liquidity;
        }
    }

    // JSON URL for order book data
    $json_url = 'https://plata.ie/plataforma/painel/order-book/order_book_data.json'; // replace with actual URL

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
    $float = floatval($PLTmarketcap);
    $float2 = floatval($total_liquidity);
    $c = ($float2 / $float) * 100;
    $cex_liquidity_percentage = round($c / 1000, 2);

    // Prepare data for DataTables
    $table_data = [];
    $cont = 1;
    foreach ($liquidity_per_exchange as $exchange => $liquidity) {
        $float = floatval($PLTmarketcap);
        $float2 = floatval($liquidity);
        $p = ($float2 / $float) * 100;
        $dex_liquidity_percentage = round($p / 1000, 2);

        $table_data[] = [
            'id' => $cont,
            'exchange' => $exchange,
            'liquidity' => number_format($liquidity, 2),
            'percentage' => $dex_liquidity_percentage . '%'
        ];
        $cont++;
    }

    // Add row for total CEX liquidity
    $table_data[] = [
        'id' => $cont,
        'exchange' => 'CEX Total',
        'liquidity' => number_format($total_liquidity, 2),
        'percentage' => $cex_liquidity_percentage . '%'
    ];

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
        </tr>
    </thead>
    <tbody>";

    foreach ($table_data as $row) {
        echo "<tr>
            <td>{$row['exchange']}</td>
            <td>{$row['liquidity']}</td>
            <td>{$row['percentage']}</td>
        </tr>";
    }


    echo "</tbody>
</table>";

    //echo "<h2>CEX Total Liquidity: " . number_format($total_liquidity, 2) . "</h2>";
    //echo "<h2>CEX Liquidity Percentage: " . $cex_liquidity_percentage . "%</h2>";
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
