<!doctype html>

<head>
<title>Token Price Converter</title>
</head>
<body>
<br>
<center><h3>Token Price Converter</h3></center>

<div id="form" class="card-body">

    <div>
        <label for="PLTvalue" class="form-label">Plata (PLT)</label>
        <input type="number" id="PLTvalue" name="PLTvalue" size="15" maxlength="13" min="0.000001" onkeyup="PLTexec()"  onkeypress="PLTexec()" onclick="this.select();" onkeypress="mascara(this,reais);PLTexec()" onfocusout="zero()" required>
        
    </div>

    <div>
        <label for="USDvalue" class="form-label">US Dollar (USD)</label>
        <input type="number" id="USDvalue" name="USDvalue" value ="1.00" size="15" maxlength="13" min="0.01" onkeyup="exec()" onkeypress="exec()" onclick="this.select();" onfocusout="zero()" required>
        <!--  -->
    </div>
    
    <div>
        <label for="MATICvalue" class="form-label">MATIC</label>
        <input type="number" id="MATICvalue" name="MATICvalue" size="15" maxlength="13" min="0.00001" onkeyup="MATICexec()" onkeypress="MATICexec()" onclick="this.select();" required>
    </div>

    <div>
        <label for="ETHvalue" class="form-label">Ethereum (ETH)</label>
        <input type="number" id="ETHvalue" name="ETHvalue" size="15" maxlength="13" min="0.000000001" onkeyup="ETHexec()" onkeypress="ETHexec()" onclick="this.select();" required>
    </div>

    <div>
        <label for="BTCvalue" class="form-label">Bitcoin (BTC)</label>
        <input type="number" id="BTCvalue" name="BTCvalue" size="15" maxlength="13" min="0.000000001"  onkeyup="BTCexec()" onkeypress="BTCexec()" onclick="this.select();" required>
    </div>
   
    <div>
      <label for="EURvalue" class="form-label">Euro (EUR)</label>
      <input type="number" id="EURvalue" name="EURvalue" size="15" maxlength="13" min="0.01" onkeyup="EURexec()" onkeypress="EURexec()" onclick="this.select();" required>
   </div>
   <div>
      <label for="BRLvalue" class="form-label">Brazilian Real (BRL)</label>
      <input type="number" id="BRLvalue" name="BRLvalue" size="15" maxlength="13" min="0.01" onkeyup="BRLexec()" onkeypress="BRLexec()" onclick="this.select();" required>
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

const form = document.getElementById("form");

form.addEventListener("focus", (event) => {
    
    console.log(event.target.name);
    

    if (event.target.name == "USDvalue") {
        let textUSD = Number(document.getElementById("USDvalue").value);
        document.getElementById("PLTvalue").value = (textUSD/_PLTUSD).toFixed(4);
        document.getElementById("BRLvalue").value = (textUSD /_BRLUSD).toFixed(4);
        document.getElementById("EURvalue").value = (textUSD / _EURUSD).toFixed(4);
        document.getElementById("MATICvalue").value = (_MATICusd * textUSD).toFixed(4);
        document.getElementById("ETHvalue").value = (textUSD / _ETHUSD).toFixed(12);
        //document.getElementById("BTCvalue").value = (textUSD / _BTCUSD).toFixed(8);
    }
    else if (event.target.name == "BRLvalue") {
        let textBRL = Number(document.getElementById("BRLvalue").value);
        document.getElementById("USDvalue").value = (_BRLUSD * textBRL).toFixed(10);
        let textUSD = Number(document.getElementById("USDvalue").value);
    
        document.getElementById("PLTvalue").value = (textUSD/_PLTUSD).toFixed(4);
        document.getElementById("EURvalue").value = (textUSD / _EURUSD).toFixed(10);
        document.getElementById("MATICvalue").value = (textUSD /_MATICusd).toFixed(10);
        document.getElementById("ETHvalue").value = (textUSD / _ETHUSD).toFixed(10);
        //document.getElementById("BTCvalue").value = (textUSD / _BTCUSD).toFixed(8);
    }
    /*
    if (event.target.name != "BTCvalue" && event.target.name != "USDvalue" ) document.getElementById("BTCvalue").value = (textUSD / _BTCUSD).toFixed(8);
    else {
        let textBTC = Number(document.getElementById("BTCvalue").value);
        document.getElementById("USDvalue").value = (_BTCUSD * textBTC).toFixed(10);
    }
    */
    //event.target.style.background = "pink";
    },
    true
);

form.addEventListener("blur", (event) => {
    event.target.style.background = "";
    },
    true
);


    let _ETHUSD = 0;
    let _BTCUSD = 0;

	const apiCryptoETH = 'https://api.coingecko.com/api/v3/coins/ethereum';
	const apiCryptoBTC = 'https://api.coingecko.com/api/v3/coins/bitcoin';
	
    async function getETHUSD()
    {
	const response = await fetch (apiCryptoETH);
	const data = await response.json();
	const { market_data } = data; 
    //console.log(data.market_data.current_price.usd);
    _ETHUSD = data.market_data.current_price.usd;

	} getETHUSD();
	
    async function getBTCUSD()
    {
	const response = await fetch (apiCryptoBTC);
	const data = await response.json();
	const { market_data } = data; 
    //console.log(data.market_data.current_price.usd);
    _BTCUSD = data.market_data.current_price.usd;

	} getBTCUSD();


function copiar() {
  var copyText = document.getElementById("brcodepix");
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */
  document.execCommand("copy");
  document.getElementById("clip_btn").innerHTML='<i class="fas fa-clipboard-check"></i>';
}

function checkey(){
    if (event.keyCode === 13) console.log("ok"); 
    
}

