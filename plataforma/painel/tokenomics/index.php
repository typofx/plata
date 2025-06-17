<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>

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
    <a href="<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/main.php'; ?>">[Back]</a>
    <a href="javascript:window.location.reload(true)">[Refresh]</a>
    <a href="menu.php">[DeFi]</a>
    <a href="tokenomics_history.php">[Tokenomics History]</a>
    <a href="https://typofx.ie/plataforma/panel/lp-contracts/index.php">[Lp Contracts]</a>
    <!-- PHP -->

    <?php
    ob_start();
    require '/home2/granna80/public_html/en/mobile/price.php';
    ob_end_clean();

    echo "<h2>Market Capitalization: " . $PLTmarketcap . "</h2>";
    echo "PLTUSD: " . $PLTUSD;

    $circulating_supply = 11299000992;

    include 'conexao.php';

    $sql = "SELECT walletname, balance, wallet_group, walletAddress FROM granna80_bdlinks.tokenomics WHERE visible = 1";

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




    $all_db_wallets = [];
    $total_data = [];

    while ($row = $result->fetch_assoc()) {
        // Cálculos
        $walletname = $row['walletname'];
        $group = $row['wallet_group'];
        $walletAddressData = $row['walletAddress'];
        $plt = $row['balance'] / 10000;
        $liquidity = ($PLTUSD * $plt);
        $percentage = ($plt / $circulating_supply);


        $all_db_wallets[] = [
            'exchange'   => $walletname,
            'walletAddress' =>  $walletAddressData,
            'group'      => $group,
            'liquidity'  => $liquidity,
            'percentage' => $percentage,
            'plata'      => $plt,
        ];


        if ($group == 'typofx') {
            $exchange = 'Typo FX - Wallets';
        } elseif ($group == 'cex') {
            $exchange = 'Centralized Exchanges';
        } elseif ($group == 'locker') {
            $exchange = 'Lockers';
        } else {

            $exchange = ucwords(str_replace('_', ' ', $group)) . ' - Wallets';
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
            'liquidity' => round($data['liquidity'], 4),
            'percentage' => round($data['percentage'], 5),
            'plata' => $data['plata']
        ];
    }




    $json_url = 'https://typofx.ie/plataforma/panel/lp-contracts/lp_contracts.json';

    // Fetch JSON data from the URL
    $json_data = file_get_contents($json_url);

    // Decode JSON data into an associative array
    $data = json_decode($json_data, true);

    $plt_contract = '0xc298812164bd558268f51cc6e3b8b5daaf0b6341';
    $liquidity_per_exchange = [];
    $dex_individual_pools = [];

    if (is_array($data)) {
        foreach ($data as $item) {
            if (isset($item['visible']) && $item['visible'] === true) {
                if (isset($item['exchange']) && isset($item['liquidity'])) {
                    $contract_a = $item['contract_a'];
                    $contract_b = $item['contract_b'];
                    $priceA = $item['priceA'];
                    $priceB = $item['priceB'];
                    $QtA = $item['QtA'];
                    $QtB = $item['QtB'];
                    $pool_contract_json =  $item['contract'];

                    if (strtolower($plt_contract) == strtolower($contract_a)) {
                        $liquidity = $item['QtA'];
                        $plt_Price = $item['priceA'];
                    } elseif (strtolower($plt_contract) == strtolower($contract_b)) {
                        $liquidity = $item['QtB'];
                        $plt_Price = $item['priceB'];
                    }

                    if ($plt_Price > 0) {

                        $plata = $liquidity / $plt_Price;
                        $percentage = ($plata / $circulating_supply);

                        $dex_individual_pools[] = [
                            'exchange'   => $item['exchange'] . " - " . $item['pair'],
                            'group'      => 'dex',
                            'liquidity'  => $liquidity,
                            'percentage' => $percentage,
                            'walletAddress' => $pool_contract_json,
                            'plata'      => $plata
                        ];


                        $exchange = $item['exchange'];
                        if ($exchange === 'SushiSwap V3') {
                            $exchange = 'SushiSwap';
                        }
                        if ($exchange === 'QuickSwap V3') {
                            $exchange = 'QuickSwap';
                        }
                        if ($exchange === 'QuickSwap V2') {
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
            }
        }
    }



    $json_url = 'https://typofx.ie/plataforma/panel/order-book/order_book_data.json';

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
    $cex_liquidity_percentage = round($cex_result / 1000, 5);


    $table_data = [];
    $cont = 1;
    foreach ($liquidity_per_exchange as $exchange => $liquidity) {
        $marketcap_float = floatval($PLTmarketcap);
        $liquidity_float = floatval($liquidity);
        $dex_result = ($liquidity_float / $marketcap_float) * 100;
        $dex_liquidity_percentage = round($dex_result / 1000, 5);
        $dex_liquidity_percentage_d = $dex_liquidity_percentage / 100;
        $plt = $circulating_supply * $dex_liquidity_percentage_d;

        $table_data[] = [
            'id' => $cont,
            'exchange' => $exchange,
            'liquidity' => round($liquidity, 4),
            'percentage' => round($dex_liquidity_percentage / 100, 5),
            'plata' => $plt
        ];
        $cont++;
    }
    $cex_liquidity_percentage_d = $cex_liquidity_percentage / 100;
    $plt = $circulating_supply * $cex_liquidity_percentage_d;

    // Add row for total CEX liquidity
    //$table_data[] = [
    //   'id' => $cont,
    //    'exchange' => 'Centralized Exchanges',
    //    'liquidity' => round($total_liquidity, 2),
    //   'percentage' => $cex_liquidity_percentage / 100,
    //   'plata' => $cexplt
    //  ];



    $cont++;



    // Merge table_data2 into table_data and adjust IDs
    foreach ($table_data2 as $row) {
        $row['id'] = $cont;
        $table_data[] = $row;
        $cont++;
    }
    $total_percentage_sum = array_sum(array_column($table_data, 'percentage'));
    $total_liquidity_sum = array_sum(array_column($table_data, 'liquidity'));
    $total_plata_sum = array_sum(array_column($table_data, 'plata'));

    $sum_plata_db_wallets = array_sum(array_column($all_db_wallets, 'plata'));


    $sum_plata_dex_pools = array_sum(array_column($dex_individual_pools, 'plata'));


    $correct_total_plata_sum = $sum_plata_db_wallets + $sum_plata_dex_pools;


    $remaining_plt = $circulating_supply - $correct_total_plata_sum;






    $remaining_percentage = $remaining_plt / $circulating_supply;
    $remaining_liquidity = $remaining_plt * $PLTUSD;

    $table_data[] = [
        'id' => $cont,
        'exchange' => 'Others',
        'liquidity' => round($remaining_liquidity, 4),
        'percentage' => round($remaining_percentage, 4),
        'plata' => $remaining_plt
    ];



    $cont++;
    $html_table_data = array_merge($all_db_wallets, $dex_individual_pools);




    foreach ($table_data as $row) {
        if ($row['exchange'] === 'Others') {
            $row['group'] = 'others';
            $html_table_data[] = $row;
            break;
        }
    }


    usort($html_table_data, function ($a, $b) {
        return $b['liquidity'] <=> $a['liquidity'];
    });






    $final_total_liquidity = array_sum(array_column($html_table_data, 'liquidity'));
    $final_total_percentage = array_sum(array_column($html_table_data, 'percentage'));
    $final_total_plata = array_sum(array_column($html_table_data, 'plata'));

    echo "<br><br>";
    echo "<br>Total: ";
    echo  "<br>liquidity: " . number_format($final_total_liquidity, 2, '.', ',');
    echo "<br>percentage: " . number_format($final_total_percentage * 100, 2) . "%";
    echo "<br>plata: " . number_format($final_total_plata, 2, '.', '');
    echo "<br><br>";

    echo "<br><br>";




    usort($table_data, function ($a, $b) {
        return $b['liquidity'] <=> $a['liquidity'];
    });


    $others_item_array = [];
    $regular_items = [];
    foreach ($table_data as $item) {
        if ($item['exchange'] === 'Others') {
            $others_item_array[] = $item;
        } else {
            $regular_items[] = $item;
        }
    }

    $total_percentage_sum = array_sum(array_column($table_data, 'percentage'));


    if ($total_percentage_sum > 1.0) {


        foreach ($table_data as &$item) {


            $new_percentage = $item['percentage'] / $total_percentage_sum;


            $item['percentage'] = $new_percentage;
            $item['plata']      = $circulating_supply * $new_percentage;
            $item['liquidity']  = $item['plata'] * $PLTUSD;
        }
        unset($item);
    }

    usort($table_data, function ($a, $b) {
        return $b['liquidity'] <=> $a['liquidity'];
    });



    $final_ordered_data = [];
    $others_final_item = null;
    $regular_final_items = [];
    foreach ($table_data as $item) {
        if ($item['exchange'] === 'Others') {
            $item['id'] = 999;
            $others_final_item = $item;
        } else {
            $regular_final_items[] = $item;
        }
    }


    foreach ($regular_final_items as $index => &$entry) {
        $entry['id'] = $index + 1;
    }
    unset($entry);


    $final_ordered_data = $regular_final_items;
    if ($others_final_item !== null) {
        $final_ordered_data[] = $others_final_item;
    }



    $file = fopen('liquidity_data.json', 'w');
    fwrite($file, "[\n");


    $timestamp_utc_iso = gmdate('d-m-Y H:i:s');
    $timestamp_json = "  {\n    \"timestamp\": \"$timestamp_utc_iso UTC\",\n    \"pltprice\": $PLTUSD\n  }";
    fwrite($file, $timestamp_json);


    if (!empty($final_ordered_data)) {
        fwrite($file, ",\n");
    }

    $lastIndex = count($final_ordered_data) - 1;
    foreach ($final_ordered_data as $i => $item) {

        $jsonLine = "  {\n";
        $jsonLine .= "    \"id\": {$item['id']},\n";
        $jsonLine .= "    \"exchange\": \"" . addslashes($item['exchange']) . "\",\n";
        $jsonLine .= "    \"liquidity\": " . number_format((float)$item['liquidity'], 4, '.', '') . ",\n";
        $jsonLine .= "    \"percentage\": " . number_format((float)$item['percentage'], 4, '.', '') . ",\n";
        $jsonLine .= "    \"plata\": " . number_format((float)$item['plata'], 4, '.', '') . "\n";
        $jsonLine .= "  }";

        if ($i < $lastIndex) {
            $jsonLine .= ",\n";
        } else {
            $jsonLine .= "\n";
        }

        fwrite($file, $jsonLine);
    }


    fwrite($file, "]");
    fclose($file);






    echo "Last update on: " . $timestamp_utc_iso . " UTC";
    echo "<br>";



    $record_year = date('Y');
    $record_month = date('m');

    $json_url = "https://typofx.ie/plataforma/panel/token-historical-data/token_data.json";
    $json_data = @file_get_contents($json_url);

    $new_historical_price = null;
    $new_historical_date = null;

    if ($json_data) {
        $historical_data = json_decode($json_data, true);
        if (is_array($historical_data)) {

            foreach ($historical_data as $record) {
                if (isset($record['date']) && isset($record['price'])) {
                    $record_date_obj = new DateTime($record['date']);

                    if ($record_date_obj->format('Y') == $record_year && $record_date_obj->format('m') == $record_month) {

                        if ($new_historical_date === null || $record_date_obj->format('Y-m-d H:i:s') > $new_historical_date) {
                            $new_historical_price = $record['price'];
                            $new_historical_date  = $record_date_obj->format('Y-m-d H:i:s');
                        }
                    }
                }
            }
        }
    }


    $db_date_for_month = null;


    $check_sql = "SELECT price_date FROM granna80_bdlinks.tokenomics_history WHERE record_year = ? AND record_month = ? ORDER BY price_date DESC LIMIT 1";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ii", $record_year, $record_month);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        $db_date_for_month = $row['price_date'];
    }
    $stmt_check->close();






    $should_run = false;


    $sql_check = "SELECT MAX(price_date) AS last_date FROM granna80_bdlinks.tokenomics_history";
    $result_check = $conn->query($sql_check);
    $row = $result_check->fetch_assoc();
    $last_saved_date = $row['last_date'];

    if ($last_saved_date === null) {
       
        $should_run = true;
        echo "<br><b>NOTICE: First run. Inserting data...</b>";
    } else {
      
        $last_date = new DateTime($last_saved_date);
        $next_allowed_date = $last_date->add(new DateInterval('P1M')); 
        $today = new DateTime();

      
        if ($today >= $next_allowed_date) {
            $should_run = true;
            echo "<br><b>NOTICE: A month has passed. Updating data...</b>";
        } else {
            echo "<br><b>Next update on: " . $next_allowed_date->format('d/m/Y') . "</b>";
        }
    }

    if ($should_run) {





        $sql_insert = "
INSERT INTO granna80_bdlinks.tokenomics_history (
    record_year, record_month, exchange, group_wallet, walletAddress, liquidity, percentage, plata, plt_price, price_date
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql_insert);

        if ($stmt === false) {
            echo "<br>Error preparing statement: " . $conn->error;
        } else {

            $stmt->bind_param("iisssdddds", $record_year, $record_month, $exchange_name, $group_wallet_val, $wallet_address_val, $liquidity_val, $percentage_val, $plata_val, $new_historical_price, $new_historical_date);

            $inserted_count = 0;
            $updated_count = 0;


            foreach ($html_table_data as $row) {
                $exchange_name  = $row['exchange'];
                $group_wallet_val = $row['group'];
                $wallet_address_val = $row['walletAddress'] ?? null;
                $liquidity_val  = $row['liquidity'];
                $percentage_val = $row['percentage'];
                $plata_val      = $row['plata'];

                $stmt->execute();

                if ($stmt->affected_rows === 1) {
                    $inserted_count++;
                } elseif ($stmt->affected_rows === 2) {
                    $updated_count++;
                }
            }



            $stmt->close();
        }


        echo "<br>";
    }


    $json_history_file_path = 'tokenomics_history.json';


    if (!function_exists('get_group_for_aggregated_exchange')) {
        function get_group_for_aggregated_exchange($exchange_name)
        {
            $exchange_name_lower = strtolower($exchange_name);
            if (in_array($exchange_name, ['SushiSwap', 'QuickSwap', 'Uniswap'])) {
                return 'dex';
            }
            if ($exchange_name === 'Centralized Exchanges') {
                return 'cex';
            }
            if ($exchange_name === 'Typo FX - Wallets') {
                return 'typofx';
            }
            if ($exchange_name === 'Lockers') {
                return 'locker';
            }
            if ($exchange_name === 'Others') {
                return 'others';
            }

            if (strpos($exchange_name_lower, ' - wallets') !== false) {
                return strtolower(str_replace(' - wallets', '', $exchange_name_lower));
            }
            return 'unknown';
        }
    }


    $current_year_for_query = date('Y');
    $historical_data_from_db = [];
    $sql_history = "
        SELECT id, record_year, record_month, exchange, group_wallet, liquidity, percentage, plata, plt_price, price_date
        FROM granna80_bdlinks.tokenomics_history
        WHERE record_year < ?
        ORDER BY record_year DESC, record_month DESC, exchange ASC
    ";
    $stmt_history = $conn->prepare($sql_history);
    if ($stmt_history) {
        $stmt_history->bind_param("i", $current_year_for_query);
        $stmt_history->execute();
        $result_history = $stmt_history->get_result();
        while ($row = $result_history->fetch_assoc()) {
            $historical_data_from_db[] = $row;
        }
        $stmt_history->close();
    }


    $current_year_for_json = [];

    foreach ($table_data as $item) {
        $current_year_for_json[] = [
            'id' => $item['id'],
            'record_year' => $record_year,
            'record_month' => $record_month,
            'exchange' => $item['exchange'],
            'group_wallet' => get_group_for_aggregated_exchange($item['exchange']),
            'liquidity' => $item['liquidity'],
            'percentage' => $item['percentage'],
            'plata' => $item['plata'],
            'plt_price' => $new_historical_price ?? $PLTUSD,
            'price_date' => $new_historical_date ?? date('Y-m-d H:i:s')
        ];
    }



    $final_data_for_json = array_merge($current_year_for_json, $historical_data_from_db);

    $formatted_json_objects = [];
    if (!empty($final_data_for_json)) {
        foreach ($final_data_for_json as $json_row) {
            $current_object_string  = "  {\n";
            $current_object_string .= "    \"id\": " . (int)$json_row['id'] . ",\n";
            $current_object_string .= "    \"record_year\": " . (int)$json_row['record_year'] . ",\n";
            $current_object_string .= "    \"record_month\": " . (int)$json_row['record_month'] . ",\n";
            $current_object_string .= "    \"exchange\": \"" . addslashes($json_row['exchange']) . "\",\n";
            $current_object_string .= "    \"liquidity\": " . number_format((float)$json_row['liquidity'], 4, '.', '') . ",\n";
            $current_object_string .= "    \"percentage\": " . number_format((float)$json_row['percentage'], 4, '.', '') . ",\n";
            $current_object_string .= "    \"plata\": " . number_format((float)$json_row['plata'], 4, '.', '') . ",\n";
            $current_object_string .= "    \"plt_price\": " . number_format((float)$json_row['plt_price'], 8, '.', '') . ",\n";
            $current_object_string .= "    \"group_wallet\": \"" . addslashes($json_row['group_wallet']) . "\",\n";
            $current_object_string .= "    \"price_date\": \"" . addslashes($json_row['price_date']) . "\"\n";
            $current_object_string .= "  }";
            $formatted_json_objects[] = $current_object_string;
        }
    }

    $final_file_content = "[\n" . implode(",\n", $formatted_json_objects) . "\n]";

    if (file_put_contents($json_history_file_path, $final_file_content)) {
    } else {
    }







    // Display HTML table with static data
    echo "
<table id='liquidityTable' class='display'>
    <thead>
        <tr>
            <th>Tag</th>
            <th>Wallet Adress</th>
            <th>Group</th>
            <th>Plata Token (PLT)</th>
            <th>Liquidity</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>";


    foreach ($html_table_data as $row) {
        echo "<tr>";

        // 1. Tag
        echo "<td>{$row['exchange']}</td>";

        // 2. Wallet Adress
        $wallet_address = $row['walletAddress'] ?? '';
        echo "<td>";
        if (!empty($wallet_address)) {
            echo "<a href='https://polygonscan.com/address/{$wallet_address}' target='_blank'>"
                . substr($wallet_address, 0, 6) . "..." . substr($wallet_address, -4)
                . "</a>";
        }
        echo "</td>";

        // 3. Group
        echo "<td><b>" . ($row['group'] ?? '') . "</b></td>";

        // 4. Plata Token (PLT)
        echo "<td>" . number_format($row['plata'], 4, '.', ',') . "</td>";

        // 5. Liquidity
        echo "<td>" . number_format($row['liquidity'], 2, '.', ',') . "</td>";


        echo "<td>" . number_format($row['percentage'], 4, '.', ',') . "</td>";

        echo "</tr>";
    }

    echo "</tbody>
</table>";








    function formatPercentages(array $data): array
    {
        foreach ($data as &$item) {
            if (isset($item['percentage']) && is_numeric($item['percentage'])) {
                $formatted = number_format($item['percentage'], 5, '.', '');
                if (substr($formatted, -6) === '0000') {
                    $item['percentage'] = (float)substr_replace($formatted, '1', -1);
                }
            }
        }
        unset($item);

        return $data;
    }



    ?>

    <script>
        $(document).ready(function() {
            var table = $('#liquidityTable').DataTable({
                "paging": true,
                "searching": true,
                "info": true,
                "pageLength": 100,
                "lengthMenu": [
                    [100, 200, 500, -1],
                    [100, 200, 500, "All"]
                ],
                "order": [
                    [4, 'desc']
                ],
                "rowCallback": function(row, data, index) {
                    if (data[0] === "Others") {
                        $(row).addClass('row-others');
                    }
                },
                "drawCallback": function(settings) {
                    var api = this.api();
                    var othersRows = api.rows('.row-others').nodes();
                    if (othersRows.length) {
                        $(othersRows).detach().appendTo(api.table().body());
                    }
                }
            });
        });
    </script>


</body>

</html>