<?php

session_start(); // Iniciar a sessão

// Verificar se o usuário está autenticado
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // O usuário não está autenticado, redirecionar de volta para a página de login
    header("Location: index.php");
    exit();
}
include '../conexao.php';

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
    $email = $_POST["zemail"];
    $telegram = $_POST["telegram"];
    
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
        Obs2 = '$obs2',
        Email = '$email',
        Telegram = '$telegram'
        WHERE ID = $id";

    if ($conn->query($sql) === TRUE) {
        echo '<script>window.location.href = "painel.php";</script>';
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
