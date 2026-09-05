<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/.scr/conexao.php';?>

<?php
session_start();
ob_start();
//require '/home2/granna80/public_html/js/cryptoprices.php';
ob_end_clean();

date_default_timezone_set('UTC');

$query_api = "SELECT is_active FROM granna80_bdlinks.api_control WHERE id = 1";
$result_api = mysqli_query($conn, $query_api);
$row_api = mysqli_fetch_assoc($result_api);
$api_actived = $row_api['is_active'];

if (isset($_POST['toggle_api'])) {
    $new_state = $api_actived ? 0 : 1;
    $update_query = "UPDATE granna80_bdlinks.api_control SET is_active = ? WHERE id = 1";
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param('i', $new_state);
    $stmt_update->execute();
    $api_actived = $new_state;
}

$live_price = $USDPLT;
$live_market_cap = isset($PLTmarketcapUSD) ? floatval(str_replace(',', '', $PLTmarketcapUSD)) : 0;
$live_volume = 0;

if ($BTCUSD == 62481) {
    echo '<b>API : OFFINE</b>';
}


if ($api_actived) {
    $data_req = json_encode(array('currency' => 'USD', 'code' => '______PLT', 'meta' => true));
    $context_options = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-type: application/json\r\n" . "x-api-key: 135b0af8-e18a-42a4-bce7-ed193b2932e6\r\n",
            'content' => $data_req,
            'timeout' => 5
        )
    );
    $context = stream_context_create($context_options);
    $response = @file_get_contents('https://api.livecoinwatch.com/coins/single', false, $context);

    if ($response !== FALSE) {
        $json_data_live = json_decode($response, true);
        if (isset($json_data_live['volume'])) {
            $live_volume = $json_data_live['volume'];
        }
    }
}

$live_json_file_path = 'live_token_data.json';
$live_file_handle = fopen($live_json_file_path, 'w');


$now_obj = new DateTime('now', new DateTimeZone('UTC'));
$live_formatted_date = $now_obj->format('d-m-Y H:i:s') . ' UTC';


$live_json_line = "{\n";
$live_json_line .= "  \"date\": \"" . $live_formatted_date . "\",\n";
$live_json_line .= "  \"price\": " . number_format((float)$live_price, 10, '.', '') . ",\n";
$live_json_line .= "  \"volume\": " . number_format((float)$live_volume, 4, '.', '') . ",\n";
$live_json_line .= "  \"market_cap\": " . number_format((float)$live_market_cap, 4, '.', '') . "\n";
$live_json_line .= "}";


fwrite($live_file_handle, $live_json_line);
fclose($live_file_handle);



$utcDate = new DateTime('now', new DateTimeZone('UTC'));
$currentDate = $utcDate->format('Y-m-d');
$currentDateTime = $utcDate->format('Y-m-d H:i:s');

$query_check = "SELECT id, price, volume, market_cap FROM granna80_bdlinks.token_historical_data WHERE DATE(date) = ?";
$stmt_check = $conn->prepare($query_check);
$stmt_check->bind_param('s', $currentDate);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows == 0 && $api_actived) {

    $insertQuery = "INSERT INTO granna80_bdlinks.token_historical_data (date, price, volume, market_cap) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insertQuery);

    $stmt_insert->bind_param('sdds', $currentDateTime, $live_price, $live_volume, $live_market_cap);
    $stmt_insert->execute();
}


$sql_table = "SELECT id, date, price, volume, market_cap FROM granna80_bdlinks.token_historical_data ORDER BY date DESC";
$result_table = mysqli_query($conn, $sql_table);
$data_table = [];
while ($row_table = mysqli_fetch_assoc($result_table)) {
    $data_table[] = $row_table;
}



$json_file_path = 'token_data.json';
$file_handle = fopen($json_file_path, 'w');


fwrite($file_handle, "[\n");


$last_index = count($data_table) - 1;
$counter_id = 1;

