<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];
    $desktop = $_POST["desktop"];
    $mobile = $_POST["mobile"];
    $score = $_POST["score"];
    $platform = $_POST["platform"];
    $type = $_POST["type"];
    $access = $_POST["access"];
    $country = $_POST["country"];
    $link = $_POST["link"];
    $rank = $_POST["rank"];
    $marketcap = $_POST["marketcap"];
    $liquidity = $_POST["liquidity"];
    $fullydilutedmkc = $_POST["fullydilutedmkc"];
    $circulatingsupply = $_POST["circulatingsupply"];
    $maxsupply = $_POST["maxsupply"];
    $totalsupply = $_POST["totalsupply"];
    $price = $_POST["price"];
    $graph = $_POST["graph"];
    $holders = $_POST["holders"];
    $tokenlogo = $_POST["tokenlogo"];
    $socialmedia = $_POST["socialmedia"];
    $metamaskbutton = $_POST["metamaskbutton"];
    $obs1 = $_POST["obs1"];
    $obs2 = $_POST["obs2"];

    $sql = "UPDATE granna80_bdlinks.links SET 
        Desktop = '$desktop',
        Mobile = '$mobile',
        Score = '$score',
        Platform = '$platform',
        Type = '$type',
        Access = '$access',
        Country = '$country',
        Link = '$link',
        Rank = '$rank',
        MarketCap = '$marketcap',
        Liquidity = '$liquidity',
        FullyDilutedMKC = '$fullydilutedmkc',
        CirculatingSupply = '$circulatingsupply',
        MaxSupply = '$maxsupply',
        TotalSupply = '$totalsupply',
        Price = '$price',
        Graph = '$graph',
        Holders = '$holders',
        TokenLogo = '$tokenlogo',
        SocialMedia = '$socialmedia',
        MetamaskButton = '$metamaskbutton',
        Obs1 = '$obs1',
        Obs2 = '$obs2'
        WHERE ID = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // Redirect back to the list page
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
