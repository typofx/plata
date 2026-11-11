<? require_once '/home2/granna80/public_html/_www-typofx/.scr/conexao.php'; ?> 
<? require_once '/home2/granna80/public_html/_www-typofx/.scr/library/api.etherscan.php'; ?>
<? require_once '/home2/granna80/public_html/_www-typofx/.scr/library/cryptoprices.extract.php'; ?>
<? require_once '/home2/granna80/public_html/_www-typofx/.scr/library/cryptoprices.helpers.php'; ?>
<? require_once '/home2/granna80/public_html/_www-typofx/.scr/library/json.helper.php'; ?>
<? //require_once 'json.helper-addon.php'; ?>
<? require_once '/home2/granna80/public_html/_www-typofx/.scr/library/plata.math.php'; ?>
<pre>
<?php

function save_json_token_output($source_name, $_ticker, $_prices, $_token_name)
{
    $json_dir = __DIR__ . '/json/' . $_token_name . '/' . gmdate('Y/m/d/H') . 'H';
    if (!is_dir($json_dir))
        mkdir($json_dir, 0755, true);

    $output = [
        'last_updated_at' => gmdate('d-m-Y H:i:s') . ' UTC',
        'id' => $source_name . ' v3',
        'prices' => $_prices
    ];

    $filename = $json_dir . '/' . $_ticker . '.' . gmdate('Y-m-d@H:i:s:') . 'UTC.json';

    file_put_contents(
        $filename,
        json_encode($output, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION)
    );

    return $filename;
}

//if (file_exists('error_log')) unlink('error_log');

$api_company = 'livecoinwatch';
$api_context = stream_context_create(['http' => ['method' => 'POST', 'header' => "Content-Type: application/json\r\nx-api-key:{$API_KEY_LIVECOINWATCH}\r\n"]]);
$api_endpoint = 'https://api.livecoinwatch.com/';

    echo $api_company.' '. gmdate('d/m/y H:i:s') . ' UTC<br><br>';

    save_json_swift_status($api_company, extract_remaining_credits_from_api($api_company, $api_endpoint, $api_context), 'online');
    print_r(json_decode(@file_get_contents('https://www.typofx.ie/.scr/library/json/status/status.'.$api_company.'.json')));

$PLT_circulating_supply = 11299000992;
$cryptocurrency = 'POL';
$company_asset = 'PLT';

$fiat_info = json_decode(file_get_contents('https://www.typofx.ie/plataforma/panel/asset/fiat.json'), true);
$fiats = array_keys($fiat_info);

foreach ($fiats as $fiat) {
    
    if ($fiat > 0) {
        ${$cryptocurrency . $fiat} = round(extract_rate_from_api($api_company, $api_endpoint, $api_context, $cryptocurrency, $fiat), 10);
        ${$fiat . $cryptocurrency} = round(1 / ${$cryptocurrency . $fiat}, 10);

        $prices_vs_usd[$cryptocurrency . $fiat] = ${$cryptocurrency . $fiat};
        $usd_vs_prices[$fiat . $cryptocurrency] = ${$fiat . $cryptocurrency};
        echo $cryptocurrency . $fiat . ' : ' . $prices_vs_usd[$cryptocurrency . $fiat] . '<br>';
        echo $fiat . $cryptocurrency . ' : ' . $usd_vs_prices[$fiat . $cryptocurrency] . '<br>';

        foreach ($fiats as $fiat_i) {
            if (isset(${$cryptocurrency . $fiat}) > 0 && isset(${$cryptocurrency . $fiat_i}) > 0) /* $fiat != $fiat_i &&*/  {
                ${$fiat . $fiat_i} = round(${$cryptocurrency . $fiat} / ${$cryptocurrency . $fiat_i},10);
                ${$fiat_i . $fiat} = round( 1 / ${$fiat . $fiat_i} , 10);

                $fiats_prices[$fiat . $fiat_i] = ${$fiat . $fiat_i};
                echo ($fiat . $fiat_i) . ' : ' . $fiats_prices[$fiat . $fiat_i] . '<br>';

                save_json_fiat_live_ticker($fiat, ${$fiat . $fiat_i}, ${$fiat_i . $fiat}, $fiat_info[$fiat_i]['nmid']);

                if ($fiat_i != $fiat) {
                    $fiats_prices[$fiat_i . $fiat] = ${$fiat_i . $fiat};
                    echo $fiat_i . $fiat . ' : ' . $fiats_prices[$fiat_i . $fiat] . '<br>';
                    save_json_fiat_live_ticker($fiat_i, ${$fiat_i . $fiat}, ${$fiat . $fiat_i}, $fiat_info[$fiat]['nmid']);
                }

            } 
            /*elseif($fiat == $fiat_i) {
                save_json_fiat_live_ticker($fiat, 1, $fiat_info[$fiat]['nmid']);
                save_json_fiat_live_ticker($fiat_i, 1, $fiat_info[$fiat_i]['nmid']);
            }*/
        }        
    }
}

//print_r($fiats_prices);


$plata_values = deploy_plata_rates($POLUSD, $POLEUR, $POLBRL, $qtd_plata_pool__0x0E1_671a6, $qtd_wmatic_pool__0x0E1_671a6, $PLT_circulating_supply);

    save_json_plt_output($api_company, $plata_values); 
    
    foreach ([$cryptocurrency, 'W' . $cryptocurrency] as $ticker) {
        $token_prices = [];
        foreach ($fiats as $fiat) {
            $token_prices[$ticker . $fiat] = ${$cryptocurrency . $fiat};
            $token_prices[$fiat . $ticker] = ${$fiat . $cryptocurrency};
        }
        save_json_token_output($api_company, $ticker, $token_prices, 'polygon');
    }

    foreach ($fiats as $fiat) {
        save_json_token_live_ticker($fiat, ${$cryptocurrency . $fiat}, 'polygon');
        save_json_plt_live_ticker($fiat, $plata_values);
    }
    
/*
    // euro/USD.json = 1 EUR em USD = $fiats_prices['USDEUR']
    $fiat_info = json_decode(file_get_contents('https://www.typofx.ie/plataforma/panel/asset/fiat.json'), true);
    foreach ($fiats as $fiat) $fiats_prices[$fiat . $fiat] = 1;
    foreach ($fiats as $fiat_base) {
        if (!isset($fiat_info[$fiat_base])) continue;
        foreach ($fiats as $fiat_quote) {
            save_json_fiat_live_ticker($fiat_quote, $fiats_prices[$fiat_quote . $fiat_base], $fiats_prices[$fiat_base . $fiat_quote], $fiat_info[$fiat_base]['nmid']);
        }
    }
*/

    echo '<br>'.$api_company.' '. gmdate('d/m/y H:i:s') . ' UTC<br>';
    
    save_json_swift_status($api_company, extract_remaining_credits_from_api($api_company, $api_endpoint, $api_context), 'offline');
    print_r(json_decode(@file_get_contents('https://www.typofx.ie/.scr/library/json/status/status.'.$api_company.'.json')));

?>