<?php include ($_SERVER['DOCUMENT_ROOT'] . '/%pw/env.php'); ?>

<?php

//echo '<pre>';

$API_KEY_MORALIS = '';
$API_KEY_ETHERSCAN = '';

$json_wmatic_pool__0x0E1_671a6 = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey='.$API_KEY_ETHERSCAN;
$json_plata_pool__0x0E1_671a6 = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey='.$API_KEY_ETHERSCAN;
 
// Endpoints organizados
$api_endpoint = 'https://deep-index.moralis.io/api/v2.2/erc20/prices?chain=eth';

// Mapeamento de códigos de moedas
$coins = ['BTC', 'ETH', 'WBTC', 'WETH', 'BNB', 'MATIC', 'XAUT', 'EURS', 'BRZ', 'USDC', 'DAI', 'USDT', 'BUSD'];

// Mapeamento de contract addresses
$contracts = [
    'WBTC' => '0x2260fac5e5542a773aa44fbcfedf7c193bc2c599',
    'WETH' => '0xc02aaa39b223fe8d0a0e5c4f27ead9083c756cc2',
    'BNB' => '0xb8c77482e45f1f44de1745f52c74426c631bdd52',
    'MATIC' => '0x7d1afa7b718fb893db30a3abc0cfc608aacfebb0',
    'XAUT' => '0x68749665FF8D2d112Fa859AA293F07A622782F38',
    'EURS' => '0xdB25f211AB05b1c97D595516F45794528a807ad8',
    'BRZ' => '0x420412E765BFa6d85aaaC94b4f7b708C89be2e2B',
    'USDC' => '0xA0b86991c6218b36c1d19D4a2e9Eb0cE3606eB48',
    'DAI' => '0x6B175474E89094C44Da98b954EedeAC495271d0F',
    'USDT' => '0xdAC17F958D2ee523a2206206994597C13D831ec7',
    'BUSD' => '0x4Fabb145d64652a948d72533023f6E7A623C7C53'
];

$tokens = [];
foreach ($contracts as $symbol => $address) {
    $tokens[] = ['token_address' => $address];
}

$context = stream_context_create(['http' => ['method' => 'POST','header' => "Content-Type: application/json\r\nX-API-Key:{$API_KEY_MORALIS}\r\n"]]);

$payload = json_encode(['tokens' => $tokens]);
stream_context_set_option($context, 'http', 'content', $payload);

$response = json_decode(file_get_contents($api_endpoint, false, $context), true);

// Mapear tokens da API para símbolos
$token_prices = [];
foreach ($response as $token) {
    $addr = strtolower($token['tokenAddress']);
    foreach ($contracts as $sym => $contract) {
        if (strtolower($contract) === $addr) {
            $token_prices[$sym] = $token['usdPrice'];
            break;
        }
    }
}

// Processar cada moeda - EXATAMENTE como livecoinwatch
foreach ($coins as $coin) {
    // BTC usa WBTC, ETH usa WETH
    $lookup_coin = ($coin === 'BTC') ? 'WBTC' : (($coin === 'ETH') ? 'WETH' : $coin);
    
    if (isset($token_prices[$lookup_coin])) {
        $rate = $token_prices[$lookup_coin];
    } else {
        // Fallbacks para BRZ e EURS (atualizado 30/01/2026 13:00 UTC)
        if ($coin === 'BRZ') {
            $rate = 0.1915; // CoinGecko 30/01/2026
        } elseif ($coin === 'EURS') {
            $rate = 1.19; // CoinGecko 30/01/2026
        } else {
            continue; // Pula se não tem preço
        }
    }
    
    // Replica EXATAMENTE o livecoinwatch
    (is_numeric($rate))
        ? ${$coin.'USD'} = number_format($rate, 5, '.', ',')
        : ${$coin . 'USD'} = rtrim(rtrim(number_format($rate, 5, '.', ''), '0'), '.');

    ${'USD'.$coin} =  number_format ( (1/(${$coin.'USD'}) ) / ( (${$coin.'USD'}) > 1000 ? (10 ** 4) : (1) ) ?? 0 , 8, '.', ',');
    
    $prices_vs_usd[$coin.'USD'] = round( (float)str_replace(',', '', ${$coin.'USD'}) , 5);
    $usd_vs_prices['USD'.$coin] = str_replace(',', '', ${'USD'.$coin});
    
    //echo $coin.'USD : ' . ${$coin.'USD'} . '<br>';     
    //echo 'USD'.$coin.' : '. ${'USD'.$coin} . '<br>';  
    
} 

