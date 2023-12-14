<!DOCTYPE html>

<link rel="stylesheet" href="style.css">

<html>
    
<style>

.row:hover {
    border: 2px solid #8247E5;
}

html {
    resize: none;
}

table {
    border-spacing: 8px;
    
}
tr, th, td {
    /*border: 1px solid;*/
}

.table-selectpayment {
    width: 500px;
}

.bottom-left {
    border-bottom-left-radius: 10px;
}

.bottom-right {
    border-bottom-right-radius: 10px;
}

.top-right {
    border-top-right-radius: 10px;
}

.row {
    width: 33%;
    height: 130px;
    background: #F5F5F5;
    padding:10px;
    border: 2px solid #F5F5F5;
}

.center {
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}

p {
    color: #3f3f3f;
}

h2 {
 font-size: 25px;
}

</style>

<meta name="viewport" content="width=device-width, user-scalable=no">

<body>

<h2 class="center">Select Payment</h2>

<table class="table-selectpayment center">
    <tr>
        <th class="row center box-over">
                <img height="55px" src="https://www.plata.ie/images/icon-select-card.svg">
                <p>Major credit and debit cards are supported. Authentication is required.</p>
                <button id="button-card" class="button-select-payment" onclick="location.href='https://www.plata.ie/card'">Credit/Debit Card</button>
        </th>
        <th class="row center">
                <img height="55px" src="https://www.plata.ie/images/icon-select-sepa.svg">
                <p>Payment-integration of the EU for simplify of bank transfers denominated in euro.</p>
                <button id="button-sepa" class="button-select-payment" onclick="">SEPA</button>
        </th>
        <th class="row center">
            <img height="55px" src="https://www.plata.ie/images/icon-select-pix.svg">
            <p>Instant payment platform created and managed by the Central Bank of Brazil.</p>
            <button id="button-pix" class="button-select-payment" onclick="location.href='https://www.plata.ie/pix/'">PIX</button> 
        </th>
        <th class="row center top-right">
                <img height="55px" src="https://www.plata.ie/images/icon-select-paypal.svg">
                <p>Operating online payments system running in the majority of countries.</p>
        <button id="button-paypal" class="button-select-payment" onclick="">PayPal</button>
        </th>

    </tr>
    <tr>
            <th class="row center bottom-left">
            <img height="55px" src="https://www.plata.ie/images/icon-select-crypto.svg">
            <p>No Registered account needed, buy Plata Tokens on cross-chain bridge.</p>
            <button id="button-crypto" class="button-select-payment" onclick="window.open('https://www.plata.ie/coinbase/','_blank')">Coinbase</button>
        </th>
        <th class="row center">
            <img height="55px" src="https://www.plata.ie/images/icon-select-uniswap.svg">
            <p>Crypto DEX uses a set of smart contracts to execute trades on its exchange.</p>
            <button id="button-uniswap" class="button-select-payment" onclick="window.open('https://app.uniswap.org/#/swap','_blank')">Uniswap</button>
        </th>
        <th class="row center">
            <img height="55px" src="https://www.plata.ie/images/icon-select-quickswap.svg">
            <p>Layer-2 DEX and automated market maker (AMM) built for Polygon Blockchain.</p>
            <button id="button-quickswap" class="button-select-payment" onclick="window.open('https://quickswap.exchange/#/swap?currency0=ETH&currency1=0xC298812164BD558268f51cc6E3B8b5daAf0b6341&swapIndex=0','_blank')">QuickSwap</button>
        </th>
        <th class="row center">
            <img height="55px" src="https://www.plata.ie/images/icon-select-sushiswap.svg">
            <p>Protocol running on ERC-20 that seeks to incentivize a network of users to operate.</p>
            <button id="button-sushiswap" class="button-select-payment" onclick="window.open('https://www.sushi.com/swap?fromChainId=137&fromCurrency=NATIVE&toChainId=137&toCurrency=0xC298812164BD558268f51cc6E3B8b5daAf0b6341','_blank')">SushiSwap</button>
        </th>
    </tr>
    
</table>

</body>
</html>