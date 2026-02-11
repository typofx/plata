<?php include ('/home2/granna80/%/env.php'); ?>


<?php

//echo '<pre>';

$PLT_circulating_supply = 11299000992;

$json_wmatic_pool__0x0E1_671a6 = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey='.$API_KEY_ETHERSCAN;
$json_plata_pool__0x0E1_671a6 = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey='.$API_KEY_ETHERSCAN;

$qtd_wmatic_pool__0x0E1_671a6 = number_format(json_decode(array(file_get_contents($json_wmatic_pool__0x0E1_671a6))[0],true)['result'] / (10 ** 18) ?? 0 , 5, '.', ',');
$qtd_plata_pool__0x0E1_671a6 = number_format(json_decode(array(file_get_contents($json_plata_pool__0x0E1_671a6))[0],true)['result'] / (10 ** 4) ?? 0 , 4, '.', ',');

// Endpoints organizados
$api_endpoint = 'https://api.livecoinwatch.com/coins/single';

// Mapeamento de c贸digos de moedas
$coins = ['BTC', 'ETH', 'WBTC', 'WETH', 'BNB', 'MATIC', 'XAUT', 'EURS', 'BRZ', 'USDC', 'DAI', 'USDT', 'BUSD'];


$context = stream_context_create(['http' => ['method' => 'POST','header' => "Content-Type: application/json\r\nx-api-key:{$API_KEY_LIVECOINWATCH}\r\n"]]);

foreach ($coins as $coin) {
    $payload = json_encode(['currency' => 'USD', 'code' => $coin, 'meta' => false]);

    stream_context_set_option($context, 'http', 'content', $payload);
    
    $response = json_decode(file_get_contents($api_endpoint, false, $context), true);
    
    // atribue a vari谩vel com base no nome da moeda dinamicamente

    (is_numeric($response['rate']))
        ? ${$coin.'USD'} = number_format($response['rate'], 5, '.', ',')
        : ${$coin . 'USD'} = rtrim(rtrim(number_format($response['rate'], 5, '.', ''), '0'), '.');

    ${'USD'.$coin} =  number_format ( (1/(${$coin.'USD'}) ) / ( (${$coin.'USD'}) > 1000 ? (10 ** 4) : (1) ) ?? 0 , 8, '.', ',');
    
    $prices_vs_usd[$coin.'USD'] = floatval(str_replace(',', '', ${$coin.'USD'}));
    $usd_vs_prices['USD'.$coin] = floatval(str_replace(',', '', ${'USD'.$coin}));
    
    //echo $coin.'USD : ' . ${$coin.'USD'} . '<br>';     
    //echo 'USD'.$coin.' : '. ${'USD'.$coin} . '<br>';  
    
} 

// valor constante do dia 25-06-2024 6:33 UTC-3 para BRZUSD

$BRLUSD = $BRZUSD;
$EURUSD = $EURSUSD;

//$ERROR_MSG = 0;

$USDEUR = number_format ( (1 / $EURUSD) / ( $EURUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDBRL = number_format ( (1 / $BRZUSD) / ( $BRZUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDMATIC = number_format ( (1 / $MATICUSD) / ( $MATICUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');

$usd_vs_prices['USDMATIC'] = floatval(str_replace(',', '', $USDMATIC));
$usd_vs_prices['USDEUR'] = floatval(str_replace(',', '', $USDEUR));
$usd_vs_prices['USDBRL'] = floatval(str_replace(',', '', $USDBRL));
$prices_vs_usd['MATICUSD'] = floatval(str_replace(',', '', $MATICUSD));
$prices_vs_usd['EURUSD'] = floatval(str_replace(',', '', $EURUSD));
$prices_vs_usd['BRLUSD'] = floatval(str_replace(',', '', $BRLUSD));

$plata_values =  deploy_plata_rates($MATICUSD, $EURSUSD, $BRZUSD, $qtd_plata_pool__0x0E1_671a6, $qtd_wmatic_pool__0x0E1_671a6, $PLT_circulating_supply);

// Monta JSON
$output = [
    'last_updated_at' => gmdate('d-m-Y H:i:s') . ' UTC', 
    'id' => 'livecoinwatch v3',
    'prices_vs_usd' => $prices_vs_usd,
    'usd_vs_prices' => $usd_vs_prices,
    'plt_prices' => $plata_values['plt_prices'],
    'plt_marketcap' => $plata_values['plt_marketcap']
];

file_put_contents(__DIR__ . '/all_pricesV3.'.basename(preg_replace('/.cryptoprices-v3./i', '',__FILE__), ".php").'.json', json_encode($output, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION));


$myfile = fopen("pltprice.css", "w") or die;
$txt = ":root { ". "\n  --usdplt: '". $USDPLT ."';
  --brlplt: '". $BRLPLT ."';
  --eurplt: '". $EURPLT ."';\n  }";

fwrite($myfile, $txt);
fclose($myfile);

//var_dump($prices_vs_usd);
//echo 'ok!';
//echo '</pre>';
?>
