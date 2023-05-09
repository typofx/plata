<!doctype html>

<head>
<title>Token Price Converter</title>
</head>
<body>
<br>
<center><h3>Token Price Converter</h3></center>

<div class="card-body">

    <div>
        <label for="PLTvalue" class="form-label">Plata (PLT)</label>
        <input type="number" id="PLTvalue" name="PLTvalue" placeholder="" size="15" maxlength="13" min="0.000001" onclick="this.select();" onkeypress="mascara(this,reais)" required>
    </div>

    <div>
        <label for="USDvalue" class="form-label">US Dollar (USD)</label>
        <input type="number" id="USDvalue" name="USDvalue" onkeypress="exec()" size="15" maxlength="13" min="0.01" value ="1.00" onclick="this.select();" onkeypress="mascara(this,reais)" required>
    </div>
    
    <div>
        <label for="MATICvalue" class="form-label">MATIC</label>
        <input type="number" id="MATICvalue" name="MATICvalue" size="15" maxlength="13" min="0.00001" onclick="this.select();" onkeypress="mascara(this,reais)" required>
    </div>

    <div>
        <label for="ETHvalue" class="form-label">Ethereum (ETH)</label>
        <input type="number" id="ETHvalue" name="ETHvalue" size="15" maxlength="13" min="0.000000001" onclick="this.select();" onkeypress="mascara(this,reais)" required>
    </div>

    <div>
        <label for="BTCvalue" class="form-label">Bitcoin (BTC)</label>
        <input type="number" id="BTCvalue" name="BTCvalue" size="15" maxlength="13" min="0.000000001" onclick="this.select();" onkeypress="mascara(this,reais)" required>
    </div>
   
    <div>
      <label for="EURvalue" class="form-label">Euro (EUR)</label>
      <input type="text" id="EURvalue" name="EURvalue" size="15" maxlength="13" min="0.01" onclick="this.select();" onkeypress="mascara(this,reais)" required>
   </div>
   <div>
      <label for="BRLvalue" class="form-label">Brazilian Real (BRL)</label>
      <input type="text" id="BRLvalue" name="BRLvalue" size="15" maxlength="13" min="0.01" onclick="this.select();" onkeypress="mascara(this,reais)" required>
   </div>


   <br>
   
<button onclick="exec()">calc</button>


<?php include '../price.php';?>
<script>

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

function exec() {

    let textUSD = Number(document.getElementById("USDvalue").value);
    
    document.getElementById("PLTvalue").value = (textUSD/_PLTUSD).toFixed(4);
    document.getElementById("BRLvalue").value = (textUSD *_BRLUSD).toFixed(4);
    document.getElementById("EURvalue").value = (textUSD / _EURUSD).toFixed(4);
    document.getElementById("MATICvalue").value = (textUSD *_MATICusd).toFixed(4);
    document.getElementById("ETHvalue").value = (textUSD / _ETHUSD).toFixed(12);
    document.getElementById("BTCvalue").value = (textUSD / _BTCUSD).toFixed(8);

} exec();

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
