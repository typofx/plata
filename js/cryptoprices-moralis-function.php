<?php include ('/home2/granna80/%/env.php'); ?>

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

// Endpoints organizados https://www.plata.ie/bound-assets/
$api_endpoint = 'https://deep-index.moralis.io/api/v2.2/erc20/prices?chain=polygon';

// 0xeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee Ethereum contract - Ether Network
// 0xc02aaa39b223fe8d0a0e5c4f27ead9083c756cc2 Wrap Ethereum contract - Ether Network

// Mapeamento de códigos de moedas
$coins = ['WBTC', 'WETH', 'BNB', 'MATIC', 'XAUT', 'EURS', 'BRZ', 'USDC', 'DAI', 'USDT', 'BUSD'];

// Mapeamento de contract addresses
$contracts = [
    'WBTC' => '0x1BFD67037B42Cf73acF2047067bd4F2C47D9BfD6',
    'WETH' => '0x7ceB23fD6bC0adD59E62ac25578270cFf1b9f619',
    'BNB' => '0xecdcb5b88f8e3c15f95c720c51c71c9e2080525d',
    'MATIC' => '0x0d500b1d8e8ef31e21c99d1db9a6444d3adf1270',
    'XAUT' => '0xF1815bd50389c46847f0Bda824eC8da914045D14',
    'EURS' => '0xE111178A87A3BFf0c8d18DECBa5798827539Ae99',
    'BRZ' => '0x4eD141110F6EeeAbA9A1df36d8c26f684d2475Dc',
    'USDC' => '0x3c499c542cEF5E3811e1192ce70d8cC03d5c3359',
    'DAI' => '0x8f3Cf7ad23Cd3CaDbD9735AFf958023239c6A063',
    'USDT' => '0xc2132D05D31c914a87C6611C10748AEb04B58e8F',
    'BUSD' => '0x9C9e5fD8bbc25984B178FdCE6117Defa39d2db39'
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

foreach ($coins as $coin){
    $rate = $token_prices[$coin];
    
    (is_numeric($rate))
        ? ${$coin.'USD'} = number_format($rate, 5, '.', ',')
        : ${$coin . 'USD'} = rtrim(rtrim(number_format($rate, 5, '.', ''), '0'), '.');
    
    
    ${'USD'.$coin} =  number_format ( (1/(${$coin.'USD'}) ) / ( (${$coin.'USD'}) > 1000 ? (10 ** 4) : (1) ) ?? 0 , 8, '.', ',');
        
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

file_put_contents(__DIR__ . '/all_prices_moralis.json', json_encode($output, JSON_PRETTY_PRINT));

?>
