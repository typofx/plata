// 0xc298812164bd558268f51cc6e3b8b5daaf0b6341 Plata Token Contract
// 0x820802Fa8a99901F52e39acD21177b0BE6EE2974 EUROe
// 0x491a4eB4f1FC3BfF8E1d2FC856a6A46663aD556f BRZ
// 0x2791bca1f2de4661ed88a30c99a7a9449aa84174 USDC
// 0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270 WMATIC
// 0xc2132D05D31c914a87C6611C10748AEb04B58e8F USDT
    
    const apiMATICusd = 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms=MATIC&tsyms=USD';
    const apiMATICbrl = 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms=MATIC&tsyms=BRL';
    const apiMATICeur = 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms=MATIC&tsyms=EUR';
    const apiEURusd = 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms=EUR&tsyms=USD';
    const apiBRLusd = 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BRL&tsyms=USD';
    
    const WMATICbalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=1Y153G4EA8DRD889PTTXYT1B2TAQE2IQP8';
    const PlataBalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';
    const MaticLastPrice = 'https://api.polygonscan.com/api?module=stats&action=maticprice&apikey=V28GTTSIS47YEB7IRH36ZQ349KHV4EKVYZ';
  

let _plataCirculatingSupply = Number(11299000992);
let _plataMarketCapUSDOUT = Number(0);
let _plataMarketCapBRLOUT = Number(0);
let _plataMarketCapEUROUT = Number(0);
let _plataMarketCap = Number(0);

let _MATICusd = Number(0);
let _MATICeur = Number(0);
let _MATICbrl = Number(0);

let _PLTliquidity = Number(0);
let _QuickswapWMATIC = Number(0);
let _QuickswapPLT = Number(0);
let _QuickswapLiquidity = Number(0);

let _USDPLT = Number(0);

let _PLTEUR = Number(0);
let _PLTUSD = Number(0);
let _PLTBRL = Number(0);

let _BRLUSD = Number(0);
let _EURUSD = Number(0);

let i = 0;
let interv = Number(1000);

    setInterval(getWMATIConPool, interv);
    setInterval(getPlataOnPool, interv);
    setInterval(getEURusd, interv);
    setInterval(getBRLusd, interv);
    setInterval(getMATICpriceUSD, interv);
    setInterval(getMATICpriceBRL, interv);
    setInterval(getMATICpriceEUR, interv);


async function getPlataOnPool(){
    const response = await fetch(PlataBalanceQuickswapV2PLTMATICPool);
    const dataPlataBalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataPlataBalanceQuickswapV2PLTMATICPool;
    let _result = Number(result);

    _QuickswapPLT = parseFloat( _result / 1e4).toFixed(5);

    //console.log("Quickswap PLT on Pool: " + parseFloat(_QuickswapPLT));
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;
    return 0;

} getPlataOnPool();

async function getEURusd(){
    const response = await fetch(apiEURusd);
    const DATAapiEURusd = await response.json();
    const { RAW } = DATAapiEURusd;
    _EURUSD = Number(RAW.EUR.USD.PRICE).toFixed(3);
    console.log("EURUSD : "  + _EURUSD);
} getEURusd();

async function getBRLusd(){
    const response = await fetch(apiBRLusd);
    const DATAapiBRLusd = await response.json();
    const { RAW } = DATAapiBRLusd;
    _BRLUSD = Number(RAW.BRL.USD.PRICE).toFixed(3);
    console.log("BRLUSD : "  + _BRLUSD);
} getBRLusd();

async function getMATICpriceUSD(){
    const response = await fetch(apiMATICusd);
    const DATAapiMATICusd = await response.json();
    const { result } = DATAapiMATICusd;
    _MATICusd = Number(DATAapiMATICusd.RAW.MATIC.USD.PRICE).toFixed(3);
    console.log("MATIC (USD) : " + _MATICusd);
} getMATICpriceUSD();

async function getMATICpriceBRL(){
    const response = await fetch(apiMATICbrl);
    const DATAapiMATICbrl = await response.json();
    const { result } = DATAapiMATICbrl;
    _MATICbrl = Number(DATAapiMATICbrl.RAW.MATIC.BRL.PRICE).toFixed(3);
    console.log("MATIC (BRL) : " + _MATICbrl);
} getMATICpriceBRL();

async function getMATICpriceEUR(){
    const response = await fetch(apiMATICeur);
    const DATAapiMATICeur = await response.json();
    const { result } = DATAapiMATICeur;
    _MATICeur = Number(DATAapiMATICeur.RAW.MATIC.EUR.PRICE).toFixed(3);
    console.log("MATIC (EUR) : " + _MATICeur);
} getMATICpriceEUR();

