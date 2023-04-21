<!DOCTYPE html>
<script>

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


const USDCbalanceQuickswapV2PLTMATICPool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x2791bca1f2de4661ed88a30c99a7a9449aa84174&address=0x05487482919f150ACDcA6154Dbd5DF4271fE5910&tag=latest&apikey=2ABY8VD5TMU2BY758M4P4AYJRDITZTU59U';

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
//let _UniswapWMATIC = parseFloat(0);

async function getPlataTotalSupply(){
    const responsePlataTotalSupply = await fetch(PlataTotalSupply);
    const dataPlataTotalSupply = await responsePlataTotalSupply.json();
    const { result } = dataPlataTotalSupply;

    _plataTotalSupply = parseFloat(parseFloat(result) * 0.0001).toFixed(5);

    console.log("Plata Total Supply : " + parseFloat(_plataTotalSupply));

} getPlataTotalSupply();


async function getCirculatingSupply(){
    const response = await fetch(PlataLockedSupply);
    const dataPlataLockedSupply = await response.json();
    const { result } = dataPlataLockedSupply;
    
    _plataCirculatingSupply = 0;
    do {
        _plataLockedSupply = parseFloat ( parseFloat(result) * 0.0001 );
        _plataCirculatingSupply = parseFloat ( ( parseFloat(_plataTotalSupply - parseFloat(_plataLockedSupply) ) ) ).toFixed(5);
        console.log("Plata Circulating Supply : " + parseFloat(_plataCirculatingSupply));
        if ( isNaN(_plataCirculatingSupply) || !isFinite(_plataCirculatingSupply)) _plataCirculatingSupply = 0;
    } while (_plataCirculatingSupply === 0);
    
} getCirculatingSupply();

