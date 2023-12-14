<?php

$json_url = 'https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC,ETH,MATIC,EUR,BRL&api_key=6023fb8068e6f17fe63800ce08f15fb6bd88d7b3b825600d58736973a6aafd98';
$json_wmatic_pool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=1Y153G4EA8DRD889PTTXYT1B2TAQE2IQP8';
$json_plata_pool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';

$json = file_get_contents($json_url);
$ar_data = json_decode($json);

function tokenbalance($output) {
    $jsonfile = file_get_contents($output);
    $data = json_decode($jsonfile);
    return round($data->result);
}

function consolelog($output) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
    echo $js_code;
}

$PLTcirculatingSupply = (11299000992);

$USDBTC = number_format($ar_data->BTC, 8, '.', ',');
$USDETH = number_format($ar_data->ETH, 8, '.', ',');
$USDMATIC = number_format($ar_data->MATIC, 4, '.', ',');
$USDEUR = number_format($ar_data->EUR, 4, '.', ',');
$USDBRL = number_format($ar_data->BRL, 4, '.', ',');

$BTCUSD = number_format((1 / $USDBTC), 4, '.', ',');

$wmatic_pool = number_format(tokenbalance($json_wmatic_pool) / 10**18, 4, '.', ',');
$plata_pool = number_format(tokenbalance($json_plata_pool) / 10**4, 4, '.', ',');

$MATICPLT = number_format(($plata_pool / $wmatic_pool) * 10**6, 4, '.', ',');

$USDPLT = number_format(($MATICPLT * $USDMATIC), 4, '.', ',');
$PLTUSD = number_format((1 / $USDPLT) / 1000, 10, '.', ',');
$PLTBRL = number_format(($PLTUSD * $USDBRL), 10, '.', ',');
$PLTEUR = number_format(($PLTUSD * $USDEUR), 10, '.', ',');

$PLTmarketcapUSD = number_format(($PLTcirculatingSupply * $PLTUSD), 4, '.', ',');
$PLTmarketcapBRL = number_format(($PLTcirculatingSupply * $PLTBRL), 4, '.', ',');
$PLTmarketcapEUR = number_format(($PLTcirculatingSupply * $PLTEUR), 4, '.', ',');

// Create an associative array for PLATA data
$plata_data = [
    'id' => 'plata',
    'symbol' => 'PLATA',
    'rank' => '', // Assign a rank if available
    'price' => $USDPLT,
];

// Convert the array to JSON format
$plata_json = json_encode($plata_data, JSON_PRETTY_PRINT);

// Save the JSON data to a file
$file_path = 'plata_data.json';
file_put_contents($file_path, $plata_json);

consolelog("USDBTC : " . $USDBTC);
consolelog("USDETH : " . $USDETH);
consolelog("USDMATIC : " . $USDMATIC);
consolelog("USDEUR : " . $USDEUR);
consolelog("USDBRL : " . $USDBRL);
consolelog("BTCUSD : " . $BTCUSD);

consolelog("MATICPLT : " . $MATICPLT);
consolelog("PLTUSD : " . $PLTUSD);
consolelog("PLTBRL : " . $PLTBRL);
consolelog("PLTEUR : " . $PLTEUR);
consolelog("MarketcapUSD : " . $PLTmarketcapUSD);
consolelog("MarketcapBRL : " . $PLTmarketcapBRL);
consolelog("MarketcapEUR : " . $PLTmarketcapEUR);

?>