function zero() {
    if (document.getElementById("USDvalue").value === "") document.getElementById("USDvalue").value = 0;
    if (document.getElementById("PLTvalue").value === "") document.getElementById("PLTvalue").value = 0;
    
}

function exec() {

    let textUSD = Number(document.getElementById("USDvalue").value);

    document.getElementById("PLTvalue").value = (textUSD/_PLTUSD).toFixed(4);
    document.getElementById("BRLvalue").value = (textUSD /_BRLUSD).toFixed(3);
    document.getElementById("EURvalue").value = (textUSD / _EURUSD).toFixed(3);
    document.getElementById("MATICvalue").value = (_MATICusd * textUSD).toFixed(8);
    document.getElementById("ETHvalue").value = (textUSD / _ETHUSD).toFixed(10);
    document.getElementById("BTCvalue").value = (textUSD / _BTCUSD).toFixed(8);

} exec();

function PLTexec() {

    let textPLT = Number(document.getElementById("PLTvalue").value);

    document.getElementById("USDvalue").value = (textPLT*_PLTUSD).toFixed(10);
    
    let textUSD = Number(document.getElementById("USDvalue").value);
    
    document.getElementById("BRLvalue").value = (textUSD /_BRLUSD).toFixed(10);
    document.getElementById("EURvalue").value = (textUSD / _EURUSD).toFixed(10);
    document.getElementById("MATICvalue").value = (_MATICusd * textUSD).toFixed(8);
    document.getElementById("ETHvalue").value = (textUSD / _ETHUSD).toFixed(10);
    document.getElementById("BTCvalue").value = (textUSD / _BTCUSD).toFixed(8);

}

function BRLexec() {

    let textBRL = Number(document.getElementById("BRLvalue").value);

    document.getElementById("USDvalue").value = (_BRLUSD * textBRL).toFixed(3);
    
    let textUSD = Number(document.getElementById("USDvalue").value);
    
    document.getElementById("PLTvalue").value = (textUSD/_PLTUSD).toFixed(4);
    document.getElementById("EURvalue").value = (textUSD / _EURUSD).toFixed(3);
    document.getElementById("MATICvalue").value = (_MATICusd * textUSD).toFixed(8);
    document.getElementById("ETHvalue").value = (textUSD / _ETHUSD).toFixed(10);
    document.getElementById("BTCvalue").value = (textUSD / _BTCUSD).toFixed(8);

}

function BTCexec() {

    let textBTC = Number(document.getElementById("BTCvalue").value);

    document.getElementById("USDvalue").value = (_BTCUSD * textBTC).toFixed(3);
    
    let textUSD = Number(document.getElementById("USDvalue").value);
    
    document.getElementById("PLTvalue").value = (textUSD/_PLTUSD).toFixed(4);
    document.getElementById("EURvalue").value = (textUSD / _EURUSD).toFixed(3);
    document.getElementById("MATICvalue").value = (_MATICusd * textUSD).toFixed(8);
    document.getElementById("ETHvalue").value = (textUSD / _ETHUSD).toFixed(10);
    document.getElementById("BRLvalue").value = (textUSD /_BRLUSD).toFixed(3);

}

function MATICexec() {

    let textMATIC = Number(document.getElementById("MATICvalue").value);

    document.getElementById("USDvalue").value = (textMATIC / _MATICusd).toFixed(3);
    
    let textUSD = Number(document.getElementById("USDvalue").value);
    
    document.getElementById("PLTvalue").value = (textUSD/_PLTUSD).toFixed(4);
    document.getElementById("EURvalue").value = (textUSD / _EURUSD).toFixed(3);
    document.getElementById("ETHvalue").value = (textUSD / _ETHUSD).toFixed(10);
    document.getElementById("BRLvalue").value = (textUSD /_BRLUSD).toFixed(3);
    document.getElementById("BTCvalue").value = (textUSD / _BTCUSD).toFixed(8);

}

function ETHexec() {

    let textETH = Number(document.getElementById("ETHvalue").value);

    document.getElementById("USDvalue").value = (_ETHUSD * textETH).toFixed(3);
    
    let textUSD = Number(document.getElementById("USDvalue").value);
    
    document.getElementById("PLTvalue").value = (textUSD/_PLTUSD).toFixed(4);
    document.getElementById("EURvalue").value = (textUSD / _EURUSD).toFixed(3);
    document.getElementById("BRLvalue").value = (textUSD /_BRLUSD).toFixed(3);
    document.getElementById("BTCvalue").value = (textUSD / _BTCUSD).toFixed(8);
    document.getElementById("MATICvalue").value = (_MATICusd * textUSD).toFixed(8);

}

function EURexec() {

    let textEUR = Number(document.getElementById("EURvalue").value);

    document.getElementById("USDvalue").value = (_EURUSD * textEUR).toFixed(3);
    
    let textUSD = Number(document.getElementById("USDvalue").value);
    
    document.getElementById("PLTvalue").value = (textUSD/_PLTUSD).toFixed(4);
    document.getElementById("ETHvalue").value = (textUSD / _ETHUSD).toFixed(10);
    document.getElementById("BRLvalue").value = (textUSD /_BRLUSD).toFixed(3);
    document.getElementById("BTCvalue").value = (textUSD / _BTCUSD).toFixed(8);
    document.getElementById("MATICvalue").value = (_MATICusd * textUSD).toFixed(8);

}

function reais(v){
    v=v.replace(/\D/g,"");
    v=v/100;
    v=v.toFixed(2);
    return v;
}
function mascara(o,f){
    v_obj=o;
    v_fun=f;
    setTimeout("execmascara()",1);
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value);
}
</script>

</body>
<body onLoad="setTimeout(exec, 800);">  
</html>
