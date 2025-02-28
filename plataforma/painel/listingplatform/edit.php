<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';?>
<?php
$userNameUser = $userEmail;
include 'conexao.php';

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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

<style>
        .form-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container label {
            font-weight: bold;
        }
        .form-container .btn {
            margin-right: 10px;
        }
    </style>

</head>

<body>
        <h2>Edit: <?php echo $row["Platform"]; ?></h2>
        <div class="form-container">
        <form action="update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $row["ID"]; ?>">

            <div class="mb-3">
                <img src="/images/icolog/<?php echo $row["logo"]; ?>" alt="Descrição da Imagem" width="50" height="50" class="img-thumbnail">
                <label for="logo" class="form-label">Logo:</label>
                <label for="logo" class="form-label">Select a file (allowed types: .jpg, .jpeg, .png, .ico | maximum size: 5MB):</label>
                <input type="file" name="logo" id="logo" class="form-control" accept=".jpg, .jpeg, .png, .ico" onchange="validateFileSize(this)" required>
            </div>

            <div class="mb-3">
                <img src="/images/icolog/<?php echo $row["full_logo"]; ?>" alt="full_logo" width="200" height="200" class="img-thumbnail">
                <label for="full_logo" class="form-label">Full logo:</label>
                <label for="full_logo" class="form-label">Select a file (allowed types: .jpg, .jpeg, .png, .ico, .svg | maximum size: 5MB):</label>
                <input type="file" name="full_logo" id="full_logo" class="form-control" accept=".jpg, .jpeg, .png, .ico, .svg" onchange="validateFileSize(this)" required>
            </div>

            <div class="mb-3">
                <label for="listed" class="form-label">Listed?</label>
                <select name="listed" id="listed" class="form-select">
                    <option value="1" <?php if ($row["Listed"] == '1') echo "selected"; ?>>YES</option>
                    <option value="0" <?php if ($row["Listed"] == '0') echo "selected"; ?>>NOT</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="desktop" class="form-label">Desktop:</label>
                <input type="text" name="desktop" class="form-control" value="<?php echo $row["Desktop"]; ?>">
            </div>

            <div class="mb-3">
                <label for="mobile" class="form-label">Mobile:</label>
                <input type="text" name="mobile" class="form-control" value="<?php echo $row["Mobile"]; ?>">
            </div>

            <div class="mb-3">
                <label for="score" class="form-label">Score:</label>
                <input type="text" id="score" name="score" class="form-control" value="<?php echo $row["Score"]; ?>">
            </div>

            <div class="mb-3">
                <label for="platform" class="form-label">Platform:</label>
                <input type="text" name="platform" class="form-control" value="<?php echo $row["Platform"]; ?>">
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type:</label>
                <select name="type" id="type" class="form-select">
                    <option value="Index" <?php if (strtolower($row["Type"]) == 'index') echo "selected"; ?>>Index</option>
                    <option value="Bot" <?php if (strtolower($row["Type"]) == 'bot') echo "selected"; ?>>Bot</option>
                    <option value="Tool" <?php if (strtolower($row["Type"]) == 'tool') echo "selected"; ?>>Tool</option>
                    <option value="Dex" <?php if (strtolower($row["Type"]) == 'dex') echo "selected"; ?>>Dex</option>
                    <option value="Audity" <?php if (strtolower($row["Type"]) == 'audity') echo "selected"; ?>>Audity</option>
                    <option value="Nft" <?php if (strtolower($row["Type"]) == 'nft') echo "selected"; ?>>Nft</option>
                    <option value="Audity/KYC" <?php if (strtolower($row["Type"]) == 'audity/kyc') echo "selected"; ?>>Audity/KYC</option>
                    <option value="Cex" <?php if (strtolower($row["Type"]) == 'cex') echo "selected"; ?>>Cex</option>
                    <option value="Newspaper" <?php if (strtolower($row["Type"]) == 'newspaper') echo "selected"; ?>>Newspaper</option>
                    <option value="Kyc" <?php if (strtolower($row["Type"]) == 'kyc') echo "selected"; ?>>Kyc</option>
                    <option value="Voting" <?php if (strtolower($row["Type"]) == 'voting') echo "selected"; ?>>Voting</option>
                    <option value="Funding" <?php if (strtolower($row["Type"]) == 'funding') echo "selected"; ?>>Funding</option>
                    <option value="Chart" <?php if (strtolower($row["Type"]) == 'chart') echo "selected"; ?>>Chart</option>
                    <option value="Wallet" <?php if (strtolower($row["Type"]) == 'wallet') echo "selected"; ?>>Wallet</option>
                    <option value="locker" <?php if (strtolower($row["Type"]) == 'locker') echo "selected"; ?>>Locker</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="link" class="form-label">Link:</label>
                <input type="text" id="link" name="link" class="form-control" value="<?php echo $row["Link"]; ?>">
            </div>

            <div class="mb-3">
                <label for="access" class="form-label">Access:</label>
                <input type="text" id="access" name="access" class="form-control" value="<?php echo $row["Access"]; ?>">
            </div>

            <div class="mb-3">
                <label for="country" class="form-label">Country:</label>
                <input type="text" name="country" class="form-control" value="<?php echo $row["Country"]; ?>">
            </div>

            <div class="mb-3">
                <label for="rank" class="form-label">Rank:</label>
                <input type="text" name="rank" class="form-control" value="<?php echo $row["Rank"]; ?>">
            </div>

            <div class="mb-3">
                <label for="marketcap" class="form-label">Market Cap:</label>
                <select name="marketcap" id="marketcap" class="form-select">
                    <option value="K" <?php if ($row["MarketCap"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["MarketCap"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["MarketCap"] != 'K' && $row["MarketCap"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="liquidity" class="form-label">Liquidity:</label>
                <select name="liquidity" id="liquidity" class="form-select">
                    <option value="K" <?php if ($row["Liquidity"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["Liquidity"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["Liquidity"] != 'K' && $row["Liquidity"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="fullydilutedmkc" class="form-label">Fully Diluted Market Cap:</label>
                <select name="fullydilutedmkc" id="fullydilutedmkc" class="form-select">
                    <option value="K" <?php if ($row["FullyDilutedMKC"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["FullyDilutedMKC"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["FullyDilutedMKC"] != 'K' && $row["FullyDilutedMKC"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="circulatingsupply" class="form-label">Circulating Supply:</label>
                <select name="circulatingsupply" id="circulatingsupply" class="form-select">
                    <option value="K" <?php if ($row["CirculatingSupply"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["CirculatingSupply"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["CirculatingSupply"] != 'K' && $row["CirculatingSupply"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="maxsupply" class="form-label">Max Supply:</label>
                <select name="maxsupply" id="maxsupply" class="form-select">
                    <option value="K" <?php if ($row["MaxSupply"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["MaxSupply"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["MaxSupply"] != 'K' && $row["MaxSupply"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="totalsupply" class="form-label">Total Supply:</label>
                <select name="totalsupply" id="totalsupply" class="form-select">
                    <option value="K" <?php if ($row["TotalSupply"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["TotalSupply"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["TotalSupply"] != 'K' && $row["TotalSupply"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <select name="price" id="price" class="form-select">
                    <option value="K" <?php if ($row["Price"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["Price"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["Price"] != 'K' && $row["Price"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="graph" class="form-label">Graph:</label>
                <select name="graph" id="graph" class="form-select">
                    <option value="K" <?php if ($row["Graph"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["Graph"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["Graph"] != 'K' && $row["Graph"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="holders" class="form-label">Holders:</label>
                <select name="holders" id="holders" class="form-select">
                    <option value="K" <?php if ($row["Holders"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["Holders"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["Holders"] != 'K' && $row["Holders"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tokenLogo" class="form-label">Token Logo:</label>
                <select name="tokenLogo" id="tokenLogo" class="form-select">
                    <option value="K" <?php if ($row["TokenLogo"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["TokenLogo"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["TokenLogo"] != 'K' && $row["TokenLogo"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="socialmedia" class="form-label">Social Media:</label>
                <select name="socialmedia" id="socialmedia" class="form-select">
                    <option value="K" <?php if ($row["SocialMedia"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["SocialMedia"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["SocialMedia"] != 'K' && $row["SocialMedia"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="metamaskbutton" class="form-label">Metamask Button:</label>
                <select name="metamaskbutton" id="metamaskbutton" class="form-select">
                    <option value="K" <?php if ($row["MetamaskButton"] == 'K') echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["MetamaskButton"] == 'W') echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["MetamaskButton"] != 'K' && $row["MetamaskButton"] != 'W') echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="obs1" class="form-label">Obs1:</label>
                <input type="text" name="obs1" class="form-control" value="<?php echo $row["Obs1"]; ?>" onblur="validateInput(this)">
            </div>

            <div class="mb-3">
                <label for="obs2" class="form-label">Obs2:</label>
                <input type="text" name="obs2" class="form-control" value="<?php echo $row["Obs2"]; ?>" onblur="validateInput(this)">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="zemail" class="form-control" value="<?php echo $row["Email"]; ?>">
            </div>

            <div class="mb-3">
                <label for="telegram" class="form-label">Telegram:</label>
                <input type="text" name="telegram" class="form-control" value="<?php echo $row["Telegram"]; ?>">
            </div>

            <div class="mb-3">
                <label for="user_edit" class="form-label">Who is editing:</label>
                <input type="text" name="user_edit" class="form-control" value="<?php echo $userNameUser; ?>">
            </div>

            <input type="hidden" name="last_updated" value="<?php echo (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'); ?>">

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script>
        function validateFileSize(input) {
            var file = input.files[0];

            if (file) {
                var size = file.size;

                if (size > 5000000) {
                    alert("The file size exceeds the limit of 5MB. Please select a smaller file.");
                    input.value = '';
                }
            }
        }
    </script>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
                function validateInput(input) {

                        var regex = /^.*$/;

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
                        var tokenLogo = document.getElementById("tokenLogo").value;
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