// valor constante do dia 25-06-2024 6:33 UTC-3 para BRZUSD (atualizado 30/01/2026 13:00 UTC)

$BRLUSD = isset($BRZUSD) ? $BRZUSD : (isset($prices_vs_usd['BRZUSD']) ? number_format($prices_vs_usd['BRZUSD'], 5, '.', ',') : number_format(0.1915, 5, '.', ','));
$EURUSD = isset($EURSUSD) ? $EURSUSD : (isset($prices_vs_usd['EURSUSD']) ? number_format($prices_vs_usd['EURSUSD'], 5, '.', ',') : number_format(1.19, 5, '.', ','));

//$ERROR_MSG = 0;

$USDEUR = number_format ( (1 / $EURUSD) / ( $EURUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDBRL = number_format ( (1 / $BRLUSD) / ( $BRLUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDMATIC = number_format ( (1 / $MATICUSD) / ( $MATICUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');

$PLTcirculatingSupply = 11299000992;

$qtd_wmatic_pool__0x0E1_671a6 = number_format(json_decode(array(file_get_contents($json_wmatic_pool__0x0E1_671a6))[0],true)['result'] / (10 ** 18) ?? 0 , 5, '.', ',');
$qtd_plata_pool__0x0E1_671a6 = number_format(json_decode(array(file_get_contents($json_plata_pool__0x0E1_671a6))[0],true)['result'] / (10 ** 4) ?? 0 , 4, '.', ',');

$PLTUSD = number_format( (($qtd_plata_pool__0x0E1_671a6 / ($qtd_wmatic_pool__0x0E1_671a6 * $MATICUSD)) * (10 ** 6)), 4, '.', ',');
$PLTEUR = number_format( (($qtd_plata_pool__0x0E1_671a6 / ($qtd_wmatic_pool__0x0E1_671a6 * $MATICUSD) * $EURUSD) * (10 ** 6)), 4, '.', ',');
$PLTBRL = number_format( (($qtd_plata_pool__0x0E1_671a6 / ($qtd_wmatic_pool__0x0E1_671a6 * $MATICUSD) * $BRLUSD) * (10 ** 6)), 4, '.', ',');

$PLTMATIC = number_format(($qtd_plata_pool__0x0E1_671a6 / $qtd_wmatic_pool__0x0E1_671a6) * (10 ** 6), 4, '.', ',');
$MATICPLT = number_format ( (1 / $PLTMATIC) / ( $PLTMATIC > 1000 ? (10 ** 3) : 1 ), 8, '.', ',');
$USDPLT = number_format((1 / $PLTUSD) / (10 ** 3), 10, '.', ',');

$PLTmarketcapUSD = number_format( ($PLTcirculatingSupply * $USDPLT) , 4, '.', ',' );
$PLTmarketcapEUR = number_format( ($PLTcirculatingSupply * (0.001/$PLTEUR) ) , 4, '.', ',' );
$PLTmarketcapBRL = number_format( ($PLTcirculatingSupply * (0.001/$PLTBRL) ) , 4, '.', ',' );

//include 'debugger.cryptoprices.php';

// Monta JSON
$output = [
    'last_updated_at' => gmdate('d-m-Y H:i:s') . ' UTC', 
    'prices_vs_usd' => $prices_vs_usd,
    'usd_vs_prices' => $usd_vs_prices,

    'plt_prices' => [
        'PLTUSD' => str_replace(',', '', $PLTUSD),
        'PLTBRL' => str_replace(',', '', $PLTBRL),
        'PLTEUR' => str_replace(',', '', $PLTEUR),
        'PLTMATIC' => str_replace(',', '', $PLTMATIC),
        'MATICPLT' => str_replace(',', '', $MATICPLT),
        'USDPLT' => str_replace(',', '', $USDPLT)
    ],

    'plt_marketcap' => [
        'USD' => str_replace(',', '', $PLTmarketcapUSD),
        'BRL' => str_replace(',', '', $PLTmarketcapBRL),
        'EUR' => str_replace(',', '', $PLTmarketcapEUR)
    ]
];

file_put_contents(__DIR__ . '/all_prices.json', json_encode($output, JSON_PRETTY_PRINT));

//var_dump($prices_vs_usd);
//echo 'ok!';
//echo '</pre>';
?>
