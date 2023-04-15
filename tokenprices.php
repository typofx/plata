<script>

// 0xc298812164bd558268f51cc6e3b8b5daaf0b6341 Plata Token Contract
// 0x820802Fa8a99901F52e39acD21177b0BE6EE2974 EUROe
// 0x491a4eB4f1FC3BfF8E1d2FC856a6A46663aD556f BRZ
// 0x2791bca1f2de4661ed88a30c99a7a9449aa84174 USDC

const PlataTotalSupply = 'https://api.polygonscan.com/api?module=stats&action=tokensupply&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';
const PlataLockedSupply = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x000000000000000000000000000000000000dead&tag=latest&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';
const MaticLastPrice = 'https://api.polygonscan.com/api?module=stats&action=maticprice&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';

const PlataBalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=V28GTTSIS47YEB7IRH36ZQ349KHV4EKVYZ';
const WMATICbalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=V28GTTSIS47YEB7IRH36ZQ349KHV4EKVYZ';

const BRZBalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x491a4eB4f1FC3BfF8E1d2FC856a6A46663aD556f&address=0x05487482919f150ACDcA6154Dbd5DF4271fE5910&tag=latest&apikey=V28GTTSIS47YEB7IRH36ZQ349KHV4EKVYZ';
const USDCbalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x2791bca1f2de4661ed88a30c99a7a9449aa84174&address=0x05487482919f150ACDcA6154Dbd5DF4271fE5910&tag=latest&apikey=2ABY8VD5TMU2BY758M4P4AYJRDITZTU59U';

const EUROeBalanceUniswapV3 = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x820802Fa8a99901F52e39acD21177b0BE6EE2974&address=0x2ccd89fc6ccaa8f49d7fa3d4d7b82b1947f08a49&tag=latest&apikey=2ABY8VD5TMU2BY758M4P4AYJRDITZTU59U';
const WMATICbalanceUniswapV3 = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x2ccd89fc6ccaa8f49d7fa3d4d7b82b1947f08a49&tag=latest&apikey=2ABY8VD5TMU2BY758M4P4AYJRDITZTU59U';


let _plataTotalSupply = 0;
let _plataCirculatingSupply = 0;
let _plataLockedSupply = 0;
let _MATICusd = 0;
let _PLTliquidity = 0;
let _QuickswapWMATIC = 0;
let _QuickswapPLT = 0;
let _QuickswapLiquidity = 0;
let _PLTUSD = 0;
let _USDPLT = 0;
let _PLTBRL = 0;
let _PLTEUR = 0;
let _EURUSD = 0;

let _QuickswapUSDC = 0;
let _QuickswapBRZ = 0;
let _BRZUSDC = 0;

let _plataMarketCap = 0;

let _UniswapEUROo = 0;
let _UniswapUSDC = 0;
let _UniswapWMATIC = 0;

async function getMATICUSD(){
    const response = await fetch(MaticLastPrice);
    const dataMaticLastPrice = await response.json();
    const { result } = dataMaticLastPrice;
    console.log(dataMaticLastPrice);
    
    _MATICusd = result.maticusd;
    console.log("MATIC price: " + _MATICusd);
    
    document.getElementById('txtMATICUSD').textContent = "MATIC (USD): " + _MATICusd;

    } getMATICUSD();

