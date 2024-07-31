<!DOCTYPE html>
<html style="font-size: 16px;" lang="en"><head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>GiveAway</title>
    <link rel="stylesheet" href="nicepage.css" media="screen">
<link rel="stylesheet" href="GiveAway.css" media="screen">
    <script class="u-script" type="text/javascript" src="jquery.js" defer=""></script>
    <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>
    <meta name="generator" content="Nicepage 4.13.4, nicepage.com">
    <script src="https://cdn.jsdelivr.net/npm/@walletconnect/web3-provider@1.7.1/dist/umd/index.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
    <script type="text/javascript" src="giveaway.js"></script>
    <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">
    <link id="u-page-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i">
    
    
   <?php

$hash = "";

if(isset($_POST['submit']) )
{
 
  $hash = $_POST['hash'];

}

$collect1 = substr($hash, 0, 5);
$collect2 = substr($hash,-4);
$es = "...";
$result = $collect1.$es.$collect2;


//echo $coletar1;
//echo $es;
//echo $coletar2;

?>


    
    
    <script type="application/ld+json">{
		"@context": "http://schema.org",
		"@type": "Organization",
		"name": "plata",
		"logo": "images/PlataFont1.SVG",
		"sameAs": [
				"https://www.facebook.com/TypoFX/",
				"https://www.instagram.com/typofx/",
				"https://chat.whatsapp.com/EWnfnZ2zyA28opVHSg4ryO",
				"https://t.me/joinchat/UIJNdhJGcYc0ZTI0",
				"https://github.com/typofx/plata"
		]
}</script>
    <meta name="theme-color" content="#478ac9">
    <meta property="og:title" content="GiveAway">
    <meta property="og:description" content="">
    <meta property="og:type" content="website">
  </head>
  <body class="u-body u-xl-mode"><header class="u-clearfix u-header u-white u-header" id="Header"><nav class="u-dropdown-icon u-menu u-menu-dropdown u-offcanvas u-menu-1">
        <div class="menu-collapse u-custom-font u-font-montserrat" style="font-size: 1.125rem; letter-spacing: 0px; font-weight: 700;">
          <a class="u-button-style u-custom-active-color u-custom-border-radius u-custom-color u-custom-custom-border-radius u-custom-hover-color u-custom-left-right-menu-spacing u-custom-padding-bottom u-custom-text-active-color u-custom-text-color u-custom-text-hover-color u-custom-top-bottom-menu-spacing u-nav-link u-text-active-palette-1-base u-text-black u-text-hover-palette-2-base" href="#" style="font-size: calc(1em + 6px);">
            <svg class="u-svg-link" viewBox="0 0 24 24"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#menu-hamburger"></use></svg>
            <svg class="u-svg-content" version="1.1" id="menu-hamburger" viewBox="0 0 16 16" x="0px" y="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"><g><rect y="1" width="16" height="2"></rect><rect y="7" width="16" height="2"></rect><rect y="13" width="16" height="2"></rect>
</g></svg>
          </a>
        </div>
        <div class="u-custom-menu u-nav-container">
          <ul class="u-custom-font u-font-montserrat u-nav u-unstyled u-nav-1"><li class="u-nav-item"><a class="u-active-white u-border-1 u-border-active-black u-border-black u-border-hover-black u-border-no-bottom u-border-no-left u-border-no-top u-button-style u-hover-white u-nav-link u-text-active-custom-color-1 u-text-grey-90 u-text-hover-custom-color-1 u-white" href="#AnchorHome" style="padding: 0px 16px;">Home</a>
</li><li class="u-nav-item"><a class="u-active-white u-border-1 u-border-active-black u-border-black u-border-hover-black u-border-no-bottom u-border-no-left u-border-no-top u-button-style u-hover-white u-nav-link u-text-active-custom-color-1 u-text-grey-90 u-text-hover-custom-color-1 u-white" href="index.html#AnchorProject" data-page-id="833520856" style="padding: 0px 16px;">Project</a><div class="u-nav-popup"><ul class="u-border-2 u-border-grey-5 u-custom-font u-font-montserrat u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-nav-2"><li class="u-nav-item"><a class="u-button-style u-nav-link u-white" href="#AnchorRoadmap">Roadmap</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link u-white" href="#AnchorTokenDistribution">Token Distribution</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link u-white" href="#">Sketch Design</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link u-white" href="https://typofx.medium.com/list/plata-litepaper-5636867064c6" target="_blank">Litepaper</a>
</li></ul>
</div>
</li><li class="u-nav-item"><a class="u-active-white u-border-1 u-border-active-black u-border-black u-border-hover-black u-border-no-bottom u-border-no-left u-border-no-top u-button-style u-hover-white u-nav-link u-text-active-custom-color-1 u-text-grey-90 u-text-hover-custom-color-1 u-white" href="#" style="padding: 0px 16px;">Giveaway</a>
</li><li class="u-nav-item"><a class="u-active-white u-border-1 u-border-active-black u-border-black u-border-hover-black u-border-no-bottom u-border-no-left u-border-no-top u-button-style u-hover-white u-nav-link u-text-active-custom-color-1 u-text-grey-90 u-text-hover-custom-color-1 u-white" href="index.html#contact" data-page-id="833520856" style="padding: 0px 16px;">Contact</a><div class="u-nav-popup"><ul class="u-border-2 u-border-grey-5 u-custom-font u-font-montserrat u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-nav-3"><li class="u-nav-item"><a class="u-button-style u-nav-link u-white" href="MeetTheTeam.html">Meet The Team</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link u-white" href="mailto:techsupport@plata.ie?subject=Report%20Bug&amp;cc=uarloque%40live.com">Report Bug</a>
</li></ul>
</div>
</li><li class="u-nav-item"><a class="u-active-white u-border-1 u-border-active-black u-border-black u-border-hover-black u-border-no-bottom u-border-no-left u-border-no-top u-button-style u-hover-white u-nav-link u-text-active-custom-color-1 u-text-grey-90 u-text-hover-custom-color-1 u-white" href="https://www.granna.ie/typofx" target="_blank" style="padding: 0px 16px;">Typo FX</a>
</li><li class="u-nav-item"><a class="u-active-white u-border-1 u-border-active-black u-border-black u-border-hover-black u-border-no-bottom u-border-no-left u-border-no-top u-button-style u-hover-white u-nav-link u-text-active-custom-color-1 u-text-grey-90 u-text-hover-custom-color-1 u-white" href="#" style="padding: 0px 16px;">( ... )</a><div class="u-nav-popup"><ul class="u-border-2 u-border-grey-5 u-custom-font u-font-montserrat u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-nav-4"><li class="u-nav-item"><a class="u-button-style u-nav-link u-white" href="#">English (EN)</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link u-white" href="#">Theme (Light)</a>
</li></ul>
</div>
</li></ul>
        </div>
        <div class="u-custom-menu u-nav-container-collapse">
          <div class="u-container-style u-grey-80 u-inner-container-layout u-opacity u-opacity-95 u-sidenav">
            <div class="u-inner-container-layout u-sidenav-overflow">
              <div class="u-menu-close"></div>
              <ul class="u-align-center u-nav u-popupmenu-items u-unstyled u-nav-5"><li class="u-nav-item"><a class="u-button-style u-nav-link" href="#AnchorHome">Home</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="index.html#AnchorProject" data-page-id="833520856">Project</a><div class="u-nav-popup"><ul class="u-border-2 u-border-grey-5 u-custom-font u-font-montserrat u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-nav-6"><li class="u-nav-item"><a class="u-button-style u-nav-link" href="#AnchorRoadmap">Roadmap</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="#AnchorTokenDistribution">Token Distribution</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="#">Sketch Design</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="https://typofx.medium.com/list/plata-litepaper-5636867064c6" target="_blank">Litepaper</a>
