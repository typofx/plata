<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php
$userNameUser = $userEmail;
include 'listingplatform.conexao.php';

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

            var linkValue = linkInput.value.trim();
            var url = new URL(linkValue);
            var formattedLink = url.hostname;

            var fullAccessLink = "https://pro.similarweb.com/#/digitalsuite/" + formattedLink;

            accessLink.innerHTML = "<a href='" + fullAccessLink + "' target='_blank'>SIMILARWEB</a>";
        }

        document.addEventListener("DOMContentLoaded", function () {
            var linkInput = document.getElementById("link");
            linkInput.addEventListener("input", generateAccessLink);
        });
    </script>



    <script>
        function generateCountryLink() {
            var linkInput = document.getElementById("link");
            var accessLink = document.getElementById("accessCountry");

            var linkValue = linkInput.value.trim();
            var url = new URL(linkValue);
            var formattedLink = url.hostname;

            var fullAccessLink = "https://who.is/whois/" + formattedLink;

            accessLink.innerHTML = "<a href='" + fullAccessLink + "' target='_blank'>WHO.IS</a>";
        }

        document.addEventListener("DOMContentLoaded", function () {
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
        <form action="listingplatform.update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $row["ID"]; ?>">

            <div class="mb-3">
                <img src="/images/icolog/<?php echo (!empty($row["logo"])) ? $row["logo"] : 'default.png'; ?>"
                    alt="Descrição da Imagem" width="50" height="50" class="img-thumbnail">

                <label for="logo" class="form-label">Logo:</label>
                <label for="logo" class="form-label">Select a file (allowed types: .jpg, .jpeg, .png, .ico | maximum
                    size: 5MB):</label>
                <input type="file" name="logo" id="logo" class="form-control" accept=".jpg, .jpeg, .png, .ico"
                    onchange="validateFileSize(this)">
            </div>

            <div class="mb-3">
                <img src="/images/icolog/<?php echo (!empty($row["full_logo"])) ? $row["full_logo"] : 'default.png'; ?>"
                    alt="full_logo" width="200" height="200" class="img-thumbnail">

                <label for="full_logo" class="form-label">Full logo:</label>
                <label for="full_logo" class="form-label">Select a file (allowed types: .jpg, .jpeg, .png, .ico, .svg |
                    maximum size: 5MB):</label>
                <input type="file" name="full_logo" id="full_logo" class="form-control"
                    accept=".jpg, .jpeg, .png, .ico, .svg" onchange="validateFileSize(this)">
            </div>

        <div class="d-flex flex-wrap gap-4 mb-3">

            <div class="mb-3">
                <input type="hidden" name="listed" value="0">
                
                <input type="checkbox" name="listed" id="listed" class="form-check-input" value="1" 
                    <?php echo (($row["Listed"] ?? 0) == 1) ? 'checked' : ''; ?>> <!-- changed to checkbox-->
                <label class="form-check-label" for="listed">Listed</label>
            </div>

            <div class="mb-3">
                <input type="hidden" name="desktop" value="0">
                
                <input type="checkbox" name="desktop" id="desktop" class="form-check-input" value="1" 
                    <?php echo (($row["Desktop"] ?? 0) == 1) ? 'checked' : ''; ?>> <!-- changed to checkbox-->
                <label class="form-check-label" for="desktop">Desktop</label>
            </div>

            <div class="mb-3">
                <input type="hidden" name="mobile" value="0">
                
                <input type="checkbox" name="mobile" id="mobile" class="form-check-input" value="1" 
                    <?php echo (($row["Mobile"] ?? 0) == 1) ? 'checked' : ''; ?>> <!-- changed to checkbox-->
                <label class="form-check-label" for="mobile">Mobile</label>
            </div>
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
                    <option value="Index" <?php if (strtolower($row["Type"]) == 'index')
                        echo "selected"; ?>>Index
                    </option>
                    <option value="Bot" <?php if (strtolower($row["Type"]) == 'bot')
                        echo "selected"; ?>>Bot</option>
                    <option value="Tool" <?php if (strtolower($row["Type"]) == 'tool')
                        echo "selected"; ?>>Tool</option>
                    <option value="Dex" <?php if (strtolower($row["Type"]) == 'dex')
                        echo "selected"; ?>>Dex</option>
                    <option value="Audity" <?php if (strtolower($row["Type"]) == 'audity')
                        echo "selected"; ?>>Audity
                    </option>
                    <option value="Nft" <?php if (strtolower($row["Type"]) == 'nft')
                        echo "selected"; ?>>Nft</option>
                    <option value="Audity/KYC" <?php if (strtolower($row["Type"]) == 'audity/kyc')
                        echo "selected"; ?>>
                        Audity/KYC</option>
                    <option value="Cex" <?php if (strtolower($row["Type"]) == 'cex')
                        echo "selected"; ?>>Cex</option>
                    <option value="Newspaper" <?php if (strtolower($row["Type"]) == 'newspaper')
                        echo "selected"; ?>>
                        Newspaper</option>
                    <option value="Kyc" <?php if (strtolower($row["Type"]) == 'kyc')
                        echo "selected"; ?>>Kyc</option>
                    <option value="Voting" <?php if (strtolower($row["Type"]) == 'voting')
                        echo "selected"; ?>>Voting
                    </option>
                    <option value="Funding" <?php if (strtolower($row["Type"]) == 'funding')
                        echo "selected"; ?>>Funding
                    </option>
                    <option value="Chart" <?php if (strtolower($row["Type"]) == 'chart')
                        echo "selected"; ?>>Chart
                    </option>
                    <option value="Wallet" <?php if (strtolower($row["Type"]) == 'wallet')
                        echo "selected"; ?>>Wallet
                    </option>
                    <option value="locker" <?php if (strtolower($row["Type"]) == 'locker')
                        echo "selected"; ?>>Locker
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="link" class="form-label">Link:</label>
                <input type="text" id="link" name="link" class="form-control" value="<?php echo $row["Link"]; ?>">
            </div>

            <div class="mb-3">
                <label for="access" class="form-label">Access:</label> <!-- changed to checkbox-->
                <select name="access" id="access" class="form-select">
                    <option value="0">Select...</option>
                    <?php
                    $options = ['1000', '10000', '100000', '1000000'];  
                    $selectedValue = $row["Access"] ?? '';
                    foreach ($options as $opt) {
                        $selected = ($selectedValue == $opt) ? 'selected' : '';
                        echo "<option value='$opt' $selected>$opt</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="country" class="form-label">Country:</label>
                <select name="country" class="form-select">
                    <option value="">Select a country...</option>
                    <?php
                    $filepath = 'countries.json';
                    $savedCountry = trim($row['Country'] ?? '');

                    if (file_exists($filepath)) {
                        $json = file_get_contents($filepath);
                        $countries = json_decode($json, true);

                        if (is_array($countries)) {
                            foreach ($countries as $country) {
                                $code = $country['code'] ?? '';
                                $name = $country['name'] ?? '';
                                $isSelected = ($savedCountry == $code) ? "selected" : "";
                                echo "<option value='" . htmlspecialchars($code, ENT_QUOTES) . "' $isSelected>" . htmlspecialchars($name) . "</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="rank" class="form-label">Rank:</label>
                <select name="rank" id="rank" class="form-select">
                    <option value="1" <? if ($row["Rank"] == true)
                        echo "selected"; ?>>Okay</option>
                    <option value="0" <? if ($row["Rank"] == false)
                        echo "selected"; ?>>Wrong</option>
                    <option value="NULL" <?php if ($row["Rank"] == "")
                        echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="marketcap" class="form-label">Market Cap:</label>
                <select name="marketcap" id="marketcap" class="form-select">
                    <option value="1" <? if ($row["MarketCap"] == true)
                        echo "selected"; ?>>Okay</option>
                    <option value="0" <? if ($row["MarketCap"] == false)
                        echo "selected"; ?>>Wrong</option>
                    <option value="NULL" <?php if ($row["MarketCap"] == NULL)
                        echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="liquidity" class="form-label">Liquidity:</label>
                <select name="liquidity" id="liquidity" class="form-select">
                    <option value="1" <?php if ($row["Liquidity"] == true)
                        echo "selected"; ?>>Okay</option>
                    <option value="0" <?php if ($row["Liquidity"] == false)
                        echo "selected"; ?>>Wrong</option>
                    <option value="NULL" <?php if ($row["Liquidity"] == NULL)
                        echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="fullydilutedmkc" class="form-label">Fully Diluted Market Cap:</label>
                <select name="fullydilutedmkc" id="fullydilutedmkc" class="form-select">
                    <option value="1" <?php if ($row["FullyDilutedMKC"] == true)
                        echo "selected"; ?>>Okay</option>
                    <option value="0" <?php if ($row["FullyDilutedMKC"] == false)
                        echo "selected"; ?>>Wrong</option>
                    <option value="NULL" <?php if ($row["FullyDilutedMKC"] == NULL)
                        echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="circulatingsupply" class="form-label">Circulating Supply:</label>
                <select name="circulatingsupply" id="circulatingsupply" class="form-select">
                    <option value="1" <?php if ($row["CirculatingSupply"] == 'true')
                        echo "selected"; ?>>Okay</option>
                    <option value="0" <?php if ($row["CirculatingSupply"] == 'false')
                        echo "selected"; ?>>Wrong</option>
                    <option value="NULL" <?php if ($row["CirculatingSupply"] == NULL )
                        echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="maxsupply" class="form-label">Max Supply:</label>
                <select name="maxsupply" id="maxsupply" class="form-select">
                    <option value="1" <?php if ($row["MaxSupply"] == 'true')
                        echo "selected"; ?>>Okay</option>
                    <option value="0" <?php if ($row["MaxSupply"] == 'false')
                        echo "selected"; ?>>Wrong</option>
                    <option value="NULL" <?php if ($row["MaxSupply"] == NULL )
                        echo "selected"; ?>>
                        Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="totalsupply" class="form-label">Total Supply:</label>
                <select name="totalsupply" id="totalsupply" class="form-select">
                    <option value="K" <?php if ($row["TotalSupply"] == 'K')
                        echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["TotalSupply"] == 'W')
                        echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["TotalSupply"] != 'K' && $row["TotalSupply"] != 'W')
                        echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <select name="price" id="price" class="form-select">
                    <option value="K" <?php if ($row["Price"] == 'K')
                        echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["Price"] == 'W')
                        echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["Price"] != 'K' && $row["Price"] != 'W')
                        echo "selected"; ?>>
                        Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="graph" class="form-label">Graph:</label>
                <select name="graph" id="graph" class="form-select">
                    <option value="K" <?php if ($row["Graph"] == 'K')
                        echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["Graph"] == 'W')
                        echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["Graph"] != 'K' && $row["Graph"] != 'W')
                        echo "selected"; ?>>
                        Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="holders" class="form-label">Holders:</label>
                <select name="holders" id="holders" class="form-select">
                    <option value="K" <?php if ($row["Holders"] == 'K')
                        echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["Holders"] == 'W')
                        echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["Holders"] != 'K' && $row["Holders"] != 'W')
                        echo "selected"; ?>>
                        Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tokenLogo" class="form-label">Token Logo:</label>
                <select name="tokenLogo" id="tokenLogo" class="form-select">
                    <option value="K" <?php if ($row["TokenLogo"] == 'K')
                        echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["TokenLogo"] == 'W')
                        echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["TokenLogo"] != 'K' && $row["TokenLogo"] != 'W')
                        echo "selected"; ?>>
                        Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="socialmedia" class="form-label">Social Media:</label>
                <select name="socialmedia" id="socialmedia" class="form-select">
                    <option value="K" <?php if ($row["SocialMedia"] == 'K')
                        echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["SocialMedia"] == 'W')
                        echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["SocialMedia"] != 'K' && $row["SocialMedia"] != 'W')
                        echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="metamaskbutton" class="form-label">Metamask Button:</label>
                <select name="metamaskbutton" id="metamaskbutton" class="form-select">
                    <option value="K" <?php if ($row["MetamaskButton"] == 'K')
                        echo "selected"; ?>>Okay</option>
                    <option value="W" <?php if ($row["MetamaskButton"] == 'W')
                        echo "selected"; ?>>Wrong</option>
                    <option value="Z" <?php if ($row["MetamaskButton"] != 'K' && $row["MetamaskButton"] != 'W')
                        echo "selected"; ?>>Unavailable</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="obs1" class="form-label">Obs1:</label>
                <input type="text" name="obs1" class="form-control" value="<?php echo $row["Obs1"]; ?>"
                    onblur="validateInput(this)">
            </div>

            <div class="mb-3">
                <label for="obs2" class="form-label">Obs2:</label>
                <input type="text" name="obs2" class="form-control" value="<?php echo $row["Obs2"]; ?>"
                    onblur="validateInput(this)">
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

            <input type="hidden" name="last_updated"
                value="<?php echo (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'); ?>">

            
            <div class="d-flex gap-2">
                <button type="submit" name="action" value="save" class="btn btn-primary"> Save </button> <!-- submit and redirect to edit.php?id=...-->
                <button type="submit" name="action" value="save_close" class="btn btn-success"> Save and Close </button> <!-- submit and redirect to index.php -->
                <a href="index.php" class="btn btn-secondary"> Cancel </a>
            </div>
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
        formFields.forEach(function (field) {
            field.addEventListener("change", calculateScore);
        });

        // Call the function initially to calculate and display the initial score
        calculateScore();
    </script>
</body>

</html>