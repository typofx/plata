function updatePrices(){

 
    

    console.log("<?php echo "USDMATIC : ".$USDMATIC?>");
    console.log("<?php echo "USDEUR : ".$USDEUR?>");
    console.log("<?php echo "USDBRL : ".$USDBRL?>");
    console.log("<?php echo "MATICPLT : ".$wmatic_pool?>");
    console.log("<?php echo "USDPLT : ".$USDPLT?>");
    console.log("<?php echo "PLTBRL : ".$PLTBRL?>");
    console.log("<?php echo "PLTEUR : ".$PLTEUR?>");
    console.log("<?php echo "PLTmarketcapUSD : ".$PLTmarketcapUSD?>");
    console.log("<?php echo "PLTmarketcapBRL : ".$PLTmarketcapBRL?>");
    console.log("<?php echo "PLTmarketcapEUR : ".$PLTmarketcapEUR?>");
    console.log("<?php echo "plata_pool : ".$plata_pool?>");
    console.log("<?php echo "MATICPLT : ".$MATICPLT?>");
    
    console.log("<?php echo $defaulCurrency?>");
    console.log("<?php echo $PLTmarketcapBRL?>");
    console.log("<?php echo $PLTBRL?>");
    console.log("<?php echo $marketSymbol?>");
    
    console.log("defx: "+ defCurrency);
    
    if (defCurrency=="USD") setUSDcurrency();
        else if (defCurrency=="BRL") setBRLcurrency();
            else if (defCurrency=="EUR") setEURcurrency();
            
}