</li></ul>
</div>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="#">Giveaway</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="index.html#contact" data-page-id="833520856">Contact</a><div class="u-nav-popup"><ul class="u-border-2 u-border-grey-5 u-custom-font u-font-montserrat u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-nav-7"><li class="u-nav-item"><a class="u-button-style u-nav-link" href="MeetTheTeam.html">Meet The Team</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="mailto:techsupport@plata.ie?subject=Report%20Bug&amp;cc=uarloque%40live.com">Report Bug</a>
</li></ul>
</div>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="https://www.granna.ie/typofx" target="_blank">Typo FX</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="#">( ... )</a><div class="u-nav-popup"><ul class="u-border-2 u-border-grey-5 u-custom-font u-font-montserrat u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-nav-8"><li class="u-nav-item"><a class="u-button-style u-nav-link" href="#">English (EN)</a>
</li><li class="u-nav-item"><a class="u-button-style u-nav-link" href="#">Theme (Light)</a>
</li></ul>
</div>
</li></ul>
            </div>
          </div>
          <div class="u-grey-90 u-menu-overlay u-opacity u-opacity-70"></div>
        </div>
      </nav><a href="https://www.plata.ie/" class="u-image u-logo u-image-1" data-image-width="199" data-image-height="86" title="plata">
        <img src="images/PlataFont1.SVG" class="u-logo-image u-logo-image-1">
      </a><span class="u-file-icon u-grey-50 u-hover-feature u-icon u-icon-circle u-icon-1" data-href="#"><img src="images/3885840.png" alt=""></span></header>
    <section class="u-clearfix u-hidden-md u-hidden-sm u-hidden-xs u-image u-section-1" id="ClaimDesktop" data-image-width="2180" data-image-height="1320">
      <div class="u-grey-90 u-opacity u-opacity-65 u-shape u-shape-rectangle u-shape-1"></div>
      <div id="carousel-ee08" data-interval="10000" data-u-ride="carousel" class="u-carousel u-slider u-slider-1">
        <ol class="u-carousel-indicators u-opacity u-opacity-55 u-carousel-indicators-1">
          <li data-u-target="#carousel-ee08" class="u-active u-border-1 u-border-white u-shape-circle u-white" data-u-slide-to="0" style="height: 10px; width: 10px;"></li>
          <li data-u-target="#carousel-ee08" class="u-border-1 u-border-white u-shape-circle u-white" data-u-slide-to="1" style="height: 10px; width: 10px;"></li>
        </ol>
        <div class="u-carousel-inner" role="listbox">
          <div class="u-active u-align-left u-carousel-item u-container-style u-shape-rectangle u-slide">
            <div class="u-container-layout u-container-layout-1">
              <h2 class="u-color-scheme-u10 u-color-style-multicolor-1 u-text u-text-white u-text-1">How to claim?</h2>
              <p class="u-large-text u-text u-text-body-alt-color u-text-variant u-text-2"> The process is pretty simple as is.<br>You need to connect your Metamask Wallet to Plata's Website by clicking the wallet icon on the top right over the screen. Plata Logo shows up and clicks on it again.<br>
              </p>
              <a href="https://nicepage.com/css-templates" class="u-border-2 u-border-custom-color-5 u-btn u-btn-round u-button-style u-hover-custom-color-5 u-none u-radius-7 u-text-body-alt-color u-text-hover-white u-btn-1">learn more</a>
            </div>
          </div>
          <div class="u-align-left u-carousel-item u-container-style u-expanded-width u-shape-rectangle u-slide">
            <div class="u-container-layout u-container-layout-2">
              <h2 class="u-align-center-lg u-align-center-md u-align-center-sm u-align-center-xs u-color-scheme-u10 u-color-style-multicolor-1 u-custom-font u-font-montserrat u-text u-text-white u-text-3">50K $PLT Giveaway</h2>
              <p class="u-custom-font u-font-montserrat u-large-text u-text u-text-body-alt-color u-text-default u-text-variant u-text-4"> PLATA DAILY&nbsp;PERFORMANCE</p>
              <p class="u-custom-font u-font-montserrat u-large-text u-text u-text-body-alt-color u-text-variant u-text-5"> Today's Plata price is $0.00000177. Which is up 10% over the last 24 hours. 24-hour PLT volume is $5.2400. It has a market cap rank of 10853 worldwide. According to the Nomics Index Website.</p>
              <a href="https://nomics.com/assets/plt9-plata" class="u-border-2 u-border-custom-color-5 u-btn u-btn-round u-button-style u-custom-font u-font-montserrat u-hover-custom-color-5 u-none u-radius-7 u-text-body-alt-color u-text-hover-white u-btn-2" target="_blank">learn more</a>
            </div>
          </div>
        </div>
        <a class="u-carousel-control u-carousel-control-prev u-spacing-10 u-text-custom-color-4 u-carousel-control-1" href="#carousel-ee08" role="button" data-u-slide="prev">
          <span aria-hidden="true">
            <svg viewBox="0 0 256 256"><g><polygon points="207.093,30.187 176.907,0 48.907,128 176.907,256 207.093,225.813 109.28,128"></polygon>