async function getPlataOnPool(){
    const response = await fetch(PlataBalanceQuickswapV2PLTMATICPool);
    const dataPlataBalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataPlataBalanceQuickswapV2PLTMATICPool;

    _QuickswapPLT = parseFloat( parseFloat(result) / 1e4).toFixed(5);

    //console.log("Quickswap PLT on Pool: " + parseFloat(_QuickswapPLT));
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getPlataOnPool();


async function getBRZonPool(){
    const response = await fetch(BRZBalanceQuickswapV2PLTMATICPool);
    const dataBRZBalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataBRZBalanceQuickswapV2PLTMATICPool;
    
    _QuickswapBRZ = parseFloat( parseFloat(result) / 1e5).toFixed(5);

    //console.log("Quickswap BRZ on Pair Pool: " + parseFloat(_QuickswapBRZ) );

} getBRZonPool();

async function getUSDConPool(){
    const response = await fetch(USDCbalanceQuickswapV2PLTMATICPool);
    const dataUSDCbalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataUSDCbalanceQuickswapV2PLTMATICPool;

    _QuickswapUSDC = (parseFloat( parseFloat(result) / 10e6 )).toFixed(5);

    _BRZUSDC = (parseFloat(_QuickswapUSDC / _QuickswapBRZ)).toFixed(5);

    //console.log("Quickswap USDC on Pair Pool: " + _QuickswapUSDC);
    console.log("BRLUSD : " + _BRZUSDC);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getUSDConPool();

async function getUSDTpool(){
    const response = await fetch(USDTbalanceUniswapV3);
    const dataUSDTbalanceUniswapV3 = await response.json();
    const { result } = dataUSDTbalanceUniswapV3;

    _UniswapUSDT = parseFloat( parseFloat(result) / 10e5 ).toFixed(5);

    //console.log("Uniswap USDT on USDTeuroo Pool: " + _UniswapUSDT);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;

} getUSDTpool();

async function getEUROpool(){
    const response = await fetch(EURObalanceUniswap);
    const dataEURObalanceUniswap = await response.json();
    const { result } = dataEURObalanceUniswap;

    _UniswapEUROo = parseFloat( parseFloat(result) / 10e5 ).toFixed(5);
    _EURUSD = ( parseFloat(_UniswapUSDT /_UniswapEUROo ) ).toFixed(5);

    //console.log("Uniswap EURO on USDTeuroo Pool: " + _UniswapEUROo);

    console.log("EURUSD : " + _EURUSD);
    
    //console.log("BRZUSDC: " + _BRZUSDC);
    //document.getElementById('txtPlataOnPool').textContent = "Quickswap PLT on Pool: " + _QuickswapPLT;
    
} getEUROpool();

async function getMATICprice(){
    const response = await fetch(MaticLastPrice);
    const dataMaticLastPrice = await response.json();
    const { result } = dataMaticLastPrice;

    _MATICusd = parseFloat(result.maticusd).toFixed(3);
    _MATICeur = parseFloat(_MATICusd/_EURUSD).toFixed(3);
    _MATICbrl = parseFloat(_MATICusd/_BRZUSDC).toFixed(3);
    console.log("MATIC (USD) : " + parseFloat(_MATICusd) );
    console.log("MATIC (EUR) : " + parseFloat(_MATICeur) );
    console.log("MATIC (BRL) : " + parseFloat(_MATICbrl) );
    document.getElementById('txtMATICUSD').textContent = "MATIC (USD): " + _MATICusd;
    
} getMATICprice();

async function funcDataUSDCbalanceQuickswapV2PLTMATICPool(){
    const response = await fetch(USDCbalanceQuickswapV2PLTMATICPool);
    const dataUSDCbalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataUSDCbalanceQuickswapV2PLTMATICPool;
    _QuickswapUSDC = (parseFloat( (parseFloat(result)) * 10e-1 )).toFixed(5);
}

async function funcWMATICbalanceUniswapV3(){
    const response = await fetch(WMATICbalanceUniswapV3);
    const dataWMATICbalanceUniswapV3 = await response.json();
    const { result } = dataWMATICbalanceUniswapV3;
    _UniswapWMATIC = (parseFloat( parseFloat(result) * 10e-13 )).toFixed(5);
}


async function getWMATIConPool(){
    const response = await fetch(WMATICbalanceQuickswapV2PLTMATICPool);
    const dataWMATICbalanceQuickswapV2PLTMATICPool = await response.json();
    const { result } = dataWMATICbalanceQuickswapV2PLTMATICPool;

        _QuickswapWMATIC = 0;
        do {
            _QuickswapWMATIC = parseFloat( parseFloat(result * 1e-18)).toFixed(5);
            console.log("Quickswap wMATIC on Pool: " + _QuickswapWMATIC);
            if ( isNaN(_QuickswapWMATIC) || !isFinite(_QuickswapWMATIC) ) _QuickswapWMATIC = 0; 
        } while (_QuickswapWMATIC === 0);
        
        _QuickswapLiquidity = parseFloat(_MATICusd*_QuickswapWMATIC).toFixed(3);
        console.log("Quickswap Liquidity on Pool: " + _QuickswapLiquidity + " USD");
        
        _USDPLT = 0;
        do {
            _USDPLT = parseFloat((_QuickswapPLT)/parseFloat(_QuickswapLiquidity)).toFixed(5);
            console.log("USDPLT : " + parseFloat(_USDPLT));
            if ( isNaN(_USDPLT) || !isFinite(_USDPLT) ) _USDPLT = 0;
        } while (_USDPLT === 0);
        
        _PLTUSD = 0;
        do {
            _PLTUSD = parseFloat(1/_USDPLT).toFixed(10);
            console.log("PLTUSD : " + parseFloat(_PLTUSD));
            if ( isNaN(_PLTUSD) || !isFinite(_PLTUSD) ) _PLTUSD = 0;
        } while (_PLTUSD === 0);

        _PLTEUR = 0;
        do {
            _PLTEUR = parseFloat(_PLTUSD/_EURUSD).toFixed(10);
            console.log("PLTEUR : " + parseFloat(_PLTEUR));
            if ( isNaN(_PLTEUR) || !isFinite(_PLTEUR) ) _PLTEUR = 0;
        } while (_PLTEUR === 0);
        
        _PLTBRL = 0;
        do {
            _PLTBRL = parseFloat(_PLTUSD/_BRZUSDC).toFixed(10);
            console.log("PLTBRL : " + parseFloat(_PLTBRL));
            if ( isNaN(_PLTBRL) || !isFinite(_PLTBRL) ) _PLTBRL = 0;
        } while (_PLTBRL === 0);
        
        _plataMarketCap = parseFloat(_plataCirculatingSupply*_PLTUSD).toFixed(3);
        console.log("Plata Marketcap (USD) : " + parseFloat(_plataMarketCap));
    
    document.getElementById('txtPlataTotalSupply').textContent = "Plata Total Supply: " + parseFloat(_plataTotalSupply);
    document.getElementById('txtPlataCirculatingSupply').textContent = "Plata Circulating Supply: " + parseFloat(_plataCirculatingSupply) + " PLT";
    document.getElementById('txtPLTUSD').textContent = "Plata Token (USD): " + parseFloat(_PLTUSD);
    document.getElementById('txtPLTEUR').textContent = "Plata Token (EUR): " + parseFloat(_PLTEUR);
    document.getElementById('txtPLTBRL').textContent = "Plata Token (BRL): " + parseFloat(_PLTBRL);
    document.getElementById('txtPlataMarketcap').textContent = "Plata Marketcap: " + parseFloat(_plataMarketCap) + " USD";
    
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
