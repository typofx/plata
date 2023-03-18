<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<style>

td {
    /*border:1px solid black;*/
    text-align:left;
}

.td-plata-font{
    text-align:left;
}

.td-menu-icons{
    text-align:right;
    width:10px;
}

.plata-font {
    height: 52px;
}

.tableIconMenu {
    /*border:1px solid red;*/
    width:100px;
    height:100%;
    text-align:right;
    padding: 0px;
}

.tableBarMenu {
    padding-left: 15px;
    padding-right: 12px;
    width:100%;
    height:75px;

}

.btnPower{
 background: no-repeat center/45% url("https://www.plata.ie/images/wallet-icon.png");
 background-color:grey ; /**/
 border-radius: 29px; 
 border: 0px solid #3D3D3D; 
 color: white; 
 font-size: 23px; 
 padding: 10px 24px; 
 cursor: pointer; 
 transition: 0.3s;
 align-content: flex-end;
 }
 
 .btnPower:hover{ 
 background-color: #67458B;
 color: #081F2D; 
 border-color: #8247E5; 
 transition: 0.3s; 
}
    
}

</style>
<!---->

<div id="menu-sandwich">

<table class="tableBarMenu">
  <tr>
    <td class="td-plata-font"><span><a href="https://www.plata.ie/"><img class="plata-font" src="https://www.plata.ie/images/PlataFont1.SVG"></a><span></td>
    <td class="td-menu-icons">
        <table class="tableIconMenu">
            <tr>
            <td>
            <div id="ClosedNav">
                <a onclick="openNav()"><?php include 'sand-menu.php';?></a>
            </div>
            
            
            </td>
            <td><button id="btnChain" class="btnPower" onclick="window.open('https://www.plata.ie/card/');">​</button></td>
            </tr>
        </table>        
  </tr>
</table>

</div>

<style>

.sidenav {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 5;
  top: 0;
  left: 0;
  background-color: #333;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidenav a {

  padding: 8px 1px 8px 28px;
  text-decoration: none;
  font-size: 19px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidenav a:hover {
  color: #f1f1f1;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 12px;}
  .sidenav a {font-size: 15px;}
}

</style>
</head>
<body>

<div id="mySidenav" class="sidenav">
    
<table>
  <tr>
    <td><a href="#" onclick="showHideSubMenuProject()">Project</a></td>
  </tr>
    <tr>
    <td id="sub-menu-project" style="display: none;">
        <table>
            <tr>
                <td><a id="sub-item-roadmap" href="#">Roadmap</a></td></tr>
                <td><a id="sub-item-tokenDistribution" href="#">Token Distribution</a></td></tr>
                <td><a id="sub-item-sketchDesign" href="#">Sketch Design</a></td></tr>
                <td><a id="sub-item-litepaper" href="#">Litepaper</a></td></tr>
            </tr>
        </table>
    </td>
    
  </tr>
  <tr>
    <td><a href="#" onclick="showHideSubMenuProducts()">Products</a></td>
  </tr>
  <tr>
    <td id="sub-menu-products" style="display: none;">
        <table>
            <tr>
                <td><a id="sub-item-buyPlata" onclick="window.open('https://www.plata.ie/card/');" href="#">Buy Plata</a></td></tr>
                <td><a id="sub-item-Merchant" href="#">Merchant</a></td></tr>
                <td><a id="sub-item-OpenSea" href="#">OpenSea</a></td></tr>
                <td><a id="sub-item-DYOR" href="#">DYOR</a></td></tr>
            </tr>
        </table>
    </td>
  </tr>
    <td><a href="#" onclick="showHideSubMenuAbout()">About</a></td>
  </tr>
  <tr>
    <td id="sub-menu-about" style="display: none;">
        <table>
            <tr>
                <td><a id="sub-item-meetTheTeam" onclick="window.open('https://www.plata.ie/mobile/team/');" href="#">Meet The Team</a></td></tr>
                <td><a id="sub-item-socialMedia" href="#">Social Media</a></td></tr>
                <td><a id="sub-item-reportBug" href="#">Report Bug</a></td></tr>
                <td><a id="sub-item-email" href="#">Email</a></td></tr>
                <td><a id="sub-item-whatsapp" href="#">Whatsapp</a></td></tr>
                <td><a id="sub-item-phone" href="#">Phone</a></td></tr>
            </tr>
        </table>
        </td>

  </tr>
    <tr>
    <td>  <a href="#">Typo FX</a></td>
  </tr>
    <tr>
    <td><a href="#">Theme</a></td>
  </tr>
    <tr>
    <td><a href="#">(EN)</a></td>
  </tr>
</table>

</div>

<div id="background" class="background"></div>

<script>

function openNav() {
    document.getElementById("mySidenav").style.width = "280px";
    document.getElementById("background").style.width = "100%";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

function HideAllSubMenu() {
    document.getElementById("sub-menu-project").style.display = "none";
    document.getElementById("sub-menu-products").style.display = "none";
    document.getElementById("sub-menu-about").style.display = "none";
}

function showHideSubMenuProject() {
    
    var sh = document.getElementById("sub-menu-project");
    
    if (sh.style.display === "block") {
        sh.style.display = "none";
    } else {
        HideAllSubMenu();
        sh.style.display = "block";
    }
}

function showHideSubMenuProducts() {
    
    var sh = document.getElementById("sub-menu-products");
    
    if (sh.style.display === "block") {
        sh.style.display = "none";
    } else {
        HideAllSubMenu();
        sh.style.display = "block";
    }
}

function showHideSubMenuAbout() {
    
    var sh = document.getElementById("sub-menu-about");
    
    if (sh.style.display === "block") {
        sh.style.display = "none";
    } else {
        HideAllSubMenu();
        sh.style.display = "block";
    }
}


</script>



