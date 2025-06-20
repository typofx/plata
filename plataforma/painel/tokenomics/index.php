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
    // echo "PLTUSD: " . $PLTUSD;

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
            'exchange' => trim($exchange),
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
    $plata_per_dex = [];
    $percentage_per_dex = [];

    if (is_array($data)) {
        foreach ($data as $item) {
            if (isset($item['visible']) && $item['visible'] === true && !empty($item['exchange'])) {
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
                        $plt_tokens = $item['tokenBalance_A'];
                    } elseif (strtolower($plt_contract) == strtolower($contract_b)) {
                        $liquidity = $item['QtB'];
                        $plt_Price = $item['priceB'];
                        $plt_tokens = $item['tokenBalance_B'];
                    }

                    if (isset($plt_Price) && $plt_Price > 0) {

                        $plata = $plt_tokens;
                        $percentage = ($plata / $circulating_supply);
                        $dex_liquidity = $plata * $PLTUSD;

                        $dex_individual_pools[] = [
                            'exchange'    => $item['exchange'] . " - " . $item['pair'],
                            'group'       => 'dex',
                            'liquidity'   => $dex_liquidity,
                            'percentage'  => $percentage,
                            'walletAddress' => $item['contract'],
                            'plata'       => $plata
                        ];


                        $exchange_base_name = get_dex_base_name_from_string($item['exchange']);


                        if (!isset($liquidity_per_exchange[$exchange_base_name])) {
                            $liquidity_per_exchange[$exchange_base_name] = 0;
                        }
                        $liquidity_per_exchange[$exchange_base_name] += $dex_liquidity;

                        if (!isset($plata_per_dex[$exchange_base_name])) {
                            $plata_per_dex[$exchange_base_name] = 0;
                        }
                        $plata_per_dex[$exchange_base_name] += $plata;

                        if (!isset($percentage_per_dex[$exchange_base_name])) {
                            $percentage_per_dex[$exchange_base_name] = 0;
                        }
                        $percentage_per_dex[$exchange_base_name] += $percentage;
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

        $plt = $plata_per_dex[$exchange] ?? 0;
        $percentage = $percentage_per_dex[$exchange] ?? 0;

        $table_data[] = [
            'id' => $cont,
            'exchange' => trim($exchange),
            'liquidity' => round($liquidity, 4),
            'percentage' => $percentage,
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
            $updateMonth =  "<br><b>NOTICE: A month has passed. Updating data...</b>";
        } else {
            $updateMonth = "<br><b>Next update on: " . $next_allowed_date->format('d/m/Y') . "</b>";
        }
    }




    $monthly_totals = null;

    $latest_period_res = $conn->query("SELECT MAX(record_year) as year FROM granna80_bdlinks.tokenomics_history");
    if ($latest_period_res && $latest_period_res->num_rows > 0) {
        $latest_year = $latest_period_res->fetch_assoc()['year'];

        if ($latest_year) {
            $latest_month_res = $conn->query("SELECT MAX(record_month) as month FROM granna80_bdlinks.tokenomics_history WHERE record_year = $latest_year");
            $latest_month = $latest_month_res->fetch_assoc()['month'];


            if ($latest_month) {
                $totals_sql = "SELECT
                            SUM(plata) as total_plata,
                            SUM(liquidity) as total_liquidity,
                            plt_price,
                            price_date
                           FROM granna80_bdlinks.tokenomics_history
                           WHERE record_year = $latest_year AND record_month = $latest_month
                           GROUP BY plt_price, price_date ORDER BY price_date DESC LIMIT 1";
                $monthly_totals_res = $conn->query($totals_sql);
                if ($monthly_totals_res && $monthly_totals_res->num_rows > 0) {
                    $monthly_totals = $monthly_totals_res->fetch_assoc();

                    if (isset($monthly_totals['total_plata'])) {
                        $monthly_totals['total_percentage'] = $monthly_totals['total_plata'] / $circulating_supply;
                    }
                }
            }
        }
    }






    $final_total_liquidity = array_sum(array_column($html_table_data, 'liquidity'));
    $final_total_percentage = array_sum(array_column($html_table_data, 'percentage'));
    $final_total_plata = array_sum(array_column($html_table_data, 'plata'));

    ?>

    <style>
        .totals-container {
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px 0;
            font-family: sans-serif;
        }

        .total-box {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            min-width: 350px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        .total-box h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            color: #333;
        }

        .total-box p {
            margin: 8px 0;
            font-size: 1em;
            display: flex;
            justify-content: space-between;
        }

        .total-box p strong {
            color: #555;
        }

        .total-box p small {
            margin-top: 10px;
            color: #777;
            display: block;
            text-align: right;
        }
    </style>

    <div class="totals-container">
        <div class="total-box">
            <h3>LIVE Totals</h3>
            <p><strong>PLTUSD:</strong> <span>$<?php echo number_format($PLTUSD, 10, '.', ','); ?></span></p>
            <p><strong>Liquidity:</strong> <span>$<?php echo number_format($final_total_liquidity, 4, '.', ','); ?></span></p>
            <p><strong>Percentage:</strong> <span><?php echo number_format($final_total_percentage * 100, 2, '.', ','); ?>%</span></p>
            <p><strong>Plata:</strong> <span><?php echo number_format($final_total_plata, 4, '.', ','); ?></span></p>
            <p><small>Last update on: <?php echo gmdate('d-m-Y H:i:s'); ?> UTC</small></p>
        </div>


    </div>

    <?php


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

    //$total_percentage_sum = array_sum(array_column($table_data, 'percentage'));


    //if ($total_percentage_sum > 1.0) {


    // foreach ($table_data as &$item) {


    //   $new_percentage = $item['percentage'] / $total_percentage_sum;


    //   $item['percentage'] = $new_percentage;
    //   $item['plata']      = $circulating_supply * $new_percentage;
    //   $item['liquidity']  = $item['plata'] * $PLTUSD;
    //  }
    //  unset($item);
    //   }

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






    //echo "Last update on: " . $timestamp_utc_iso . " UTC";
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







    $grouped_by_period = [];

    if (!function_exists('get_dex_base_name')) {
        function get_dex_base_name($exchange_name)
        {
            $stop_words = ['V2', 'V3', 'V4', 'DEX', 'LP'];
            $words = explode(' ', $exchange_name);
            $last_word = end($words);
            if (in_array(strtoupper($last_word), $stop_words)) {
                array_pop($words);
                return implode(' ', $words);
            }
            return $exchange_name;
        }
    }


    foreach ($final_data_for_json as $item) {
        $year = (int)$item['record_year'];
        $month = (int)$item['record_month'];
        $group_wallet = $item['group_wallet'];
        $exchange_original = $item['exchange'];
        $group_key = '';

        switch ($group_wallet) {
            case 'dex':
                $group_key = get_dex_base_name($exchange_original);
                break;
            case 'cex':
                $group_key = 'Centralized Exchanges';
                break;
            case 'typofx':
                $group_key = 'Typo FX - Wallets';
                break;
            default:
                if (!empty($group_wallet) && $group_wallet !== 'unknown') {
                    $group_key = ucwords(str_replace('_', ' ', $group_wallet));
                } else {
                    $group_key = $exchange_original;
                }
                break;
        }

        if (!isset($grouped_by_period[$year][$month][$group_key])) {

            $grouped_by_period[$year][$month][$group_key] = [
                'liquidity' => 0.0,
                'percentage' => 0.0,
                'plata' => 0.0,
                'plt_price' => 0.0,
                'price_date' => ''
            ];
        }


        $grouped_by_period[$year][$month][$group_key]['liquidity'] += (float)$item['liquidity'];
        $grouped_by_period[$year][$month][$group_key]['percentage'] += (float)$item['percentage'];
        $grouped_by_period[$year][$month][$group_key]['plata'] += (float)$item['plata'];


        $stored_date = $grouped_by_period[$year][$month][$group_key]['price_date'];
        if (empty($stored_date) || strtotime($item['price_date']) > strtotime($stored_date)) {
            $grouped_by_period[$year][$month][$group_key]['plt_price'] = (float)$item['plt_price'];
            $grouped_by_period[$year][$month][$group_key]['price_date'] = $item['price_date'];
        }
    }


    $ordered_flat_list = [];
    krsort($grouped_by_period);
    foreach ($grouped_by_period as $year => $months) {
        krsort($months);
        foreach ($months as $month => $groups) {
            $period_data_temp = [];
            foreach ($groups as $exchange_name => $data) {

                $period_data_temp[] = [
                    'exchange' => $exchange_name,
                    'liquidity' => $data['liquidity'],
                    'percentage' => $data['percentage'],
                    'plata' => $data['plata'],
                    'record_year' => $year,
                    'record_month' => $month,
                    'plt_price' => $data['plt_price'],
                    'price_date' => $data['price_date']
                ];
            }
            usort($period_data_temp, function ($a, $b) {
                return $b['liquidity'] <=> $a['liquidity'];
            });
            $ordered_flat_list = array_merge($ordered_flat_list, $period_data_temp);
        }
    }


    $json_final_grouped = [];
    $id_counter = 1;
    foreach ($ordered_flat_list as $item) {

        $json_final_grouped[] = [
            'id' => $id_counter++,
            'exchange' => $item['exchange'],
            'liquidity' => $item['liquidity'],
            'percentage' => $item['percentage'],
            'plata' => $item['plata'],
            'record_year' => $item['record_year'],
            'record_month' => $item['record_month'],
            'plt_price' => $item['plt_price'],
            'price_date' => $item['price_date']
        ];
    }


    $formatted_json_objects = [];
    if (!empty($json_final_grouped)) {
        foreach ($json_final_grouped as $json_row) {

            $current_object_string  = "    {\n";
            $current_object_string .= "        \"id\": " . (int)$json_row['id'] . ",\n";
            $current_object_string .= "        \"exchange\": \"" . addslashes($json_row['exchange']) . "\",\n";
            $current_object_string .= "        \"liquidity\": " . number_format((float)$json_row['liquidity'], 4, '.', '') . ",\n";
            $current_object_string .= "        \"percentage\": " . number_format((float)$json_row['percentage'], 4, '.', '') . ",\n";
            $current_object_string .= "        \"plata\": " . number_format((float)$json_row['plata'], 4, '.', '') . ",\n";
            $current_object_string .= "        \"record_year\": " . (int)$json_row['record_year'] . ",\n";
            $current_object_string .= "        \"record_month\": " . (int)$json_row['record_month'] . ",\n";
            $current_object_string .= "        \"plt_price\": " . number_format((float)$json_row['plt_price'], 8, '.', '') . ",\n";
            $current_object_string .= "        \"price_date\": \"" . addslashes($json_row['price_date']) . "\"\n";
            $current_object_string .= "    }";
            $formatted_json_objects[] = $current_object_string;
        }
    }


    $final_file_content = "[\n" . implode(",\n", $formatted_json_objects) . "\n]";


    file_put_contents('tokenomics_history_agrupado.json', $final_file_content);










    function get_dex_base_name_from_string($exchange_name)
    {

        $known_dexes = [
            'MM Finance',
            'CurveFi',
            'SushiSwap',
            'QuickSwap',
            'Uniswap'
        ];


        foreach ($known_dexes as $dex) {

            if (stripos($exchange_name, $dex) === 0) {
                return $dex;
            }
        }


        return explode(' ', $exchange_name)[0];
    }


    function get_authoritative_display_name($group_name)
    {
        if ($group_name == 'typofx') return 'Typo FX - Wallets';
        if ($group_name == 'cex') return 'Centralized Exchanges';
        if ($group_name == 'locker') return 'Lockers';
        if ($group_name == 'others') return 'Others';

        return ucwords(str_replace('_', ' ', $group_name)) . ' - Wallets';
    }



    $grouped_individuals = [];
    foreach ($html_table_data as $item) {
        $group_key = '';

        if ($item['group'] == 'dex') {

            $group_key = get_dex_base_name_from_string($item['exchange']);
        } else {

            $group_key = get_authoritative_display_name($item['group']);
        }

        if (!isset($grouped_individuals[$group_key])) {
            $grouped_individuals[$group_key] = [];
        }
        $grouped_individuals[$group_key][] = $item;
    }


    $table_render_data = [];
    foreach ($final_ordered_data as $group_total) {
        $group_display_name = $group_total['exchange'];

        $table_render_data[] = [
            'is_group' => true,
            'data' => $group_total
        ];

        if (isset($grouped_individuals[$group_display_name])) {
            usort($grouped_individuals[$group_display_name], function ($a, $b) {
                return $b['liquidity'] <=> $a['liquidity'];
            });

            foreach ($grouped_individuals[$group_display_name] as $individual_item) {
                $table_render_data[] = [
                    'is_group' => false,
                    'data' => $individual_item
                ];
            }
        }
    }

    ?>

    <style>
        .group-total-row {
            background-color: #ecf0f1;
            font-weight: bold;
        }

        .group-total-row td {
            border-bottom: 1px solid #bdc3c7;
            border-top: 2px solid #95a5a6;
        }
    </style>

    <?php

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

    foreach ($table_render_data as $row_item) {
        $row = $row_item['data'];
        $is_group = $row_item['is_group'];

        $row_class = $is_group ? 'group-total-row' : 'individual-row';
        echo "<tr class='{$row_class}'>";

        echo "<td>" . htmlspecialchars($row['exchange'] ?? '') . "</td>";

        echo "<td>";
        if (!$is_group) {
            $wallet_address = $row['walletAddress'] ?? '';
            if (!empty($wallet_address)) {
                echo "<a href='https://polygonscan.com/address/{$wallet_address}' target='_blank'>"
                    . substr($wallet_address, 0, 6) . "..." . substr($wallet_address, -4)
                    . "</a>";
            }
        }
        echo "</td>";

        echo "<td><b>";
        if ($is_group) {
            echo "TOTAL GROUP";
        } else {
            echo htmlspecialchars($row['group'] ?? '');
        }
        echo "</b></td>";

        echo "<td>" . number_format($row['plata'] ?? 0, 4, '.', ',') . "</td>";
        echo "<td>" . number_format($row['liquidity'] ?? 0, 4, '.', ',') . "</td>";
        echo "<td>" . number_format(($row['percentage'] ?? 0) * 100, 4, '.', ',') . "%</td>";

        echo "</tr>";
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
                "pageLength": -1,
                "lengthMenu": [
                    [100, 200, 500, -1],
                    [100, 200, 500, "All"]
                ],

                "order": []
            });
        });
    </script>


</body>

</html>