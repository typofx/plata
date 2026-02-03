<?php include ($_SERVER['DOCUMENT_ROOT'] . '/%pw/env.php'); ?>

<?php

function deploy_plata_rates($_MATICUSD, $_EURUSD, $_BRLUSD, $_JSON_MATIC_ENDPOINT, $_JSON_PLATA_ENDPOINT, $_PLT_CIRCULATING_SUPPLY = 11299000992): array {    
    
    $qtd_wmatic_pool__0x0E1_671a6 = number_format(json_decode(array(file_get_contents($_JSON_MATIC_ENDPOINT))[0],true)['result'] / (10 ** 18) ?? 0 , 5, '.', ',');
    $qtd_plata_pool__0x0E1_671a6 = number_format(json_decode(array(file_get_contents($_JSON_PLATA_ENDPOINT))[0],true)['result'] / (10 ** 4) ?? 0 , 4, '.', ',');
    
    $PLTUSD = number_format( (($qtd_plata_pool__0x0E1_671a6 / ($qtd_wmatic_pool__0x0E1_671a6 * $_MATICUSD)) * (10 ** 6)), 4, '.', ',');
    $PLTEUR = number_format( (($qtd_plata_pool__0x0E1_671a6 / ($qtd_wmatic_pool__0x0E1_671a6 * $_MATICUSD) * $_EURUSD) * (10 ** 6)), 4, '.', ',');
    $PLTBRL = number_format( (($qtd_plata_pool__0x0E1_671a6 / ($qtd_wmatic_pool__0x0E1_671a6 * $_MATICUSD) * $_BRLUSD) * (10 ** 6)), 4, '.', ',');
    
    $PLTMATIC = number_format(($qtd_plata_pool__0x0E1_671a6 / $qtd_wmatic_pool__0x0E1_671a6) * (10 ** 6), 4, '.', ',');
    $MATICPLT = number_format ( (1 / $PLTMATIC) / ( $PLTMATIC > 1000 ? (10 ** 3) : 1 ), 8, '.', ',');
    $USDPLT = number_format((1 / $PLTUSD) / (10 ** 3), 10, '.', ',');
    
    $PLTmarketcapUSD = number_format( ($_PLT_CIRCULATING_SUPPLY * $USDPLT) , 4, '.', ',' );
    $PLTmarketcapEUR = number_format( ($_PLT_CIRCULATING_SUPPLY * (0.001/$PLTEUR) ) , 4, '.', ',' );
    $PLTmarketcapBRL = number_format( ($_PLT_CIRCULATING_SUPPLY * (0.001/$PLTBRL) ) , 4, '.', ',' );
    
    return [
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
}    

$json_wmatic_pool__0x0E1_671a6 = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey='.$API_KEY_ETHERSCAN;
$json_plata_pool__0x0E1_671a6 = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey='.$API_KEY_ETHERSCAN;

$api_endpoint = 'https://api.coinpaprika.com/v1/tickers/';

$coins = [
'BTC' => 'btc-bitcoin', 
'ETH' => 'eth-ethereum', 
'WBTC' => 'wbtc-wrapped-bitcoin', 
'WETH' => 'weth-weth', 
'BNB' => 'bnb-bnb', 
'MATIC' => 'matic-polygon', 
'XAUT' => 'xaut-tether-gold', 
'EURS' => 'eurs-stasis-euro', 
'BRZ' => 'brz-brazilian-digital-token', 
'USDC' => 'usdc-usd-coin', 
'DAI' => 'dai-dai', 
'USDT' => 'usdt-tether', 
'BUSD' => 'busd-binance-usd'
];

foreach ($coins as $coin => $coinId) {
    $json_endpoints[$coin] = $api_endpoint . $coinId;
    ${$coin . 'USD'} = number_format(json_decode(array(file_get_contents($json_endpoints[$coin]))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');
    ${'USD' . $coin} = number_format(1 / ${$coin . 'USD'}, 8, '.', ',') ;
    $prices_vs_usd[$coin.'USD'] = round( (float)str_replace(',', '', ${$coin.'USD'}) , 5);
    $usd_vs_prices['USD'.$coin] = round((float)str_replace(',', '', ${'USD'.$coin}), 8);    
}

$plata_values =  deploy_plata_rates($MATICUSD, $EURSUSD, $BRZUSD, $json_wmatic_pool__0x0E1_671a6, $json_plata_pool__0x0E1_671a6);

// Monta JSON
$output = [
  'last_updated_at' => gmdate('d-m-Y H:i:s') . ' UTC',
  'prices_vs_usd' => $prices_vs_usd,
  'usd_vs_prices' => $usd_vs_prices,
  'plt_prices' => $plata_values['plt_prices'],
  'plt_marketcap' => $plata_values['plt_marketcap']
];

file_put_contents(__DIR__ . '/all_prices_coinpaprika.json', json_encode($output, JSON_PRETTY_PRINT));

?>