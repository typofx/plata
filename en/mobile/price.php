<!--<script class="u-script" type="text/javascript" src="https://www.plata.ie/js/cryptoprices.js" defer=""></script>-->

<?php include $_SERVER['DOCUMENT_ROOT'] . '/js/cryptoprices.php';?>

<script>

    function setUSDcurrency(){
    
    try {
        updatePrices();
    } catch (err) { console.log("error line 008"); }
    
    <?php
    $defaulCurrency = "(USD)";
    $PLTpair = $PLTUSD;
    $marketSymbol = "$";
    $PLTmarketcap = $PLTmarketcapUSD;
    ?>

    defCurrency = "<?php echo $defaulCurrency; ?>";
    console.log("defC: "+ defCurrency);
    
    
    //document.getElementById("BTCvalue").value = Number(_BTCUSD);

    console.log("<?php echo $marketSymbol?>");
    console.log("<?php echo $defaulCurrency?>");
    console.log("<?php echo $PLTmarketcapUSD?>");
    console.log("<?php echo $PLTUSD?>");
    
    try {
    <?php
    echo 'document.getElementById("txtCurrencyEnv").innerText ="'.$defaulCurrency.'";';
    echo 'document.getElementById("txtPAIR").innerText ="'.$PLTpair.'";';
    echo 'document.getElementById("txtMarketcap").innerText ="'.$txtTokenMarketcap ." ". $marketSymbol. " ".$PLTmarketcap.'";';
    ?>
    }
    catch(err)  { console.log("error"); }
    
    try {
    updatePrices();
    
    } catch (err) { console.log("error line 037"); }

    try {
        HideAllSubMenu();
        closeMenuClick();
    } catch (err) { console.log("error line 045"); }
    
}

function setBRLcurrency(){
    
    updatePrices();
     
    <?php
    $defaulCurrency = "(BRL)";
    $PLTpair = $PLTBRL;
    $marketSymbol = "R$";
    $PLTmarketcap = $PLTmarketcapBRL;
    ?>
    
    defCurrency = "<?php echo $defaulCurrency; ?>";
    console.log("defC: "+ defCurrency);
    
    console.log("<?php echo $marketSymbol?>");
    console.log("<?php echo $defaulCurrency?>");
    console.log("<?php echo $PLTmarketcapBRL?>");
    console.log("<?php echo $PLTBRL?>");
    
    
    try {
    <?php
    echo 'document.getElementById("item-currency").innerText ="teste";';
    echo 'document.getElementById("txtCurrencyEnv").innerText ="'.$defaulCurrency.'";';
    echo 'document.getElementById("txtPAIR").innerText ="'.$PLTpair.'";';
    echo 'document.getElementById("txtMarketcap").innerText ="'.$txtTokenMarketcap ." ". $marketSymbol. " ".$PLTmarketcap.'";';
    ?>
    }
    catch(err)  { console.log("error"); }
    
    updatePrices();
    
    try {
        HideAllSubMenu();
        closeMenuClick();
    } catch (err) { console.log("error line 085"); }
}

function setEURcurrency(){
    
    updatePrices();
    
    <?php
    $defaulCurrency = "(EUR)";
    $PLTpair = $PLTEUR;
    $marketSymbol = "€";
    $PLTmarketcap = $PLTmarketcapEUR;
    ?>
    
    defCurrency = "<?php echo $defaulCurrency; ?>";
    console.log("defC: "+ defCurrency);

    console.log("<?php echo $marketSymbol?>");
    console.log("<?php echo $defaulCurrency?>");
    console.log("<?php echo $PLTmarketcapBRL?>");
    console.log("<?php echo $PLTBRL?>");
    
    try {
    <?php
    
    echo 'document.getElementById("txtCurrencyEnv").innerText ="'.$defaulCurrency.'";';
    echo 'document.getElementById("txtPAIR").innerText ="'.$PLTpair.'";';
    echo 'document.getElementById("txtMarketcap").innerText ="'.$txtTokenMarketcap ." ". $marketSymbol. " ".$PLTmarketcap.'";';
    ?>
    }
    catch(err)  { console.log("error"); }
    
    updatePrices();

    try {
        HideAllSubMenu();
        closeMenuClick();
    } catch (err) { console.log("error line 118"); }
    
}
    
</script>
<style>

/*
table, th, td {
  border:1px solid black;
}
*/



.gray-button {
    display: inline-block;
    background-color: #EBECF0;
    border-radius: 8px; 
    border: 0px solid #3D3D3D;
    color: black; 
    font-size: 13px; 
    font-family: 'Montserrat'; 
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    padding: 6px 12px;
}

.table-crypto-price {
    border-collapse: collapse;
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}



.tr-price {
  text-align: center;
}

.th-side {
    width:5%;
}

.th-token-icon {
    width:10%;
}

.center {
    text-align: center;
}

.right {
    text-align: right;
}

.left {
    text-align: left;
}

.th-crypto-name {
    width:40%;
    padding-left: 5px;
}

.th-crypto-price {
    padding-right: 5px;
    width:40%;
}

.a-crypto-name {
    font-family: "Montserrat", sans-serif;
    font-size: 13px; 
    background: white;
    text-decoration: none;
    -webkit-background-clip: text;
}

.a-crypto-symbol {
    font-family: "Montserrat", sans-serif;
    font-size: 12px; 
    background: white;
    text-decoration: none;
    -webkit-background-clip: text;
    color: gray;
}

.padtop {
    padding-top: 15px;
}

</style>

<section id="CryptoPrices">

<table class="table-crypto-price">
  <tr class="tr-price">
    <th class="th-side"></th>
    <th></th>
    <th class="th-crypto-name left padtop"><a class="a-crypto-symbol">PLT</a>
    </th>
    <th class="th-crypto-price right padtop"><a id="txtPrice"class="a-crypto-symbol"></a><a id="txtCurrencyEnv" class="a-crypto-symbol"></a></th>
    <th class="th-side"></th>
  </tr>

  <tr id="blockCurrency" class="tr-price">
    <th class="th-side"></th>
    <th class="th-token-icon left"><img height="38px" src="https://www.plata.ie/images/platasmall.svg"></th>
    <th class="th-crypto-name left">
        <a class="a-crypto-name" id="txtTokenName">Plata Token</a>
    </th>

    <th class="th-crypto-price right"><a class="a-crypto-name right" id="txtPAIR"></a></th>
    <th class="th-side"></th>
  </tr>



  <tr id="tr-price" class="tr-price">
    <th class="th-side"></th>
    <th class="th-crypto-name center" colspan="3"><button id="txtMarketcap" class="gray-button"></button></th>
    <th class="th-side"></th>
  </tr>
</table>

</section>

<script>

    setUSDcurrency();
        try {
        setUSDcurrency();
    } catch (err) { console.log("error line 242"); }
    
</script>