foreach ($data_table as $index => $item) {

    $date_obj = new DateTime($item['date'], new DateTimeZone('UTC'));
    $formatted_date = $date_obj->format('d-m-Y H:i:s') . ' UTC';


    $json_line = "  {\n";
    $json_line .= "    \"id\": " . $counter_id . ",\n";
    $json_line .= "    \"date\": \"" . $formatted_date . "\",\n";
    $json_line .= "    \"price\": " . number_format((float)$item['price'], 10, '.', '') . ",\n";
    $json_line .= "    \"volume\": " . number_format((float)$item['volume'], 4, '.', '') . ",\n";
    $json_line .= "    \"market_cap\": " . number_format((float)$item['market_cap'], 4, '.', '') . "\n";
    $json_line .= "  }";


    if ($index < $last_index) {
        $json_line .= ",\n";
    } else {
        $json_line .= "\n";
    }


    fwrite($file_handle, $json_line);
    $counter_id++;
}


fwrite($file_handle, "]");
fclose($file_handle);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historical Data</title>

    <?php echo "Circulating Supply : 11,299,000,992.0000" ?>

    <link rel="stylesheet" href="https://www.typofx.ie/.scr/jquery.dataTables.min.css">
    <link rel="stylesheet" href="historicaldata.style.css">

    <script src="https://www.typofx.ie/.scr/jquery-3.5.1.min.js"></script>
    <script src="https://www.typofx.ie/.scr/jquery.dataTables.min.js"></script>
    <script src="historicaldata.config.js"></script>
</head>

<body>

    <div class="container">
        <h2>Token Historical Data</h2>
        <br>
        <a href="<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/main.php'; ?>">[Back]</a>
        <a href="historicaldata.add.php">[Add new record]</a>
        <a href="token_data.json" target="_blank">[Json]</a>
        <a href="live_plt.json" target="_blank">[Live Json]</a>
        <a href="historicaldata.fetch.php">[Fetch]</a>
        <h4>API Status: <?php echo $api_actived ? 'Activated' : 'Disabled'; ?></h4>

        <form method="post" style="display: inline-block;">
            <button type="submit" name="toggle_api">
                <?php echo $api_actived ? 'Disable API' : 'Enable API'; ?>
            </button>
        </form>
        <br><br><br>

        <?php

        $sql_table = "SELECT id, date, price, volume, market_cap FROM granna80_bdlinks.token_historical_data ORDER BY date DESC";
        $result_table = mysqli_query($conn, $sql_table);
        $data_table = [];
        while ($row_table = mysqli_fetch_assoc($result_table)) {
            $data_table[] = $row_table;
        }
        ?>

        <table id="tokenTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Price</th>
                    <th>Volume</th>
                    <th>Market Cap</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color: bisque;">
                    <td style="background-color: bisque;">0</td>
                    <td>
                        <?php

                        $now = new DateTime('now', new DateTimeZone('UTC'));
                        echo $now->format('d-m-Y H:i:s') . ' UTC';
                        ?>
                    </td>
                    <td><?= number_format($live_price, 10, '.', ','); ?></td>
                    <td><?= number_format($live_volume, 4, '.', ','); ?></td>
                    <td><?= number_format($live_market_cap, 4, '.', ','); ?></td>
                    <td>LIVE</td>
                </tr>

                <?php $cont = 1; ?>
                <?php foreach ($data_table as $row): ?>
                    <tr>
                        <td><?= $cont; ?></td>
                        <td>
                            <?php

                            $date_obj = new DateTime($row['date'], new DateTimeZone('UTC'));
                            echo $date_obj->format('d-m-Y H:i:s') . ' UTC';
                            ?>
                        </td>
                        <td><?= number_format($row['price'], 10, '.', ','); ?></td>
                        <td><?= number_format($row['volume'], 4, '.', ','); ?></td>
                        <td><?= number_format($row['market_cap'], 4, '.', ','); ?></td>
                        <td><?php echo "<a href='historicaldata.edit.php?id={$row['id']}'>Edit</a>"; ?></td>
                    </tr>
                <?php $cont++;
                endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>