</g></svg>
          </span>
          <span class="sr-only">
            <svg viewBox="0 0 256 256"><g><polygon points="207.093,30.187 176.907,0 48.907,128 176.907,256 207.093,225.813 109.28,128"></polygon>
</g></svg>
          </span>
        </a>
        <a class="u-carousel-control u-carousel-control-next u-spacing-10 u-text-custom-color-4 u-carousel-control-2" href="#carousel-ee08" role="button" data-u-slide="next">
          <span aria-hidden="true">
            <svg viewBox="0 0 306 306"><g id="chevron-right"><polygon points="94.35,0 58.65,35.7 175.95,153 58.65,270.3 94.35,306 247.35,153"></polygon>
</g></svg>
          </span>
          <span class="sr-only">
            <svg viewBox="0 0 306 306"><g id="chevron-right"><polygon points="94.35,0 58.65,35.7 175.95,153 58.65,270.3 94.35,306 247.35,153"></polygon>
</g></svg>
          </span>
        </a>
      </div>
      <div class="u-list u-list-1">
        <div class="u-repeater u-repeater-1">
          <div class="u-border-11 u-border-custom-color-5 u-border-no-left u-border-no-right u-border-no-top u-container-style u-custom-border u-list-item u-radius-35 u-repeater-item u-shape-round u-white u-list-item-1">
            <div class="u-container-layout u-similar-container u-container-layout-3">
              <img class="u-align-center u-image u-image-default u-image-1" src="images/token.gif" alt="" data-image-width="998" data-image-height="1138">
              <a href="https://nicepage.me" class="u-border-none u-btn u-btn-round u-button-style u-custom-font u-font-montserrat u-grey-40 u-hover-custom-color-5 u-radius-50 u-btn-3">REQUEST PLATA</a>
            </div>
          </div>
        </div>
      </div>
      <a id ="ConnectedWallet" onclick="connectMetamaskPC()" class="u-black u-border-hover-custom-color-5 u-border-none u-btn u-btn-round u-button-style u-custom-font u-font-montserrat u-hover-custom-color-6 u-radius-50 u-text-custom-color-2 u-btn-4">connect</a>
      <img class="u-image u-image-default u-preserve-proportions u-image-2" src="images/polygon3D.png" alt="" data-image-width="202" data-image-height="208">
      <div class="u-border-2 u-border-grey-90 u-image u-image-circle u-preserve-proportions u-image-3" alt="" data-image-width="300" data-image-height="300"></div>
      <div class="u-border-2 u-border-grey-90 u-image u-image-circle u-preserve-proportions u-image-4" alt="" data-image-width="150" data-image-height="150"></div>
    </section>
    <section class="u-clearfix u-hidden-lg u-hidden-xl u-hidden-xs u-image u-valign-middle-xl u-section-2" id="carousel_1ba5" data-image-width="2180" data-image-height="1320">
      <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-grey-90 u-opacity u-opacity-65 u-shape u-shape-rectangle u-shape-1"></div>
      <div id="carousel-ee08" data-interval="10000" data-u-ride="carousel" class="u-carousel u-slider u-slider-1">
        <ol class="u-carousel-indicators u-opacity u-opacity-55 u-carousel-indicators-1">
          <li data-u-target="#carousel-ee08" class="u-active u-border-1 u-border-white u-shape-circle u-white" data-u-slide-to="0" style="height: 10px; width: 10px;"></li>
          <li data-u-target="#carousel-ee08" class="u-border-1 u-border-white u-shape-circle u-white" data-u-slide-to="1" style="height: 10px; width: 10px;"></li>
        </ol>
        <div class="u-carousel-inner" role="listbox">
          <div class="u-active u-align-left u-carousel-item u-container-style u-shape-rectangle u-slide">
            <div class="u-container-layout u-container-layout-1">
              <h2 class="u-align-center-md u-align-center-sm u-align-center-xs u-color-scheme-u10 u-color-style-multicolor-1 u-custom-font u-text u-text-white u-text-1">How to claim?</h2>
              <p class="u-large-text u-text u-text-body-alt-color u-text-variant u-text-2"> The process is pretty simple as is.<br>You need to connect your Web3 Wallet to Plata's Website by clicking the wallet icon on the top right over the screen. Plata Logo shows up and clicks on it again.<br>
              </p>
              <a href="https://nicepage.com/css-templates" class="u-border-2 u-border-custom-color-5 u-btn u-btn-round u-button-style u-hover-custom-color-5 u-none u-radius-7 u-text-body-alt-color u-text-hover-white u-btn-1">learn more</a>
            </div>
          </div>
          <div class="u-align-left u-carousel-item u-container-style u-expanded-width u-shape-rectangle u-slide">
            <div class="u-container-layout u-container-layout-2">
              <h2 class="u-align-center-lg u-align-center-md u-align-center-sm u-align-center-xs u-color-scheme-u10 u-color-style-multicolor-1 u-custom-font u-font-montserrat u-text u-text-white u-text-3">50K $PLT Giveaway</h2>
              <p class="u-custom-font u-font-montserrat u-large-text u-text u-text-body-alt-color u-text-default u-text-variant u-text-4"> PLATA DAILY&nbsp;PERFORMANCE</p>
              <p class="u-custom-font u-font-montserrat u-large-text u-text u-text-body-alt-color u-text-variant u-text-5"> Today's Plata price is $0.00000177. Which is up 10% over the last 24 hours. 24-hour PLT volume is $5.2400. It has a market cap rank of 10853 worldwide. According to the Nomics Index Website.</p>
              <a href="https://nomics.com/assets/plt9-plata" class="u-align-center-sm u-align-center-xs u-border-2 u-border-custom-color-5 u-btn u-btn-round u-button-style u-custom-font u-font-montserrat u-hover-custom-color-5 u-none u-radius-7 u-text-body-alt-color u-text-hover-white u-btn-2" target="_blank">learn more</a>
            </div>
          </div>
        </div>
        <a class="u-carousel-control u-carousel-control-prev u-spacing-10 u-text-custom-color-4 u-carousel-control-1" href="#carousel-ee08" role="button" data-u-slide="prev">
          <span aria-hidden="true">
            <svg viewBox="0 0 256 256"><g><polygon points="207.093,30.187 176.907,0 48.907,128 176.907,256 207.093,225.813 109.28,128"></polygon>