async function getPlataOnPool(){
    const response = await fetch(PlataBalanceQuickswapV2PLTMATICPool);
    const dataPlataBalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataPlataBalanceQuickswapV2PLTMATICPool;

    _QuickswapPLT = result / 10000;
    
    console.log("Quickswap PLT on Pool: " + _QuickswapPLT);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getPlataOnPool();

async function getBRZonPool(){
    const response = await fetch(BRZBalanceQuickswapV2PLTMATICPool);
    const dataBRZBalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataBRZBalanceQuickswapV2PLTMATICPool;

    _QuickswapBRZ = result / 10000;
    
    console.log("Quickswap BRZ on Pair Pool: " + _QuickswapBRZ);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getBRZonPool();

async function getUSDConPool(){
    const response = await fetch(USDCbalanceQuickswapV2PLTMATICPool);
    const dataUSDCbalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataUSDCbalanceQuickswapV2PLTMATICPool;

    _QuickswapUSDC = result / 1000000;
    _BRZUSDC = (_QuickswapUSDC / _QuickswapBRZ).toFixed(10);
    
    console.log("Quickswap USDC on Pair Pool: " + _QuickswapUSDC);
    console.log("BRZUSDC: " + _BRZUSDC);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getUSDConPool();

async function getEUROoOnEURUSDCpool(){
    const response = await fetch(EUROeBalanceUniswapV3);
    const dataEUROeBalanceUniswapV3 = await response.json();
    const { result } = dataEUROeBalanceUniswapV3;

    _UniswapEUROo = result / 1000000;
    //_BRZUSDC = (_QuickswapUSDC / _QuickswapBRZ).toFixed(10);
    
    console.log("Uniswap EUROo on EUROoUSDC Pool: " + _UniswapEUROo);
    //console.log("BRZUSDC: " + _BRZUSDC);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getEUROoOnEURUSDCpool();

async function getWMATIConEURUSDCpool(){
    const response = await fetch(WMATICbalanceUniswapV3);
    const dataWMATICbalanceUniswapV3 = await response.json();
    const { result } = dataWMATICbalanceUniswapV3;

    _UniswapWMATIC = (result / 1000000000000000000);
    _UniswapUSDC = _UniswapWMATIC * _MATICusd;
    _EURUSD = (_UniswapUSDC / _UniswapEUROo).toFixed(10);

    console.log("Uniswap WMATIC on EUROoUSDC Pool: " + _UniswapWMATIC);
    console.log("Uniswap USDC on EUROoUSDC Pool: " + _UniswapUSDC);
    console.log("EURoUSDC Price: " + _EURUSD);
    
    //console.log("BRZUSDC: " + _BRZUSDC);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getWMATIConEURUSDCpool();

async function getWMATIConPool(){
    const response = await fetch(WMATICbalanceQuickswapV2PLTMATICPool);
    const dataWMATICbalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataWMATICbalanceQuickswapV2PLTMATICPool;
    
    _QuickswapLiquidity = (_MATICusd * result / 1000000000000000000).toFixed(12);
    _QuickswapWMATIC = (result / 1000000000000000000).toFixed(12);

    _USDPLT = _QuickswapPLT / _QuickswapLiquidity;
    _PLTUSD = (1 / _USDPLT).toFixed(12);
    _PLTBRL = (_PLTUSD / _BRZUSDC).toFixed(12);

    console.log("Quickswap wMATIC on Pool: " + _QuickswapWMATIC);
    console.log("Quickswap Liquidity on Pool: " + _QuickswapLiquidity);
    
    _PLTEUR = (_PLTUSD / _EURUSD).toFixed(12);
    
    console.log("USDPLT Price: " + _USDPLT);
    console.log("PLTEUR Price: " + _PLTEUR);
    
    console.log("PLTUSD Price: " + _PLTUSD);
    console.log("PLTBRL Price: " + _PLTBRL);
    

    //document.getElementById('txtWMATIConPool').textContent = "Quickswap wMATIC on Pool: " + _QuickswapWMATIC;
    //document.getElementById('txtUSDonPool').textContent = "Quickswap Liquidity on Pool: " + _QuickswapLiquidity;
    document.getElementById('txtPLTEUR').textContent = "Plata Token (EUR): " + _PLTEUR;
    document.getElementById('txtPLTUSD').textContent = "Plata Token (USD): " + _PLTUSD;
    document.getElementById('txtPLTBRL').textContent = "Plata Token (BRL): " + _PLTBRL;

} getWMATIConPool();

async function getPlataTotalSupply(){
    const response = await fetch(PlataTotalSupply);
    const dataPlataTotalSupply = await response.json();
    const { result } = dataPlataTotalSupply;

    _plataTotalSupply = result / 10000;

    console.log("Plata Total Supply : " + _plataTotalSupply);

    //document.getElementById('txtPlataTotalSupply').textContent = "Plata Total Suppy: " + _plataTotalSupply;

} getPlataTotalSupply();


async function getCirculatingSupply(){
    const response = await fetch(PlataLockedSupply);
    const dataPlataLockedSupply = await response.json();
    const { result } = dataPlataLockedSupply;
    
    _plataCirculatingSupply = (result - _plataTotalSupply) / 10000;
    _plataLockedSupply = result / 10000;
    
    _plataMarketCap = ( _plataCirculatingSupply * _PLTUSD).toFixed(3);

    console.log("Plata Circulating Supply : " + _plataCirculatingSupply);
    console.log("Plata Locked Supply : " + _plataLockedSupply);
    console.log("Plata Marketcap : " + _plataMarketCap);    

    document.getElementById('txtPlataCirculatingSupply').textContent = "Plata Circulating Suppy: " + _plataCirculatingSupply + " PLT";
    document.getElementById('txtPlataMarketcap').textContent = "Plata Marketcap: " + _plataMarketCap + " USD";
    //document.getElementById('txtPlataLockedgSupply').textContent = "Plata Locked Suppy: " + _plataLockedSupply;

} getCirculatingSupply();





</script>

<body>

<p id="txtPlataCirculatingSupply"/>
<p id="txtPlataMarketcap"/>
<p id="txtMATICUSD"/>
<p id="txtPLTEUR"/>
<p id="txtPLTUSD"/>
<p id="txtPLTBRL"/>

</body>
