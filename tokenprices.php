// 0xc298812164bd558268f51cc6e3b8b5daaf0b6341 Plata Token Contract
// 0x820802Fa8a99901F52e39acD21177b0BE6EE2974 EUROe
// 0x491a4eB4f1FC3BfF8E1d2FC856a6A46663aD556f BRZ
// 0x2791bca1f2de4661ed88a30c99a7a9449aa84174 USDC
// 0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270 WMATIC
// 0xc2132D05D31c914a87C6611C10748AEb04B58e8F USDT

const WMATICbalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=V28GTTSIS47YEB7IRH36ZQ349KHV4EKVYZ';
const PlataTotalSupply = 'https://api.polygonscan.com/api?module=stats&action=tokensupply&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';
const PlataLockedSupply = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x000000000000000000000000000000000000dead&tag=latest&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';
const PlataBalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=V28GTTSIS47YEB7IRH36ZQ349KHV4EKVYZ';
const MaticLastPrice = 'https://api.polygonscan.com/api?module=stats&action=maticprice&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';
const BRZBalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x491a4eB4f1FC3BfF8E1d2FC856a6A46663aD556f&address=0x05487482919f150ACDcA6154Dbd5DF4271fE5910&tag=latest&apikey=V28GTTSIS47YEB7IRH36ZQ349KHV4EKVYZ';

const USDTbalanceUniswapV3 = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0xc2132D05D31c914a87C6611C10748AEb04B58e8F&address=0xb664c44fceb51113a4e075405fbd39dac5db8998&tag=latest&apikey=2ABY8VD5TMU2BY758M4P4AYJRDITZTU59U';
const EURObalanceUniswap = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x820802Fa8a99901F52e39acD21177b0BE6EE2974&address=0xb664c44fceb51113a4e075405fbd39dac5db8998&tag=latest&apikey=2ABY8VD5TMU2BY758M4P4AYJRDITZTU59U';

const USDCbalanceQuickswap = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x2791bca1f2de4661ed88a30c99a7a9449aa84174&address=0x05487482919f150ACDcA6154Dbd5DF4271fE5910&tag=latest&apikey=2ABY8VD5TMU2BY758M4P4AYJRDITZTU59U';

let _plataTotalSupply = Number(0);
let _plataCirculatingSupply = Number(0);
let _plataLockedSupply = Number(0);
let _plataMarketCapUSDOUT = Number(0);
let _plataMarketCapBRLOUT = Number(0);
let _plataMarketCapEUROUT = Number(0);

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

let _BRZUSDC = Number(0);
let _EURUSD = Number(0);

let _QuickswapUSDC = Number(0);
let _QuickswapBRZ = Number(0);

let _plataMarketCap = Number(0);

let _UniswapEUROo = Number(0);
let _UniswapUSDC = Number(0);
let _UniswapUSDT = Number(0);

let i = 0;
let interv = Number(6666);

    setInterval(getWMATIConPool, interv);
    setInterval(getCirculatingSupply, interv);
    setInterval(getPlataOnPool, interv);
    setInterval(getBRZonPool, interv);
    setInterval(getUSDConPool, interv);
    setInterval(getUSDTpool, interv);
    setInterval(getEUROpool, interv);
    setInterval(getMATICprice, interv);

async function getPlataTotalSupply(){
    const responsePlataTotalSupply = await fetch(PlataTotalSupply);
    const dataPlataTotalSupply = await responsePlataTotalSupply.json();
    const { result } = dataPlataTotalSupply;
    let _result = Number(result);

    _plataTotalSupply = Number(_result * 0.0001).toFixed(5);

    //console.log("Plata Total Supply : " + parseFloat(_plataTotalSupply));
    return 0;
} getPlataTotalSupply();