</g></svg>
          </span>
          <span class="sr-only">
            <svg viewBox="0 0 256 256"><g><polygon points="207.093,30.187 176.907,0 48.907,128 176.907,256 207.093,225.813 109.28,128"></polygon>
</g></svg>
          </span>
        </a>
        <a class="u-carousel-control u-carousel-control-next u-spacing-10 u-text-custom-color-4 u-carousel-control-2" href="#carousel-ee08" role="button" data-u-slide="next">
          <span aria-hidden="true">
            <svg viewBox="0 0 306 306"><g id="chevron-right"><polygon points="94.35,0 58.65,35.7 175.95,153 58.65,270.3 94.35,306 247.35,153"></polygon>
</g></svg>
          </span>
          <span class="sr-only">
            <svg viewBox="0 0 306 306"><g id="chevron-right"><polygon points="94.35,0 58.65,35.7 175.95,153 58.65,270.3 94.35,306 247.35,153"></polygon>
</g></svg>
          </span>
        </a>
      </div>
      <div class="u-list u-list-1">
        <div class="u-repeater u-repeater-1">
          <div class="u-border-2 u-border-grey-80 u-container-style u-custom-border u-list-item u-radius-35 u-repeater-item u-shape-round u-white u-list-item-1">
            <div class="u-container-layout u-similar-container u-valign-middle-md u-valign-middle-sm u-valign-middle-xs u-container-layout-3">
              <img class="u-align-center u-image u-image-default u-preserve-proportions u-image-1" src="images/token.gif" alt="" data-image-width="998" data-image-height="1138">
              <a href="https://nicepage.me" class="u-border-none u-btn u-btn-round u-button-style u-custom-font u-font-montserrat u-grey-40 u-hover-custom-color-5 u-radius-50 u-btn-3">REQUEST PLATA</a>
            </div>
          </div>
        </div>
      </div>
      <a href="https://nicepage.com/k/aesthetic-html-templates" class="u-black u-border-hover-custom-color-5 u-border-none u-btn u-btn-round u-button-style u-custom-font u-font-montserrat u-hover-custom-color-6 u-radius-50 u-text-custom-color-2 u-btn-4">connect</a>
      <img class="u-image u-image-default u-preserve-proportions u-image-2" src="images/polygon3D.png" alt="" data-image-width="202" data-image-height="208">
      <div class="u-border-2 u-border-grey-90 u-image u-image-circle u-preserve-proportions u-image-3" alt="" data-image-width="300" data-image-height="300"></div>
      <div class="u-border-2 u-border-grey-90 u-image u-image-circle u-preserve-proportions u-image-4" alt="" data-image-width="150" data-image-height="150"></div>
    </section>
    <section class="u-clearfix u-hidden-lg u-hidden-md u-hidden-sm u-hidden-xl u-image u-section-3" id="ClaimMobile" data-image-width="2180" data-image-height="1320">
      <div class="u-align-center-xs u-expanded-width-xs u-grey-90 u-opacity u-opacity-65 u-shape u-shape-rectangle u-shape-1"></div>
      <div id="carousel-17dd" data-interval="10000" data-u-ride="carousel" class="u-carousel u-slider u-slider-1">
        <ol class="u-carousel-indicators u-opacity u-opacity-55 u-carousel-indicators-1">
          <li data-u-target="#carousel-17dd" class="u-active u-active-white u-custom-color-1 u-shape-circle" data-u-slide-to="0" style="height: 10px; width: 10px;"></li>
          <li data-u-target="#carousel-17dd" class="u-active-white u-custom-color-1 u-shape-circle" data-u-slide-to="1" style="height: 10px; width: 10px;"></li>
        </ol>
        <div class="u-carousel-inner" role="listbox">
          <div class="u-active u-align-left-lg u-align-left-md u-align-left-sm u-align-left-xl u-carousel-item u-container-style u-shape-rectangle u-slide">
            <div class="u-container-layout u-valign-top-lg u-valign-top-md u-valign-top-sm u-valign-top-xl u-container-layout-1">
              <h2 class="u-align-center-xs u-color-scheme-u10 u-color-style-multicolor-1 u-text u-text-white u-text-1">How to claim?</h2>
              <p class="u-align-center-xs u-large-text u-text u-text-body-alt-color u-text-variant u-text-2"> The process is pretty simple as is.<br>You need to connect your Metamask Wallet to Plata's Website by clicking the wallet icon on the top right over the screen. Plata Logo shows up and clicks on it again.<br>
                <br>
                <br>
                <br>
              </p>
              <a href="https://nicepage.com/css-templates" class="u-border-2 u-border-custom-color-1 u-btn u-btn-round u-button-style u-hover-custom-color-1 u-none u-radius-7 u-text-body-alt-color u-text-hover-white u-btn-1">learn more</a>
            </div>
          </div>
          <div class="u-align-left-lg u-align-left-md u-align-left-sm u-align-left-xl u-carousel-item u-container-style u-expanded-width-lg u-expanded-width-md u-expanded-width-sm u-expanded-width-xl u-shape-rectangle u-slide">
            <div class="u-container-layout u-container-layout-2">
              <h2 class="u-align-center-xs u-color-scheme-u10 u-color-style-multicolor-1 u-custom-font u-font-montserrat u-text u-text-default-xs u-text-white u-text-3">50K $PLT Giveaway</h2>
              <p class="u-align-center-xs u-custom-font u-font-montserrat u-large-text u-text u-text-body-alt-color u-text-default u-text-variant u-text-4"> PLATA DAILY&nbsp;PERFORMANCE</p>
              <p class="u-align-center-xs u-custom-font u-font-montserrat u-large-text u-text u-text-body-alt-color u-text-variant u-text-5"> Today's Plata price is $0.00000177. Which is up 10% over the last 24 hours. 24-hour PLT volume is $5.2400. It has a market cap rank of 10853 worldwide. According to the Nomics Index Website.</p>
              <a href="https://nomics.com/assets/plt8-plata" class="u-align-center-xs u-border-2 u-border-custom-color-1 u-btn u-btn-round u-button-style u-custom-font u-font-montserrat u-hover-custom-color-1 u-none u-radius-7 u-text-body-alt-color u-text-hover-white u-btn-2" target="_blank">learn more</a>
            </div>
          </div>
        </div>
        <a class="u-absolute-vcenter-lg u-absolute-vcenter-md u-absolute-vcenter-sm u-absolute-vcenter-xl u-carousel-control u-carousel-control-prev u-spacing-10 u-text-custom-color-2 u-carousel-control-1" href="#carousel-17dd" role="button" data-u-slide="prev">
          <span aria-hidden="true">
            <svg viewBox="0 0 256 256"><g><polygon points="207.093,30.187 176.907,0 48.907,128 176.907,256 207.093,225.813 109.28,128"></polygon>
