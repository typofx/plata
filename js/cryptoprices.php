<?php

$json_url = 'https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC,ETH,WBTC,WETH,BNB,XAUT,MATIC,EUR,BRL&api_key=xxxAPIKEYxxx'; // 345463f7b620b68fa2fa8fe0b0ade5d8fb18ca4e06437689fbe720395979fdcc,  7ddd13ecf0d3ea7df245a3a2bbdefff844c402c794a4b9f1e44c8bef74f85197'; //6023fb8068e6f17fe63800ce08f15fb6bd88d7b3b825600d58736973a6aafd98

$json_wmatic_pool = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=xxxAPIKEYxxx';
$json_plata_pool = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x8922978912e9adfea6f259423c73baa5daebce38&tag=latest&apikey=xxxAPIKEYxxx';
$json_weth_pool = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0x7ceb23fd6bc0add59e62ac25578270cff1b9f619&address=0x8922978912e9adfea6f259423c73baa5daebce38&tag=latest&apikey=xxxAPIKEYxxx';


$json = file_get_contents($json_url);
$ar_data = array($json);
$ar_data = $ar_data[0];
$ar_data = json_decode($ar_data);
if (!function_exists('tokenbalance')) {

    function tokenbalance($output)
    {

        $requests = 300;
        $cache_key = 'tokenbalance_' . md5($output);
        $cached_data_json = apcu_fetch($cache_key);
        if ($cached_data_json !== false) {
            $data = json_decode($cached_data_json);
        } else {
            $data_from_api = @file_get_contents($output);
            if ($data_from_api && $data_from_api[0] === '{') {
                apcu_store($cache_key, $data_from_api, $requests);
            }

            $data = json_decode($data_from_api);
        }

        if ($data && isset($data->result) && is_numeric($data->result)) {
            return (float)$data->result;
        }


        return 1;
    }
}
function consolelog($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

$PLTcirculatingSupply = (11299000992);

$USDBTC = number_format($ar_data->{'BTC'} ?? 0, 8, '.', ',');
$USDETH = number_format($ar_data->{'ETH'} ?? 0, 8, '.', ',');
$USDWBTC = number_format($ar_data->{'WBTC'} ?? 0, 8, '.', ',');
$USDWETH = number_format($ar_data->{'WETH'} ?? 0, 8, '.', ',');
$USDBNB = number_format($ar_data->{'BNB'} ?? 0, 8, '.', ',');
$USDXAUT = number_format($ar_data->{'XAUT'} ?? 0, 8, '.', ',');
$USDMATIC = number_format($ar_data->{'MATIC'} ?? 0, 4, '.', ',');
$MATICUSD = number_format(1 / 500000, 4, '.', ',');
$USDEUR = number_format($ar_data->{'EUR'} ?? 0, 4, '.', ',');
$USDBRL = number_format($ar_data->{'BRL'} ?? 0, 4, '.', ',');
$EURUSD = number_format(1 / 500000, 4, '.', ',');
$BRLUSD = number_format(1 / 500000, 4, '.', ',');


/*

$BTCUSD = number_format((!empty($ar_data->{'BTC'}) ? 1 / $ar_data->{'BTC'} : 0), 4, '.', ',');
$ETHUSD = number_format((!empty($ar_data->{'ETH'}) ? 1 / $ar_data->{'ETH'} : 0), 4, '.', ',');
$WBTCUSD = number_format((!empty($ar_data->{'WBTC'}) ? 1 / $ar_data->{'WBTC'} : 0), 4, '.', ',');
$WETHUSD = number_format((!empty($ar_data->{'WETH'}) ? 1 / $ar_data->{'WETH'} : 0), 4, '.', ',');
$BNBUSD = number_format((!empty($ar_data->{'BNB'}) ? 1 / $ar_data->{'BNB'} : 0), 4, '.', ',');
$XAUTUSD = number_format((!empty($ar_data->{'XAUT'}) ? 1 / $ar_data->{'XAUT'} : 0), 4, '.', ',');
$BRZUSD = number_format((!empty($ar_data->{'BRZ'}) ? 1 / $ar_data->{'BRZ'} : 0), 4, '.', ',');
$USDCUSD = number_format((!empty($ar_data->{'USDC'}) ? 1 / $ar_data->{'USDC'} : 0), 4, '.', ',');
$BNBUSD = number_format((!empty($ar_data->{'BNB'}) ? 1 / $ar_data->{'BNB'} : 0), 4, '.', ',');
$DAIUSD = number_format((!empty($ar_data->{'DAI'}) ? 1 / $ar_data->{'DAI'} : 0), 4, '.', ',');
$USDTUSD = number_format((!empty($ar_data->{'USDT'}) ? 1 / $ar_data->{'USDT'} : 0), 4, '.', ',');
$BUSDUSD = number_format((!empty($ar_data->{'BUSD'}) ? 1 / $ar_data->{'BUSD'} : 0), 4, '.', ',');

*/

number_format($ar_data->{'BRL'} ?? 0, 4, '.', ',');

$wmatic_pool = is_numeric(tokenbalance($json_wmatic_pool)) && tokenbalance($json_wmatic_pool) > 0
    ? number_format(tokenbalance($json_wmatic_pool) / 10 ** 18, 4, '.', ',')
    : 1;

$plata_pool = is_numeric(tokenbalance($json_plata_pool)) && tokenbalance($json_plata_pool) > 0
    ? number_format(tokenbalance($json_plata_pool) / 10 ** 2, 4, '.', ',')
    : 1;

$weth_pool = is_numeric(tokenbalance($json_weth_pool)) && tokenbalance($json_weth_pool) > 0
    ? number_format(tokenbalance($json_weth_pool) / 10 ** 2, 4, '.', ',')
    : 1;

$MATICPLT = (is_numeric(str_replace(',', '', $plata_pool)) && is_numeric($wmatic_pool) && $wmatic_pool > 0)
    ? number_format((floatval(str_replace(',', '', $plata_pool)) / floatval($wmatic_pool)) * 10 ** 6, 4, '.', ',')
    : 1;



//echo $USDMATIC ;


$ETHPLT = is_numeric($plata_pool) && is_numeric($weth_pool) && $weth_pool > 0
    ? number_format(($plata_pool / $weth_pool) * 10 ** 6, 4, '.', ',')
    : 1;

//$USDPLT = number_format(($MATICPLT * $USDMATIC), 4, '.', ',');

$plata_pool = floatval(str_replace(',', '.', $plata_pool));

$weth_pool = floatval(str_replace(',', '.', $weth_pool));

//echo $weth_pool;

$USDPLT = ($weth_pool > 0 && $ETHUSD > 0)
    ? number_format(($plata_pool / ($weth_pool * $ETHUSD)) * 10 ** 7, 4, '.', '')
    : 138888.88;

$PLTUSD = ($USDPLT > 0)
    ? number_format((1 / $USDPLT) / 10, 10, '.', ',')
    : 0.0000072;

//echo $PLTUSD;

$PLTBRL = number_format(($PLTUSD * $USDBRL), 10, '.', ',');
$PLTEUR = number_format(($PLTUSD * $USDEUR), 10, '.', ',');

$PLTmarketcapUSD = number_format(($PLTcirculatingSupply * $PLTUSD), 4, '.', ',');
$PLTmarketcapBRL = number_format(($PLTcirculatingSupply * $PLTBRL), 4, '.', ',');
$PLTmarketcapEUR = number_format(($PLTcirculatingSupply * $PLTEUR), 4, '.', ',');


$MATIC_PER_PLT = $PLTUSD * $USDMATIC;
$PLT_PER_MATIC = 500000;

$PLT_PER_MATIC = number_format(($PLT_PER_MATIC), 4, '.', '');
$MATIC_PER_PLT = number_format(($MATIC_PER_PLT), 10, '.', ',');

//echo $MATIC_PER_PLT;

//consolelog("USDBTC : " . $USDBTC);
//consolelog("USDETH : " . $USDETH);
//consolelog("USDWBTC : " . $USDWBTC);
//consolelog("USDWETH : " . $USDWETH);
//consolelog("USDBNB : " . $USDBNB);
//consolelog("USDXAUT : " . $USDXAUT);
//consolelog("USDMATIC : " . $USDMATIC);
//consolelog("MATICUSD : " . $MATICUSD);
//consolelog("USDEUR : " . $USDEUR);
//consolelog("EURUSD : " . $EURUSD);
//consolelog("USDBRL : " . $USDBRL);

//consolelog("BTCUSD : " . $BTCUSD);
//consolelog("ETHUSD : " . $ETHUSD);
//consolelog("WBTCUSD : " . $WBTCUSD);
//consolelog("WETHUSD : " . $WETHUSD);
//consolelog("BNBUSD : " . $BNBUSD);
//consolelog("XAUTUSD : " . $XAUTUSD);


//consolelog("MATICPLT : " . $PLT_PER_MATIC);
//consolelog("PLTMATIC : " . $MATIC_PER_PLT);
//consolelog("PLTUSD : " . $PLTUSD);
//consolelog("PLTBRL : " . $PLTBRL);
//consolelog("PLTEUR : " . $PLTEUR);
//consolelog("MarketcapUSD : " . $PLTmarketcapUSD);
//consolelog("MarketcapBRL : " . $PLTmarketcapBRL);
//consolelog("MarketcapEUR : " . $PLTmarketcapEUR);


/*
echo $USDBTC;
echo '<br> ' . $USDETH;
echo '<br> ' . $USDWBTC;
echo '<br> ' . $USDWETH;
echo '<br> ' . $USDBNB;
echo '<br> ' . $USDXAUT;
echo '<br> ' . $USDMATIC;
echo '<br> ' . $USDEUR;
echo '<br> ' . $USDBRL;
echo '<br> ' . $BTCUSD;
echo '<br> ' . $WBTCUSD;
echo '<br> ' . $WETHUSD;
echo '<br> ' . $BNBUSD;
echo '<br> ' . $XAUTUSD;
echo '<br> ' . $MATICUSD;
echo '<br> ' . $EURUSD;
echo '<br> ' . $PLTUSD;
echo '<br> ' . $PLTBRL;
echo '<br> ' . $PLTEUR;
echo '<br> ' . $PLT_PER_MATIC;
echo '<br> ' . $MATIC_PER_PLT;
echo '<br> ' . $PLTmarketcapUSD;
echo '<br> ' . $PLTmarketcapBRL;
echo '<br> ' . $PLTmarketcapEUR;
*/

$prices_json_file = __DIR__ . '/all_prices.json'; //cryptoprices.json *****



$USDBTC_num = str_replace(',', '', $USDBTC);
$USDETH_num = str_replace(',', '', $USDETH);
$USDWBTC_num = str_replace(',', '', $USDWBTC);
$USDWETH_num = str_replace(',', '', $USDWETH);
$USDBNB_num = str_replace(',', '', $USDBNB);
$USDXAUT_num = str_replace(',', '', $USDXAUT);
$USDMATIC_num = str_replace(',', '', $USDMATIC);
$USDEUR_num = str_replace(',', '', $USDEUR);
$USDBRL_num = str_replace(',', '', $USDBRL);
$BTCUSD_num = str_replace(',', '', $BTCUSD);
$WBTCUSD_num = str_replace(',', '', $WBTCUSD);
$WETHUSD_num = str_replace(',', '', $WETHUSD);
$BNBUSD_num = str_replace(',', '', $BNBUSD);
$XAUTUSD_num = str_replace(',', '', $XAUTUSD);
$MATICUSD_num = str_replace(',', '', $MATICUSD);
$EURUSD_num = str_replace(',', '', $EURUSD);
$PLTUSD_num = str_replace(',', '', $PLTUSD);
$PLTBRL_num = str_replace(',', '', $PLTBRL);
$PLTEUR_num = str_replace(',', '', $PLTEUR);
$PLT_PER_MATIC_num = str_replace(',', '', $PLT_PER_MATIC);
$MATIC_PER_PLT_num = str_replace(',', '', $MATIC_PER_PLT);
$PLTmarketcapUSD_num = str_replace(',', '', $PLTmarketcapUSD);
$PLTmarketcapBRL_num = str_replace(',', '', $PLTmarketcapBRL);
$PLTmarketcapEUR_num = str_replace(',', '', $PLTmarketcapEUR);


$last_updated_at = gmdate('d-m-Y H:i:s') . ' UTC';


$json_string_manual = <<<JSON
{
  "last_updated_at": "{$last_updated_at}",
  "prices_vs_usd": {
    "USDBTC": {$USDBTC_num},
    "USDETH": {$USDETH_num},
    "USDWBTC": {$USDWBTC_num},
    "USDWETH": {$USDWETH_num},
    "USDBNB": {$USDBNB_num},
    "USDXAUT": {$USDXAUT_num},
    "USDMATIC": {$USDMATIC_num},
    "USDEUR": {$USDEUR_num},
    "USDBRL": {$USDBRL_num}
  },
  "usd_vs_prices": {
    "BTCUSD": {$BTCUSD_num},
    "ETHUSD": {$ETHUSD},
    "WBTCUSD": {$WBTCUSD_num},
    "WETHUSD": {$WETHUSD_num},
    "BNBUSD": {$BNBUSD_num},
    "XAUtUSD": {$XAUTUSD_num},
    "MATICUSD": {$MATICUSD_num},
    "EURUSD": {$EURUSD_num},
     "BRLUSD": {$BRLUSD}
  },
  "plt_prices": {
    "PLTUSD": {$PLTUSD_num},
    "PLTBRL": {$PLTBRL_num},
    "PLTEUR": {$PLTEUR_num},
    "PLTMATIC": {$PLT_PER_MATIC_num},
    "MATICPLT": {$MATIC_PER_PLT_num}
  },
  "plt_marketcap": {
    "USD": {$PLTmarketcapUSD_num},
    "BRL": {$PLTmarketcapBRL_num},
    "EUR": {$PLTmarketcapEUR_num}
  }
}
JSON;


file_put_contents($prices_json_file, $json_string_manual);
