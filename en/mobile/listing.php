<link rel="stylesheet" href="https://www.plata.ie/en/mobile/style-desktop-listing2.css">

<style>
.obj-non-visible {
    display: none;    
}
    
</style>

<script>
function cleanAllLinks(){
    const listElement0 = document.querySelectorAll(".bot");
        for (let i = 0; i < listElement0.length; i++) { 
            listElement0[i].classList.add("obj-non-visible");  
        }

    const listElement1 = document.querySelectorAll(".def");
        for (let i = 0; i < listElement1.length; i++) { 
            listElement1[i].classList.add("obj-non-visible");  
        }
    
    const listElement2 = document.querySelectorAll(".ind");
        for (let i = 0; i < listElement2.length; i++) { 
            listElement2[i].classList.add("obj-non-visible");  
        }
        
    const listElement3 = document.querySelectorAll(".aud");
        for (let i = 0; i < listElement3.length; i++) { 
            listElement3[i].classList.add("obj-non-visible");  
        }
        
    const listElement4 = document.querySelectorAll(".loc");
        for (let i = 0; i < listElement4.length; i++) { 
        listElement4[i].classList.add("obj-non-visible");  
        }
}


function showIndexBot() {
    const listElement1 = document.querySelectorAll(".bot");
        for (let i = 0; i < listElement1.length; i++) {
            listElement1[i].classList.toggle("obj-non-visible");
        }
        
    const listElement2 = document.querySelectorAll(".ind");
        for (let i = 0; i < listElement2.length; i++) {
            listElement2[i].classList.toggle("obj-non-visible");
        }
    }

function showAuditing() {
    const listElement = document.querySelectorAll(".aud");
        for (let i = 0; i < listElement.length; i++) {
            listElement[i].classList.toggle("obj-non-visible");
        }
    }

function showLockers() {
    const listElement = document.querySelectorAll(".loc");
        for (let i = 0; i < listElement.length; i++) {
            listElement[i].classList.toggle("obj-non-visible");
        }
    }    
    
function showDefi() {
    const listElement = document.querySelectorAll(".def");
        for (let i = 0; i < listElement.length; i++) {
            listElement[i].classList.toggle("obj-non-visible");
        }
    }    
    
    
</script>
    