</g></svg>
          </span>
          <span class="sr-only">
            <svg viewBox="0 0 256 256"><g><polygon points="207.093,30.187 176.907,0 48.907,128 176.907,256 207.093,225.813 109.28,128"></polygon>
</g></svg>
          </span>
        </a>
        <a class="u-absolute-vcenter-lg u-absolute-vcenter-md u-absolute-vcenter-sm u-absolute-vcenter-xl u-carousel-control u-carousel-control-next u-spacing-10 u-text-custom-color-2 u-carousel-control-2" href="#carousel-17dd" role="button" data-u-slide="next">
          <span aria-hidden="true">
            <svg viewBox="0 0 306 306"><g id="chevron-right"><polygon points="94.35,0 58.65,35.7 175.95,153 58.65,270.3 94.35,306 247.35,153"></polygon>
</g></svg>
          </span>
          <span class="sr-only">
            <svg viewBox="0 0 306 306"><g id="chevron-right"><polygon points="94.35,0 58.65,35.7 175.95,153 58.65,270.3 94.35,306 247.35,153"></polygon>
</g></svg>
          </span>
        </a>
      </div>
      <div class="u-align-center-xs u-list u-list-1">
        <div class="u-repeater u-repeater-1">
          <div class="u-border-2 u-border-grey-75 u-container-style u-custom-border u-list-item u-radius-35 u-repeater-item u-shape-round u-white u-list-item-1">
            <div class="u-container-layout u-similar-container u-container-layout-3">
              <img class="u-align-center u-image u-image-default u-image-1" src="images/token.gif" alt="" data-image-width="998" data-image-height="1138">
              <a href="https://nicepage.me" class="u-btn u-btn-round u-button-style u-custom-font u-font-montserrat u-grey-40 u-hover-custom-color-1 u-radius-50 u-btn-3">REQUEST PLATA</a>
            </div>
          </div>
        </div>
      </div>
      <a href="https://nicepage.com/k/aesthetic-html-templates" class="u-border-hover-custom-color-5 u-border-none u-btn u-btn-round u-button-style u-custom-font u-font-montserrat u-grey-90 u-hover-custom-color-6 u-radius-50 u-text-custom-color-2 u-text-hover-custom-color-2 u-btn-4">connect</a>
      <div class="u-border-2 u-border-grey-90 u-image u-image-circle u-preserve-proportions u-image-2" alt="" data-image-width="300" data-image-height="300"></div>
      <div class="u-border-2 u-border-grey-90 u-image u-image-circle u-preserve-proportions u-image-3" alt="" data-image-width="150" data-image-height="150"></div>
    </section>
    
    
    
    <footer class="u-align-center-md u-align-center-sm u-align-center-xs u-black u-clearfix u-footer" id="footer"><div class="u-clearfix u-sheet u-valign-middle-xs u-sheet-1">
        <a href="/typofx/" class="u-image u-logo u-image-1" data-image-width="190" data-image-height="139" title="Typo FX" target="_blank">
          <img src="images/TypoFXLogo021.SVG" class="u-logo-image u-logo-image-1">
        </a>
        <div class="u-align-center-lg u-align-center-md u-align-center-sm u-align-center-xs u-align-left-xl u-social-icons u-spacing-7 u-social-icons-1">
          <a class="u-social-url" target="_blank" data-type="Facebook" title="Facebook" href="https://www.facebook.com/TypoFX/"><span class="u-icon u-social-facebook u-social-icon u-text-grey-75 u-icon-1"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-5c05"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-5c05"><circle fill="currentColor" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M73.5,31.6h-9.1c-1.4,0-3.6,0.8-3.6,3.9v8.5h12.6L72,58.3H60.8v40.8H43.9V58.3h-8V43.9h8v-9.2
            c0-6.7,3.1-17,17-17h12.5v13.9H73.5z"></path></svg></span>
          </a>
          <a class="u-social-url" target="_blank" data-type="Instagram" title="Instagram" href="https://www.instagram.com/typofx/"><span class="u-icon u-social-icon u-social-instagram u-text-grey-75 u-icon-2"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-1c50"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-1c50"><circle fill="currentColor" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M55.9,38.2c-9.9,0-17.9,8-17.9,17.9C38,66,46,74,55.9,74c9.9,0,17.9-8,17.9-17.9C73.8,46.2,65.8,38.2,55.9,38.2
            z M55.9,66.4c-5.7,0-10.3-4.6-10.3-10.3c-0.1-5.7,4.6-10.3,10.3-10.3c5.7,0,10.3,4.6,10.3,10.3C66.2,61.8,61.6,66.4,55.9,66.4z"></path><path fill="#FFFFFF" d="M74.3,33.5c-2.3,0-4.2,1.9-4.2,4.2s1.9,4.2,4.2,4.2s4.2-1.9,4.2-4.2S76.6,33.5,74.3,33.5z"></path><path fill="#FFFFFF" d="M73.1,21.3H38.6c-9.7,0-17.5,7.9-17.5,17.5v34.5c0,9.7,7.9,17.6,17.5,17.6h34.5c9.7,0,17.5-7.9,17.5-17.5V38.8
            C90.6,29.1,82.7,21.3,73.1,21.3z M83,73.3c0,5.5-4.5,9.9-9.9,9.9H38.6c-5.5,0-9.9-4.5-9.9-9.9V38.8c0-5.5,4.5-9.9,9.9-9.9h34.5
            c5.5,0,9.9,4.5,9.9,9.9V73.3z"></path></svg></span>
          </a>
          <a class="u-social-url" target="_blank" data-type="Whatsapp" title="Whatsapp" href="https://chat.whatsapp.com/EWnfnZ2zyA28opVHSg4ryO"><span class="u-icon u-social-icon u-social-whatsapp u-text-grey-75 u-icon-3"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-528e"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-528e"><circle fill="currentColor" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M83.8,28.3C77.2,21.7,68.4,18,59,18c-19.3,0-35.1,15.7-35.1,35.1c0,6.2,1.6,12.2,4.7,17.5l-5,18.2L42.2,84
	c5.1,2.8,10.9,4.3,16.8,4.3h0l0,0c19.3,0,35.1-15.7,35.1-35.1C94.1,43.8,90.5,35,83.8,28.3 M59,82.3L59,82.3
	c-5.2,0-10.4-1.4-14.9-4.1l-1.1-0.6l-11,2.9L35,69.8l-0.7-1.1c-2.9-4.6-4.5-10-4.5-15.5C29.8,37,42.9,24,59,24
	c7.8,0,15.1,3,20.6,8.6c5.5,5.5,8.5,12.8,8.5,20.6C88.2,69.2,75.1,82.3,59,82.3 M75,60.5c-0.9-0.4-5.2-2.6-6-2.9
	c-0.8-0.3-1.4-0.4-2,0.4s-2.3,2.9-2.8,3.4c-0.5,0.6-1,0.7-1.9,0.2c-0.9-0.4-3.7-1.4-7.1-4.4c-2.6-2.3-4.4-5.2-4.9-6.1
	c-0.5-0.9-0.1-1.4,0.4-1.8c0.4-0.4,0.9-1,1.3-1.5c0.4-0.5,0.6-0.9,0.9-1.5c0.3-0.6,0.1-1.1-0.1-1.5c-0.2-0.4-2-4.8-2.7-6.5
	c-0.7-1.7-1.4-1.5-2-1.5c-0.5,0-1.1,0-1.7,0c-0.6,0-1.5,0.2-2.3,1.1c-0.8,0.9-3.1,3-3.1,7.3c0,4.3,3.1,8.5,3.6,9.1
	c0.4,0.6,6.2,9.4,15,13.2c2.1,0.9,3.7,1.4,5,1.8c2.1,0.7,4,0.6,5.5,0.3c1.7-0.3,5.2-2.1,5.9-4.2c0.7-2,0.7-3.8,0.5-4.2
	C76.5,61.1,75.9,60.9,75,60.5"></path></svg></span>
          </a>
          <a class="u-social-url" target="_blank" data-type="Telegram" title="Telegram" href="https://t.me/joinchat/UIJNdhJGcYc0ZTI0"><span class="u-icon u-social-icon u-social-telegram u-text-grey-75 u-icon-4"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-6249"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-6249"><circle fill="currentColor" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M18.4,53.2l64.7-24.9c3-1.1,5.6,0.7,4.7,5.3l0,0l-11,51.8c-0.8,3.7-3,4.6-6.1,2.8L53.9,75.8l-8.1,7.8
	c-0.9,0.9-1.7,1.6-3.4,1.6l1.2-17l31.1-28c1.4-1.2-0.3-1.9-2.1-0.7L34.2,63.7l-16.6-5.2C14,57.4,14,54.9,18.4,53.2L18.4,53.2z"></path></svg></span>
          </a>
          <a class="u-social-url" target="_blank" data-type="Github" title="Github" href="https://github.com/typofx/plata"><span class="u-icon u-social-github u-social-icon u-text-grey-75 u-icon-5"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-e3b7"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-e3b7"><circle fill="currentColor" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M88,51.3c0-5.5-1.9-10.2-5.3-13.7c0.6-1.3,2.3-6.5-0.5-13.5c0,0-4.2-1.4-14,5.3c-4.1-1.1-8.4-1.7-12.7-1.8
	c-4.3,0-8.7,0.6-12.7,1.8c-9.7-6.6-14-5.3-14-5.3c-2.8,7-1,12.2-0.5,13.5C25,41.2,23,45.7,23,51.3c0,19.6,11.9,23.9,23.3,25.2
	c-1.5,1.3-2.8,3.5-3.2,6.8c-3,1.3-10.2,3.6-14.9-4.3c0,0-2.7-4.9-7.8-5.3c0,0-5-0.1-0.4,3.1c0,0,3.3,1.6,5.6,7.5c0,0,3,9.1,17.2,6
	c0,4.3,0.1,8.3,0.1,9.5h25.2c0-1.7,0.1-7.2,0.1-14c0-4.7-1.7-7.9-3.4-9.4C76,75.2,88,70.9,88,51.3z"></path></svg></span>
          </a>
        </div>
        <p class="u-align-center-lg u-align-center-md u-align-center-xl u-align-center-xs u-text u-text-default-lg u-text-default-md u-text-default-sm u-text-default-xs u-text-1"> © 2022 - ​Typo FX</p>
      </div></footer><section class="u-align-center u-clearfix u-cookies-consent u-grey-80 u-cookies-consent" id="sec-f314">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
          <div class="u-gutter-0 u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-left-cell u-size-43-md u-size-43-sm u-size-43-xs u-size-46-lg u-size-46-xl u-layout-cell-1">
                <div class="u-container-layout u-valign-middle u-container-layout-1">
                  <h3 class="u-text u-text-default u-text-1">Cookies &amp; Privacy</h3>
                  <p class="u-text u-text-2">This website uses cookies to ensure you get the best experience on our website.</p>
                </div>
              </div>
              <div class="u-align-left u-container-style u-layout-cell u-right-cell u-size-14-lg u-size-14-xl u-size-17-md u-size-17-sm u-size-17-xs u-layout-cell-2">
                <div class="u-container-layout u-valign-middle-lg u-valign-middle-md u-valign-middle-xl u-valign-top-sm u-valign-top-xs u-container-layout-2">
                  <a href="###" class="u-btn u-button-confirm u-button-style u-palette-1-base u-btn-1">Confirm</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <style> .u-cookies-consent {
  background-image: none;
}

