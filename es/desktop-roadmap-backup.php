<?php include $_SERVER['DOCUMENT_ROOT'] . '/languages/languages.php';?>

    <link rel="stylesheet" href="https://www.plata.ie/en/desktop-roadmap-style.css" media="screen">


<style> 
@import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap"); /* need to be at index */

body {
  font-family: "Montserrat", sans-serif;
  margin: 0;
  padding: 0;
  text-align:left;
}

/*
table, th, td {
    border: 1px solid black;
    color: black;
    }*/

.img-line {
    display: block;
    margin-left: auto;
    margin-right: auto;
    height: 65px;
    }

.roadmap-main-text {
    border-collapse: collapse;
    width: 100%;
    }
    
.roadmap-main-text .roadmap-header{
    background-color: #4C00B0;
    margin: auto;
    color: white;
   /* padding-top:15px;*/
    /*border: 1px solid red;*/
    vertical-align: middle;
    }
    
.roadmap-main-text .roadmap-icon{
    background-color: #8A00C2;
    width: 90px;
    height: 90px;
    /*border: 1px solid red;*/
    vertical-align: middle;
    }
    
.roadmap-table {
    border-collapse: collapse;
    width: calc(100% - 120px);
    margin-left: 60px;
    margin-top: 60px;
    margin-bottom: 30px;
    text-align: left;
    }
    
.roadmap-table .tdleft {
    margin: auto;
    text-align: center;
    width: 100px;
    text-align: center;
    margin-left: auto;
    margin-right: auto;

}

.fontStyle {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px;
    color: white;
}

.roadmap-table .tdline1 {
    background-color: #9966CC;
}

.roadmap-table h2 {
    font-size: 25px;
    margin: auto;
    text-align: center;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.roadmap-table .tdline2 {
    background-color: #8A00C2;
}

.div-roadmap{
    background-color: #1C0024;
    padding-bottom: 30px;
}

.centre {
    display: flex;
    justify-content: center;
    align-items: center;
    vertical-align: middle;
}

.a-link{
    color: yellow;
}

.cursor {
    cursor: default;
}

</style>




<section id="AnchorRoadmap">
<div class="div-roadmap">
    
<table class="roadmap-main-text">
 	<tr>
		<td class="roadmap-header cursor"><h2><?php echo $txtRoadmap ?></h2></td>
		<td class="roadmap-icon"><img class= "img-line" src="https://www.plata.ie/images/plata-lines-logo.svg"></td>
	</tr>
</table>







<table class="roadmap-table">
    
	<tr>
		<td class="tdleft tdline1"><div class="centre cursor"><h2>Q1</h2><br><br><br><br><br><br><br><br><br><br></div></td>
		<td class="tdline1 fontStyle"><br><b class="cursor"> <?php echo  $txtOpenSea;?></b><br>
                            <b class="cursor"> <?php echo  $txtRoadmapPayment ?></b><br>
                            <b class="cursor"> <?php echo  $txtCoingecko?></b><br>
                            <b class="a-link"> <a class="a-link" href="https://curve.fi/#/polygon/pools/factory-crypto-150" target="_blank"><?php echo $txtCoinmarketcapListing?></a></b><br>
                            <b class="a-link"> <?php echo $txtMerchandising ?><a class="a-link" href="https://www.coininn.io/coin/PLT" target="_blank">www.coininn.io/coin/PLT</a></b><br>
                            <b class="cursor"> <?php echo $txtWorkshopL1 ?><br><br>
		</td>
	</tr>
	<tr class="noooo">
		<td class="tdleft tdline2 cursor"><div class="centre"><h2>Q2</h2><br><br><br><br><br><br><br><br><br></div></td>
		<td class="tdline2 fontStyle"><br><b class="cursor">  <?php echo $txtPresentation ?></b><br>
                            <b class="cursor">  <?php echo $txtWorkshopL2 ?></b><br>
                            <b class="cursor">  <?php echo $txtPIXPayment ?></b><br>
                            <b class="cursor">  <?php echo $txtCoinbasePayment ?></b><br>
                            <b class="cursor">  <?php echo  $txtStageOne  ?></b><br><br>
        </td>
	</tr>

</table>
</div>
<div class="div-roadmap">
<table class="roadmap-main-text">
 	<tr>
 	    <td class="roadmap-icon"><img class= "img-line" src="https://www.plata.ie/images/plata-lines-logo.svg"></td>
		<td class="roadmap-header cursor"><h2><?php echo $txtStageOne ?></h2></td>
	</tr>

</table>

<table class="roadmap-table">
    
	<tr>
		<td class="tdleft tdline1 cursor"><div class="centre"><h2>S2</h2><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></div></td>
		<td class="tdline1 fontStyle cursor"><br><b>&#x2713; <?php echo $txtStageProjectAnnouncement ?></b><br>
                            <b class="cursor">&#x2713; <?php echo $txtStageAirdrop ?></b><br>
                            <b class="cursor">&#x2713; <?php echo $txtStagePlata ?></b><br>
                            <b class="cursor">&#x2713; <?php echo $txtStageSketch ?></b><br>
                            <b class="cursor">&#x2713; <?php echo $txtStageSocialMedia ?></b><br>
                            <b class="cursor">&#x2713; <?php echo $txtStageLitepaper ?></b><br>
                            <b class="cursor">&#x2713; <?php echo $txtStageGiveaway ?></b><br>
                            <b class="cursor">&#x2713; <?php echo $txtStagePolygonTeamAproval ?></b><br>
                            <b class="cursor">&#x2713; <?php echo $txtStageWalletsPlata ?></b><br>
                            <b class="cursor">&#x2713; <?php echo $txtStagePrototype ?></b><br>
                            <b class="cursor">&#x2713; <?php echo $txtStageSoftcap ?></b><br>
                            <b class="cursor">&#x2713; <?php echo $txtStageLiquidity ?></b><br><br>
		</td>
	</tr>
</table>
</div>

</section>