function USDcurrency() {
    document.getElementById('txtPAIR').textContent = "$ " + _PLTUSD;
    document.getElementById('numTokenMarketcap').textContent = " $ " + _plataMarketCapUSDOUT;
}

function EURcurrency() {
    document.getElementById('txtPAIR').textContent = "€ " + _PLTEUR;
    document.getElementById('numTokenMarketcap').textContent = " € " + _plataMarketCapEUROUT;
}

function BRLcurrency() {
    document.getElementById('txtPAIR').textContent = "R$ " + _PLTBRL;
    document.getElementById('numTokenMarketcap').textContent = " R$ " +  _plataMarketCapBRLOUT;
}

async function getWMATIConPool(){
    const response = await fetch(WMATICbalanceQuickswapV2PLTMATICPool);
    const dataWMATICbalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataWMATICbalanceQuickswapV2PLTMATICPool;
    let _result = Number(result);

        _QuickswapWMATIC = parseFloat( _result * 1e-18).toFixed(5);
        _QuickswapLiquidity = Number(_MATICusd*_QuickswapWMATIC).toFixed(3);
 
            _USDPLT = parseFloat((_QuickswapPLT)/parseFloat(_QuickswapLiquidity)).toFixed(5);
            _PLTUSD = parseFloat(1/_USDPLT).toFixed(10);
            _PLTEUR = parseFloat(_PLTUSD/_EURUSD).toFixed(10);
            _PLTBRL = parseFloat(_PLTUSD/_BRLUSD).toFixed(10);
            
            console.log("USDPLT : " + _USDPLT);
            console.log("PLTUSD : " + _PLTUSD);
            console.log("PLTEUR : " + _PLTEUR);
            console.log("PLTBRL : " + _PLTBRL);
        
        _plataMarketCapUSD = parseFloat(_plataCirculatingSupply*_PLTUSD);
        _plataMarketCapBRL = parseFloat(_plataCirculatingSupply*_PLTBRL);
        _plataMarketCapEUR = parseFloat(_plataCirculatingSupply*_PLTEUR);
        
        const _plataMarketCapUSDSTR = Math.abs(_plataMarketCapUSD);
        const _plataMarketCapBRLSTR = Math.abs(_plataMarketCapBRL);
        const _plataMarketCapEURSTR = Math.abs(_plataMarketCapEUR);
        
        _plataMarketCapUSDOUT = _plataMarketCapUSDSTR.toLocaleString("en-US");
        _plataMarketCapBRLOUT = _plataMarketCapBRLSTR.toLocaleString("en-US");
        _plataMarketCapEUROUT = _plataMarketCapEURSTR.toLocaleString("en-US");
        
        //console.log("Marketcap (USD)" + _plataMarketCapUSDOUT);
        //console.log("Marketcap (BRL)" + _plataMarketCapBRLOUT);
        //console.log("Marketcap (EUR)" + _plataMarketCapEUROUT);
        //console.log("Plata Marketcap (USD) : " + parseFloat(_plataMarketCap));

    //document.getElementById('txtTokenCurrency').textContent = "$ ";
    //document.getElementById('txtTokenPrice').textContent = parseFloat(<?php $txtTokenPriceCurrency ?>);
    //document.getElementById('txtMarketcap').textContent = "Marketcap : $ " + _plataMarketCapOUT;
        
    //document.getElementById('txtPlataTotalSupply').textContent = "Plata Total Supply: " + parseFloat(_plataTotalSupply) + " PLT";
    //document.getElementById('txtPlataCirculatingSupply').textContent = "Plata Circulating Supply: " + parseFloat(_plataCirculatingSupply) + " PLT";
    
    //document.getElementById('txtPlataMarketcap').textContent = "Plata Marketcap: " + parseFloat(_plataMarketCap) + " USD";
    
    //const d = new Date();
    //console.log("Time : "+ d);
    
    document.getElementById('txtTokenName').textContent = "Plata Token";
    document.getElementById('txtTokenSymbol').textContent = " (PLT)";
    
    if (document.getElementById("txtCurrencyEnv").textContent === " (USD)") USDcurrency();
    if (document.getElementById("txtCurrencyEnv").textContent === " (EUR)") EURcurrency();
    if (document.getElementById("txtCurrencyEnv").textContent === " (BRL)") BRLcurrency();
    
    //document.getElementById("txtHour").innerHTML = d.toLocaleTimeString();
    
} getWMATIConPool();

USDcurrency();

/*
<script> </script>

<body>

<p id="txtPlataTotalSupply"/>
<p id="txtPlataCirculatingSupply"/>
<p id="txtPlataMarketcap"/>
<p id="txtMATICUSD"/>
<p id="txtPLTEUR"/>
<p id="txtPLTUSD"/>
<p id="txtPLTBRL"/>
<br>
<p id="demo"/>

</body>
<script>
*/
