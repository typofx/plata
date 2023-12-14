<?php include $_SERVER['DOCUMENT_ROOT'] . '/languages/languages.php';?>

    <link rel="stylesheet" href="https://www.plata.ie/en/desktop-roadmap-style.css" media="screen">

<style>
/*
table, th, td {
    border: 1px solid black;
    color: black;
    }*/

.img-line {
    display: block;
    margin-left: auto;
    margin-right: auto;
    height: 47px;
    }

.roadmap-main-text {
    border-collapse: collapse;
    width: 100%;
    height: 80px; /* width="70%" height="80px"*/
    margin-left: auto;
    margin-right: auto;
    }
    
.roadmap-main-text .roadmap-header{
    background-color: #4C00B0;
    color: white;
    }
    
.roadmap-main-text .roadmap-icon{
    background-color: #8A00C2;
    width: 80px;
    }
    
.roadmap-table {
    border-collapse: collapse;
    width: calc(100% - 60px);
    margin-left: 30px;
    margin-top: 30px;
    margin-bottom: 0px;
    text-align: left;
    }
    
.roadmap-table h3 {
    text-align: left;

}

.roadmap-table .tdleft {
    margin-top: 30px;
    text-align: center;
    width: 50px;
}

.roadmap-table .tdline1 {
    background-color: #9966CC;
    color: white;
}

.roadmap-table .tdline2 {
    background-color: #8A00C2;
    color: white;
}

.div-roadmap{
    background-color: #1C0024;
    padding-bottom: 30px;
}

    
</style>




<section id="AnchorRoadmapMobile">
<div class="div-roadmap">
    
<table class="roadmap-main-text">
 	<tr>
		<td class="roadmap-header"><h2><?php echo  $txtRoadmap ?></h2></td>
		<td class="roadmap-icon"><img class= "img-line" src="https://www.plata.ie/images/plata-lines-logo.svg"></td>
	</tr>
    <td>
</table>

<table class="roadmap-table">
    
	<tr>
		<td class="tdleft tdline1">Q1</td>
		<td class="tdline1"><h3><br>&#x2713; <?php echo $txtOpenSea ?></h3>
                            <h3>&#x2713; <?php echo $txtRoadmapPayment ?></h3>
                            <h3>&#x2713; <?php echo $txtPIXPayment ?></h3>
		</td>
	</tr>
	<tr>
		<td class="tdleft tdline2">Q</td>
		<td class="tdline2"><h3><br>
		                           <?php echo  $txtPresentation ?></h3>
                            <h3>   <?php echo $txtWorkshopL1 ?></h3>
                            <h3>   <?php echo $txtCoingecko ?></h3>
                            <h3>   <?php echo $txtCoinmarketcapListing ?></h3>
                            <h3>   <?php echo $txtMerchandising ?></h3>
	</tr>

</table>
</div>
<div class="div-roadmap">
<table class="roadmap-main-text">
 	<tr>
 	    <td class="roadmap-icon"><img class= "img-line" src="https://www.plata.ie/images/plata-lines-logo.svg"></td>
		<td class="roadmap-header"><h2>Phase 01 (2022)</h2></td>
	</tr>

</table>

<table class="roadmap-table">
    
	<tr>
		<td class="tdleft tdline1">S2</td>
		<td class="tdline1"><h3><br>&#x2713; Project Announcement</h3>
                            <h3>&#x2713; Airdrop</h3>
                            <h3>&#x2713; https://www.plata.ie</h3>
                            <h3>&#x2713; Sketch Project</h3>
                            <h3>&#x2713; Social Media</h3>
                            <h3>&#x2713; Litepaper</h3>
                            <h3>&#x2713; Giveaway</h3>
                            <h3>&#x2713; Polygon Team Approval</h3>
                            <h3>&#x2713; 1k Wallets hodling Plata Token</h3>
                            <h3>&#x2713; 50% Prototype Done (UX)</h3>
                            <h3>&#x2713; Softcap Reached</h3>
                            <h3>&#x2713; DEX Liquidity Pools</h3>
		</td>
	</tr>
</table>
</div>

</section>