async function getCirculatingSupply(){
    const response = await fetch(PlataLockedSupply);
    const dataPlataLockedSupply = await response.json();
    const { result } = dataPlataLockedSupply;
    let _result = Number(result);
    
    do {
        _plataLockedSupply = Number( _result * 0.0001 );
        _plataCirculatingSupply = Number( _plataTotalSupply - _plataLockedSupply ).toFixed(5);
        if (_plataCirculatingSupply > 0 || !isNaN(_plataCirculatingSupply) || isFinite(_plataCirculatingSupply)) break;
        //console.log("Plata Circulating Supply : " + parseFloat(_plataCirculatingSupply));
        i=i+1;
    } while (_plataCirculatingSupply <= 0 || isNaN(_plataCirculatingSupply) || !isFinite(_plataCirculatingSupply) || i<5);
    
    return 0;
    
} getCirculatingSupply();

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


async function getBRZonPool(){
    const response = await fetch(BRZBalanceQuickswapV2PLTMATICPool);
    const dataBRZBalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataBRZBalanceQuickswapV2PLTMATICPool;
    let _result = Number(result);
    
    _QuickswapBRZ = parseFloat(_result/1e5).toFixed(5);

    //console.log("Quickswap BRZ on Pair Pool: " + parseFloat(_QuickswapBRZ) );
    return 0;

} getBRZonPool();

