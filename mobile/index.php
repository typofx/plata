<?php
    $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

    if ($language != 'en') {
        $redirectLink = 'https://www.plata.ie/'.$language.'/mobile/';
        echo '<script type="text/javascript"> window.location.replace('.$redirectLink.'); </script>';
    }

?>

<!DOCTYPE html>
<html lang="en">
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

<body>
    
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/languages/languages.php';?>
    
    <?php include 'header.php';?>
    <?php include 'main.php';?>
    <?php include 'price.php';?>
    <?php include 'project.php';?>
    <?php include 'listing.php';?>
    <br>
    <?php include 'roadmap.php';?>
    <?php include 'adcard.php';?>
    <?php include 'wallets.php';?>
    
    <?php include 'footer.php';?>
    
    <div id="myModal" class="modal">
    <div class="modal-contentt">
        <center>
            <p id="modal-messageLine1"/>
            <!--<p id="modal-messageLine2"/>-->
        </center>
    
    </div>
    </div>

    
<!--<div style="opacity:0;"><input type="text" name="mobile" id="mobile" value="0xc298812164bd558268f51cc6e3b8b5daaf0b6341" readonly/></div>-->
</body>
