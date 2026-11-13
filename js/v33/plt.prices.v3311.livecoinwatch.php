<? require_once '/home2/granna80/public_html/_www-typofx/.scr/conexao.php'; ?> 
<? require_once '/home2/granna80/public_html/_www-typofx/.scr/library/api.etherscan.php'; ?>
<? require_once '/home2/granna80/public_html/_www-typofx/.scr/library/cryptoprices.extract.php'; ?>
<? require_once '/home2/granna80/public_html/_www-typofx/.scr/library/cryptoprices.helpers.php'; ?>
<? //require_once '/home2/granna80/public_html/_www-typofx/.scr/library/json.helper.php'; // producao ?>
<? require_once __DIR__ . '/json.helper.php'; // teste: grava em ./json ?>
<? require_once '/home2/granna80/public_html/_www-typofx/.scr/library/plata.math.php'; ?>
<pre>
<?php

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

$crypto_info = json_decode(file_get_contents('https://www.typofx.ie/sirka/murilofurtado/assets/json-new.json'), true)['chain']['polygon'];
$ticker_to_nmid = array_column($crypto_info, 'nmid', 'ticker');

foreach ($fiats as $fiat) {

    if ($fiat > 0) {
        
        ${$cryptocurrency . $fiat} = round(extract_rate_from_api($api_company, $api_endpoint, $api_context, $cryptocurrency, $fiat), 10);
        ${$fiat . $cryptocurrency} = round(1 / ${$cryptocurrency . $fiat}, 10);

        $prices_vs_usd[$cryptocurrency . $fiat] = ${$cryptocurrency . $fiat};
        $usd_vs_prices[$fiat . $cryptocurrency] = ${$fiat . $cryptocurrency};
        echo $cryptocurrency . $fiat . ' : ' . $prices_vs_usd[$cryptocurrency . $fiat] . '<br>';
        echo $fiat . $cryptocurrency . ' : ' . $usd_vs_prices[$fiat . $cryptocurrency] . '<br>';
        
        save_json_token_live_ticker($fiat, ${$cryptocurrency . $fiat}, $ticker_to_nmid[$cryptocurrency]);
        
        foreach ($fiats as $fiat_i) {

            if (isset(${$cryptocurrency . $fiat}) > 0 && isset(${$cryptocurrency . $fiat_i}) > 0) {

                ${$fiat . $fiat_i} = round(${$cryptocurrency . $fiat} / ${$cryptocurrency . $fiat_i},10);
                ${$fiat_i . $fiat} = round( 1 / ${$fiat . $fiat_i} , 10);

                $fiats_prices[$fiat . $fiat_i] = ${$fiat_i . $fiat};
                echo ($fiat . $fiat_i) . ' : ' . $fiats_prices[$fiat . $fiat_i] . '<br>';
                
                save_json_fiat_live_ticker($fiat_i, ${$fiat_i . $fiat}, ${$fiat . $fiat_i}, $fiat_info[$fiat]['nmid']);
                save_json_fiat_live_ticker($fiat, ${$fiat . $fiat_i}, ${$fiat_i . $fiat}, $fiat_info[$fiat_i]['nmid']);

                if ($fiat_i != $fiat) {

                    $fiats_prices[$fiat_i . $fiat] = ${$fiat . $fiat_i};
                    echo $fiat_i . $fiat . ' : ' . $fiats_prices[$fiat_i . $fiat] . '<br>';
                    }

            }

        }

    }

}

    foreach ($fiats as $fiat) {
        save_json_token_output($api_company, $fiat, ['prices' => $fiats_prices], $fiat_info[$fiat]['nmid']);
    }

    save_json_token_output($api_company, $cryptocurrency, ['prices' => array_merge($prices_vs_usd,$usd_vs_prices)], $ticker_to_nmid[$cryptocurrency]);

    $plata_values = deploy_plata_rates($POLUSD, $POLEUR, $POLBRL, $qtd_plata_pool__0x0E1_671a6, $qtd_wmatic_pool__0x0E1_671a6, $PLT_circulating_supply);
    save_json_token_output($api_company, $company_asset, ['plt_prices' => $plata_values['plt_prices'], 'plt_marketcap' => $plata_values['plt_marketcap']], $ticker_to_nmid[$company_asset]);

    $contracts = json_decode(file_get_contents('https://www.typofx.ie/sirka/murilofurtado/assets/json-exm.json'), true)['chain']['polygon'];
    $except_contract_assets = [$cryptocurrency, $company_asset];

    foreach ($contracts as $contract) {
        $token = $crypto_info[$contract];
        if (in_array($token['ticker'], $except_contract_assets)) continue;

        $prices = [];
        foreach ($fiats as $fiat) {
            $rate = json_decode(@file_get_contents('https://www.typofx.ie/.scr/library/json/' . $token['nmid'] . '/' . $fiat . '.json'), true);
            if (!$rate) continue 2;
            $prices[$token['ticker'] . $fiat] = (float) $rate['pc'];
            $prices[$fiat . $token['ticker']] = (float) $rate['ft'];
        }
        save_json_token_output('execInfoLP', $token['ticker'], ['prices' => $prices], $token['nmid']);
    }

    foreach ($fiats as $fiat) {
        save_json_plt_live_ticker($fiat, $plata_values);
    }
    

    echo '<br>'.$api_company.' '. gmdate('d/m/y H:i:s') . ' UTC<br>';
    
    save_json_swift_status($api_company, extract_remaining_credits_from_api($api_company, $api_endpoint, $api_context), 'offline');
    print_r(json_decode(@file_get_contents('https://www.typofx.ie/.scr/library/json/status/status.'.$api_company.'.json')));
    
    echo 'done.';

?>