<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 


session_start();



require '/home2/granna80/public_html/js/cryptoprices.php';
include('conexao.php');

date_default_timezone_set('UTC');



$live_price = isset($PLTUSD) ? floatval($PLTUSD) : 0;
$live_market_cap = isset($PLTmarketcapUSD) ? floatval(str_replace(',', '', $PLTmarketcapUSD)) : 0;
$live_volume = 0;

$query_api = "SELECT is_active FROM granna80_bdlinks.api_control WHERE id = 1";
$result_api = mysqli_query($conn, $query_api);
$row_api = mysqli_fetch_assoc($result_api);
$api_activate = $row_api['is_active'];

if ($api_activate) {
    $data_req = json_encode(array('currency' => 'USD', 'code' => '______PLT', 'meta' => true));
    $context_options = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-type: application/json\r\n" . "x-api-key: \r\n",
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


$now_obj = new DateTime('now', new DateTimeZone('UTC'));
$live_formatted_date = $now_obj->format('d-m-Y H:i:s') . ' UTC';


$json_output = "{\n";
$json_output .= "  \"date\": \"" . $live_formatted_date . "\",\n";
$json_output .= "  \"price\": " . number_format((float)$live_price, 10, '.', '') . ",\n";
$json_output .= "  \"volume\": " . number_format((float)$live_volume, 4, '.', '') . ",\n";
$json_output .= "  \"market_cap\": " . number_format((float)$live_market_cap, 4, '.', '') . "\n";
$json_output .= "}";


echo $json_output;


exit();
?>