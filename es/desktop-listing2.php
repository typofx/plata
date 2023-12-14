<?php include_once "conexao.php"; ?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap">
    <style>
        html {
            scroll-behavior: smooth;
        }

        /*table, th, td {
  border:1px solid black;
}*/

        .cursor {
            cursor: default;
        }

        .non-visible {
            display: none
        }

        .listing-link {
            color: black;
            font-family: 'Montserrat', sans-serif;
        }

        .listing-link:hover {
            color: gray;
        }
    </style>

    <script>
        function hideColA() {
            document.getElementById("colA").classList.toggle("non-visible");
            document.getElementById("colB").classList.toggle("non-visible");
            document.getElementById("colE").classList.toggle("non-visible");
            document.getElementById("colF").classList.toggle("non-visible");
            document.getElementById("rightButton").classList.toggle("non-visible");
            document.getElementById("leftButton").classList.toggle("non-visible");
            document.getElementById("Col1").classList.toggle("non-visible");
            document.getElementById("Col2").classList.toggle("non-visible");

        }
    </script>

</head>

<body>


    <section class="u-align-left u-clearfix u-hidden-lg u-hidden-md u-hidden-sm u-hidden-xs u-section-11" id="AnchorDYOR">
        <div class="div-indexes-background">

            <center>
                <table class="u-align-center">
                    <tr>
                        <td><img src="https://www.plata.ie/images/PlataTokenIconSmall.svg"></td>
                        <td>
                            <h2 class="u-align-center u-custom-font u-font-montserrat u-text u-text-default u-text-grey-80 u-text-1 cursor"> <?php echo $txtPlataToken ?></h2>
                           
                           
                

                        </td>


                        <br>

                    </tr>
            </center>
            </table>

            <center>
                <table>
                    <tr>
                        <td>
                            <table height="399px" id="leftButton" class="listing-table-desktop non-visible">
                                <tr>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td><img onclick="hideColA()" style="cursor: pointer;" id="Col-Pos" src="https://www.plata.ie/images/btn-listing-left.svg" width="22px"><br><br><br><br></td>
                                </tr>
                                <tr>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td> </td>
                                </tr>
                    </tr>
                </table>
                </td>
                <td>
                    <table height="379px" width="200px" id="colA">
                        <tr>
                            <td width="180px"><a href="https://www.livecoinwatch.com/price/PlataToken-______PLT" alt="livecoinwatch.com" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-livecoinwatch.svg"></a></td>
                        </tr>
                        <tr>
                            <td><a href="https://www.geckoterminal.com/polygon_pos/pools/0x5d64b0ec81a0648f52be7a50267bf8d1d0ffb161" alt="XXX" target="_blank"><img width="180px" src="https://www.plata.ie/images/geckoTerminalLogo.svg"></a></td>
                        </tr>
                        <tr>
                            <td><a href="https://dexscreener.com/polygon/0x5d64b0ec81a0648f52be7a50267bf8d1d0ffb161" alt="DEX Screener" target="_blank"><img width="180px" src="https://www.plata.ie/images/dexScreenerLogo.svg"></a></td>
                        </tr>
                        <tr>
                            <td><a href="https://www.dextools.io/app/en/polygon/pair-explorer/0x0e145c7637747cf9cffef81b6a0317ca3c9671a6" alt="DEXtools" target="_blank"><img width="180px" src="https://www.plata.ie/images/dextoolsLogo.svg"></a></td>
                        </tr>
                        <tr>
                            <td><a href="https://tradingstrategy.ai/trading-view/polygon/uniswap-v3/plt-usdt-fee-30#4h" alt="tradingstrategy.ai" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-tradingstrategy.svg"></a></td>
                        </tr>
                        </tr>
                    </table>
                </td>
                <td>
                    <table height="379px" width="200px" id="colB" class="listing-table-desktop">
                        <tr>
                            <td><a href="https://www.dexview.com/polygon/0xC298812164BD558268f51cc6E3B8b5daAf0b6341/" alt="dexview.com" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-dexview.svg"></a></td>
                        </tr>
                        <tr>
                            <td><a href="https://www.coincarp.com/currencies/plata-token/" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-coincarp.svg"></a></td>
                        </tr>
                        <tr>
                            <td><a href="https://tokensniffer.com/token/poly/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" alt="Token Sniffer" target="_blank"><img width="180px" src="https://www.plata.ie/images/tokenSnifferLogo.svg"></a></td>
                        </tr>
                        <tr>
                            <td><a href="https://top100token.com/address/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" target="_blank"><img width="180px" src="https://www.plata.ie/images/top100TokenLogo.svg"></a></td>
                        </tr>
                        <tr>
                            <td><a href="https://bitkeep.com/en/swap/matic/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" alt="BitKeep" target="_blank"><img width="160px" src="https://www.plata.ie/images/bitKeepLogo.svg"></a></td>
                        </tr>
                        </tr>
                    </table>

                </td>
                <td>
                    <table height="379px" width="200px" id="colC" class="listing-table-desktop">
                        <tr>
                            <td><a href="https://app.bubblemaps.io/poly/token/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-bubblemaps.svg"></a></td>
                        </tr>
                        <tr>
                            <td><a href="https://www.cyberscope.io/cyberscan?network=MATIC&address=0xc298812164bd558268f51cc6e3b8b5daaf0b6341" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-cyberscope.svg"></a></td>
                        </tr>
                        <tr>
                            <td><a href="https://explorer.bitquery.io/matic/token/0xc298812164bd558268f51cc6e3b8b5daaf0b6341?theme=dark" target="_blank"><img width="150px" src="https://www.plata.ie/images/listing-bitquery.svg"></a></td>
                        </tr>
                        <tr>
                            <td width="180px"><a href="https://coinmarketcap.com/dexscan/polygon/0x5d64b0ec81a0648f52be7a50267bf8d1d0ffb161/" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-coinmarketcap.svg"></a></td>
                        <tr>
                            <td height="60px"><a href="https://polygon.poocoin.app/tokens/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" alt="poocoin.app" target="_blank"><img width="155px" src="https://www.plata.ie/images/poocoinLogo.svg"></a></td>
                        </tr>
                        </tr>
                    </table>

                </td>
                <td>
                    <table height="379px" width="200px" id="colD" class="listing-table-desktop">
                        <tr>
                            <td><a href="https://coinhunt.cc/coin/64b3504bdbb88c2c4a99bdc1" alt="coinhunt.cc" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-coinhunt.svg"></a></td>
                        </tr>
                        <tr>
                            <td width="180px"><a href="https://mobula.fi/asset/plata-token" alt="mobula.fi" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-mobula.svg"></a></td>
                        <tr>
                            <td width="180px"><a href="https://gopluslabs.io/token-security/137/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" target="_blank"><img width="180px" src="https://www.plata.ie/images/goplusLogo.svg"></a></td>
                        </tr>
                        <tr>
                            <td width="180px"><a href="https://coinbrain.com/coins/poly-0xc298812164bd558268f51cc6e3b8b5daaf0b6341" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-coinbrain.svg"></a></td>
                        </tr>
                        <tr>
                            <td width="180px"><a href="https://de.fi/scanner/contract/0xc298812164bd558268f51cc6e3b8b5daaf0b6341?137" target="_blank"><img width="150px" src="https://www.plata.ie/images/listing-defi.svg"></a></td>
                        </tr>

                        </tr>
                    </table>

                </td>
                <td>
                    <table height="379px" width="200px" id="colE" class="listing-table-desktop non-visible">
                        <tr>
                            <td><a href="https://charts.bogged.finance/?page=chart&c=polygon&t=0xC298812164BD558268f51cc6E3B8b5daAf0b6341" target="_blank"><img width="160px" src="https://www.plata.ie/images/listing-bogged.svg"></a></td>
                        </tr>
                        <tr>
                            <td width="180px"><a href="https://swap.arken.finance/tokens/polygon/0xc298812164bd558268f51cc6e3b8b5daaf0b6341/" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-arken.svg"></a></td>
                        <tr>
                            <td width="180px"><a href="https://onlymoons.io/#/locker/polygon/8" target="_blank"><img src="https://www.plata.ie/images/listing-onlymoons.svg"></a></td>
                        <tr>
                            <td width="180px"><a href="https://coinsniper.net/coin/51518" alt="coinsniper.net" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-coinsniper.svg"></a></td>
                        <tr>
                            <td width="180px"><a href="https://www.pinksale.finance/pinklock/record/1002011?chain=Matic" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-pinksale.svg"></a></td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table height="379px" width="200px" id="colF" class="listing-table-desktop non-visible">
                        <tr>
                            <td><a href="https://coinboom.net/coin/plata" alt="coinboom.net" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-coinboom.svg"></a></td>
                        <tr>
                            <td><a href="https://coinboom.net/coin/plata" alt="coinboom.net" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-quickintel.svg"></a></td>
                        <tr>
                            <td><a href="https://flooz.xyz/trade/0xC298812164BD558268f51cc6E3B8b5daAf0b6341?network=polygon" alt="flooz.xyz" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-flooz.svg"></a></td>
                        <tr>
                            <td><a href="https://coinsgem.com/token/0xc298812164bd558268f51cc6e3b8b5daaf0b6341" alt="coinsgem.com" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-coinsgem.svg"></a></td>
                        <tr>
                            <td><a href="https://coinxhigh.com/coin/plt-plata-token" alt="coinxhigh.com" target="_blank"><img width="180px" src="https://www.plata.ie/images/listing-coinxhigh.svg"></a></td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table height="399px" id="rightButton" class="listing-table-desktop">
                        <tr>
                            <td> </td>
                        </tr>
                        <tr>
                            <td> </td>
                        </tr>
                        <tr>
                            <td><img onclick="hideColA()" style="cursor: pointer;" id="Col-Pos" src="https://www.plata.ie/images/btn-listing-right.svg" width="22px"><br><br><br><br></td>
                </td>
                </tr>
                <tr>
                    <td> </td>
                </tr>
                <tr>
                    <td> </td>
                </tr>
                </tr>
                </table>
            </center>

            </table>
            <table>
                <tr>
                    <td colspan="5">
                        <center>
                            <img id="Col1" class="non-visible" src="https://www.plata.ie/images/dot-menu.svg" width="8px">
                            <img id="Col-Pos" src="https://www.plata.ie/images/dot-menu-selected.svg" width="8px">
                            <img id="Col2" src="https://www.plata.ie/images/dot-menu.svg" width="8px">
                    </td>
                    </center>
                </tr>
            </table>

            <table>
                <tr>
                    <td colspan="5">
                        <center>
                   <?php  $sqlTotal = "SELECT COUNT(*) AS TotalListed FROM granna80_bdlinks.links WHERE Listed = 1";
    $resultTotal = $conn->query($sqlTotal);


    // Check if there are results and fetch the total
    if ($resultTotal->num_rows > 0) {
        $totalRow = $resultTotal->fetch_assoc();
        $totalListed = $totalRow['TotalListed'];

     

    }

  ?>

                            <a class="listing-link" href="https://www.plata.ie/listingplatform/">
                                
                                
                      <?php echo $totalListed;  ?>          attached affliate plataforms</a>
                    </td>
                    </center>
                </tr>
            </table>

            <br>
            <h3 class="u-align-center u-custom-font u-font-montserrat u-text u-text-grey-80 u-text-2 cursor"><?php echo $txtAlwaysResearch ?></h3>
            <p class="u-align-center u-custom-font u-font-montserrat u-text u-text-grey-90 u-text-3 cursor"><?php echo $txtPlataText ?></p>
            <br>
        </div>
        </div>

    </section>
    <script>
        //window.onload = function() {
            // Função para recuperar o valor do cookie 'totalListed'
           // function getCookie(name) {
              //  const cname = name + "=";
              //  const decodedCookie = decodeURIComponent(document.cookie);
               // const cookieArray = decodedCookie.split(';');
               // for (let i = 0; i < cookieArray.length; i++) {
                //    let c = cookieArray[i];
                //    while (c.charAt(0) === ' ') {
                  //      c = c.substring(1);
                  //  }
                  //  if (c.indexOf(cname) === 0) {
                  //      const value = c.substring(cname.length, c.length);
                  //      console.log('Cookie encontrado:', name, value); // Log adicional
                  //      return value;
                  //  }
             //   }
            //    console.log('Cookie não encontrado:', name); // Log adicional
          //      return "";
          //  }

            // Recupera o valor do cookie 'totalListed'
         //   const totalListedValue = getCookie('totalListed');

            // Exibe o valor do cookie no console
         //   console.log('Valor do cookie:', totalListedValue);
     //   }
    </script>