<section id="aaa">
    <div class="table-area">
        <div class="table-container">
            <table class="table-listing">
                <tbody>      
                    <tr> 
                        <td class="td-space-content"> </td>
                        <td class="td-listing">
                            <div class="content-listing">  
                                <img class="image-content" src="https://www.plata.ie/images/listing-menu-auditing.jpg" onclick="cleanAllLinks(); showAuditing()">
                                <div class="description-image-dark">Auditing</div>
                            </div>
                        </td>
                        <td class="td-space-content"> </td>
                        <td class="td-listing">
                            <div class="content-listing">  
                                <img class="image-content" src="https://www.plata.ie/images/listing-menu-index.jpg" onclick="cleanAllLinks(); showIndexBot()">
                                <div class="description-image">Indexes and Listing Bots</div>
                            </div>
                        </td>
                        <td class="td-space-content"> </td>
                        <td class="td-listing">
                            <div class="content-listing">  
                                <img class="image-content" src="https://www.plata.ie/images/listing-menu-defi.jpg" onclick="cleanAllLinks(); showDefi();">
                                <div class="description-image">DeFi</div>
                            </div>
                        </td>
                        <td class="td-space-content"> </td>
                        <td class="td-listing">
                            <div class="content-listing">  
                                <img class="image-content" src="https://www.plata.ie/images/listing-menu-kyc.jpg" onclick="cleanAllLinks();">
                                <div class="description-image-dark">KYC</div>
                            </div>
                        </td>
                        <td class="td-space-content"> </td>
                        <td class="td-listing">
                            <div class="content-listing">  
                                <img class="image-content" src="https://www.plata.ie/images/listing-menu-nft.jpg" onclick="cleanAllLinks();">
                                <div class="description-image">NFT</div>
                            </div>
                        </td>
                        <td class="td-space-content"> </td>
                        <td class="td-listing">
                            <div class="content-listing">  
                                <img class="image-content" src="https://www.plata.ie/images/listing-menu-lockers.jpg" onclick="cleanAllLinks(); showLockers()">
                                <div class="description-image">Lockers</div>
                            </div>
                        </td>
                        <td class="td-space-content"> </td>
                        <td class="td-listing">
                            <div class="content-listing">  
                                <img class="image-content" src="https://www.plata.ie/images/listing-menu-ceex.jpg" onclick="cleanAllLinks();">
                                <div class="description-image">Centralized Exchange</div>
                            </div>
                        </td>
                        <td class="td-space-content"> </td>
                        <td class="td-space-content"> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section id="AnchorDYORmobile">

    <h2><?php echo $txtPlataToken ?></h2>

      <table class="listing-table">
        <tr>
            <td class="ind"><a href="https://www.coincarp.com/currencies/plata-token/" target="_blank"><img src="https://www.plata.ie/images/listing-coincarp.svg" ></a></td>
            <td class="ind"><a href="https://www.livecoinwatch.com/price/PlataToken-______PLT" alt="livecoinwatch.com" target="_blank"><img src="https://www.plata.ie/images/listing-livecoinwatch.svg" ></a></td>                  
        </tr>
        <tr>
          <td class="ind def"><a href="https://dexscreener.com/polygon/0x5d64b0ec81a0648f52be7a50267bf8d1d0ffb161" alt="DEX Screener" target="_blank"><img src="https://www.plata.ie/images/dexScreenerLogo.svg" ></a></td>
          <td class="ind def"><a href="https://www.dextools.io/app/en/polygon/pair-explorer/0x0e145c7637747cf9cffef81b6a0317ca3c9671a6" alt="DEXtools" target="_blank"><img src="https://www.plata.ie/images/dextoolsLogo.svg" ></a></td>
        </tr>
        <tr>
            <td class="ind def"><a href="https://coinbrain.com/coins/poly-0xc298812164bd558268f51cc6e3b8b5daaf0b6341" target="_blank"><img src="https://www.plata.ie/images/listing-coinbrain.svg" ></a></td>
            <td class="ind"><a href="https://top100token.com/address/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" target="_blank"><img src="https://www.plata.ie/images/top100TokenLogo.svg" ></a></td>
        </tr>
        <tr>
            <td class="aud"><a href="https://tokensniffer.com/token/poly/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" alt="tokensniffer.com" target="_blank"><img src="https://www.plata.ie/images/tokenSnifferLogo.svg" ></a></td>            
            <td class="aud"><a href="https://gopluslabs.io/token-security/137/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" alt="gopluslabs.io" target="_blank"><img src="https://www.plata.ie/images/goplusLogo.svg"></a></td>
        </tr>
        <tr>
          <td class="aud"><a href="https://app.bubblemaps.io/poly/token/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" target="_blank"><img src="https://www.plata.ie/images/listing-bubblemaps.svg" ></a></td>
          <td class="aud"><a href="https://www.cyberscope.io/cyberscan?network=MATIC&address=0xc298812164bd558268f51cc6e3b8b5daaf0b6341" target="_blank"><img src="https://www.plata.ie/images/listing-cyberscope.svg"></a></td>
        </tr>
        <tr>
          <td class="aud def obj-non-visible"><a href="https://explorer.bitquery.io/matic/token/0xc298812164bd558268f51cc6e3b8b5daaf0b6341?theme=dark" target="_blank"><img src="https://www.plata.ie/images/listing-bitquery.svg" ></a></td>
          <td class="aud obj-non-visible"><a href="https://de.fi/scanner/contract/0xc298812164bd558268f51cc6e3b8b5daaf0b6341?137" target="_blank"><img src="https://www.plata.ie/images/listing-defi.svg" ></a></td>
        </tr>
        <tr>
            <td class="def obj-non-visible"><a href="https://bitkeep.com/en/swap/matic/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" alt="BitKeep" target="_blank"><img src="https://www.plata.ie/images/bitKeepLogo.svg" ></a></td>
            <td class="def"><a href="https://charts.bogged.finance/?page=chart&c=polygon&t=0xC298812164BD558268f51cc6E3B8b5daAf0b6341" target="_blank"><img src="https://www.plata.ie/images/listing-bogged.svg" ></a></td>
        </tr>
        <tr>
            <td class="bot"><a href="https://coinmarketcap.com/dexscan/polygon/0x5d64b0ec81a0648f52be7a50267bf8d1d0ffb161/" target="_blank"><img src="https://www.plata.ie/images/listing-coinmarketcap.svg" ></a></td>
            <td class="bot def"><a href="https://polygon.poocoin.app/tokens/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" target="_blank"><img src="https://www.plata.ie/images/poocoinLogo.svg" ></a></td>
        </tr>
        <tr>
            <td class="loc"><a href="https://www.pinksale.finance/pinklock/record/1002011?chain=Matic" target="_blank"><img src="https://www.plata.ie/images/listing-pinksale.svg" ></a></td>
            <td class="loc"><a href="https://onlymoons.io/#/locker/polygon/8" target="_blank"><img src="https://www.plata.ie/images/listing-onlymoons.svg" ></a></td>
        </tr>
        <tr>
            <td class="bot def"><a href="https://www.dexview.com/polygon/0xC298812164BD558268f51cc6E3B8b5daAf0b6341/" alt="dexview.com" target="_blank"><img src="https://www.plata.ie/images/listing-dexview.svg" ></a></td>          
            <td class="ind"><a href="https://coinhunt.cc/coin/64b3504bdbb88c2c4a99bdc1" alt="coinhunt.cc" target="_blank"><img src="https://www.plata.ie/images/listing-coinhunt.svg" ></a></td>          
        </tr>
        <tr>
            <td class="bot"><a href="https://tradingstrategy.ai/trading-view/polygon/uniswap-v3/plt-usdt-fee-30#4h" alt="tradingstrategy.ai" target="_blank"><img src="https://www.plata.ie/images/listing-tradingstrategy.svg" ></a></td>          
            <td class="bot def obj-non-visible"><a href="https://swap.arken.finance/tokens/polygon/0xc298812164bd558268f51cc6e3b8b5daaf0b6341/" alt="arken.finance" target="_blank"><img src="https://www.plata.ie/images/listing-arken.svg" ></a></td>          
        </tr>
        <tr>
            <td class="bot"><a href="https://coinsniper.net/coin/51518" alt="coinsniper.net" target="_blank"><img src="https://www.plata.ie/images/listing-coinsniper.svg" ></a></td>          
            <td class="bot"><a href="https://www.coinscope.co/coin/1-plt" alt="coinscope.co" target="_blank"><img src="https://www.plata.ie/images/listing-coinscope.svg" ></a></td>          
        </tr>
        <tr>
            <td class="bot"><a href="https://www.geckoterminal.com/polygon_pos/pools/0x5d64b0ec81a0648f52be7a50267bf8d1d0ffb161" alt="geckoterminal.com" target="_blank"><img src="https://www.plata.ie/images/geckoTerminalLogo.svg"></a></td>
            <td class="def"><a href="https://mobula.fi/asset/plata-token" alt="mobula.fi" target="_blank"><img src="https://www.plata.ie/images/listing-mobula.svg" ></a></td>
        </tr>

      </table>
        <br>
        <table class="listing-table">
            <td>
                <h2><?php echo $txtAlwaysResearch ?></h2>
                <h3>
                    <?php echo  $txtPlataTextL1 ?><br>
                    <?php echo  $txtPlataTextL2 ?>
                </h3>
            </td>
        </table>

</section>