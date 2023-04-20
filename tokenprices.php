<!DOCTYPE html>

<script>

// 0xc298812164bd558268f51cc6e3b8b5daaf0b6341 Plata Token Contract
// 0x820802Fa8a99901F52e39acD21177b0BE6EE2974 EUROe
// 0x491a4eB4f1FC3BfF8E1d2FC856a6A46663aD556f BRZ
// 0x2791bca1f2de4661ed88a30c99a7a9449aa84174 USDC

let _plataTotalSupply = parseFloat(0);
let _plataCirculatingSupply = parseFloat(0);
let _plataLockedSupply = parseFloat(0);
let _MATICusd = parseFloat(0);
let _PLTliquidity = parseFloat(0);
let _QuickswapWMATIC = parseFloat(0);
let _QuickswapPLT = parseFloat(0);
let _QuickswapLiquidity = parseFloat(0);

let _USDPLT = parseFloat(0);

let _PLTEUR = parseFloat(0);
let _PLTUSD = parseFloat(0);
let _PLTBRL = parseFloat(0);

let _BRZUSDC = parseFloat(0);
let _EURUSD = parseFloat(0);

let _QuickswapUSDC = parseFloat(0);
let _QuickswapBRZ = parseFloat(0);

let _plataMarketCap = parseFloat(0);

let _UniswapEUROo = parseFloat(0);
let _UniswapUSDC = parseFloat(0);
let _UniswapWMATIC = parseFloat(0);

async function getPlataTotalSupply(){
    const PlataTotalSupply = 'https://api.polygonscan.com/api?module=stats&action=tokensupply&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';
    const responsePlataTotalSupply = await fetch(PlataTotalSupply);
    const dataPlataTotalSupply = await responsePlataTotalSupply.json();
    const { result } = dataPlataTotalSupply;

    _plataTotalSupply = parseFloat(parseFloat(result) * 0.0001).toFixed(5);

    console.log("Plata Total Supply : " + parseFloat(_plataTotalSupply));

    document.getElementById('txtPlataTotalSupply').textContent = "Plata Total Supply: " + parseFloat(_plataTotalSupply);

} getPlataTotalSupply();


async function getCirculatingSupply(){
    const PlataLockedSupply = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x000000000000000000000000000000000000dead&tag=latest&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';
    const response = await fetch(PlataLockedSupply);
    const dataPlataLockedSupply = await response.json();
    const { result } = dataPlataLockedSupply;
    
    _plataLockedSupply = parseFloat ( parseFloat(result) * 0.0001 );
    _plataCirculatingSupply = parseFloat ( ( parseFloat(_plataTotalSupply - parseFloat(_plataLockedSupply) ) ) ).toFixed(5);

    console.log("Plata Circulating Supply : " + parseFloat(_plataCirculatingSupply));
    console.log("Plata Locked Supply : " + parseFloat(_plataLockedSupply));
    
    document.getElementById('txtPlataCirculatingSupply').textContent = "Plata Circulating Supply: " + parseFloat(_plataCirculatingSupply) + " PLT";
    
    //document.getElementById('txtPlataLockedgSupply').textContent = "Plata Locked Supply: " + _plataLockedSupply;

} getCirculatingSupply();

async function getMATICUSD(){
    const MaticLastPrice = 'https://api.polygonscan.com/api?module=stats&action=maticprice&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';
    const response = await fetch(MaticLastPrice);
    const dataMaticLastPrice = await response.json();
    const { result } = dataMaticLastPrice;

    _MATICusd = parseFloat(result.maticusd).toFixed(3);

    console.log("MATIC price: " + parseFloat(_MATICusd) );

    document.getElementById('txtMATICUSD').textContent = "MATIC (USD): " + _MATICusd;
    
} getMATICUSD();

