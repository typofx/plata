<?php include ($_SERVER['DOCUMENT_ROOT'] . '/%pw/env.php'); ?>

<?php

$debugging_mode = false;

$json_wmatic_pool__0x0E1_671a6 = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey='.$API_KEY_ETHERSCAN;
$json_plata_pool__0x0E1_671a6 = 'https://api.etherscan.io/v2/api?module=account&chainid=137&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey='.$API_KEY_ETHERSCAN;

$PLTcirculatingSupply = (11299000992);

$json_btcusd = 'https://api.coinpaprika.com/v1/tickers/btc-bitcoin';
$json_ethusd = 'https://api.coinpaprika.com/v1/tickers/eth-ethereum';
$json_wbtcusd = 'https://api.coinpaprika.com/v1/tickers/wbtc-wrapped-bitcoin';
$json_wethusd = 'https://api.coinpaprika.com/v1/tickers/weth-weth';
$json_bnbusd = 'https://api.coinpaprika.com/v1/tickers/bnb-bnb';
$json_xautusd = 'https://api.coinpaprika.com/v1/tickers/xaut-tether-gold';
$json_maticusd = 'https://api.coinpaprika.com/v1/tickers/matic-polygon';
$json_eursusd = 'https://api.coinpaprika.com/v1/tickers/eurs-stasis-euro';
$json_brlusd = 'https://api.coinpaprika.com/v1/tickers/brz-brazilian-digital-token';
$json_brzusd = 'https://api.coinpaprika.com/v1/tickers/brz-brazilian-digital-token';
$json_usdcusd = 'https://api.coinpaprika.com/v1/tickers/usdc-usd-coin';
$json_daiusd = 'https://api.coinpaprika.com/v1/tickers/dai-dai';
$json_usdtusd = 'https://api.coinpaprika.com/v1/tickers/usdt-tether';
$json_busdusd = 'https://api.coinpaprika.com/v1/tickers/busd-binance-usd';