async function getUSDConPool(){
    const response = await fetch(USDCbalanceQuickswap);
    const dataUSDCbalanceQuickswap = await response.json();
    const { result } = dataUSDCbalanceQuickswap;
    let _result = Number(result);

        i=0;
    do {
        _QuickswapUSDC = parseFloat(_result /10e6).toFixed(5);
        if (_QuickswapUSDC > 0 || !isNaN(_QuickswapUSDC) || isFinite(_QuickswapUSDC)) break;
        i=i+1;
    } while (_QuickswapUSDC <= 0 || isNaN(_QuickswapUSDC) || !isFinite(_QuickswapUSDC) || i<5);
    
        i=0;
    do {
        _BRZUSDC = (parseFloat(_QuickswapUSDC / _QuickswapBRZ)).toFixed(5);
        if (_BRZUSDC > 0 || !isNaN(_BRZUSDC) || isFinite(_BRZUSDC)) break;
        i=i+1;
    } while (_BRZUSDC <= 0 || isNaN(_BRZUSDC) || !isFinite(_BRZUSDC) || i<5);
    
    //console.log("Quickswap USDC on Pair Pool: " + _QuickswapUSDC);
    //console.log("BRLUSD : " + _BRZUSDC);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getUSDConPool();

async function getUSDTpool(){
    const response = await fetch(USDTbalanceUniswapV3);
    const dataUSDTbalanceUniswapV3 = await response.json();
    const { result } = dataUSDTbalanceUniswapV3;
    let _result = Number(result);

    _UniswapUSDT = parseFloat( _result / 10e5 ).toFixed(5);

    //console.log("Uniswap USDT on USDTeuroo Pool: " + _UniswapUSDT);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getUSDTpool();

async function getEUROpool(){
    const response = await fetch(EURObalanceUniswap);
    const dataEURObalanceUniswap = await response.json();
    const { result } = dataEURObalanceUniswap;
    let _result = Number(result);

    _UniswapEUROo = parseFloat( _result / 10e5 ).toFixed(5);
    _EURUSD = ( parseFloat(_UniswapUSDT /_UniswapEUROo ) ).toFixed(5);

    //console.log("Uniswap EURO on USDTeuroo Pool: " + _UniswapEUROo);

    //console.log("EURUSD : " + _EURUSD);
    
    //console.log("BRZUSDC: " + _BRZUSDC);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;
    
} getEUROpool();

async function getMATICprice(){
    const response = await fetch(MaticLastPrice);
    const dataMaticLastPrice = await response.json();
    const { result } = dataMaticLastPrice;
    let _result = Number(result.maticusd);

    i=0;
    do {
        _MATICusd = parseFloat(_result).toFixed(3);
        if (_MATICusd > 0 || !isNaN(_MATICusd) || isFinite(_MATICusd)) break;
        i=i+1;
    } while (_MATICusd > 0 || isNaN(_MATICusd) || !isFinite(_MATICusd) || i<5);
    
    i=0;
    do {
         _MATICeur = parseFloat(_MATICusd/_EURUSD).toFixed(3);
         if (_MATICeur > 0 || !isNaN(_MATICeur) || isFinite(_MATICeur)) break;
         i=i+1;
    } while (_MATICeur <= 0 || isNaN(_MATICeur) || !isFinite(_MATICeur) || i<5);
    
    i=0;
    do {
        _MATICbrl = parseFloat(_MATICusd/_BRZUSDC).toFixed(3);
        if (_MATICbrl > 0 || !isNaN(_MATICbrl) || isFinite(_MATICbrl)) break;
        i=i+1;
    } while (_MATICbrl <= 0 || isNaN(_MATICbrl) || !isFinite(_MATICbrl) || i<5);
    
    //console.log("MATIC (USD) : " + parseFloat(_MATICusd) );
    //console.log("MATIC (EUR) : " + parseFloat(_MATICeur) );
    //console.log("MATIC (BRL) : " + parseFloat(_MATICbrl) );
    //document.getElementById('txtMATICUSD').textContent = "MATIC (USD): " + _MATICusd;
    
    return _MATICusd;
    
} getMATICprice();

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

        i = 0;
        do {
            _QuickswapWMATIC = parseFloat( _result * 1e-18).toFixed(5);
            //console.log("Quickswap wMATIC on Pool: " + _QuickswapWMATIC);
            if (_QuickswapWMATIC > 0 || !isNaN(_QuickswapWMATIC) || isFinite(_QuickswapWMATIC)) break;
            i = i+1;   
        } while (_QuickswapWMATIC <= 0 || isNaN(_QuickswapWMATIC) || !isFinite(_QuickswapWMATIC) || i<5);
        
        _QuickswapLiquidity = Number(_MATICusd*_QuickswapWMATIC).toFixed(3);
        //console.log("Quickswap Liquidity on Pool: " + _QuickswapLiquidity + " USD");
        
        i = 0;
        do {
            _USDPLT = parseFloat((_QuickswapPLT)/parseFloat(_QuickswapLiquidity)).toFixed(5);
            //console.log("USDPLT : " + _USDPLT);
            if (_USDPLT > 0 || !isNaN(_USDPLT) || isFinite(_USDPLT)) break;
            i = i+1;            
        } while (_USDPLT <= 0 || isNaN(_USDPLT) || !isFinite(_USDPLT));
        
        i = 0;
        do {
            _PLTUSD = parseFloat(1/_USDPLT).toFixed(10);
            //console.log("PLTUSD : " + _PLTUSD);
            if (_PLTUSD > 0 || !isNaN(_PLTUSD) || isFinite(_PLTUSD)) break;
            i = i+1;
        } while (_PLTUSD <= 0 || isNaN(_PLTUSD) || !isFinite(_PLTUSD) || i<5);
        
        i = 0;
        do {
            _PLTEUR = parseFloat(_PLTUSD/_EURUSD).toFixed(10);
            //console.log("PLTEUR : " + _PLTEUR);
            if (_PLTEUR > 0 || !isNaN(_PLTEUR) || isFinite(_PLTEUR)) break;
            i = i+1;
        } while (_PLTEUR <= 0 || isNaN(_PLTEUR) || !isFinite(_PLTEUR) || i<5);
        
        i = 0;
        do {
            _PLTBRL = parseFloat(_PLTUSD/_BRZUSDC).toFixed(10);
            //console.log("PLTBRL : " + _PLTBRL);
            if (_PLTBRL > 0 || !isNaN(_PLTBRL) || isFinite(_PLTBRL)) break;
            i = i+1;
        } while (_PLTBRL <= 0 || isNaN(_PLTBRL) || !isFinite(_PLTBRL) || i<5);
        
        _plataMarketCapUSD = parseFloat(_plataCirculatingSupply*_PLTUSD);
        _plataMarketCapBRL = parseFloat(_plataCirculatingSupply*_PLTBRL);
        _plataMarketCapEUR = parseFloat(_plataCirculatingSupply*_PLTEUR);
        
        const _plataMarketCapUSDSTR = _plataMarketCapUSD;
        const _plataMarketCapBRLSTR = _plataMarketCapBRL;
        const _plataMarketCapEURSTR = _plataMarketCapEUR;
        
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
