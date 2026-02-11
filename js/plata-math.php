<?php
// circulating supply : 11299000992

function deploy_plata_rates($_MATICUSD, $_EURUSD, $_BRLUSD, $_qtd_plata_pool__0x0E1_671a6, $_qtd_wmatic_pool__0x0E1_671a6, $_PLT_circulating_supply): array {    

    $poolWMATIC = round ((float)str_replace(',', '', $_qtd_wmatic_pool__0x0E1_671a6), 18);
    $poolUSD = round (( $poolWMATIC * (float)str_replace(',', '', $_MATICUSD)), 4);
    $poolPLT = round ((float)str_replace(',', '', $_qtd_plata_pool__0x0E1_671a6), 4);
    
    $PLTMATIC = round( $poolPLT/$poolWMATIC , 8);
    $PLTUSD = round( $poolPLT/$poolUSD , 8);
    $PLTEUR = round( $poolPLT/($poolUSD/$_EURUSD) , 8);
    $PLTBRL = round( $poolPLT/($poolUSD/$_BRLUSD) , 8);
    
    $MATICPLT = round( $poolWMATIC/$poolPLT , 8);
    $USDPLT = round( $poolUSD/$poolPLT , 8);
    $EURPLT = round( $poolUSD * ($_EURUSD/$poolPLT) , 8);
    $BRLPLT = round( $poolUSD * ($_BRLUSD/$poolPLT) , 8);

    $PLTmarketcapUSD = round( $_PLT_circulating_supply * $USDPLT , 4);
    $PLTmarketcapEUR = round( $PLTmarketcapUSD/$_EURUSD, 4);
    $PLTmarketcapBRL = round( $PLTmarketcapUSD/$_BRLUSD, 4);
    
    return [
        'plt_prices' => [
            //'poolPLT' => $poolPLT,
            //'poolUSD' => $poolUSD,
            //'poolWMATIC' => $poolWMATIC,
            'PLTMATIC' => $PLTMATIC,
            'PLTUSD' => $PLTUSD,
            'PLTBRL' => $PLTBRL,
            'PLTEUR' => $PLTEUR,
            'MATICPLT' => $MATICPLT,
            'USDPLT' => $USDPLT,
            'EURPLT' => $EURPLT,
            'BRLPLT' => $BRLPLT
        ],    
        'plt_marketcap' => [
            'USD' => $PLTmarketcapUSD,
            'BRL' => $PLTmarketcapBRL,
            'EUR' => $PLTmarketcapEUR
        ]    
    ];    
}    

?>