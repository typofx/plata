<!DOCTYPE html>




<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta charset="utf-8">
    <meta name="keywords" content="Base Information, ​Countdown $PLT Airdrop ends in, The Project, Do you need more information?, The Roadmap, Meet The Team, ​Best Wallets For $PLT Plata">
    <meta name="description" content="">
    
    <title>Token Price Converter</title>

    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-index-style.css" media="screen">
    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-header-style.css" media="screen">
    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-sand-menu.css" media="screen">
    
    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-main-style.css" media="screen">
    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-button-style.css" media="screen">

    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-listing-style.css" media="screen">

    <script class="u-script" type="text/javascript" src="https://www.plata.ie/copyContract.js"></script> 
</head>

<body>

<body>
<br>
<center><h3>Token Price Converter</h3></center>

<div id="form" class="card-body">

    <div>
        <label for="PLTvalue" class="form-label">Plata (PLT)</label>
        <input type="number" id="PLTvalue" name="PLTvalue" size="15" maxlength="13" min="0.000001" onfocusout="calcFromPLT()" required>
        <!--  onkeyup="PLTexec()"  onkeypress="PLTexec()" onclick="this.select();" onkeypress="mascara(this,reais);PLTexec()"   -->
    </div>

    <div>
        <label for="USDvalue" class="form-label">US Dollar (USD)</label>
        <input type="number" id="USDvalue" name="USDvalue" value ="1.00" size="15" maxlength="13" min="0.01" onfocusout="calcFromUSD()" required>

    </div>
    
    <div>
        <label for="MATICvalue" class="form-label">MATIC</label>
        <input type="number" id="MATICvalue" name="MATICvalue" size="15" maxlength="13" min="0.00001" required>
    </div>

    <div>
        <label for="ETHvalue" class="form-label">Ethereum (ETH)</label>
        <input type="number" id="ETHvalue" name="ETHvalue" size="15" maxlength="13" min="0.000000001" required>
    </div>

    <div>
        <label for="BTCvalue" class="form-label">Bitcoin (BTC)</label>
        <input type="number" id="BTCvalue" name="BTCvalue" size="15" maxlength="13" min="0.000000001"  required>
    </div>
   
    <div>
      <label for="EURvalue" class="form-label">Euro (EUR)</label>
      <input type="number" id="EURvalue" name="EURvalue" size="15" maxlength="13" min="0.01" required>
   </div>
   <div>
      <label for="BRLvalue" class="form-label">Brazilian Real (BRL)</label>
      <input type="number" id="BRLvalue" name="BRLvalue" size="15" maxlength="13" min="0.01" required>
   </div>

   <br>

<!--
   
<button onclick="PLTexec()">PLT</button>
<button onclick="exec()">USD</button>
<button onclick="MATICexec()">MATIC</button>
<button onclick="ETHexec()">ETH</button>
<button onclick="BTCexec()">BTC</button>
<button onclick="EURexec()">EUR</button>
<button onclick="BRLexec()">BRL</button>

-->

<?php include '../price.php';?>

<script>

    let qtdUSD = 0;

    function atrAssets() {
        
        qtdUSD = document.getElementById("USDvalue").value;
        _PLTUSD = document.getElementById("PLTvalue").value;
        _USDBTC = document.getElementById("BTCvalue").value;
        _USDETH = document.getElementById("ETHvalue").value;
        _USDMATIC = document.getElementById("MATICvalue").value;
        _USDEUR = document.getElementById("EURvalue").value;
        _USDBRL = document.getElementById("BRLvalue").value;
        
        console.log("qtdUSD : " + qtdUSD);
        console.log("_PLTUSD : " + _PLTUSD);
        console.log("_USDBTC : " + _USDBTC);
        console.log("_USDETH : " + _USDETH);
        console.log("_USDMATIC : " + _USDMATIC);
        console.log("_USDEUR : " + _USDEUR);
        console.log("_USDBRL : " + _USDBRL);
    }
    
    function calcFromUSD() {
    
        <?php echo 'document.getElementById("messager").innerText ="From USD";'; ?>
    
        qtdUSD = document.getElementById("USDvalue").value;
        
        _PLTUSD = <?php echo number_format($PLTUSD, 8, '.' , ''); ?>;
        _USDBTC = <?php echo number_format($USDBTC, 8, '.' , ''); ?>;
        _USDETH = <?php echo number_format($USDETH, 8, '.' , ''); ?>;
        _USDMATIC = <?php echo number_format($USDMATIC, 8, '.' , ''); ?>;
        _USDEUR = <?php echo number_format($USDEUR, 8, '.' , ''); ?>;
        _USDBRL = <?php echo number_format($USDBRL, 8, '.' , ''); ?>;
    
        
        document.getElementById("PLTvalue").value = Number(qtdUSD / _PLTUSD).toFixed(4);

        document.getElementById("BTCvalue").value = Number(qtdUSD * _USDBTC).toFixed(8);
        document.getElementById("ETHvalue").value = Number(qtdUSD * _USDETH).toFixed(8);
        document.getElementById("MATICvalue").value = Number(qtdUSD * _USDMATIC).toFixed(5);
        document.getElementById("EURvalue").value = Number(qtdUSD * _USDEUR).toFixed(3);
        document.getElementById("BRLvalue").value = Number(qtdUSD * _USDBRL).toFixed(3);
    }
    
    function calcFromPLT() {
    
        <?php echo 'document.getElementById("messager").innerText ="From PLT";'; ?>
        
        qtdPLT = document.getElementById("PLTvalue").value;
    
        _USDPLT = <?php echo number_format($USDPLT, 8, '.' , ''); ?>;
        _PLTUSD = <?php echo number_format($PLTUSD, 8, '.' , ''); ?>;
        _USDBTC = <?php echo number_format($USDBTC, 8, '.' , ''); ?>;
        _USDETH = <?php echo number_format($USDETH, 8, '.' , ''); ?>;
        _USDMATIC = <?php echo number_format($USDMATIC, 8, '.' , ''); ?>;
        _USDEUR = <?php echo number_format($USDEUR, 8, '.' , ''); ?>;
        _USDBRL = <?php echo number_format($USDBRL, 8, '.' , ''); ?>;
    
        
        document.getElementById("USDvalue").value = Number(_USDPLT / qtdPLT).toFixed(4);
        
        qtdUSD = document.getElementById("USDvalue").value;
        
        document.getElementById("BTCvalue").value = Number(qtdUSD * _USDBTC).toFixed(8);
        document.getElementById("ETHvalue").value = Number(qtdUSD * _USDETH).toFixed(8);
        document.getElementById("MATICvalue").value = Number(qtdUSD * _USDMATIC).toFixed(5);
        document.getElementById("EURvalue").value = Number(qtdUSD * _USDEUR).toFixed(3);
        document.getElementById("BRLvalue").value = Number(qtdUSD * _USDBRL).toFixed(3);
    }

</script>

<button type="button" onclick="calcFromUSD()">Click Me!</button>

<a id="messager"/>

</body>
<script>
       
    try {
        calcFromUSD();
    } catch (err) { console.log("error line 114"); }
    
</script>
</html>