async function getPlataOnPool(){
    const PlataBalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=V28GTTSIS47YEB7IRH36ZQ349KHV4EKVYZ';
    const response = await fetch(PlataBalanceQuickswapV2PLTMATICPool);
    const dataPlataBalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataPlataBalanceQuickswapV2PLTMATICPool;

    _QuickswapPLT = parseFloat( parseFloat(result) / 10000).toFixed(5);

    console.log("Quickswap PLT on Pool: " + parseFloat(_QuickswapPLT));
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getPlataOnPool();

async function getBRZonPool(){
    const BRZBalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x491a4eB4f1FC3BfF8E1d2FC856a6A46663aD556f&address=0x05487482919f150ACDcA6154Dbd5DF4271fE5910&tag=latest&apikey=V28GTTSIS47YEB7IRH36ZQ349KHV4EKVYZ';
    const response = await fetch(BRZBalanceQuickswapV2PLTMATICPool);
    const dataBRZBalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataBRZBalanceQuickswapV2PLTMATICPool;
    
    _QuickswapBRZ = parseFloat( parseFloat(result) / 100000).toFixed(5);

    console.log("Quickswap BRZ on Pair Pool: " + parseFloat(_QuickswapBRZ) );
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getBRZonPool();

async function getUSDConPool(){
    const USDCbalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x2791bca1f2de4661ed88a30c99a7a9449aa84174&address=0x05487482919f150ACDcA6154Dbd5DF4271fE5910&tag=latest&apikey=2ABY8VD5TMU2BY758M4P4AYJRDITZTU59U';
    const response = await fetch(USDCbalanceQuickswapV2PLTMATICPool);
    const dataUSDCbalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataUSDCbalanceQuickswapV2PLTMATICPool;

    _QuickswapUSDC = parseFloat( parseFloat(result) * 10e-8 ).toFixed(5);

    _BRZUSDC = parseFloat(_QuickswapUSDC / _QuickswapBRZ).toFixed(5);

    console.log("Quickswap USDC on Pair Pool: " + _QuickswapUSDC);
    console.log("BRZUSDC: " + _BRZUSDC);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getUSDConPool();

async function getEUROoOnEURUSDCpool(){
    const EUROeBalanceUniswapV3 = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x820802Fa8a99901F52e39acD21177b0BE6EE2974&address=0x2ccd89fc6ccaa8f49d7fa3d4d7b82b1947f08a49&tag=latest&apikey=2ABY8VD5TMU2BY758M4P4AYJRDITZTU59U';
    const response = await fetch(EUROeBalanceUniswapV3);
    const dataEUROeBalanceUniswapV3 = await response.json();
    const { result } = dataEUROeBalanceUniswapV3;

    _UniswapEUROo = parseFloat( parseFloat(result) * 10e-8 ).toFixed(5);

    console.log("Uniswap EUROo on EUROoUSDC Pool: " + _UniswapEUROo);
    //console.log("BRZUSDC: " + _BRZUSDC);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getEUROoOnEURUSDCpool();

async function getWMATIConEURUSDCpool(){
    const WMATICbalanceUniswapV3 = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x2ccd89fc6ccaa8f49d7fa3d4d7b82b1947f08a49&tag=latest&apikey=2ABY8VD5TMU2BY758M4P4AYJRDITZTU59U';
    const response = await fetch(WMATICbalanceUniswapV3);
    const dataWMATICbalanceUniswapV3 = await response.json();
    const { result } = dataWMATICbalanceUniswapV3;

    _UniswapWMATIC = parseFloat( parseFloat(result) * 10e-20 ).toFixed(5);
    if ( (_UniswapWMATIC <= 0) || isNaN(_UniswapWMATIC) || !isFinite(_UniswapWMATIC) ) window.location.reload();

    _UniswapUSDC = parseFloat(_UniswapWMATIC * _MATICusd).toFixed(5);
    if ( (_UniswapUSDC <= 0) || isNaN(_UniswapUSDC) || !isFinite(_UniswapUSDC) ) window.location.reload();

    _EURUSD = ( parseFloat(_UniswapUSDC / _UniswapEUROo) ).toFixed(5);
    if ( (_EURUSD <= 0) || isNaN(_EURUSD) || !isFinite(_EURUSD) ) window.location.reload();

    console.log("Uniswap WMATIC on EUROoUSDC Pool: " + _UniswapWMATIC);
    console.log("Uniswap USDC on EUROoUSDC Pool: " + _UniswapUSDC);
    console.log("EURoUSDC Price: " + _EURUSD);
    
    //console.log("BRZUSDC: " + _BRZUSDC);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getWMATIConEURUSDCpool();

async function getWMATIConPool(){
    const WMATICbalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=V28GTTSIS47YEB7IRH36ZQ349KHV4EKVYZ';
    const response = await fetch(WMATICbalanceQuickswapV2PLTMATICPool);
    const dataWMATICbalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataWMATICbalanceQuickswapV2PLTMATICPool;

    _QuickswapLiquidity = parseFloat( parseFloat(_MATICusd) * parseFloat(result) * 10e-19 ).toFixed(5);
    if ( (_QuickswapLiquidity <= 0) || isNaN(_QuickswapLiquidity) || !isFinite(_QuickswapLiquidity) ) window.location.reload();

    _QuickswapWMATIC = parseFloat( parseFloat(result) * 10e-19 ).toFixed(5);
    if ( (_QuickswapWMATIC <= 0) || isNaN(_QuickswapWMATIC) || !isFinite(_QuickswapWMATIC) ) window.location.reload();

    _USDPLT = parseFloat (parseFloat(_QuickswapPLT)/parseFloat(_QuickswapLiquidity)).toFixed(1);
    if ( (_USDPLT <= 0) || isNaN(_USDPLT) || !isFinite(_USDPLT) ) window.location.reload();
    
    _PLTUSD = parseFloat( 1/parseFloat(_USDPLT) ).toFixed(10);
    if ( (_PLTUSD <= 0) || isNaN(_PLTUSD) || !isFinite(_PLTUSD) ) window.location.reload();

    _PLTBRL = ( parseFloat(parseFloat(_PLTUSD) / parseFloat(_BRZUSDC)) ).toFixed(10);
    if ( (_PLTBRL <= 0) || isNaN(_PLTBRL) || !isFinite(_PLTBRL) ) window.location.reload();

    _PLTEUR = parseFloat(parseFloat(_PLTUSD)/parseFloat(_EURUSD)).toFixed(10);
    if ( (_PLTEUR <= 0) || isNaN(_PLTEUR) || !isFinite(_PLTEUR) ) window.location.reload();

    _plataMarketCap = parseFloat(parseFloat(_plataCirculatingSupply)*parseFloat(_PLTUSD)).toFixed(3);
    if ( (_plataMarketCap <= 0) || isNaN(_plataMarketCap) || !isFinite(_plataMarketCap) ) window.location.reload();

    console.log("Quickswap wMATIC on Pool: " + _QuickswapWMATIC);
    console.log("Quickswap Liquidity on Pool: " + _QuickswapLiquidity);
    
    console.log("USDPLT Price: " + parseFloat(_USDPLT));
    
    console.log("PLTEUR Price: " + parseFloat(_PLTEUR));
    console.log("PLTUSD Price: " + parseFloat(_PLTUSD));
    console.log("PLTBRL Price: " + parseFloat(_PLTBRL));
    
    console.log("Plata Marketcap : " + parseFloat(_plataMarketCap));

    document.getElementById('txtPlataMarketcap').textContent = "Plata Marketcap: " + parseFloat(_plataMarketCap) + " USD";

    document.getElementById('txtPLTEUR').textContent = "Plata Token (EUR): " + parseFloat(_PLTEUR);
    document.getElementById('txtPLTUSD').textContent = "Plata Token (USD): " + parseFloat(_PLTUSD);
    document.getElementById('txtPLTBRL').textContent = "Plata Token (BRL): " + parseFloat(_PLTBRL);

} getWMATIConPool();


</script>

<body>

<p id="txtPlataTotalSupply"/>
<p id="txtPlataCirculatingSupply"/>
<p id="txtPlataMarketcap"/>
<p id="txtMATICUSD"/>
<p id="txtPLTEUR"/>
<p id="txtPLTUSD"/>
<p id="txtPLTBRL"/>

</body>
