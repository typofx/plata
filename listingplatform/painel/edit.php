<?php
session_start(); // Start session

// Check if the user is authenticated
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
        //User is not authenticated, redirect back to login page
        header("Location: index.php");
        exit();
}
$userName = $_SESSION["user_email"];

include '../conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT * FROM granna80_bdlinks.links WHERE ID = $id";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
        } else {
                echo "Register not found.";
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

                        var linkValue = linkInput.value.trim(); // Remove leading/trailing whitespace
                        var url = new URL(linkValue);
                        var formattedLink = url.hostname; // Extract the hostname (TLD)

                        var fullAccessLink = "https://www.similarweb.com/website/" + formattedLink;

                        accessLink.innerHTML = "<a href='" + fullAccessLink + "' target='_blank'>SIMILARWEB</a>";
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

                        var linkValue = linkInput.value.trim(); // Remove leading/trailing whitespace
                        var url = new URL(linkValue);
                        var formattedLink = url.hostname; // Extract the hostname (TLD)

                        var fullAccessLink = "https://who.is/whois/" + formattedLink;

                        accessLink.innerHTML = "<a href='" + fullAccessLink + "' target='_blank'>WHO.IS</a>";
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

                <label for="listed">Listed?</label>
                <select name="listed" id="listed" value="<?php $row["Listed"] ?>">
                        <option value="<?php echo '1'; ?>" <?php if ($row["Listed"] == '1') {
                                                                        echo "selected";
                                                                } ?>>YES</option>
                        <option value="<?php echo '0'; ?>" <?php if ($row["Listed"] == '0') {
                                                                        echo "selected";
                                                                } ?>>NOT</option>
                </select> <br>

                Desktop: <input type="text" name="desktop" value="<?php echo $row["Desktop"]; ?>"><br>
                Mobile: <input type="text" name="mobile" value="<?php echo $row["Mobile"]; ?>"><br>
                Score: <input type="text" id="score" name="score" value="<?php echo $row["Score"]; ?>"><br>
                Platform: <input type="text" name="platform" value="<?php echo $row["Platform"]; ?>"><br>
                <br><label for="type">Type:</label>
                <select name="type" id="type">
                        <option value="Index" <?php if ($row["Type"] == 'Index') {
                                                        echo "selected";
                                                } ?>>Index</option>
                        <option value="Bot" <?php if ($row["Type"] == 'Bot') {
                                                        echo "selected";
                                                } ?>>Bot</option>
                        <option value="Tool" <?php if ($row["Type"] == 'Tool') {
                                                        echo "selected";
                                                } ?>>Tool</option>
                        <option value="Dex" <?php if ($row["Type"] == 'DEX') {
                                                        echo "selected";
                                                } ?>>Dex</option>
                        <option value="Audity" <?php if ($row["Type"] == 'Audity') {
                                                        echo "selected";
                                                } ?>>Audity</option>
                        <option value="Nft" <?php if ($row["Type"] == 'NFT') {
                                                        echo "selected";
                                                } ?>>Nft</option>
                        <option value="Audity/KYC" <?php if ($row["Type"] == 'Audity/KYC') {
                                                                echo "selected";
                                                        } ?>>Audity/KYC</option>
                        <option value="Cex" <?php if ($row["Type"] == 'CEX') {
                                                        echo "selected";
                                                } ?>>Cex</option>
                        <option value="Newspaper" <?php if ($row["Type"] == 'Newspaper') {
                                                                echo "selected";
                                                        } ?>>Newspaper</option>
                        <option value="Kyc" <?php if ($row["Type"] == 'Kyc') {
                                                        echo "selected";
                                                } ?>>Kyc</option>
                        <option value="Voting" <?php if ($row["Type"] == 'Voting') {
                                                        echo "selected";
                                                } ?>>Voting</option>
                        <option value="Funding" <?php if ($row["Type"] == 'Funding') {
                                                        echo "selected";
                                                } ?>>Funding</option>
                        <option value="Chart" <?php if ($row["Type"] == 'Chart') {
                                                        echo "selected";
                                                } ?>>Chart</option>
                        <option value="Wallet" <?php if ($row["Type"] == 'Wallet') {
                                                        echo "selected";
                                                } ?>>Wallet</option> 
                </select><br>

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

                Obs1: <input type="text" name="obs1" value="<?php echo $row["Obs1"]; ?>" onblur="validateInput(this)"><br>
                Obs2: <input type="text" name="obs2" value="<?php echo $row["Obs2"]; ?>" onblur="validateInput(this)"><br>

                <label for="email"></label>Email:</label> <input type="email" name="zemail" value="<?php echo $row["Email"]; ?>"><br>
                <label for="telegram"></label>Telegram:</label> <input type="text" name="telegram" value="<?php echo $row["Telegram"]; ?>"><br>
                <label for="user_edit"></label>Who is editing:</label> <input type="user_edit" name="user_edit" value="<?php echo $userName; ?>"><br>
                <input type="hidden" name="last_updated" value="<?php echo (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'); ?>">


                <input type="submit" value="Update">
                <a href="https://plata.ie/listingplatform/painel/painel.php">Cancel</a>
        </form>
        <script>
                function validateInput(input) {

                        var regex = /^[a-zA-Z0-9\s]*$/;

                        if (!regex.test(input.value)) {
                                alert("Please enter only alphanumeric characters.");
                                input.value = '';
                        }
                }
        </script>

        <script>
                // Function to calculate the score as a percentage from 0 to 100%
                function calculateScore() {
                        var marketCap = document.getElementById("marketcap").value;
                        var liquidity = document.getElementById("liquidity").value;
                        var fullyDilutedMkc = document.getElementById("fullydilutedmkc").value;
                        var circulatingSupply = document.getElementById("circulatingsupply").value;
                        var maxSupply = document.getElementById("maxsupply").value;
                        var totalSupply = document.getElementById("totalsupply").value;
                        var price = document.getElementById("price").value;
                        var graph = document.getElementById("graph").value;
                        var holders = document.getElementById("holders").value;
                        var tokenLogo = document.getElementById("tokenlogo").value;
                        var socialMedia = document.getElementById("socialmedia").value;
                        var metamaskButton = document.getElementById("metamaskbutton").value;

                        // Check if all fields are set to "Unavailable"
                        if (
                                marketCap === "Z" &&
                                liquidity === "Z" &&
                                fullyDilutedMkc === "Z" &&
                                circulatingSupply === "Z" &&
                                maxSupply === "Z" &&
                                totalSupply === "Z" &&
                                price === "Z" &&
                                graph === "Z" &&
                                holders === "Z" &&
                                tokenLogo === "Z" &&
                                socialMedia === "Z" &&
                                metamaskButton === "Z"
                        ) {
                                document.getElementById("score").value = 0; // All fields are "Unavailable"
                                return;
                        }

                        // Calculate the sum of positive values
                        var sumOfPositiveValues = (
                                (marketCap === "K" ? 1 : 0) +
                                (liquidity === "K" ? 1 : 0) +
                                (fullyDilutedMkc === "K" ? 1 : 0) +
                                (circulatingSupply === "K" ? 1 : 0) +
                                (maxSupply === "K" ? 1 : 0) +
                                (totalSupply === "K" ? 1 : 0) +
                                (price === "K" ? 1 : 0) +
                                (graph === "K" ? 1 : 0) +
                                (holders === "K" ? 1 : 0) +
                                (tokenLogo === "K" ? 1 : 0) +
                                (socialMedia === "K" ? 1 : 0) +
                                (metamaskButton === "K" ? 1 : 0)
                        );

                        // Check for "Wrong" fields and subtract 1 for each
                        var wrongFields = (
                                (marketCap === "W" ? 1 : 0) +
                                (liquidity === "W" ? 1 : 0) +
                                (fullyDilutedMkc === "W" ? 1 : 0) +
                                (circulatingSupply === "W" ? 1 : 0) +
                                (maxSupply === "W" ? 1 : 0) +
                                (totalSupply === "W" ? 1 : 0) +
                                (price === "W" ? 1 : 0) +
                                (graph === "W" ? 1 : 0) +
                                (holders === "W" ? 1 : 0) +
                                (tokenLogo === "W" ? 1 : 0) +
                                (socialMedia === "W" ? 1 : 0) +
                                (metamaskButton === "W" ? 1 : 0)
                        );

                        // Calculate the score as a percentage from 0 to 100%
                        var score = ((sumOfPositiveValues + wrongFields) - wrongFields) / (sumOfPositiveValues + wrongFields) * 100;

                        // Update the score field in real-time
                        document.getElementById("score").value = score.toFixed(2); // Set the value with two decimal places
                }

                // Add a change event listener to all select fields
                var formFields = document.querySelectorAll("select"); // Select all select fields
                formFields.forEach(function(field) {
                        field.addEventListener("change", calculateScore);
                });

                // Call the function initially to calculate and display the initial score
                calculateScore();
        </script>
</body>

</html>