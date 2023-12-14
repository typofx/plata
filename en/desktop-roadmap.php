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

</style>




<section id="AnchorRoadmap">
<div class="div-roadmap">
    
<table class="roadmap-main-text">
 	<tr>
		<td class="roadmap-header"><h2>2023's Roadmap</h2></td>
		<td class="roadmap-icon"><img class= "img-line" src="https://www.plata.ie/images/plata-lines-logo.svg"></td>
	</tr>
</table>

<table class="roadmap-table">
    
	<tr>
		<td class="tdleft tdline1"><div class="centre"><h2>Q1</h2><br><br><br><br><br><br><br><br><br><br></div></td>
		<td class="tdline1 fontStyle"><br><b>&#x2713; <?php echo $txtOpenSea ?></b><br>
                            <b>&#x2713; <?php echo $txtRoadmapPayment ?></b><br>
                            <b>&#x2713; <?php echo $txtPIXPayment ?></b><br>
                            <b class="a-link">&#x2713; <a class="a-link" href="https://curve.fi/#/polygon/pools/factory-crypto-150" target="_blank">Curve.fi LP deployed</a></b><br>
                            <b class="a-link">&#x2713; 1st CEEX Registration! <a class="a-link" href="https://www.coininn.io/coin/PLT" target="_blank">www.coininn.io/coin/PLT</a></b><br>
                            <b>&#x2713; Coinbase Commerce Payment<br><br>
		</td>
	</tr>
	<tr class="noooo">
		<td class="tdleft tdline2"><div class="centre"><h2>Q2</h2><br><br><br><br><br><br><br><br><br></div></td>
		<td class="tdline2 fontStyle"><br><b>-  <?php echo  $txtPresentation ?></b><br>
                            <b>-  <?php echo $txtWorkshopL1 ?></b><br>
                            <b>-  <?php echo $txtCoingecko ?></b><br>
                            <b>-  <?php echo $txtCoinmarketcapListing ?></b><br>
                            <b>-  <?php echo $txtMerchandising ?></b><br><br>
        </td>
	</tr>

</table>
</div>
<div class="div-roadmap">
<table class="roadmap-main-text">
 	<tr>
 	    <td class="roadmap-icon"><img class= "img-line" src="https://www.plata.ie/images/plata-lines-logo.svg"></td>
		<td class="roadmap-header"><h2>Stage One (2022)</h2></td>
	</tr>

</table>

<table class="roadmap-table">
    
	<tr>
		<td class="tdleft tdline1"><div class="centre"><h2>S2</h2><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></div></td>
		<td class="tdline1 fontStyle"><br><b>&#x2713; Project Announcement</b><br>
                            <b>&#x2713; Airdrop</b><br>
                            <b>&#x2713; https://www.plata.ie</b><br>
                            <b>&#x2713; Sketch Project</b><br>
                            <b>&#x2713; Social Media</b><br>
                            <b>&#x2713; Litepaper</b><br>
                            <b>&#x2713; Giveaway</b><br>
                            <b>&#x2713; Polygon Team Approval</b><br>
                            <b>&#x2713; 1k Wallets hodling Plata Token</b><br>
                            <b>&#x2713; 50% Prototype Done (UX)</b><br>
                            <b>&#x2713; Softcap Reached</b><br>
                            <b>&#x2713; DEX Liquidity Pools</b><br><br>
		</td>
	</tr>
</table>
</div>

</section>