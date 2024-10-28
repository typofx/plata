<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM granna80_bdlinks.links WHERE ID = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Registro não encontrado.";
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar</title>
</head>
<body>
    <h2>Editar</h2>
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row["ID"]; ?>">
        Desktop: <input type="text" name="desktop" value="<?php echo $row["Desktop"]; ?>"><br>
        Mobile: <input type="text" name="mobile" value="<?php echo $row["Mobile"]; ?>"><br>
        Score: <input type="text" name="score" value="<?php echo $row["Score"]; ?>"><br>
        Platform: <input type="text" name="platform" value="<?php echo $row["Platform"]; ?>"><br>
        Type: <input type="text" name="type" value="<?php echo $row["Type"]; ?>"><br>
        Access: <input type="text" name="access" value="<?php echo $row["Access"]; ?>"><br>
        Country: <input type="text" name="country" value="<?php echo $row["Country"]; ?>"><br>
        Link: <input type="text" name="link" value="<?php echo $row["Link"]; ?>"><br>
        Rank: <input type="text" name="rank" value="<?php echo $row["Rank"]; ?>"><br>

        
        <label for="marketcap">Market Cap:</label>
            <select name="cars" id="cars">
                <option value="<?php echo $row["MarketCap"]='K'; ?>">Okay</option>
                <option value="W">Wrong</option>
            </select>
        
        
        Liquidity: <input type="text" name="liquidity" value="<?php echo $row["Liquidity"]; ?>"><br>
        Fully Diluted MKC: <input type="text" name="fullydilutedmkc" value="<?php echo $row["FullyDilutedMKC"]; ?>"><br>
        Circulating Supply: <input type="text" name="circulatingsupply" value="<?php echo $row["CirculatingSupply"]; ?>"><br>
        Max Supply: <input type="text" name="maxsupply" value="<?php echo $row["MaxSupply"]; ?>"><br>
        Total Supply: <input type="text" name="totalsupply" value="<?php echo $row["TotalSupply"]; ?>"><br>
        Price: <input type="text" name="price" value="<?php echo $row["Price"]; ?>"><br>
        Graph: <input type="text" name="graph" value="<?php echo $row["Graph"]; ?>"><br>
        Holders: <input type="text" name="holders" value="<?php echo $row["Holders"]; ?>"><br>
        Token Logo: <input type="text" name="tokenlogo" value="<?php echo $row["TokenLogo"]; ?>"><br>
        Social Media: <input type="text" name="socialmedia" value="<?php echo $row["SocialMedia"]; ?>"><br>
        Metamask Button: <input type="text" name="metamaskbutton" value="<?php echo $row["MetamaskButton"]; ?>"><br>
        Obs1: <input type="text" name="obs1" value="<?php echo $row["Obs1"]; ?>"><br>
        Obs2: <input type="text" name="obs2" value="<?php echo $row["Obs2"]; ?>"><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
