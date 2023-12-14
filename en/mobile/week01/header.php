<div id="menu-sandwich">

<table class="tableBarMenu">
  <tr>
    <td class="td-plata-font"><span><a href="https://www.plata.ie/"><img class="plata-font" src="https://www.plata.ie/images/PlataFont1.svg"></a><span></td>
    <td class="td-menu-icons">
        <table class="tableIconMenu">
            <tr>
            <td>
            <div id="ClosedNav">
                <a onclick="openNav()"><?php include '../../en/mobile/sand-menu.php';?></a>
            </div>

            </td>
            <td><button id="btnChain" class="btnPower" onclick="window.open('https://www.plata.ie/card/');">​</button></td>
            </tr>
        </table>        
  </tr>
</table>

</div>

<style>
    .alignleft{
        float: left;
    }
/*td,tr{
    border: 1px solid;
}*/

</style>

<div id="mySidenav-background" class="sidenav-background"></div>

<div id="mySidenav" class="sidenav">
    
<table  width="100%">
  <tr>
    <td><a id="item-project" onclick="showHideSubMenuProject()" href="#"><?php echo $txtSandMenuProject;?></h2></td>
  </tr>
    <tr>
    <td id="sub-menu-project" class="sub-menu">
        <table>
            <tr>
                <td><a id="sub-item-roadmap" href="#AnchorRoadmapMobile"><?php echo $txtSandMenuRoadmap;?></a></td></tr>
                <td><a id="sub-item-tokenDistribution" href="#"><?php echo $txtSandMenuTokenDistrib;?></a></td></tr>
                <td><a id="sub-item-DYOR" href="#AnchorDYORmobile"><?php echo $txtSandMenuDYOR;?></a></td></tr>
                <td><a id="sub-item-sketchDesign" onclick="window.open('https://www.instagram.com/p/CkCE1n2q8mL/','_blank');" href="#" ><?php echo $txtSandMenuSketch;?></a></td></tr>
                <td><a id="sub-item-litepaper" onclick="window.open('https://typofx.gitbook.io/','_blank');" href="#"                  ><?php echo $txtSandMenuLitepaper;?></a></td></tr>
            </tr>
        </table>
    </td>
    
  </tr>
  <tr>
    <td><a id="item-products" onclick="showHideSubMenuProducts()" href="#"><?php echo $txtSandMenuProducts;?></a></td>
  </tr>
  <tr>
    <td id="sub-menu-products" class="sub-menu">
        <table>
            <tr>
                <td><a id="sub-item-buyPlata" onclick="window.open('https://www.plata.ie/card/','_self');" href="#"><?php echo $txtSandMenuBuyPlata;?></a></td></tr>
                <td><a id="sub-item-Merchant" onclick="window.open('https://plata.sellfy.store/','_blank');" href="#"><?php echo $txtSandMenuMerchant;?></a></td></tr>
                <td><a id="sub-item-OpenSea"  onclick="window.open('https://opensea.io/collection/platatoken','_blank');" href="#">OpenSea</a></td></tr>
            </tr>
        </table>
    </td>
  </tr>
    <td><a id="item-about" onclick="showHideSubMenuAbout()" href="#"><?php echo $txtSandMenuAbout;?></a></td>
  </tr>
  <tr>
    <td id="sub-menu-about" class="sub-menu">
        <table>
            <tr>
                <td><a id="sub-item-meetTheTeam" onclick="window.open('<?php echo "https://www.plata.ie/".$language."/mobile/team/" ?>','_self');"><?php echo $txtSandMenuMeetTheTeam;?></a></td></tr>
                <td><a id="sub-item-socialMedia" onclick="window.open('https://linktr.ee/typofx','_blank');" href="#"><?php echo $txtSandMenuSocialMedia;?></a></td></tr>
                <td><a id="sub-item-reportBug"   onclick="window.open('https://www.plata.ie/reportbug','_blank');" href="#"><?php echo $txtSandMenuReportBug;?></a></td></tr>
                <td><a id="sub-item-email"       onclick="window.open('mailto:actm@plata.ie');" href="#"><?php echo $txtSandMenuEmail;?></a></td></tr>
                <td><a id="sub-item-whatsapp"    onclick="window.open('https://chat.whatsapp.com/EWnfnZ2zyA28opVHSg4ryO','_blank');" href="#">Whatsapp</a></td></tr>
                <!--<td><a id="sub-item-phone"       onclick="window.open('https://www.plata.ie/mobile/team/');" href="#">Phone</a></td></tr>-->
            </tr>
        </table>
        </td>
  </tr>
    <tr>
    <td><a id="item-typofx" onclick="window.open('https://www.linkedin.com/company/typofx','_blank');" href="#">Typo FX</a></td>
  </tr>
    <tr>
    <td><a id="item-theme" id="text-menu-theme" href="#" onclick="showHideTheme()"><?php echo $txtSandMenuTheme;?></a></td>
    </tr>
  <tr>
    <td><a id="item-language" href="#" onclick="showHideSubMenuLanguage()"><?php echo $txtSandMenuLanguage;?></a>
    </tr>
  <tr>
    <td id="sub-menu-language" class="sub-menu">
    <table>
            <tr>
                <td><a id="sub-item-english" onclick="window.open('https://www.plata.ie/en/mobile','_self');" href="https://www.plata.ie/en/mobile"><?php echo $txtSandMenuEnglish;?></a></td></tr>
                <td><a id="sub-item-portuguese" onclick="window.open('https://www.plata.ie/pt/mobile','_self');" href="https://www.plata.ie/pt/mobile"><?php echo $txtSandMenuPortuguese;?></a></td></tr>
                <td><a id="sub-item-spanish" onclick="window.open('https://www.plata.ie/es/mobile','_self');" href="https://www.plata.ie/es/mobile"><?php echo $txtSandMenuSpanish;?></a></td></tr>
            </tr>
        </table>
        </td>
  </tr>
  <td><a id="item-currency" onclick="showHideSubMenuCurrency()" href="#"><?php echo $txtSandMenuCurrency.$txtUSD;?></td>
    </tr>
  <tr>
    <td id="sub-menu-currency" class="sub-menu">
    <table>
            <tr>
                <td><a id="sub-item-brl" onclick="setBRLcurrency()"><?php echo $txtSandMenuBRL;?></a></td></tr>
                <td><a id="sub-item-eur" onclick="setEURcurrency()"><?php echo $txtSandMenuEUR;?></a></td></tr>
                <td><a id="sub-item-usd" onclick="setUSDcurrency()"><?php echo $txtSandMenuUSD;?></a></td></tr>
            </tr>
        </table>
        </td>
  </tr>
