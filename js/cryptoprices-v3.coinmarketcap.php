<?php include ('/home2/granna80/%/env.php'); ?>
<?php

$PLTcirculatingSupply = 11299000992;


$json_wmatic_pool__0x0E1_671a6 = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey='.$API_KEY_ETHERSCAN;
$json_plata_pool__0x0E1_671a6 = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey='.$API_KEY_ETHERSCAN;

$qtd_wmatic_pool__0x0E1_671a6 = number_format(json_decode(array(file_get_contents($json_wmatic_pool__0x0E1_671a6))[0],true)['result'] / (10 ** 18) ?? 0 , 5, '.', ',');
$qtd_plata_pool__0x0E1_671a6 = number_format(json_decode(array(file_get_contents($json_plata_pool__0x0E1_671a6))[0],true)['result'] / (10 ** 4) ?? 0 , 4, '.', ',');


// Endpoint CoinMarketCap
$api_endpoint = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';

// Mapeamento de códigos de moedas
$coins = ['BTC', 'ETH', 'WBTC', 'WETH', 'BNB', 'POL', 'XAUt', 'EURS', 'BRZ', 'USDC', 'DAI', 'USDT', 'BUSD'];

$symbols = implode(',', $coins);
$url = $api_endpoint . '?symbol=' . $symbols . '&convert=USD';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "X-CMC_PRO_API_KEY: {$API_KEY_COINMARKETCAP}\r\nAccept: application/json\r\n"
    ]
]);

$response = json_decode(file_get_contents($url, false, $context), true);

foreach ($coins as $coin) {
    $rate = $response['data'][$coin]['quote']['USD']['price'] ?? 0;
    
    // atribue a variável com base no nome da moeda dinamicamente
    (is_numeric($rate))
        ? ${$coin.'USD'} = number_format($rate, 5, '.', ',')
        : ${$coin . 'USD'} = rtrim(rtrim(number_format($rate, 5, '.', ''), '0'), '.');

    ${'USD'.$coin} =  number_format ( (1/(${$coin.'USD'}) ) / ( (${$coin.'USD'}) > 1000 ? (10 ** 4) : (1) ) ?? 0 , 8, '.', ',');
    
    $prices_vs_usd[$coin.'USD'] = floatval(str_replace(',', '', ${$coin.'USD'}));
    $usd_vs_prices['USD'.$coin] = floatval(str_replace(',', '', ${'USD'.$coin}));
    
} 
 
$BRLUSD = $BRZUSD;
$EURUSD = $EURSUSD;
$MATICUSD = $POLUSD; // MATIC não retorna o valor nessa API, usar POL\
unset($usd_vs_prices['USDPOL']);

$USDEUR = number_format ( (1 / $EURUSD) / ( $EURUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDBRL = number_format ( (1 / $BRZUSD) / ( $BRZUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDMATIC = number_format ( (1 / $MATICUSD) / ( $MATICUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');

$usd_vs_prices['USDMATIC'] = floatval(str_replace(',', '', $USDMATIC));
$usd_vs_prices['USDEUR'] = floatval(str_replace(',', '', $USDEUR));
$usd_vs_prices['USDBRL'] = floatval(str_replace(',', '', $USDBRL));
$prices_vs_usd['MATICUSD'] = floatval(str_replace(',', '', $MATICUSD));
$prices_vs_usd['EURUSD'] = floatval(str_replace(',', '', $EURUSD));
$prices_vs_usd['BRLUSD'] = floatval(str_replace(',', '', $BRLUSD));
$plata_values =  deploy_plata_rates($MATICUSD, $EURSUSD, $BRZUSD, $qtd_plata_pool__0x0E1_671a6, $qtd_wmatic_pool__0x0E1_671a6, $PLTcirculatingSupply);

// Monta JSON
$output = [
    'last_updated_at' => gmdate('d-m-Y H:i:s') . ' UTC',
    'id' => 'coinmarketcap v3',
    'prices_vs_usd' => $prices_vs_usd,
    'usd_vs_prices' => $usd_vs_prices,
    'plt_prices' => $plata_values['plt_prices'],
    'plt_marketcap' => $plata_values['plt_marketcap']
];

file_put_contents(__DIR__ . '/all_prices-'.basename(preg_replace('/.cryptoprices-v3./i', '',__FILE__), ".php").'.json', json_encode($output, JSON_PRETTY_PRINT | JSON_PRESERVE_ZERO_FRACTION));

?>