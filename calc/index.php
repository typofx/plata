<!DOCTYPE html>




<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta charset="utf-8">
    <meta name="keywords" content="Base Information, ​Countdown $PLT Airdrop ends in, The Project, Do you need more information?, The Roadmap, Meet The Team, ​Best Wallets For $PLT Plata">
    <meta name="description" content="">
    
    <title>Token Price Converter</title>

    <link rel="stylesheet" href="calc-style.css" media="screen">


    <script class="u-script" type="text/javascript" src="https://www.plata.ie/copyContract.js"></script> 
</head>

<body>

<br>

<div id="boxApp" align="center">
<div id="box" class="box">
<center><h3>Token Price Converter</h3></center>

<div id="forml">

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
        <input type="number" id="MATICvalue" name="MATICvalue" size="15" maxlength="13" min="0.00001"  onfocusout="calcFromMATIC()" required>
    </div>

    <div>
        <label for="ETHvalue" class="form-label">Ethereum (ETH)</label>
        <input type="number" id="ETHvalue" name="ETHvalue" size="15" maxlength="13" min="0.000000001" required>
    </div>

    <div>
        <label for="BTCvalue" class="form-label">Bitcoin (BTC)</label>
        <input type="number" id="BTCvalue" name="BTCvalue" size="15" maxlength="13" min="0.000000001"  onfocusout="calcFromBTC()" required>
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

    let qtdUSD = 0, qtdPLT = 0, qtdBTC = 0, qtdETH = 0, qtdMATIC = 0, qtdEUR = 0, qtdBRL = 0;

    function atrAssets() {
        
        _USDPLT = <?php echo number_format($USDPLT, 8, '.' , ''); ?>;
        _PLTUSD = <?php echo number_format($PLTUSD, 8, '.' , ''); ?>;
        _USDBTC = <?php echo number_format($USDBTC, 8, '.' , ''); ?>;
        _USDETH = <?php echo number_format($USDETH, 8, '.' , ''); ?>;
        _USDMATIC = <?php echo number_format($USDMATIC, 8, '.' , ''); ?>;
        _USDEUR = <?php echo number_format($USDEUR, 8, '.' , ''); ?>;
        _USDBRL = <?php echo number_format($USDBRL, 8, '.' , ''); ?>;
        
        console.log("qtdUSD : " + qtdUSD);
        console.log("_PLTUSD : " + _PLTUSD);
        console.log("_USDBTC : " + _USDBTC);
        console.log("_USDETH : " + _USDETH);
        console.log("_USDMATIC : " + _USDMATIC);
        console.log("_USDEUR : " + _USDEUR);
        console.log("_USDBRL : " + _USDBRL);
        
        qtdUSD = 0;
        qtdPLT = 0;
        qtdBTC = 0;
        qtdETH = 0;
        qtdMATIC = 0;
        qtdEUR = 0;
        qtdBRL = 0;
        
    }
    
    function calcAllAssets() {
        
        qtdUSD = document.getElementById("USDvalue").value;
        
        if (qtdPLT == 0) document.getElementById("PLTvalue").value = Number(qtdUSD / _PLTUSD).toFixed(4);
        if (qtdBTC == 0) document.getElementById("BTCvalue").value = Number(qtdUSD * _USDBTC).toFixed(8);
        if (qtdETH == 0) document.getElementById("ETHvalue").value = Number(qtdUSD * _USDETH).toFixed(8);
        if (qtdMATIC == 0) document.getElementById("MATICvalue").value = Number(qtdUSD * _USDMATIC).toFixed(5);
        if (qtdEUR == 0) document.getElementById("EURvalue").value = Number(qtdUSD * _USDEUR).toFixed(3);
        if (qtdBRL == 0) document.getElementById("BRLvalue").value = Number(qtdUSD * _USDBRL).toFixed(3);
    }
    
    function calcFromUSD() {
    
        atrAssets();
        
        qtdUSD = Number(document.getElementById("USDvalue").value);
        
        calcAllAssets();
    }
    
    function calcFromPLT() {
    
        atrAssets();
        
        qtdPLT = document.getElementById("PLTvalue").value;
        document.getElementById("USDvalue").value = Number( _PLTUSD * qtdPLT ).toFixed(8);

        console.log("qtdPLT: " + qtdPLT);
        
        if (Number(qtdPLT) < 100000) {
            qtdUSD = document.getElementById("USDvalue").value;
            document.getElementById("BTCvalue").value = Number(qtdUSD * _USDBTC).toFixed(12);
            document.getElementById("ETHvalue").value = Number(qtdUSD * _USDETH).toFixed(10);
            document.getElementById("MATICvalue").value = Number(qtdUSD * _USDMATIC).toFixed(10);
            document.getElementById("EURvalue").value = Number(qtdUSD * _USDEUR).toFixed(8);
            document.getElementById("BRLvalue").value = Number(qtdUSD * _USDBRL).toFixed(10);
        } else {
            document.getElementById("USDvalue").value = Number( _PLTUSD * qtdPLT ).toFixed(3);
            calcAllAssets();
        }
        
    }

    function calcFromBTC() {
    
        atrAssets();
        
        qtdBTC = document.getElementById("BTCvalue").value;
        
        if (qtdUSD == 0) {
            document.getElementById("USDvalue").value = Number( qtdBTC / _USDBTC  ).toFixed(3);
            qtdUSD = document.getElementById("USDvalue").value;
        }
        
        calcAllAssets();

    }
    
    function calcFromMATIC() {
    
        atrAssets();
        
        qtdMATIC = document.getElementById("MATICvalue").value;
        
        if (qtdUSD == 0) {
            document.getElementById("USDvalue").value = Number( qtdMATIC / _USDMATIC  ).toFixed(3);
            qtdUSD = document.getElementById("USDvalue").value;
        }
        
        calcAllAssets();

    }
    
    function calcFromETHC() {
    
        atrAssets();
        
        qtdETH = document.getElementById("ETHvalue").value;

        document.getElementById("USDvalue").value = Number( qtdETH / _USDETH  ).toFixed(3);
        

        calcAllAssets();

    }


</script>

<button type="button" onclick="calcFromUSD()">Click Me!</button>
<button type="button" onclick="calcFromPLT()">Click Me!</button>

<a id="messager"/>

</body>
<script>
       
    try {
        calcFromUSD();
    } catch (err) { console.log("error line 114"); }
    
</script>
</html>
