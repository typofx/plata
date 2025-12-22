<?php

$json_url = 'https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC,ETH,WBTC,WETH,BNB,XAUT,MATIC,EUR,BRL&api_key='; 

$json_wmatic_pool = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=';
$json_plata_pool = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x8922978912e9adfea6f259423c73baa5daebce38&tag=latest&apikey=';
$json_weth_pool = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0x7ceb23fd6bc0add59e62ac25578270cff1b9f619&address=0x8922978912e9adfea6f259423c73baa5daebce38&tag=latest&apikey=';

$json = file_get_contents($json_url);
$ar_data = array($json);
$ar_data = $ar_data[0];
$ar_data = json_decode($ar_data);

if (!function_exists('tokenbalance')) {

    function tokenbalance($output)
    {

        $requests = 300;
        $cache_key = 'tokenbalance_' . md5($output);
        $cached_data_json = false;

        // usar APCu apenas se disponível
        if (function_exists('apcu_fetch')) {
            $cached_data_json = @apcu_fetch($cache_key);
        }

        if ($cached_data_json !== false) {
            $data = json_decode($cached_data_json);
        } else {
            $data_from_api = @file_get_contents($output);
            if ($data_from_api && $data_from_api[0] === '{') {
                if (function_exists('apcu_store')) {
                    @apcu_store($cache_key, $data_from_api, $requests);
                }
                $data = json_decode($data_from_api);
            } else {
                $data = null;
            }
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

// definir variáveis USD* (valor de 1 USD em cada moeda/cripto)
$USDBTC = $ar_data->{'BTC'} ?? 0;
$USDETH = $ar_data->{'ETH'} ?? 0;
$USDWBTC = $ar_data->{'WBTC'} ?? 0;
$USDWETH = $ar_data->{'WETH'} ?? 0;
$USDBNB = $ar_data->{'BNB'} ?? 0;
$USDXAUT = $ar_data->{'XAUT'} ?? 0;
$USDMATIC = $ar_data->{'MATIC'} ?? 0;
$USDEUR = $ar_data->{'EUR'} ?? 0;
$USDBRL = $ar_data->{'BRL'} ?? 0;

// calcular os valores inversos (valor de 1 unidade da moeda em USD)
$BTCUSD = ($USDBTC != 0) ? 1 / $USDBTC : 0;
$ETHUSD = ($USDETH != 0) ? 1 / $USDETH : 0;
$WBTCUSD = ($USDWBTC != 0) ? 1 / $USDWBTC : 0;
$WETHUSD = ($USDWETH != 0) ? 1 / $USDWETH : 0;
$BNBUSD = ($USDBNB != 0) ? 1 / $USDBNB : 0;
$XAUTUSD = ($USDXAUT != 0) ? 1 / $USDXAUT : 0;
$MATICUSD = ($USDMATIC != 0) ? 1 / $USDMATIC : 0;
$EURUSD = ($USDEUR != 0) ? 1 / $USDEUR : 0;
$BRLUSD = ($USDBRL != 0) ? 1 / $USDBRL : 0;

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


$ETHPLT = is_numeric($plata_pool) && is_numeric($weth_pool) && $weth_pool > 0
    ? number_format(($plata_pool / $weth_pool) * 10 ** 6, 4, '.', ',')
    : 1;

$plata_pool = floatval(str_replace(',', '.', $plata_pool));

$weth_pool = floatval(str_replace(',', '.', $weth_pool));

$USDPLT = ($weth_pool > 0 && $ETHUSD > 0)
    ? number_format(($plata_pool / ($weth_pool * $ETHUSD)) * 10 ** 7, 4, '.', '')
    : 138888.88;

$PLTUSD = ($USDPLT > 0)
    ? number_format((1 / $USDPLT) / 10, 10, '.', ',')
    : 0.0000072;

$PLTBRL = number_format(($PLTUSD * $USDBRL), 10, '.', ',');
$PLTEUR = number_format(($PLTUSD * $USDEUR), 10, '.', ',');

$PLTmarketcapUSD = number_format(($PLTcirculatingSupply * $PLTUSD), 4, '.', ',');
$PLTmarketcapBRL = number_format(($PLTcirculatingSupply * $PLTBRL), 4, '.', ',');
$PLTmarketcapEUR = number_format(($PLTcirculatingSupply * $PLTEUR), 4, '.', ',');

$MATIC_PER_PLT = $PLTUSD * $USDMATIC;
$PLT_PER_MATIC = ($MATIC_PER_PLT > 0) ? (1 / $MATIC_PER_PLT) : 0;

$PLT_PER_MATIC = number_format(($PLT_PER_MATIC), 4, '.', '');
$MATIC_PER_PLT = number_format(($MATIC_PER_PLT), 10, '.', ',');

$prices_json_file = __DIR__ . '/all_prices.json';

// formatar valores numericamente (sem vírgulas de milhares)
$USDBTC_num = number_format($USDBTC, 8, '.', '');
$USDETH_num = number_format($USDETH, 8, '.', '');
$USDWBTC_num = number_format($USDWBTC, 8, '.', '');
$USDWETH_num = number_format($USDWETH, 8, '.', '');
$USDBNB_num = number_format($USDBNB, 8, '.', '');
$USDXAUT_num = number_format($USDXAUT, 8, '.', '');
$USDMATIC_num = number_format($USDMATIC, 4, '.', '');
$USDEUR_num = number_format($USDEUR, 4, '.', '');
$USDBRL_num = number_format($USDBRL, 4, '.', '');
$BTCUSD_num = number_format($BTCUSD, 2, '.', '');
$ETHUSD_num = number_format($ETHUSD, 2, '.', '');
$WBTCUSD_num = number_format($WBTCUSD, 2, '.', '');
$WETHUSD_num = number_format($WETHUSD, 2, '.', '');
$BNBUSD_num = number_format($BNBUSD, 2, '.', '');
$XAUTUSD_num = number_format($XAUTUSD, 2, '.', '');
$MATICUSD_num = number_format($MATICUSD, 4, '.', '');
$EURUSD_num = number_format($EURUSD, 4, '.', '');
$BRLUSD_num = number_format($BRLUSD, 4, '.', '');
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
