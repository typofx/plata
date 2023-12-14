    <link rel="stylesheet" href="https://www.plata.ie/es/desktop-style-split.css">



    <?php include $_SERVER['DOCUMENT_ROOT'] . '/languages/languages.php';?>

    

<script>



    function show2022(){

        document.getElementById("txtSplit").innerText = "<?php echo $txtInitialSplit?>";

        document.getElementById("tbInitialSplit").classList.remove('invisibled');

        document.getElementById("tbInitialSplitChart").classList.remove('invisibled');

        document.getElementById("tbAllocation2023").classList.add('invisibled');

        document.getElementById("tbAllocation2023Chart").classList.add('invisibled');

        document.getElementById("tbNFTsell").classList.add('invisibled');

        document.getElementById("tbNFTsellChart").classList.add('invisibled');

        document.getElementById("btnSplit2022").classList.remove('inative');

        document.getElementById("btnSplit2023").classList.add('inative');

        document.getElementById("btnSplitNFT").classList.add('inative');

    }



    function show2023(){

        document.getElementById("txtSplit").innerText = "<?php echo $txtTokenAllocation23?>";

        document.getElementById("tbAllocation2023").classList.remove('invisibled');

        document.getElementById("tbAllocation2023Chart").classList.remove('invisibled');

        document.getElementById("tbInitialSplit").classList.add('invisibled');

        document.getElementById("tbInitialSplitChart").classList.add('invisibled');

        document.getElementById("tbNFTsell").classList.add('invisibled');

        document.getElementById("tbNFTsellChart").classList.add('invisibled');

        document.getElementById("btnSplit2023").classList.remove('inative');

        document.getElementById("btnSplit2022").classList.add('inative');

        document.getElementById("btnSplitNFT").classList.add('inative');

    } show2023();



    function showNFT(){

        document.getElementById("txtSplit").innerText = "<?php echo $txtNFTmarketplace?>"; 

        document.getElementById("tbNFTsell").classList.remove('invisibled');

        document.getElementById("tbNFTsellChart").classList.remove('invisibled');

        document.getElementById("tbInitialSplit").classList.add('invisibled');

        document.getElementById("tbInitialSplitChart").classList.add('invisibled');

        document.getElementById("tbAllocation2023").classList.add('invisibled');

        document.getElementById("tbAllocation2023Chart").classList.add('invisibled');

        document.getElementById("btnSplitNFT").classList.remove('inative');

        document.getElementById("btnSplit2023").classList.add('inative');

        document.getElementById("btnSplit2022").classList.add('inative');

    }





</script>



<section id="AnchorTokenDistribution">