</table>

</div>



<script>

function openNav() {
    HideAllSubMenu();
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("mySidenav-background").style.display = "block";
    document.getElementById("mySidenav").style.width = "280px";
    document.getElementById("mySidenav-background").style.width = "100%";
    
        document.getElementById("item-project").textContent = "<?php echo $txtSandMenuProject;?>";
        document.getElementById("item-products").textContent = "<?php echo $txtSandMenuProducts;?>";
        document.getElementById("item-about").textContent = "<?php echo $txtSandMenuAbout;?>";
        document.getElementById("item-typofx").textContent = "Typo FX";
        document.getElementById("item-theme").textContent = "<?php echo $txtSandMenuTheme;?>";
        document.getElementById("item-language").textContent = "<?php echo $txtSandMenuLanguage;?>";
        document.getElementById("item-currency").textContent = "<?php echo $txtSandMenuCurrency;?>" + document.getElementById("txtCurrencyEnv").textContent;

}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("mySidenav-background").style.display = "none";
    document.getElementById("mySidenav-background").style.width = "0";
}

function HideAllSubMenu() {
    document.getElementById("sub-menu-project").style.display = "none";
    document.getElementById("sub-menu-products").style.display = "none";
    document.getElementById("sub-menu-about").style.display = "none";
    document.getElementById("sub-menu-language").style.display = "none";
    document.getElementById("sub-menu-currency").style.display = "none";
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

function showHideSubMenuLanguage() {
    
    var sh = document.getElementById("sub-menu-language");
    
    if (sh.style.display === "block") {
        sh.style.display = "none";
    } else {
        HideAllSubMenu();
        sh.style.display = "block";
    }
}

function showHideSubMenuCurrency() {
    
    var sh = document.getElementById("sub-menu-currency");
    
    if (sh.style.display === "block") {
        sh.style.display = "none";
    } else {
        HideAllSubMenu();
        sh.style.display = "block";
    }
}

function showHideTheme() {
    
    var sh = document.getElementById("text-menu-theme");
    console.log(sh.innerText);

    if (sh.innerText === "Light Theme") {
        HideAllSubMenu();
        sh.innerText = "Dark Theme";
        } else {
        HideAllSubMenu();
        sh.innerText = "Light Theme";
    }
}

function hideAllCurrency(){
    //document.getElementById("currencyUSD").style.display = "none";
    //document.getElementById("currencyBRL").style.display = "none";
    //document.getElementById("currencyEUR").style.display = "none";
}

function setCurrency() {
    
    var sh = document.getElementById("menu-currency");
    
    if (sh.style.display === "block") {
        sh.style.display = "none";
    } else {
        HideAllSubMenu();
        sh.style.display = "block";
    }
}


function setUSDcurrency(){
    HideAllSubMenu();
    USDcurrency();
    document.getElementById("txtCurrencyEnv").textContent = " (USD)";
    document.getElementById("item-currency").textContent = "<?php echo $txtSandMenuCurrency.$txtUSD;?>";
    HideAllSubMenu();
    closeMenuClick();
}

function setBRLcurrency(){
    HideAllSubMenu();
    BRLcurrency();
    document.getElementById("txtCurrencyEnv").textContent = " (BRL)";
    document.getElementById("item-currency").textContent = "<?php echo $txtSandMenuCurrency.$txtBRL;?>";
    HideAllSubMenu();
    closeMenuClick();
}

function setEURcurrency(){
    HideAllSubMenu();
    EURcurrency();
    document.getElementById("txtCurrencyEnv").textContent = " (EUR)";
    document.getElementById("item-currency").textContent = "<?php echo $txtSandMenuCurrency.$txtEUR;?>";
    HideAllSubMenu();
    closeMenuClick();
}

var closeMenu = document.getElementById('mySidenav-background');
var closeMenu2 = document.getElementById('mySidenav');

function hideAllitem() {
    document.getElementById("item-project").textContent = "";
    document.getElementById("item-products").textContent = "";
    document.getElementById("item-about").textContent = "";
    document.getElementById("item-typofx").textContent = "";
    document.getElementById("item-theme").textContent = "";
    document.getElementById("item-language").textContent = "";
    document.getElementById("item-currency").textContent = "";
}

function closeMenuClick(){
    HideAllSubMenu();
    hideAllitem ();
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("mySidenav-background").style.width = "0"; 
}

window.onclick = function(event) {
  if (event.target == closeMenu || event.target == closeMenu2) {
        closeMenuClick();
  }
}

//reset burger button
document.body.addEventListener('click', function(event) {
  var plate = event.target.closest('.plate');
  if (!plate) {
    var activePlates = document.querySelectorAll('.plate.active');
    for (var i = 0; i < activePlates.length; i++) {
      activePlates[i].classList.remove('active');
    }
  }
});


</script>