.u-cookies-consent .u-sheet-1 {
  min-height: 212px;
}

.u-cookies-consent .u-layout-wrap-1 {
  margin-top: 30px;
  margin-bottom: 30px;
}

.u-cookies-consent .u-layout-cell-1 {
  min-height: 152px;
}

.u-cookies-consent .u-container-layout-1 {
  padding: 30px 60px;
}

.u-cookies-consent .u-text-1 {
  margin-top: 0;
  margin-right: 20px;
  margin-bottom: 0;
}

.u-cookies-consent .u-text-2 {
  margin: 8px 158px 0 0;
}

.u-cookies-consent .u-layout-cell-2 {
  min-height: 152px;
}

.u-cookies-consent .u-container-layout-2 {
  padding: 30px;
}

.u-cookies-consent .u-btn-1 {
  margin: 0 auto 0 0;
}

@media (max-width: 991px) {
  .u-cookies-consent .u-sheet-1 {
    min-height: 187px;
  }

  .u-cookies-consent .u-layout-cell-1 {
    min-height: 100px;
  }

  .u-cookies-consent .u-container-layout-1 {
    padding-left: 30px;
    padding-right: 30px;
  }

  .u-cookies-consent .u-text-2 {
    margin-right: 13px;
  }

  .u-cookies-consent .u-layout-cell-2 {
    min-height: 100px;
  }
}

