<div id="menu-sandwich">
<table class="tableBarMenu">
        <td class="td-plata-font"><a href="https://www.plata.ie/"><img id="plata-font" class="plata-font" src="<?php echo $plataFont?>"></a></td>
        <td class="td-menu-icons">
        <table class="tableIconMenu">
                <td><a href="https://www.plata.ie/en/mobile/calc/" target="_blank"><img id="header-icon-calc" class="img-menu" src="<?php echo $headerIconCalc?>"></a></td>
                <td><a href="https://www.plata.ie/en/mobile/select/"><img id="header-icon-trolley" class="img-menu" src="<?php echo $headerIconTrolley?>"></a></td>
                <td><a onclick="openNav()"><img id="header-icon-burger" class="img-menu" src="<?php echo $headerIconBurger?>"></a></td>
        </table>
</table>

</div>

<style>
    .alignleft{
        float: left;
    }
    
    /*
td,tr{
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
                <td><a id="sub-item-roadmap" onclick="closeNav()" href="#AnchorRoadmapMobile"><?php echo $txtSandMenuRoadmap;?></a></td></tr>
                <td><a id="sub-item-tokenDistribution" onclick="closeNav()" href="#AnchorInitialSplit"><?php echo $txtSandMenuTokenDistrib;?></a></td></tr>
                <td><a id="sub-item-DYOR" onclick="closeNav()" href="#AnchorDYORmobile"><?php echo $txtSandMenuDYOR;?></a></td></tr>
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
                <td><a id="sub-item-buyPlata" onclick="window.open('https://www.plata.ie/en/mobile/select/','_self');" href="#"><?php echo $txtSandMenuBuyPlata;?></a></td></tr>
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
                <td><a id="sub-item-reportBug"   onclick="window.open('https://www.plata.ie/en/reportbug','_blank');" href="#"><?php echo $txtSandMenuReportBug;?></a></td></tr>
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
    <td><a id="item-theme" href="#" onclick="showHideSubMenuTheme()"></a>
    
    <tr>
    <td id="sub-menu-theme" class="sub-menu">
    <table>
        <form method="post" action="">
            <tr>
                <td> <button type="submit" id="sub-item-darkmode" name="change_appearance" class="sidenavAparaance" value="off"><?php echo $txtSandMenuThemeDark?></button></td>
                </tr>
                <td> <button type="submit" id="sub-item-lightmode" name="change_appearance" class="sidenavAparaance" value="on"><?php echo $txtSandMenuThemeLight?></button></td></tr>
            </tr>
        </form>
    </table>
        </td>
  </tr>
    
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
  <td><a id="item-currency" onclick="showHideSubMenuCurrency()" href="#"/></td>
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

setTimeout(updatePrices, 180000);

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
        document.getElementById("item-theme").textContent = "<?php echo $txtSandMenuTheme." ".$txtSandMenuAtributte;?>"; 
        document.getElementById("item-language").textContent = "<?php echo $txtSandMenuLanguage;?>";
        document.getElementById("item-currency").textContent = "<?php echo $txtSandMenuCurrency." ";?>" + defCurrency;

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
    document.getElementById("sub-menu-theme").style.display = "none";
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

function showHideSubMenuTheme() {
    
    var sh = document.getElementById("sub-menu-theme");

    if (sh.style.display === "block") {
        sh.style.display = "none";
    } else {
        HideAllSubMenu();
        sh.style.display = "block";
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

let defCurrency = "(USD)";

function updatePrices(){

    <?php
        $json_url = 'https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC,ETH,MATIC,EUR,BRL&api_key=6023fb8068e6f17fe63800ce08f15fb6bd88d7b3b825600d58736973a6aafd98';
        $json_wmatic_pool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0x0d500B1d8E8eF31E21C99d1Db9A6444d3ADf1270&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=1Y153G4EA8DRD889PTTXYT1B2TAQE2IQP8';
        $json_plata_pool = 'https://api.polygonscan.com/api?module=account&action=tokenbalance&contractaddress=0xc298812164bd558268f51cc6e3b8b5daaf0b6341&address=0x0E145c7637747CF9cfFEF81b6A0317cA3c9671a6&tag=latest&apikey=Y7KBS7GQBHUEQ3CM3JSQK1I69UIGGPDC1J';

        $json = file_get_contents($json_url);
        $ar_data = array($json);
        $ar_data = $ar_data[0];
        $ar_data = json_decode($ar_data);
        
        $PLTcirculatingSupply = (11299000992);

        //$USDBTC = number_format($ar_data->{'BTC'}, 8, '.', ',');
        //$USDETH = number_format($ar_data->{'ETH'}, 8, '.', ',');
        $USDMATIC = number_format($ar_data->{'MATIC'}, 4, '.', ',');
        $USDEUR = number_format($ar_data->{'EUR'}, 4, '.', ',');
        $USDBRL = number_format($ar_data->{'BRL'}, 4, '.', ',');
    
        //$BTCUSD = number_format( (1/$USDBTC) , 4, '.', ',');
    
            $jsonfile = file_get_contents($json_wmatic_pool);
            $data = array($jsonfile);
            $data = $data[0];
            $data = json_decode($data);
            $result = round($data->{'result'});
        $wmatic_pool = number_format($result/ 10**18, 4, '.', ',');
        
            $jsonfile = file_get_contents($json_plata_pool);
            $data = array($jsonfile);
            $data = $data[0];
            $data = json_decode($data);
            $result = round($data->{'result'});
        $plata_pool = number_format($result/ 10**4, 4, '.', ',');
    
        $MATICPLT = number_format(($plata_pool/$wmatic_pool)*10**6, 4, '.', ',');

        $USDPLT = number_format(($MATICPLT*$USDMATIC)*10**3, 4, '.', ',');
        $PLTUSD = number_format((0.001/$USDPLT), 10, '.', ',');
        $PLTBRL = number_format(($PLTUSD*$USDBRL), 10, '.', ',');
        $PLTEUR = number_format(($PLTUSD*$USDEUR), 10, '.', ',');
    
        $PLTmarketcapUSD = number_format(($PLTcirculatingSupply * $PLTUSD), 4, '.', ',');
        $PLTmarketcapBRL = number_format(($PLTcirculatingSupply * $PLTBRL), 4, '.', ',');
        $PLTmarketcapEUR = number_format(($PLTcirculatingSupply * $PLTEUR), 4, '.', ',');
        
    ?>
    

    console.log("<?php echo "USDMATIC : ".$USDMATIC?>");
    console.log("<?php echo "USDEUR : ".$USDEUR?>");
    console.log("<?php echo "USDBRL : ".$USDBRL?>");
    console.log("<?php echo "MATICPLT : ".$wmatic_pool?>");
    console.log("<?php echo "USDPLT : ".$USDPLT?>");
    console.log("<?php echo "PLTBRL : ".$PLTBRL?>");
    console.log("<?php echo "PLTEUR : ".$PLTEUR?>");
    console.log("<?php echo "PLTmarketcapUSD : ".$PLTmarketcapUSD?>");
    console.log("<?php echo "PLTmarketcapBRL : ".$PLTmarketcapBRL?>");
    console.log("<?php echo "PLTmarketcapEUR : ".$PLTmarketcapEUR?>");
    console.log("<?php echo "plata_pool : ".$plata_pool?>");
    console.log("<?php echo "MATICPLT : ".$MATICPLT?>");
    
    console.log("<?php echo "Currency:". $defaulCurrency?>");
    console.log("<?php echo $PLTmarketcapBRL?>");
    console.log("<?php echo $PLTBRL?>");
    console.log("<?php echo $marketSymbol?>");
    
    console.log("defCurrency: "+ defCurrency);
    
    if (defCurrency=="USD") setUSDcurrency();
        else if (defCurrency=="BRL") setBRLcurrency();
            else if (defCurrency=="EUR") setEURcurrency();
            
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

function darkMode() {
   document.body.classList.add("dark-mode");
   document.getElementById("plata-font").src="https://www.plata.ie/images/plata-font-gray.svg";
   document.getElementById("header-icon-calc").src="https://www.plata.ie/images/header-icon-calc-gray.svg";
   document.getElementById("header-icon-trolley").src="https://www.plata.ie/images/header-icon-trolley-gray.svg";
   document.getElementById("header-icon-burger").src="https://www.plata.ie/images/header-icon-hamburger-gray.svg";
   document.getElementById("ad-card").src="https://www.plata.ie/images/buy-card-dark.svg";
   console.log("Dark-Mode");
   closeNav();
}

function lightMode() {
   document.body.classList.remove("dark-mode");
   document.getElementById("plata-font").src="https://www.plata.ie/images/plata-font-original.svg";
   document.getElementById("header-icon-calc").src="https://www.plata.ie/images/header-icon-calc.svg";
   document.getElementById("header-icon-trolley").src="https://www.plata.ie/images/header-icon-trolley.svg";
   document.getElementById("header-icon-burger").src="https://www.plata.ie/images/header-icon-hamburger.svg";
   document.getElementById("ad-card").src="https://www.plata.ie/images/buy-card.svg";
   console.log("Light-Mode");
   closeNav();
}

</script>