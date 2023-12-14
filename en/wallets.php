<style>
    
.wallets-table {
    border-collapse: collapse;
    width: 75%;
    margin-left: auto;
    margin-right: auto;
}

/*table, tr,td {
    border:1px solid black;
}*/

.wallets-table .wlttd {
    width: 25%;
    text-align: center;
    padding: 10px;
}

.wallets-table img {
    width: 80%;
}

</style>

<section>
        <br>

                <h2 class="u-align-center u-custom-font u-font-montserrat u-text u-text-grey-80 u-text-1"><?php echo $txtBestWallets?></h2>
                <h3 class="u-align-center u-custom-font u-font-montserrat u-text u-text-grey-80 u-text-2"><?php echo $txtKeysBlockchain?></h3>
                
        <table class="wallets-table">
            <td>
                
                 <table class="wallets-table">
                <tr>
                    <td class="wlttd"><a href="https://metamask.io/"target="_blank"> <img src="https://www.plata.ie/images/wallet-metamask.svg"><br><a>Metamask</a></td>
                    <td class="wlttd"><a href="https://apps.apple.com/us/app/uniswap-wallet/id6443944476"target="_blank"><img style="85px" src="https://www.plata.ie/images/wallet-uniswap.svg"><br><a>Uniswap</a></td>
                    <td class="wlttd"><a href="https://trustwallet.com/"target="_blank"><img style="85px" src="https://www.plata.ie/images/wallet-connect.svg"><br><a>WalletConnect</a></td>
                    <td class="wlttd"><a href="https://www.coinbase.com/wallet"target="_blank"><img src="https://www.plata.ie/images/wallet-coinbase.svg"><br><a>Coinbase</a></td>
                </tr>
                </table>
                <p  class="u-align-center u-custom-font u-font-montserrat u-text u-text-grey-90 u-text-3"><?php echo $txtExploreBlockchain?></p>
            </td>
        </table>
</section>