@media (max-width: 767px) {
  .u-cookies-consent .u-sheet-1 {
    min-height: 225px;
  }

  .u-cookies-consent .u-layout-cell-1 {
    min-height: 154px;
  }

  .u-cookies-consent .u-container-layout-1 {
    padding-left: 10px;
    padding-right: 10px;
    padding-bottom: 20px;
  }

  .u-cookies-consent .u-layout-cell-2 {
    min-height: 65px;
  }

  .u-cookies-consent .u-container-layout-2 {
    padding: 10px;
  }
}

@media (max-width: 575px) {
  .u-cookies-consent .u-sheet-1 {
    min-height: 121px;
  }

  .u-cookies-consent .u-layout-cell-1 {
    min-height: 100px;
  }

  .u-cookies-consent .u-text-2 {
    margin-right: 0;
  }

  .u-cookies-consent .u-layout-cell-2 {
    min-height: 15px;
  }
}</style></section>



<body>
    

    
  <button onclick="connectMetamaskPC()">Metamask</button><br>  
  <button onclick="connectWC()">Wallet Connect</button><br>
  <br>
  <br>
  <button onclick="changeNetworkToPolygon()">Change Network</button><br>
  <button onclick="addPlata()">Add Plata</button><br>
  <button onclick="requestEtherWORKING()">Claim Metamask</button><br>
  <button onclick="requestPlataWalletConnect()">Claim WalletConnect</button><br>
  <button onclick="sign-message()">Contract Test</button><br>
  <br>
  <button onclick="disconnect()">Disconnect</button><br>
  <br>
  
  
  
  <a id="NetNetWork">(X)</a><br>
  <a id="ConnectedWallet">Disconnected</a>


  <script type="text/javascript">
    var account;

    // https://docs.walletconnect.com/quick-start/dapps/web3-provider
    var provider = new WalletConnectProvider.default({
      rpc: {
          1: "https://mainnet.mycustomnode.com",
        137: "https://polygon-rpc.com/", // https://docs.polygon.technology/docs/develop/network-details/network/
        // ...

      },
      bridge: 'https://bridge.walletconnect.org',
    });


    var connectWC = async () => {

      await provider.enable();
      const web3 = new Web3(provider);
      window.w3 = web3;


      var accounts  = await web3.eth.getAccounts(); // get all connected accounts
      account = accounts[0]; // get the primary account
      document.getElementById("ConnectedWallet").innerText = accounts[0];
      
      
      const chainId = await web3.eth.getChainId();
      
      if (chainId != 137) 
      {
          alert ("Wrong Chain, please select POLYGON (137) ");
          disconnect();
      }
      
      document.getElementById("NetNetWork").innerText = "Chain id: " + chainId;

    }


    var sign = async (msg) => {
      if (w3) {
        return await w3.eth.personal.sign(msg, account);
      } else {
        return false;
      }
    }

    var contract = async (abi, address) => {
      if (w3) {
        return new w3.eth.Contract(abi, address);
      } else {
        return false;
      }
    }

    var disconnect = async () => {
      // Close provider session
      await provider.disconnect();
      document.getElementById("ConnectedWallet").innerText = "disconnected";
      document.getElementById("NetNetWork").innerText = "";
    }


    var address = "0x4b4f8ca8FB3e66B5DdAfCEbFE86312cEC486DAE1";
    var abi = [{"inputs":[],"name":"count","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"increment","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"nonpayable","type":"function"}];
    
    var addressGiveAway = "0x40955A82Ef6fBe989D9ce9393E80A4dD8c93fd0B";
    var abiGiveAway = [{"inputs":[],"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"address","name":"_from","type":"address"},{"indexed":false,"internalType":"uint256","name":"_amount","type":"uint256"}],"name":"TransferReceived","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"address","name":"_from","type":"address"},{"indexed":false,"internalType":"address","name":"_destAddr","type":"address"},{"indexed":false,"internalType":"uint256","name":"_amount","type":"uint256"}],"name":"TransferSent","type":"event"},{"inputs":[{"internalType":"contract IERC20","name":"token","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"WithdrawTotal","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"balance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"endGiveaway","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"contract IERC20","name":"token","type":"address"},{"internalType":"address","name":"to","type":"address"}],"name":"giveAwayERC20","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"_account","type":"address"}],"name":"setExcludeFromBlackList","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"_account","type":"address"}],"name":"setIncludeToBlackList","outputs":[],"stateMutability":"nonpayable","type":"function"},{"stateMutability":"payable","type":"receive"}];


  </script>

    <script>
    
    let PolygonWallet;
    let accounts;
    
