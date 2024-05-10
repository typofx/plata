<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-token.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:ital,wght@0,400;0,700;1,400;1,700&display=swap">

    <title>Token Split</title>

    
<script>

    function show2022(){
        document.getElementById("txtSplit").innerText = "<?php echo $txtInitialSplit?>";
        document.getElementById("tbInitialSplit").classList.remove('invisibled');
        document.getElementById("tbAllocation2023").classList.add('invisibled');
        document.getElementById("tbNFTsell").classList.add('invisibled');
        document.getElementById("btnSplit2022").classList.remove('<?php echo $classInativeButton?>');
        document.getElementById("btnSplit2023").classList.add('<?php echo $classInativeButton?>');
        document.getElementById("btnSplitNFT").classList.add('<?php echo $classInativeButton?>');
    }

    function show2023(){
        document.getElementById("txtSplit").innerText = "<?php echo $txtTokenAllocation23?>";
        document.getElementById("tbAllocation2023").classList.remove('invisibled');
        document.getElementById("tbInitialSplit").classList.add('invisibled');
        document.getElementById("tbNFTsell").classList.add('invisibled');
        document.getElementById("btnSplit2023").classList.remove('<?php echo $classInativeButton?>');
        document.getElementById("btnSplit2022").classList.add('<?php echo $classInativeButton?>');
        document.getElementById("btnSplitNFT").classList.add('<?php echo $classInativeButton?>');
    }

    function showNFT(){
        document.getElementById("txtSplit").innerText = "<?php echo $txtNFTmarketplace?>"; 
        document.getElementById("tbNFTsell").classList.remove('invisibled');
        document.getElementById("tbInitialSplit").classList.add('invisibled');
        document.getElementById("tbAllocation2023").classList.add('invisibled');
        document.getElementById("btnSplitNFT").classList.remove('<?php echo $classInativeButton?>');
        document.getElementById("btnSplit2023").classList.add('<?php echo $classInativeButton?>');
        document.getElementById("btnSplit2022").classList.add('<?php echo $classInativeButton?>');
    }

</script>
</head>

<body>
    <table class="tb-token">
        <tr>
            <th colspan="8"><p class="tb-title">PLATA TOKENOMICS</p></th>
        </tr>
        <tr class="line-column-border">
            <td colspan="4" class="td-token-column td-token-split">
                <section id="AnchorInitialSplit">
                    <br>
                    <h2 id="txtSplit"><?php echo $txtInitialSplit?></h2>
                    <table id="tbInitialSplit" class="split-table"> 
                        <tr>
                            <td><img class="center-img" src="https://www.plata.ie/images/token-split-chart-2022.svg"><br></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq01.svg">  <?php echo $txtPlatform?></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq02.svg">  <?php echo $txtLegalSupport?></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq03.svg">  <?php echo $txtDecentralized?></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq04.svg">  <?php echo $txtExpenses?></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq05.svg">  <?php echo $txtMarketing?></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq06.svg">  <?php echo $txtReserve?></td>
                        </tr>
                        <tr>
                            <td><br></td>
                        </tr>
                    </table>
            
                    <table id="tbAllocation2023" class="split-table invisibled">
                        <tr>
                            <td><img class="center-img" src="https://www.plata.ie/images/token-split-chart-2023.svg"><br></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq07.svg">  <?php echo $txtNullAddress?></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq05.svg">  <?php echo $txtTypoFx?></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq01.svg">  <?php echo $txtUniswap?></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq04.svg">  <?php echo $txtQuickswap?></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq03.svg">  <?php echo $txtSushiSwap?></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq02.svg">  <?php echo $txtPromotionalGiveaway?></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq10.svg">  <?php echo $txtAirDrop?></td>
                        </tr>
                    </table>
                    
                    <table id="tbNFTsell" class="split-table invisibled">
                        <tr>
                            <td><img class="center-img" src="https://www.plata.ie/images/token-split-chart-nft.svg"><br></td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq03.svg">  <?php echo $txtArtistsCollaboration?> </td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq02.svg">  <?php echo $txtLiquidityACTM?> </td>
                        </tr>
                        <tr>
                            <td><img src="https://www.plata.ie/images/sq01.svg">  <?php echo $txtRainfallVictims?> </td>
                        </tr>
                        <tr>
                            <td><br></td>
                        </tr>
                        <tr>
                            <td><br></td>
                        </tr>
                        <tr>
                            <td><br></td>
                        </tr>
                        <tr>
                            <td><br></td>
                        </tr>
                    </table>                     
            </section>

            </td>
            <td colspan="4" class="td-tb-info td-token-column line-column-border">
                <table class="tb-info border-text-align">
                    <tr>
                        <td class="td-tb-info td-info-top td-info-left"></td>
                        <th class="td-tb-info td-info-top">info</th>
                        <th class="td-tb-info td-info-top">info</th>
                        <th class="td-tb-info td-info-top td-info-right">info</th>
                    </tr>
                    <tr>
                        <th class="td-tb-info td-info-left">info</th>
                        <td class="td-tb-info">info</td>
                        <td class="td-tb-info">info</td>
                        <td class="td-tb-info td-info-right">info</td>
                    </tr>
                    <tr>
                        <th class="td-tb-info td-info-left">info</th>
                        <td class="td-tb-info">info</td>
                        <td class="td-tb-info">info</td>
                        <td class="td-tb-info td-info-right">info</td>
                    </tr>
                    <tr>
                        <th class="td-tb-info td-info-left">info</th>
                        <td class="td-tb-info">info</td>
                        <td class="td-tb-info">info</td>
                        <td class="td-tb-info td-info-right">info</td>
                    </tr>
                    <tr>
                        <th class="td-tb-info td-info-left">info</th>
                        <td class="td-tb-info">info</td>
                        <td class="td-tb-info">info</td>
                        <td class="td-tb-info td-info-right">info</td>
                    </tr>
                    <tr>
                        <th class="td-tb-info td-info-left td-info-bottom">info</th>
                        <td class="td-tb-info td-info-bottom">info</td>
                        <td class="td-tb-info td-info-bottom">info</td>
                        <td class="td-tb-info td-info-right td-info-bottom">info</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="td-suply line-column-border">Circulating Supply : 11,299,000,992.0000</td>
            <td class="td-typo-fx">Typo FX</td>
            <td><button id="btnSplit2022" class="button-year" onclick="show2022()">2022</button></td>
            <td><button id="btnSplit2023" class="button-year <?php echo $classInativeButton?>" onclick="show2023()">2023</button></td>
            <td><button id="btnSplitNFT" class="button-year <?php echo $classInativeButton?>" onclick="showNFT()" onclick="">NFT</button></td>
        </tr>
    </table>
</body>
</html>
