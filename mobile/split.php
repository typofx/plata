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

.invisibled {
    display: none;
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
    width: 120px;
    height: 40px;
    display: block;
    background-color: #4A0086;
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

</style>

<section id="AnchorInitialSplit">
<br>
<h2><?php echo $txtInitialSplit?></h2>

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

<table id="tbInitialSplit" class="split-table invisibled">
  <tr>
    <td><img class="center-img" src="https://www.plata.ie/images/token-split-chart-2022.svg"><br></td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq01.svg">  Null: 0x00...dEaD 49%</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq02.svg">  Typo FX: Wallets 26%</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq03.svg">  Uniswap V3 5%</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq04.svg">  Quickswap DEX 5%</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq05.svg">  SushiSwap V2 5%</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq06.svg">  Promotional Giveaway 5%</td>
  </tr>
  <tr>
    <td><img src="https://www.plata.ie/images/sq06.svg">  AirDrop dApp 4%</td>
  </tr>
    <tr>
    <td><br></td>
  </tr>
</table>

<table style="width:100%">
  <tr>
    <td><button class="button-year" onclick="show2022()">2022</button></td>
    <td><button class="button-year" onclick="show2023()">2023</button></td>
    <td><button class="button-year" onclick="showNFT()">NFT</button></td>
  </tr>
</table>

</section>
