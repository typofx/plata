<!DOCTYPE html>
<html lang="pt">

<?php include 'languagePT.php';?>

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
    .alignleft{
        float: left;
    }

</style>

<div id="mySidenav-background" class="sidenav-background" onclick="HideSidenavBackground()"></div>

<div id="mySidenav" class="sidenav">
    
<table width="100%">
  <tr>
    <td><a id="item-project" onclick="showHideSubMenuProject()" href="#">Project</h2></td>
  </tr>
    <tr>
    <td id="sub-menu-project" class="sub-menu">
        <table>
            <tr>
                <td><a id="sub-item-roadmap" href="#"><?php echo $txtRoadmap; ?></a></td></tr>
                <td><a id="sub-item-tokenDistribution" href="#">Token Distribution</a></td></tr>
                <td><a id="sub-item-DYOR"     onclick="window.open('https://www.plata.ie/card/','_self');" href="#"              >DYOR      </a></td></tr>
                <td><a id="sub-item-sketchDesign" onclick="window.open('https://www.instagram.com/p/CkCE1n2q8mL/','_blank');" href="#" >Sketch Design </a></td></tr>
                <td><a id="sub-item-litepaper" onclick="window.open('https://typofx.gitbook.io/','_blank');" href="#"                  >Litepaper     </a></td></tr>
            </tr>
        </table>
    </td>
    
  </tr>
  <tr>
    <td><a id="item-products" onclick="showHideSubMenuProducts()" href="#">Products</a></td>
  </tr>
  <tr>
    <td id="sub-menu-products" class="sub-menu">
        <table>
            <tr>
                <td><a id="sub-item-buyPlata" onclick="window.open('https://www.plata.ie/card/','_self');" href="#"              >Buy Plata </a></td></tr>
                <td><a id="sub-item-Merchant" onclick="window.open('https://plata.sellfy.store/','_blank');" href="#"            >Merchant  </a></td></tr>
                <td><a id="sub-item-OpenSea"  onclick="window.open('https://opensea.io/collection/plata-v2','_blank');" href="#" >OpenSea   </a></td></tr>
            </tr>
        </table>
    </td>
  </tr>
    <td><a id="item-about" onclick="showHideSubMenuAbout()" href="#">About</a></td>
  </tr>
  <tr>
    <td id="sub-menu-about" class="sub-menu">
        <table>
            <tr>
                <td><a id="sub-item-meetTheTeam" onclick="window.open('https://www.plata.ie/mobile/team/','_self');" href="#"                >Meet The Team </a></td></tr>
                <td><a id="sub-item-socialMedia" onclick="window.open('https://linktr.ee/typofx','_blank');" href="#"                        >Social Media  </a></td></tr>
                <td><a id="sub-item-reportBug"   onclick="window.open('https://www.plata.ie/reportbug','_blank');" href="#"                  >Report Bug    </a></td></tr>
                <td><a id="sub-item-email"       onclick="window.open('mailto:actm@plata.ie');" href="#"                                     >Email         </a></td></tr>
                <td><a id="sub-item-whatsapp"    onclick="window.open('https://chat.whatsapp.com/EWnfnZ2zyA28opVHSg4ryO','_blank');" href="#">Whatsapp      </a></td></tr>
                <!--<td><a id="sub-item-phone"       onclick="window.open('https://www.plata.ie/mobile/team/');" href="#">Phone</a></td></tr>-->
            </tr>
        </table>
        </td>

  </tr>
    <tr>
    <td id="item-typofx"><a onclick="window.open('https://www.linkedin.com/company/typofx','_blank');" href="#">Typo FX</a></td>
  </tr>
    <tr>
    <td id="menu-theme"><a id="text-menu-theme" href="#" onclick="showHideTheme()">Light Theme</a></td>
    </tr>
  <tr>
    <td id="menu-language"><a href="#" onclick="showHideSubMenuLanguage()">Language (EN)</a>
    </tr>
    <tr>
    <td id="sub-menu-language" class="sub-menu">
    <table>
            <tr>
                <td><a id="sub-item-english" onclick="" href="#"    >English (EN)</a></td></tr>
                <td><a id="sub-item-portuguese" onclick="" href="#" >Portuguese (PT)</a></td></tr>
                <td><a id="sub-item-spanish" onclick="" href="#"    >Spanish (ES)</a></td></tr>
            </tr>
        </table>
        </td>
  </tr>
</table>

</div>



<script>

function openNav() {
    document.getElementById("mySidenav-background").style.display = "block";
    document.getElementById("mySidenav").style.width = "280px";
    document.getElementById("mySidenav-background").style.width = "100%";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

function HideAllSubMenu() {
    document.getElementById("sub-menu-project").style.display = "none";
    document.getElementById("sub-menu-products").style.display = "none";
    document.getElementById("sub-menu-about").style.display = "none";
    document.getElementById("sub-menu-language").style.display = "none";
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




var closeMenu = document.getElementById('mySidenav-background');

window.onclick = function(event) {
  if (event.target == closeMenu) {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("mySidenav-background").style.width = "0";
  }
}


</script>


