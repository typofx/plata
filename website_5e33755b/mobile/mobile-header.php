<?php
session_start();

if (isset($_POST['change_appearance'])) {
    $_SESSION['theme'] = $_POST['change_appearance'] == 'off' ? 'dark-mode' : 'light-mode';
}

$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light-mode';

$themeLabel = ($theme == 'dark-mode') ? 'Dark' : 'Light';
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/../languages/languages.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/../en/mobile/cookies.php'; ?>
<!-- <link rel="stylesheet" href="https://www.plata.ie/en/mobile/mobile-header-style.css" media="screen"> -->
<link rel="stylesheet" href="mobile-header-style.css" media="screen">
<link rel="stylesheet" href="mobile-sand-menu.css" media="screen">

<div id="menu-sandwich">

    <input type="checkbox" id="menu-toggle">

    <table class="tableBarMenu">
        <td class="td-plata-font"><a href="https://www.plata.ie/"><img id="plata-font" class="plata-font"
                    src="<?php echo $plataFont ?>"></a></td>
        <td class="td-menu-icons">
            <table class="tableIconMenu">
                <td><a href="https://www.plata.ie/en/mobile/calc/" target="_blank"><img id="header-icon-calc"
                            class="img-menu" src="<?php echo $headerIconCalc ?>"></a></td>
                <td><a href="https://www.plata.ie/en/mobile/select/"><img id="header-icon-trolley" class="img-menu"
                            src="<?php echo $headerIconTrolley ?>"></a></td>
                <td><label for="menu-toggle" class="menu-icon"><a class="menu-icon"><img id="header-icon-burger"
                                class="img-menu" src="<?php echo $headerIconBurger ?>"></a></label></td>
            </table>
    </table>



    <style>
        .alignleft {
            float: left;
        }

        /*
td,tr{
    border: 1px solid;
}*/
    </style>

    <label class="sidenav-background" for="menu-toggle"></label>

    <div id="mySidenav" class="sidenav">

        <table width="100%">
            <tr>
                <td>
                    <label for="toggle-project"><a><?php echo $txtSandMenuProject; ?></a></label>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">
                    <input type="checkbox" id="toggle-project">
                    <table class="sub-menu-project">
                        <tr>
                            <td><a id="sub-item-roadmap" href="#AnchorRoadmapMobile"><?php echo $txtSandMenuRoadmap; ?></a></td>
                        </tr>
                        <td><a id="sub-item-tokenDistribution" href="#AnchorInitialSplit"><?php echo $txtSandMenuTokenDistrib; ?></a></td>
            </tr>
            <td><a id="sub-item-DYOR" href="#AnchorDYORmobile"><?php echo $txtSandMenuDYOR; ?></a></td>
            </tr>
            <td><a id="sub-item-sketchDesign" onclick="window.open('https://www.instagram.com/p/CkCE1n2q8mL/','_blank');" href="#"><?php echo $txtSandMenuSketch; ?></a></td>
            </tr>
            <td><a id="sub-item-litepaper" onclick="window.open('https://typofx.gitbook.io/','_blank');" href="#"><?php echo $txtSandMenuLitepaper; ?></a></td>
            </tr>
            </tr>
        </table>
        </td>
        </tr>

        <tr>
            <td>
                <label for="toggle-products"><a><?php echo $txtSandMenuProducts; ?></a></label>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">
                <input type="checkbox" id="toggle-products">
                <table class="sub-menu-products">
                    <tr>
                        <td><a id="sub-item-cmcUpvote" onclick="window.open('https://www.plata.ie/upvote/','_self');" href="#">👍 <?php echo $txtCMCupvote; ?></a></td>
                    </tr>
                    <td><a id="sub-item-buyPlata" onclick="window.open('https://www.plata.ie/en/mobile/select/','_self');" href="#"><?php echo $txtSandMenuBuyPlata; ?></a></td>
        </tr>
        <td><a id="sub-item-cmcUpvote" onclick="window.open('https://www.plata.ie/plataforma/?mode=full');" href="">Plataforma</a></td>
        </tr>
        <td><a id="sub-item-Merchant" onclick="window.open('https://plata.sellfy.store/','_blank');" href="#"><?php echo $txtSandMenuMerchant; ?></a></td>
        </tr>
        <td><a id="sub-item-OpenSea" onclick="window.open('https://opensea.io/collection/platatoken','_blank');" href="#">OpenSea</a></td>
        </tr>
        </tr>
        </table>
        </td>
        </tr>

        <tr>
            <td>
                <label for="toggle-about"><a><?php echo $txtSandMenuAbout; ?></a></label>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">
                <input type="checkbox" id="toggle-about">
                <table class="sub-menu-about">
                    <tr>
                        <td><a id="sub-item-meetTheTeam" onclick="window.open('<?php echo "https://www.plata.ie/" . $language . "/mobile/team/" ?>','_self');"><?php echo $txtSandMenuMeetTheTeam; ?></a></td>
                    </tr>
                    <td><a id="sub-item-socialMedia" onclick="window.open('https://linktr.ee/typofx','_blank');" href="#"><?php echo $txtSandMenuSocialMedia; ?></a></td>
        </tr>
        <td><a id="sub-item-reportBug" onclick="window.open('https://www.plata.ie/en/reportbug','_blank');" href="#"><?php echo $txtSandMenuReportBug; ?></a></td>
        </tr>
        <td><a id="sub-item-email" onclick="window.open('mailto:actm@plata.ie');" href="#"><?php echo $txtSandMenuEmail; ?></a></td>
        </tr>
        <td><a id="sub-item-whatsapp" onclick="window.open('https://chat.whatsapp.com/EWnfnZ2zyA28opVHSg4ryO','_blank');" href="#">Whatsapp</a></td>
        </tr>
        <!--<td><a id="sub-item-phone"       onclick="window.open('https://www.plata.ie/mobile/team/');" href="#">Phone</a></td></tr>-->
        </tr>
        </table>
        </td>
        </tr>

        <tr>
            <td>
                <a id="item-typofx" onclick="window.open('https://www.linkedin.com/company/typofx','_blank');" href="#">Typo FX</a>
            </td>
        </tr>

        <tr>
            <td>
                <label for="toggle-theme"><a>Theme (<?php echo $themeLabel ?>)</a></label>
            </td>
        </tr>
        <tr>
            <td style="padding-left:20px">
                <input type="checkbox" id="toggle-theme">
                <table class="sub-menu-theme">
                    <form method="post" action="">
                        <tr>
                            <td> <button type="submit" id="sub-item-darkmode" name="change_appearance" class="sidenavAparaance" value="off"><?php echo $txtSandMenuThemeDark ?></button></td>
                        </tr>
                        <tr>
                            <td> <button type="submit" id="sub-item-lightmode" name="change_appearance" class="sidenavAparaance" value="on"><?php echo $txtSandMenuThemeLight ?></button></td>
                        </tr>
        </tr>
        </form>
        </table>
        </td>
        </tr>

   
        <tr>
            <td style="padding-left: 20px;">
                <?php
                include_once 'conexao.php';

                $query = "SELECT code, name FROM granna80_bdlinks.languages";
                $result = $conn->query($query);

                if (!$result) {
                    die("Error: " . $conn->error);
                }

                $idiomas = [];

                while ($row = $result->fetch_assoc()) {
                    $idiomas[] = $row;
                }


                function compareLanguages($a, $b)
                {
                    $priority = ['ga', 'en', 'pt'];

                    $indexA = array_search($a['code'], $priority);
                    $indexB = array_search($b['code'], $priority);

                    if ($indexA === false) $indexA = count($priority);
                    if ($indexB === false) $indexB = count($priority);

                    if ($indexA != $indexB) {
                        return $indexA - $indexB;
                    }


                    return strcmp($a['code'], $b['code']);
                }


                usort($idiomas, 'compareLanguages');
                ?>
                <input type="checkbox" id="toggle-language">
                <table class="sub-menu-language">
                    <select id="language-select" onchange="window.open('https://www.plata.ie/' + this.value + '/mobile', '_self');">
                        <option value=""><?php echo $txtSandMenuLanguage; ?></option> <!-- Placeholder option -->
                        <?php foreach ($idiomas as $idioma): ?>
                            <option value="<?php echo htmlspecialchars($idioma['code']); ?>">
                                <?php echo htmlspecialchars($idioma['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <label for="toggle-currency"><a>Currency (BRL)</a></label>
            </td>
        </tr>
        <tr>
            <td style="padding-left:20px;">
                <input type="checkbox" id="toggle-currency">
                <table class="sub-menu-currency">
                    <tr>
                        <td><a id="sub-item-brl"><?php echo $txtSandMenuBRL; ?></a></td>
                    </tr>
                    <td><a id="sub-item-eur"><?php echo $txtSandMenuEUR; ?></a></td>
        </tr>
        <td><a id="sub-item-usd"><?php echo $txtSandMenuUSD; ?></a></td>
        </tr>
        </tr>
        </table>
        </td>
        </tr>
        </table>
    </div>
</div>