<!DOCTYPE html>
<html>
<style>
.split-table {
    border-collapse: collapse;
    width: 90%;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}

.split-table-buttons {
    border-collapse: collapse;
    width: 95%;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}

.invisibled {
    display: none;
}

.tdwid {
    width: 32%;
}

/*table, tr,td {
    border:1px solid black;
}*/

.center-img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 70%;
}

.button-year {
    width: 95%;
    height: 40px;
    display: block;
    background-color: #6E46AE;
    border-radius: 6px; 
    border: 0px solid #3D3D3D;
    color: white; 
    font-size: 15px; 
    cursor: pointer; 
    transition: 0.3s; 
    font-family: 'Montserrat';
    text-align: center;
}

.button-year:hover {
    background-color: #8247E5;
    border-radius: 6px; 
    color: white;
    transition: 0.3s;
}

.inative {
    background-color: #3a3b3c;
}

</style>

<script>
    function show2022(){
        document.getElementById("txtSplit").innerText = "<?php echo $txtInitialSplit?>";
        document.getElementById("tbInitialSplit").classList.remove('invisibled');
        document.getElementById("tbAllocation2023").classList.add('invisibled');
        document.getElementById("btnSplit2022").classList.remove('inative');
        document.getElementById("btnSplit2023").classList.add('inative');
        document.getElementById("btnSplitNFT").classList.add('inative');
    }

    function show2023(){
        document.getElementById("txtSplit").innerText = "Token Allocation (2023)";
        document.getElementById("tbAllocation2023").classList.remove('invisibled');
        document.getElementById("tbInitialSplit").classList.add('invisibled');
        document.getElementById("btnSplit2023").classList.remove('inative');
        document.getElementById("btnSplit2022").classList.add('inative');
        document.getElementById("btnSplitNFT").classList.add('inative');
    }
    
</script>

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
    <td> </td>
  </tr>
    <tr>
    <td><br></td>
  </tr>
</table>

<table id="tbAllocation2023" class="split-table invisibled">
  <tr>
    <td><img class="center-img" src="https://www.plata.ie/images/token-split-chart-2022.svg"><br></td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq01.svg">  Null: 0x00...dEaD ( 49% )</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq02.svg">  Typo FX: Wallets ( 26% )</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq03.svg">  Uniswap V3 ( 5% )</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq04.svg">  Quickswap DEX ( 5% )</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq05.svg">  SushiSwap V2 ( 5% )</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq06.svg">  Promotional Giveaway ( 5% )</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq06.svg">  AirDrop dApp ( 4% )</td>
  </tr>
    <tr>
    <td><br></td>
  </tr>
</table>

<table class="split-table-buttons">
  <tr>
    <td class="tdwid"><button id="btnSplit2022" class="button-year" onclick="show2022()">2022</button></td>
    <td class="tdwid"><button id="btnSplit2023" class="button-year inative" onclick="show2023()">2023</button></td>
    <td class="tdwid"><button id="btnSplitNFT" class="button-year inative" onclick="">NFT</button></td>
  </tr>
</table>

</section>