<div class="background-ground">

    <table class="tb-token">

        <tr>

            <td class="tb-subtitle cursor" colspan="8"><center><h2><?php echo $txtTokenDistribution?></h2></center></td>

        </tr>

        

        <tr class="line-column-border">

            <td colspan="4" class="td-token-column td-token-split">

                

                    <table class="split-table"> 

                        <tr>

                            <td><h3 class="tb-subtitle cursor" id="txtSplit"><?php echo $txtTokenAllocation23?></h3></td>

                        </tr>

                    </table>

                    <table id="tbInitialSplitChart" class="split-table invisibled"> 

                        <tr>

                            <td><img class="center-img" src="https://www.plata.ie/images/token-split-chart-2022.svg"><br></td>

                        </tr>

                    </table>

            

                    <table id="tbAllocation2023Chart" class="split-table">

                        <tr>

                            <td><img class="center-img" src="https://www.plata.ie/images/token-split-chart-2023.svg"><br></td>

                        </tr>

                    </table>

                    

                    <table id="tbNFTsellChart" class="split-table invisibled">

                        <tr>

                            <td><img class="center-img" src="https://www.plata.ie/images/token-split-chart-nft.svg"><br></td>

                        </tr>

                    </table>

                    <table class="split-table">

                        <tr>

                            <td class="td-suply"><span class="gray-button cursor"><?php echo $txtCirculatingSupply?></span></td>

                        </tr>

                    </table>

            </section>



            </td>

            <td colspan="4" class="td-tb-info td-token-column line-column-border">

                <table id="tbInitialSplit" class="tb-info border-text-align invisibled cursor">

                    <tr>

                        <td class="td-tb-info td-info-top td-info-left"><img src="https://www.plata.ie/images/sq01.svg"><a class="a-list "> <?php echo $txtPlatform?></a></td>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"><img src="https://www.plata.ie/images/sq02.svg"><a class="a-list"> <?php echo $txtLegalSupport?></a></td>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"><img src="https://www.plata.ie/images/sq03.svg"><a class="a-list"> <?php echo $txtDecentralized?></a></td>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"><img src="https://www.plata.ie/images/sq04.svg"><a class="a-list"> <?php echo $txtExpenses?></a></td>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"><img src="https://www.plata.ie/images/sq05.svg"><a class="a-list"> <?php echo $txtMarketing?></a></td>

                    </tr>                    

                    <tr>

                        <td class="td-tb-info td-info-left td-info-bottom"><img src="https://www.plata.ie/images/sq06.svg"><a class="a-list"> <?php echo $txtReserve?></a></td>

                    </tr>

                </table>

                

                <table id="tbAllocation2023" class="tb-info border-text-align cursor">

                    <tr>

                        <td class="td-tb-info td-info-top td-info-left"><img src="https://www.plata.ie/images/sq07.svg"><a class="a-list"> <?php echo $txtNullAddress?></a></td>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"><img src="https://www.plata.ie/images/sq05.svg"><a class="a-list"> <?php echo $txtTypoFx?></a></td>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"><img src="https://www.plata.ie/images/sq01.svg"><a class="a-list"> <?php echo $txtUniswap?></a></td>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"><img src="https://www.plata.ie/images/sq04.svg"><a class="a-list"> <?php echo $txtQuickswap?></a></td>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"><img src="https://www.plata.ie/images/sq03.svg"><a class="a-list"> <?php echo $txtSushiSwap?></a></td>

                    </tr>       

                                        <tr>

                        <td class="td-tb-info td-info-left"><img src="https://www.plata.ie/images/sq02.svg"><a class="a-list"> <?php echo $txtPromotionalGiveaway?></a></td>

                    </tr>   



                    <tr>

                        <td class="td-tb-info td-info-left td-info-bottom"><img src="https://www.plata.ie/images/sq10.svg"><a class="a-list"> <?php echo $txtAirDrop?></a></td>

                    </tr>

                </table>

                

 

                 <table id="tbNFTsell" class="tb-info border-text-align invisibled cursor">

                    <tr>

                        <td class="td-tb-info td-info-top td-info-left"><img src="https://www.plata.ie/images/sq03.svg"><a class="a-list"> <?php echo $txtArtistsCollaboration?></td>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"><img src="https://www.plata.ie/images/sq02.svg"><a class="a-list"> <?php echo $txtLiquidityACTM?></td>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"><img src="https://www.plata.ie/images/sq01.svg"><a class="a-list"> <?php echo $txtRainfallVictims?></td>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"> </th>

                    </tr>

                    <tr>

                        <td class="td-tb-info td-info-left"> </th>

                    </tr>       

                    <tr>

                        <td class="td-tb-info td-info-left"> </th>

                    </tr>   

                                        <tr>

                        <td class="td-tb-info td-info-left"> </th>

                    <tr>

                        <td class="td-tb-info td-info-left td-info-bottom"></th>

                    </tr>

                </table>

                

                

                <br>

                

    <table class="split-table-buttons">

        <tr>

            <td class="tdwid"><button id="btnSplit2023" class="button-year " onclick="show2023()">2023</button></td>

            <td class="tdwid"><button id="btnSplitNFT" class="button-year inative"  onclick="showNFT()">NFT</button></td>

            <td class="tdwid"><button id="btnSplit2022" class="button-year inative" onclick="show2022()">2022</button></td>

            <td class="tb-typofx cursor"><span>Typo FX</span></td>

        </tr>

    </table>

            </td>

        </tr>



    </table>

<br>

<br>

</div>  

</section>





</body>

</html>