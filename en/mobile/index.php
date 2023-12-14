<?php

    $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

    if ($language != 'en') {
        $redirectLink = 'https://www.plata.ie/'.$language.'/mobile/';
        echo '<script type="text/javascript"> window.location.replace('.$redirectLink.'); </script>';
    }

if (isset($_COOKIE['appearance'])) $appearance = $_COOKIE['appearance'];

    if (isset($_POST['change_appearance'])) {
        $appearance = $_POST['change_appearance'];
        setcookie('appearance', $appearance, time() + (86400 * 30), '/', '', true, true); // Definir cookie por 30 dias 
    }

    if ($appearance === 'off') {
        $backgroundColor = "#222222";
        $textColor = "white";
        $plataFont = "https://www.plata.ie/images/plata-font-gray.svg";
        $headerIconCalc = "https://www.plata.ie/images/header-icon-calc-gray.svg";
        $headerIconTrolley = "https://www.plata.ie/images/header-icon-trolley-gray.svg";
        $headerIconBurger = "https://www.plata.ie/images/header-icon-hamburger-gray.svg";
        $adCard = "https://www.plata.ie/images/buy-card-dark.svg";
        $txtSandMenuAtributte = "(Dark)";
        $txtSandMenuFullTheme = $txtSandMenuTheme ." ". $txtSandMenuAtributte;
        $classModal = "modaldark";
        $classInativeButton = "inative-darkmode";
    } else {
        $plataFont = "https://www.plata.ie/images/plata-font-original.svg";
        $headerIconCalc = "https://www.plata.ie/images/header-icon-calc.svg";
        $headerIconTrolley = "https://www.plata.ie/images/header-icon-trolley.svg";
        $headerIconBurger = "https://www.plata.ie/images/header-icon-hamburger.svg";
        $adCard = "https://www.plata.ie/images/buy-card.svg";
        $txtSandMenuAtributte = "(Light)";
        $txtSandMenuFullTheme = $txtSandMenuTheme . " ". $txtSandMenuAtributte;
        $classModal = "modalcontent";
        $classInativeButton = "inative";
    }

?>

<!DOCTYPE html>
<html lang="en">
    
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-RXYGWW7KHB"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-RXYGWW7KHB');
</script>
    
<head>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta charset="utf-8">
    <meta name="keywords" content="Base Information, ​Countdown $PLT Airdrop ends in, The Project, Do you need more information?, The Roadmap, Meet The Team, ​Best Wallets For $PLT Plata">
    <meta name="description" content="">
    
    <title>$PLT Plata Token for ACTM</title>

    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-index-style.css" media="screen">
    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-header-style.css" media="screen">
    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-sand-menu.css" media="screen">
    
    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-main-style.css" media="screen">
    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-button-style.css" media="screen">

    <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-listing-style.css" media="screen">

    <script class="u-script" type="text/javascript" src="https://www.plata.ie/copyContract.js"></script>

<body style="background-color: <?php echo $backgroundColor; ?>; color: <?php echo $textColor; ?>">
    
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/languages/languages.php';?>
    
    <?php include 'header.php';?>
    <?php include 'main.php';?>
    <?php include 'price.php';?>
    <?php include 'project.php';?>
    <?php include 'video.php';?>
    <?php include 'listing.php';?>
    <br>
    <?php include 'roadmap.php';?>
    <?php include 'split.php';?>
    <?php include 'adcard.php';?>
    <?php include 'wallets.php';?>
    <?php include 'footer.php';?>
    
    

    <div id="myModal" class="modalbackground">
    <div id="ModalContent" class="<?php echo $classModal;?>">
        <center>
            <p id="modal-messageLine1"/>
            <!--<p id="modal-messageLine2"/>-->
        </center>
    
    </div>
    </div>

<!--<div style="opacity:0;"><input type="text" name="mobile" id="mobile" value="0xc298812164bd558268f51cc6e3b8b5daaf0b6341" readonly/></div>-->
</body>