<?php
session_start(); // Iniciar a sessão

// Verificar se o usuário está autenticado
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
        // O usuário não está autenticado, redirecionar de volta para a página de login
        header("Location: index.php");
        exit();
}

include '../conexao.php';

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

        <script>
            function generateAccessLink() {
    var linkInput = document.getElementById("link");
    var accessLink = document.getElementById("accessLink");

    var linkValue = linkInput.value.trim();  // Remove leading/trailing whitespace
    var formattedLink = linkValue.replace(/^https?:\/\/(?:www\.)?/i, "");

    var fullAccessLink = "https://www.similarweb.com/website/" + formattedLink;

    accessLink.innerHTML = "<a href='" + fullAccessLink + "' target='_blank'>Access Link</a>";
}


                document.addEventListener("DOMContentLoaded", function() {
                        var linkInput = document.getElementById("link");
                        linkInput.addEventListener("input", generateAccessLink);
                });
        </script>

<script>
                function generateCountryLink() {
                        var linkInput = document.getElementById("link");
                        var accessLink = document.getElementById("accessCountry");

                        var linkValue = linkInput.value;
                        var formattedLink = linkValue.replace(/^https:\/\/www\./i, "");

                        var fullAccessLink = "https://who.is/whois/" + formattedLink;
                       

                        accessLink.innerHTML = "<a href='" + fullAccessLink + "' target='_blank'>Access Link</a>";
                }

                document.addEventListener("DOMContentLoaded", function() {
                        var linkInput = document.getElementById("link");
                        linkInput.addEventListener("input", generateCountryLink);
                });
        </script>
</head>

<body>
        <h2>Edit: <?php echo $row["Platform"]; ?></h2>
        <form action="update.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $row["ID"]; ?>">
                Desktop: <input type="text" name="desktop" value="<?php echo $row["Desktop"]; ?>"><br>
                Mobile: <input type="text" name="mobile" value="<?php echo $row["Mobile"]; ?>"><br>
                Score: <input type="text" name="score" value="<?php echo $row["Score"]; ?>"><br>
                Platform: <input type="text" name="platform" value="<?php echo $row["Platform"]; ?>"><br>
                Type: <input type="text" name="type" value="<?php echo $row["Type"]; ?>"><br>
                Link: <input type="text" id="link" name="link" value="<?php echo $row["Link"]; ?>"><br>
                <br>
                Access: <input type="text" id="access" name="access" value="<?php echo $row["Access"]; ?>"><br>
                <label for="accessLink">Access Link:</label>
                <span id="accessLink"></span>
                <br>
                Country: <input type="text" name="country" value="<?php echo $row["Country"]; ?>"><br>
                <label for="accessCountry">Access Country:</label>
                <span id="accessCountry"></span>
                <br>
                <br>
                Rank: <input type="text" name="rank" value="<?php echo $row["Rank"]; ?>"><br>


                <label for="marketcap">Market Cap:</label>
                <select name="marketcap" id="marketcap" value="<?php $row["MarketCap"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["MarketCap"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["MarketCap"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["MarketCap"] != 'K' && $row["MarketCap"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                <label for="liquidity"></label>Liquidity:</label>
                <select name="liquidity" id="liquidity" value="<?php echo $row["Liquidity"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["Liquidity"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["Liquidity"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["Liquidity"] != 'K' && $row["Liquidity"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                <label for="fullydilutedmkc"></label>Fully Diluted Market Cap:</label>
                <select name="fullydilutedmkc" id="fullydilutedmkc" value="<?php echo $row["FullyDilutedMKC"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["FullyDilutedMKC"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["FullyDilutedMKC"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["FullyDilutedMKC"] != 'K' && $row["FullyDilutedMKC"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                <label for="circulatingsupply"></label>Circulating Supply:</label>
                <select name="circulatingsupply" id="circulatingsupply" value="<?php echo $row["CirculatingSupply"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["CirculatingSupply"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["CirculatingSupply"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["CirculatingSupply"] != 'K' && $row["CirculatingSupply"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                <label for="maxsupply"></label>Max Supply:</label>
                <select name="maxsupply" id="maxsupply" value="<?php echo $row["MaxSupply"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["MaxSupply"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["MaxSupply"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["MaxSupply"] != 'K' && $row["MaxSupply"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                <label for="totalsupply"></label>Total Supply:</label>
                <select name="totalsupply" id="totalsupply" value="<?php echo $row["TotalSupply"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["TotalSupply"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["TotalSupply"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["TotalSupply"] != 'K' && $row["TotalSupply"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                <label for="price"></label>Price:</label>
                <select name="price" id="price" value="<?php echo $row["Price"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["Price"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["Price"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["Price"] != 'K' && $row["Price"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                <label for="graph"></label>Graph:</label>
                <select name="graph" id="graph" value="<?php echo $row["Graph"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["Graph"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["Graph"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["Graph"] != 'K' && $row["Graph"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                <label for="holders"></label>Holders:</label>
                <select name="holders" id="holders" value="<?php echo $row["Holders"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["Holders"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["Holders"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["Holders"] != 'K' && $row["Holders"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                <label for="tokenlogo"></label>Token Logo:</label>
                <select name="tokenlogo" id="tokenlogo" value="<?php echo $row["TokenLogo"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["TokenLogo"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["TokenLogo"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["TokenLogo"] != 'K' && $row["TokenLogo"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                <label for="socialmedia"></label>Social Media:</label>
                <select name="socialmedia" id="socialmedia" value="<?php echo $row["SocialMedia"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["SocialMedia"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["SocialMedia"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["SocialMedia"] != 'K' && $row["SocialMedia"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                <label for="metamaskbutton"></label>Metamask Button:</label>
                <select name="metamaskbutton" id="metamaskbutton" value="<?php echo $row["MetamaskButton"] ?>">
                        <option value="<?php echo 'K'; ?>" <?php if ($row["MetamaskButton"] == 'K') {
                                                                        echo "selected";
                                                                } ?>>Okay</option>
                        <option value="<?php echo 'W'; ?>" <?php if ($row["MetamaskButton"] == 'W') {
                                                                        echo "selected";
                                                                } ?>>Wrong</option>
                        <option value="<?php echo 'Z'; ?>" <?php if ($row["MetamaskButton"] != 'K' && $row["MetamaskButton"] != 'W') {
                                                                        echo "selected";
                                                                } ?>>Unavailable</option>
                </select> <br>

                Obs1: <input type="text" name="obs1" value="<?php echo $row["Obs1"]; ?>"><br>
                Obs2: <input type="text" name="obs2" value="<?php echo $row["Obs2"]; ?>"><br>
                <label for="email"></label>Email:</label> <input type="email" name="zemail" value="<?php echo $row["Email"]; ?>"><br>
                <label for="telegram"></label>Telegram:</label> <input type="text" name="telegram" value="<?php echo $row["Telegram"]; ?>"><br>
                <input type="submit" value="Update">
                <a href="https://plata.ie/listingplatform/painel/painel.php">Cancel</a>
        </form>
</body>

</html>