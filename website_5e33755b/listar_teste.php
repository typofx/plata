<?php
include 'conexao.php';

$fullMode = isset($_GET['mode']) && $_GET['mode'] === 'full';

$sql = "SELECT * FROM granna80_bdlinks.links";


if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $sql .= " WHERE (`Platform` LIKE '%$searchTerm%') || (`Type` LIKE '%$searchTerm%')";
}

if ($fullMode) {
    $sql .= " WHERE (`Listed` = 1) ORDER BY `Score` DESC, Access DESC, Rank DESC";
} else {
    $sql .= " ORDER BY `Score` DESC, Access DESC, Rank DESC";
}

$result = $conn->query($sql);

$sqlScore = "SELECT * FROM granna80_bdlinks.links ORDER BY `Access` DESC LIMIT 1 OFFSET 5";
$resultScore = $conn->query($sqlScore);

if ($resultScore->num_rows > 0) {

    while ($rowScore = $resultScore->fetch_assoc()) {
        $avgScore = (float)($rowScore["Access"])/2;
    }

}

function getTXTcolor($value)
{
    if ($value == 'K' || $value == 'Y') {
        return 'color: green;';
    } elseif ($value == 'Z') {
        return 'color: gray;';
    } elseif ($value == 'E' || $value == 'W') {
        return 'color: red;';
    } else {
        return '';
    }
}

    function getAccessLetter($accessValue,$avgScore) {
        if ( $accessValue >= ($avgScore*0.5) ) { return 'A+'; }
        if ( $accessValue >= ($avgScore*0.25) ) { return 'A'; }
        if ( $accessValue >= ($avgScore*0.125) ) { return 'B'; }
        if ( $accessValue >= ($avgScore*0.03) ) { return 'C'; }
        else { return 'D'; }
    }

function getTICKcolor($value)
{
    if ($value == 'K' || $value == 'Y') {
        return 'green';
    } else {
        return 'gray';
    }
}

function backgroundLine($number)
{
    if ($number % 2 == 0) {
        return 'tb-bg-color-light-fortable';
    } else {
        return 'tb-bg-color-dark-fortable';
    }
}

function tokenRanked($data)
{
    if ($data == '-') {
        return 'color: gray;';
    } else {
        return 'color: green;';
    }
}
$sqlTotal = "SELECT COUNT(*) AS TotalListed FROM granna80_bdlinks.links WHERE Listed = 1";
$resultTotal = $conn->query($sqlTotal);


// Check if there are results and fetch the total
if ($resultTotal->num_rows > 0) {
    $totalRow = $resultTotal->fetch_assoc();
    $totalListed = $totalRow['TotalListed'];
}
?>

<link rel="stylesheet" href="./new-listing/desktop-style-new-listing.css">

<center>
 
    <form action="" method="get">
        <label for="search">Filter : </label>
        <input type="text" id="search" name="search" autocomplete="off">&nbsp;<input type="submit" value="&#128269;">
        <br><p>Plata Token (PLT) is listed on <?php echo $totalListed ?> websites.</p>
    </form>
    
    <div class="listing">
        <table class="listing-container">
            <tr class="label">
                <td class="number padding-column border-bottom2px">#</td>
                <td class="desktop padding-column border-bottom2px" onclick="msgInfo()">🖳</td>
                <td class="mobile padding-column border-bottom2px" onclick="msgInfo()">☎</td>
                <td colspan="2" class="platform padding-column border-bottom2px">Platform</td>
                <td class="category padding-column border-bottom2px">Category</td>
                <td class="country padding-column border-bottom2px">     </td>
                <td class="metrics padding-column border-bottom2px">Metrics</td>
                <td colspan="13" class="performance padding-column border-bottom2px">Performance</td>
                <td class="score padding-column border-bottom2px">Score</td>
            </tr>

<?php
    $i = 1;
    while ($row = $result->fetch_assoc()) {
?>
    <tr class="platform-data">
                <td class="number padding-column border-bottom1px" id="number">
                    <span class="text-number"><?php echo $i ?></span>
                </td>
                <td class="desktop padding-column border-bottom1px" id="desktop"><center><?php echo '<img height="13px" src="https://www.plata.ie/images/listing-' . getTICKcolor($row["Desktop"]) . '-tick.svg">' ?></center></td>
                <td class="mobile padding-column border-bottom1px" id="mobile"><center><?php echo '<img height="13px" src="https://www.plata.ie/images/listing-' . getTICKcolor($row["Mobile"]) . '-tick.svg">' ?></center></td>
                <td class="logo-platform padding-column border-bottom1px"><img src="https://www.plata.ie/images/icolog/svglogo.svg" alt="img" height="25px"></td>
                <td class="platform1 padding-column border-bottom1px" id="platform"><?php echo'<a class="a-listing-places" href="' . $row["Link"] . '" target="_blank">' . $row["Platform"] . '</a>'?></td>
                <td class="category padding-column border-bottom1px" id="category"><?php echo $row["Type"] ?> </td>
                <td class="country padding-column border-bottom1px" id="country"><?php echo'<img src="https://www.plata.ie/images/flags/' . $row["Country"] . '.png" alt="' . $row["Country"] . '" height="15" width="15">'?></td>
                <td class="metrics padding-column border-bottom1px" id="metrics"><?php echo getAccessLetter($row["Access"],$avgScore) ?></td>
                <td class="performance-bars border-bottom1px" style="<?php echo tokenRanked($row["Rank"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["MarketCap"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["Liquidity"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["FullyDilutedMKC"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["CirculatingSupply"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["MaxSupply"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["TotalSupply"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["Price"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["Graph"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["Holders"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["TokenLogo"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["SocialMedia"])?>">█</td>
                <td class="performance-bars border-bottom1px" style="<?php echo getTXTcolor($row["MetamaskButton"])?>">█</td>
                <td class="score padding-column border-bottom1px" id="score"><?php echo$row["Score"] ?>%</td>
            </tr>
    <?php
        $i++;
    }
   echo ' </table></center></div><br><br>';
$conn->close();




echo '<div class="button-container">';
echo '<center><form action="" method="get">';
echo '<input type="checkbox" id="platalisted" name="mode" value="full" onchange="this.form.submit()"';
if ($fullMode) {
    echo ' checked';
}
echo '>';
echo '<label for="platalisted">Plata Token listed Platforms.</label>';
echo '</form></center><br><br>';
echo '<br>';
echo '<br>';
echo '</div>';
?>


<script>
    function msgInfo() {
        alert("Help\nWebsite Listed on Desktop Version;\nWebsite Listed on Mobile Version;");
    }
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-RXYGWW7KHB"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-RXYGWW7KHB');
</script>