<?php

// chave temporária
$API_KEY = '4fa26157-2789-49d3-814e-f20d3aadec9a';

// endpoints organizados
$apis = [
    'btc' => 'https://api.livecoinwatch.com/coins/single',
    'eth' => 'https://api.livecoinwatch.com/coins/single',
    'wbtc' => 'https://api.livecoinwatch.com/coins/single',
    'weth' => 'https://api.livecoinwatch.com/coins/single',
    'bnb' => 'https://api.livecoinwatch.com/coins/single',
    'xaut' => 'https://api.livecoinwatch.com/coins/single',
    'matic' => 'https://api.livecoinwatch.com/coins/single',
    'eurs' => 'https://api.livecoinwatch.com/coins/single',
    'brz' => 'https://api.livecoinwatch.com/coins/single',
    'usdc' => 'https://api.livecoinwatch.com/coins/single',
    'dai' => 'https://api.livecoinwatch.com/coins/single',
    'usdt' => 'https://api.livecoinwatch.com/coins/single',
    'busd' => 'https://api.livecoinwatch.com/coins/single',
    'wmatic_pool' => 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=AIG6PVV6734H8V2HI8HCK6J1HACYNRHVTC',
    'plata_pool' => 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=AIG6PVV6734H8V2HI8HCK6J1HACYNRHVTC'
];

// mapeamento de códigos de moedas
$coins = ['BTC', 'ETH', 'WBTC', 'WETH', 'BNB', 
          'XAUT', 'MATIC', 'EURS', 'BRZ', 
          'USDC', 'DAI', 'USDT', 'BUSD'];

// montagem de parametros para requisição
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\nx-api-key: {$API_KEY}\r\n",
        'content' => ''
    ]
]);

foreach ($coins as $coin) {
    $payload = json_encode(['currency' => 'USD', 'code' => $coin, 'meta' => false]);

    stream_context_set_option($context, 'http', 'content', $payload);
    
    $response = json_decode(file_get_contents($apis[strtolower($coin)], false, $context), true);
    
    // atribue a variável com base no nome da moeda dinamicamente
    ${$coin . 'USD'} = $response['rate'];
}

// valor constante do dia 25-06-2024 6:33 UTC-3 para BRZUSD
$BRZUSD = 0.185675;

$PLTcirculatingSupply = 11299000992;

// busca pools
$wmatic_pool = json_decode(file_get_contents($apis['wmatic_pool']), true)['result'] / (10 ** 18);
$plata_pool = json_decode(file_get_contents($apis['plata_pool']), true)['result'] / (10 ** 2);

// calcula PLT
$PLTUSD = ($plata_pool / ($wmatic_pool * $MATICUSD)) * (10 ** 4);
$PLTEUR = ($plata_pool / ($wmatic_pool * $MATICUSD * $EURSUSD)) * (10 ** 4);
$PLTBRL = ($plata_pool / ($wmatic_pool * $MATICUSD * $BRZUSD)) * (10 ** 4);
$PLTMATIC = ($plata_pool / $wmatic_pool) * (10 ** 4);
$MATICPLT = 1 / $PLTMATIC;

$PLTmarketcapUSD = ($PLTcirculatingSupply * $PLTUSD) / (10 ** 9);
$PLTmarketcapEUR = ($PLTcirculatingSupply * $PLTEUR) / (10 ** 9);
$PLTmarketcapBRL = ($PLTcirculatingSupply * $PLTBRL) / (10 ** 6);

// Monta JSON
$output = [
    'last_updated_at' => gmdate('d-m-Y H:i:s') . ' UTC',
    'prices_vs_usd' => [
        'USDBTC' => number_format(1 / $BTCUSD, 8, '.', ''),
        'USDETH' => number_format(1 / $ETHUSD, 8, '.', ''),
        'USDWBTC' => number_format(1 / $WBTCUSD, 8, '.', ''),
        'USDWETH' => number_format(1 / $WETHUSD, 8, '.', ''),
        'USDBNB' => number_format(1 / $BNBUSD, 8, '.', ''),
        'USDXAUT' => number_format(1 / $XAUTUSD, 8, '.', ''),
        'USDMATIC' => number_format(1 / $MATICUSD, 8, '.', ''),
        'USDEUR' => number_format(1 / $EURSUSD, 8, '.', ''),
        'USDBRL' => number_format(1 / $BRZUSD, 8, '.', ''),
        'USDBRZ' => number_format(1 / $BRZUSD, 8, '.', ''),
        'USDUSDC' => number_format(1 / $USDCUSD, 8, '.', ''),
        'USDDAI' => number_format(1 / $DAIUSD, 8, '.', ''),
        'USDUSDT' => number_format(1 / $USDTUSD, 8, '.', ''),
        'USDBUSD' => number_format(1 / $BUSDUSD, 8, '.', '')
    ],
    'usd_vs_prices' => [
        'BTCUSD' => number_format($BTCUSD, 5, '.', ''),
        'ETHUSD' => number_format($ETHUSD, 5, '.', ''),
        'WBTCUSD' => number_format($WBTCUSD, 5, '.', ''),
        'WETHUSD' => number_format($WETHUSD, 5, '.', ''),
        'BNBUSD' => number_format($BNBUSD, 5, '.', ''),
        'XAUTUSD' => number_format($XAUTUSD, 5, '.', ''),
        'MATICUSD' => number_format($MATICUSD, 5, '.', ''),
        'EURUSD' => number_format($EURSUSD, 5, '.', ''),
        'BRLUSD' => number_format($BRZUSD, 5, '.', ''),
        'BRZUSD' => number_format($BRZUSD, 5, '.', ''),
        'USDCUSD' => number_format($USDCUSD, 5, '.', ''),
        'DAIUSD' => number_format($DAIUSD, 5, '.', ''),
        'USDTUSD' => number_format($USDTUSD, 5, '.', ''),
        'BUSDUSD' => number_format($BUSDUSD, 5, '.', '')
    ],
    'plt_prices' => [
        'PLTUSD' => number_format($PLTUSD, 8, '.', ''),
        'PLTBRL' => number_format($PLTBRL, 8, '.', ''),
        'PLTEUR' => number_format($PLTEUR, 8, '.', ''),
        'PLTMATIC' => number_format($PLTMATIC, 8, '.', ''),
        'MATICPLT' => rtrim(rtrim(number_format($MATICPLT, 14, '.', ''), '0'), '.')
    ],
    'plt_marketcap' => [
        'USD' => number_format($PLTmarketcapUSD, 4, '.', ''),
        'BRL' => number_format($PLTmarketcapBRL, 4, '.', ''),
        'EUR' => number_format($PLTmarketcapEUR, 4, '.', '')
    ]
];

file_put_contents(__DIR__ . '/all_prices.json', json_encode($output, JSON_PRETTY_PRINT));

?>