$BTCUSD = number_format(json_decode(array(file_get_contents($json_btcusd))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');
$ETHUSD = number_format(json_decode(array(file_get_contents($json_ethusd))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');
$WBTCUSD = number_format(json_decode(array(file_get_contents($json_wbtcusd))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');
$WETHUSD = number_format(json_decode(array(file_get_contents($json_wethusd))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');
$BNBUSD = number_format(json_decode(array(file_get_contents($json_bnbusd))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');
$XAUTUSD = number_format(json_decode(array(file_get_contents($json_xautusd))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');
$MATICUSD = number_format(json_decode(array(file_get_contents($json_maticusd))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');
$EURUSD = number_format(json_decode(array(file_get_contents($json_eursusd))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');
$BRZUSD = number_format(json_decode(array(file_get_contents($json_brzusd))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');
$USDCUSD = number_format(json_decode(array(file_get_contents($json_usdcusd))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');
$BRLUSD = number_format(json_decode(array(file_get_contents($json_brzusd))[0],true)['quotes']['USD']['price'] ?? 0, 5, '.', ',');

$DAIUSD_raw = json_decode(array(file_get_contents($json_daiusd))[0],true)['quotes']['USD']['price'] ?? 0;
$USDTUSD_raw = json_decode(array(file_get_contents($json_usdtusd))[0],true)['quotes']['USD']['price'] ?? 0;
$BUSDUSD_raw = json_decode(array(file_get_contents($json_busdusd))[0],true)['quotes']['USD']['price'] ?? 0;

$DAIUSD = number_format($DAIUSD_raw, 5, '.', ',');
$USDTUSD = number_format($USDTUSD_raw, 5, '.', ',');
$BUSDUSD = number_format($BUSDUSD_raw, 5, '.', ',');
    
    if ($BTCUSD == 0) {
        
        $BTCUSD = 0x3440;
        $ETHUSD = 0xF411;
        
        $ERROR_MSG = $BTCUSD = $WBTCUSD = $WETHUSD = $BNBUSD = $XAUTUSD = $MATICUSD = $EURUSD = $BRLUSD = $BRZUSD = $USDCUSD = $DAIUSD = $USDTUSD = $BUSDUSD = 0xF411;
        $ERROR_OUTPUT = "\n  " . '"offline": ' . $ERROR_MSG. ",";
        printf("0x" . "%02x\n", $BTCUSD . " // " . $ETHUSD);
    }


$qtd_wmatic_pool__0x0E1_671a6 = number_format(json_decode(array(file_get_contents($json_wmatic_pool__0x0E1_671a6))[0],true)['result'] / (10 ** 18) ?? 0 , 5, '.', ',');
$qtd_plata_pool__0x0E1_671a6 = number_format(json_decode(array(file_get_contents($json_plata_pool__0x0E1_671a6))[0],true)['result'] / (10 ** 4) ?? 0 , 4, '.', ',');

$PLTUSD = number_format( ($qtd_plata_pool__0x0E1_671a6 / ($qtd_wmatic_pool__0x0E1_671a6 * $MATICUSD ) ) * (10 ** 6) ?? 0 , 4, '.', ',');
$PLTEUR = number_format( ($qtd_plata_pool__0x0E1_671a6 / ($qtd_wmatic_pool__0x0E1_671a6 * $MATICUSD * $EURUSD) ) * (10 ** 6) ?? 0 , 4, '.', ',');
$PLTBRL = number_format( ($qtd_plata_pool__0x0E1_671a6 / ($qtd_wmatic_pool__0x0E1_671a6 * $MATICUSD * $BRLUSD) ) * (10 ** 6) ?? 0 , 4, '.', ',');

$USDBTC = number_format ( (1 / $BTCUSD) / ( $BTCUSD > 1000 ? (10 ** 3) : (1) ) ?? 0 , 8, '.', ',');
$USDETH = number_format ( (1 / $ETHUSD) / ( $ETHUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDWBTC = number_format ( (1 / $WBTCUSD) / ( $WBTCUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDWETH = number_format ( (1 / $WETHUSD) / ( $WETHUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDBNB = number_format ( (1 / $BNBUSD) / ( $BNBUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDXAUT = number_format ( (1 / $XAUTUSD) / ( $XAUTUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDMATIC = number_format ( (1 / $MATICUSD) / ( $MATICUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDEUR = number_format ( (1 / $EURUSD) / ( $EURUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDBRZ = number_format ( (1 / $BRZUSD) / ( $BRZUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDUSDC = number_format ( (1 / $USDCUSD) / ( $USDUSDC > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDBRL = number_format ( (1 / $BRZUSD) / ( $BRZUSD > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');


$USDDAI = number_format ( ($DAIUSD_raw > 0 ? 1 / $DAIUSD_raw : 0) , 8, '.', ',' );
$USDUSDT = number_format ( ($USDTUSD_raw > 0 ? 1 / $USDTUSD_raw : 0) , 8, '.', ',' );
$USDBUSD = number_format ( ($BUSDUSD_raw > 0 ? 1 / $BUSDUSD_raw : 0) , 8, '.', ',' );

$PLTMATIC = number_format ( ($qtd_plata_pool__0x0E1_671a6 / $qtd_wmatic_pool__0x0E1_671a6) * (10 ** 6) ?? 0 , 4, '.', ',');
$MATICPLT = number_format ( (1 / $PLTMATIC) / ( $PLTMATIC > 1000 ? (10 ** 3) : 1 ) ?? 0 , 8, '.', ',');
$USDPLT = number_format((1 / $PLTUSD) / (10 ** 3) ?? 0 , 10, '.', ',');

$PLTmarketcapUSD = number_format( ($PLTcirculatingSupply * $USDPLT) , 4, '.', ',' );
$PLTmarketcapEUR = number_format( ($PLTcirculatingSupply * $USDPLT * $USDEUR) , 4, '.', ',' );
$PLTmarketcapBRL = number_format( ($PLTcirculatingSupply * $USDPLT * $USDBRL) , 4, '.', ',' );

$USDBTC_num = str_replace(',', '', $USDBTC);
$USDETH_num = str_replace(',', '', $USDETH);
$USDWBTC_num = str_replace(',', '', $USDWBTC);
$USDWETH_num = str_replace(',', '', $USDWETH);
$USDBNB_num = str_replace(',', '', $USDBNB);
$USDXAUT_num = str_replace(',', '', $USDXAUT);
$USDMATIC_num = str_replace(',', '', $USDMATIC);
$USDEUR_num = str_replace(',', '', $USDEUR);
$USDBRL_num = str_replace(',', '', $USDBRL);
$USDBRZ_num = str_replace(',', '', $USDBRZ);
$USDUSDC_num = str_replace(',', '', $USDUSDC);
$USDDAI_num = str_replace(',', '', $USDDAI);
$USDUSDT_num = str_replace(',', '', $USDUSDT);
$USDBUSD_num = str_replace(',', '', $USDBUSD);
$BTCUSD_num = str_replace(',', '', $BTCUSD);
$WBTCUSD_num = str_replace(',', '', $WBTCUSD);
$WETHUSD_num = str_replace(',', '', $WETHUSD);
$ETHUSD_num = str_replace(',', '', $ETHUSD);
$BNBUSD_num = str_replace(',', '', $BNBUSD);
$XAUTUSD_num = str_replace(',', '', $XAUTUSD);
$MATICUSD_num = str_replace(',', '', $MATICUSD);
$EURUSD_num = str_replace(',', '', $EURUSD);
$BRZUSD_num = str_replace(',', '', $BRZUSD);
$USDCUSD_num = str_replace(',', '', $USDCUSD);
$DAIUSD_num = str_replace(',', '', $DAIUSD);
$USDTUSD_num = str_replace(',', '', $USDTUSD);
$BUSDUSD_num = str_replace(',', '', $BUSDUSD);
$PLTUSD_num = str_replace(',', '', $PLTUSD);
$PLTBRL_num = str_replace(',', '', $PLTBRL);
$PLTEUR_num = str_replace(',', '', $PLTEUR);
$PLTMATIC_num = str_replace(',', '', $PLTMATIC);
$MATICPLT_num = str_replace(',', '', $MATICPLT);
$USDPLT_num = str_replace(',', '', $USDPLT);
$PLTmarketcapUSD_num = str_replace(',', '', $PLTmarketcapUSD);
$PLTmarketcapBRL_num = str_replace(',', '', $PLTmarketcapBRL);
$PLTmarketcapEUR_num = str_replace(',', '', $PLTmarketcapEUR);

$prices_json_file = __DIR__ . '/all_prices.json';

$last_updated_at = gmdate('d-m-Y H:i:s') . ' UTC';

$json_string_manual = <<<JSON
{
  "last_updated_at": "{$last_updated_at}", $ERROR_OUTPUT
  "prices_vs_usd": {
    "USDBTC": {$USDBTC},
    "USDETH": {$USDETH_num},
    "USDWBTC": {$USDWBTC_num},
    "USDWETH": {$USDWETH_num},
    "USDBNB": {$USDBNB_num},
    "USDXAUT": {$USDXAUT_num},
    "USDMATIC": {$USDMATIC_num},
    "USDEUR": {$USDEUR_num},
    "USDBRL": {$USDBRL_num},
    "USDBRZ": {$USDBRZ_num},
    "USDUSDC": {$USDUSDC_num},
    "USDDAI": {$USDDAI_num},
    "USDUSDT": {$USDUSDT_num},
    "USDBUSD": {$USDBUSD_num}
  },
  "usd_vs_prices": {
    "BTCUSD": {$BTCUSD_num},
    "ETHUSD": {$ETHUSD_num},
    "WBTCUSD": {$WBTCUSD_num},
    "WETHUSD": {$WETHUSD_num},
    "BNBUSD": {$BNBUSD_num},
    "XAUtUSD": {$XAUTUSD_num},
    "MATICUSD": {$MATICUSD_num},
    "EURUSD": {$EURUSD_num},
    "BRLUSD": {$BRLUSD},
    "BRZUSD": {$BRZUSD_num},
    "USDCUSD": {$USDCUSD_num},
    "DAIUSD": {$DAIUSD_num},
    "USDTUSD": {$USDTUSD_num},
    "BUSDUSD": {$BUSDUSD_num}
  },
  "plt_prices": {
    "PLTUSD": {$PLTUSD_num},
    "PLTBRL": {$PLTBRL_num},
    "PLTEUR": {$PLTEUR_num},
    "PLTMATIC": {$PLTMATIC_num},
    "MATICPLT": {$MATICPLT_num},
    "USDPLT": {$USDPLT_num}
  },
  "plt_marketcap": {
    "USD": {$PLTmarketcapUSD_num},
    "BRL": {$PLTmarketcapBRL_num},
    "EUR": {$PLTmarketcapEUR_num}
  }
}
JSON;


file_put_contents($prices_json_file, $json_string_manual);

if ($debugging_mode == true) include 'debugger.cryptoprices.php';

?>