window.onload= () => {
  if(window.ethereum) {
     document.getElementById("NetNetWork").innerText = "MetaMask Detected";
     web3 = new Web3(new Web3.providers.HttpProvider('http://localhost:8545'));
     web3 = new Web3(window.ethereum);
  }
  else {}
     //document.getElementById("NetNetWork").innerText = "MetaMask not detected";
}



           function requestEtherWORKING(){
            
            new Promise((resolve, reject) => {
                getAccounts(function(result) {
                    const Faucet = new web3.eth.Contract(abiGiveAway, addressGiveAway);
                    
                    Faucet.methods.giveAwayERC20("0xc298812164bd558268f51cc6e3b8b5daaf0b6341" , document.getElementById("ConnectedWallet").innerText ).send({from:result[0]},function (error, result){
                        if(!error){
                            console.log(result);
                        }else{
                            console.log(error);
                        }
                    });
                });
                resolve();
            });
            
        }
   
   
        var requestPlataWalletConnect = async () =>
            {
                const token = String("0xc298812164bd558268f51cc6e3b8b5daaf0b6341");
                conn = new w3.eth.Contract(abiGiveAway, addressGiveAway);
                await conn.methods.giveAwayERC20(token,account).send({from:account});
            }
   
        var changeNetworkToPolygonWalletConnect = async () =>
            {

    await w3.eth
  .request({
    method: "wallet_addEthereumChain",
    params: [
        { chainId: "0x89",
          chainName: "Polygon Mainnet",
          rpcUrls:[  "https://polygon-rpc.com"],
          blockExplorerUrls: ["https://polygonscan.com/"],
          
          nativeCurrency: {
                        name: "MATIC",
                        symbol: "MATIC",
                        decimals: 18,
                      },
        }   ],
  })
  .catch(() => {});


            }
   
   
        function getAccounts(callback) {
            web3.eth.getAccounts((error,result) => {
                if (error) {
                    console.log(error);
                } else {
                    callback(result);
                }
            });
        }
        
const addPlata = async() => {
await window.ethereum

  .request({
    method: 'wallet_watchAsset',
    params: {
      type: 'ERC20',
      options: {
        address: '0xc298812164bd558268f51cc6e3b8b5daaf0b6341',
        symbol: 'PLT',
        decimals: 4,
        image: 'https://www.plata.ie/PlataImage.svg',
      },
    },
  })
  .then((success) => {
    if (success) {
      console.log('PLATA successfully added to wallet!');
    } else {
      throw new Error('Something went wrong.');
    }
  })
  .catch(console.error);
}

const changeNetworkToPolygon = async() => {
await window.ethereum
  .request({
    method: "wallet_addEthereumChain",
    params: [
        { chainId: "0x89",
          chainName: "Polygon Mainnet",
          rpcUrls:[  "https://polygon-rpc.com"],
          blockExplorerUrls: ["https://polygonscan.com/"],
          
          nativeCurrency: {
                        name: "MATIC",
                        symbol: "MATIC",
                        decimals: 18,
                      },
        }   ],
  })
  .catch(() => {});
}

//const getAccounts = async () =>  await window.ethereum.request({method: 'eth_accounts'})[0] || false;

    const connectMetamaskPC = async() => {
        accounts = await window.ethereum.request({method : 'eth_requestAccounts'}).catch((err) => {
   })
    document.getElementById("NetNetWork").innerText = "Polygon Network: ";

    changeNetworkToPolygon();
    let PolygonWallet = accounts[0];
    document.getElementById("ConnectedWallet").innerText = accounts[0];
    console.log(PolygonWallet);
    <?php $hash = "document.getElementById('ConnectedWallet').innerText" ?>;
    console.log (<?php "result:". $hash ?>);
    }
        
        
    </script>

<!-- The Modal -->
<div id="ModalWallet" class="prevent-select modal">

  <!-- Modal content -->
  <div class="prevent-select modal-content">
    <span class="prevent-select close">&times;</span>
    <p>Select Wallet</p>
	<button onclick = "closeDown(),connectMetamaskPC()" id="BtnMetamask">MetaMask</button><br><br>
	<button onclick = "closeDown(),connectWC()" id="BtnWalletConnect">WalletConnect</button><br>
  </div>
</div>

<script>
// Get the modal
var modal = document.getElementById("ModalWallet");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
function displayBlock() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

function closeDown() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

<form method="post" action="hash.php"> 


Resultado:  <input type="text" value="<?php echo $collect1.$es.$collect2;?>"/><hr/>
<input type="submit" name="submit" value="Submit">  
</form>


</body></html>