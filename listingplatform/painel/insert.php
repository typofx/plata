<?php
session_start(); // Start session

// Check if the user is authenticated
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // User is not authenticated, redirect back to login page
    header("Location: index.php");
    exit();
}
$userName = $_SESSION["user_email"];

$utcZeroTimezone = new DateTimeZone('Etc/UTC');
$currentDateTime = new DateTime('now', $utcZeroTimezone);
$currentDateTimeFormatted = $currentDateTime->format('Y-m-d H:i:s');
$currentDateTime = $currentDateTimeFormatted;
echo $currentDateTime;



include '../conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$desktop = $_POST['desktop'];
    //$mobile = $_POST['mobile'];
   
    $score = 'NOT';
    $platform = $_POST['platform'];
    $platform = $_POST['platform'];
    $type = $_POST['type'];
    $access = $_POST['access'];
    $country = $_POST['country'];
    $link = $_POST['link'];
    //$rank = $_POST['rank'];
    //$marketCap = $_POST['marketCap'];
    //$liquidity = $_POST['liquidity'];
    //$fullyDilutedMKC = $_POST['fullyDilutedMKC'];
    //$circulatingSupply = $_POST['circulatingSupply'];
    //$maxSupply = $_POST['maxSupply'];
    //$totalSupply = $_POST['totalSupply'];
    //$price = $_POST['price'];
    //$graph = $_POST['graph'];
    //$holders = $_POST['holders'];
    //$tokenLogo = $_POST['tokenLogo'];
    //$socialMedia = $_POST['socialMedia'];
    //$metamaskButton = $_POST['metamaskButton'];
    $obs1 = $_POST['obs1'];
    $obs2 = $_POST['obs2'];
    $telegram = $_POST['telegram'];
    $email = $_POST['zemail'];

    $utcZeroTimezone = new DateTimeZone('Etc/UTC');
    $currentDateTime = new DateTime('now', $utcZeroTimezone);
    $currentDateTimeFormatted = $currentDateTime->format('Y-m-d H:i:s');
    $currentDateTime = $currentDateTimeFormatted;
    echo $currentDateTime;


    $sql = "INSERT INTO granna80_bdlinks.links (Desktop, Mobile, Score, Platform, Link, Type, Access, Country, Rank, MarketCap, Liquidity, FullyDilutedMKC, CirculatingSupply, MaxSupply, TotalSupply, Price, Graph, Holders, TokenLogo, SocialMedia, MetamaskButton, Obs1, Obs2, Telegram, Email, InsertDateTime, insertBy)
    VALUES ('$desktop', '$mobile', '$score', '$platform','$link', '$type', '$access', '$country', '$rank', '$marketCap', '$liquidity', '$fullyDilutedMKC', '$circulatingSupply', '$maxSupply', '$totalSupply', '$price', '$graph', '$holders', '$tokenLogo', '$socialMedia', '$metamaskButton', '$obs1', '$obs2', '$telegram', '$email'  , '$currentDateTime', '$userName')";


    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>


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
    <h2>Add New Record</h2>
    <form method="POST" action="">
        <!--<label for="desktop">Desktop:</label>
        <input type="text" name="desktop" required><br>
        
        <label for="mobile">Mobile:</label>
        <input type="text" name="mobile" required><br>

        <label for="score">Score:</label>
        <input type="text" name="score" required><br>-->

        <label for="platform">Platform (Name):</label>
        <input type="text" name="platform" required><br>

        <label for="Link">Link:</label>
        <input type="text" id="link" name="link" required><br>

        <br> <label for="type">Type:</label>
        <select name="type" id="type">
            <option value="Index">Index</option>
            <option value="Bot">Bot</option>
            <option value="Tool">Tool</option>
            <option value="Dex">Dex</option>
            <option value="Audity">Audity</option>
            <option value="Nft">Nft</option>
            <option value="Audity/KYC">Audity/KYC</option>
            <option value="Cex">Cex</option>
            <option value="Newspaper">Newspaper</option>
            <option value="Kyc">Kyc</option>
            <option value="Voting">Voting</option>
            <option value="Funding">Funding</option>
            <option value="Chart">Chart</option>
            <option value="Wallet">Wallet</option>
        </select>
        <br>
        <table>
            <tr>
                <td>
                    <label for="access">Access:</label>
                    <input type="text" id="access" name="access" required>
                </td>
                <td>
                    <label for="accessLink">Views Analytics:</label>
                    <span id="accessLink"></span>
                </td>
            </tr>
        </table>


        <br>

        <table>
            <tr>
                <td>
                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country" required>
                </td>
                <td>
                    <label for="accessCountry">Country info:</label>
                    <span id="accessCountry"></span>
                </td>
            </tr>
        </table>

        <!--<label for="rank">Rank:</label>
        <input type="text" name="rank" required><br>

        <label for="marketCap">Market Cap:</label>
        <input type="text" name="marketCap" required><br>

        <label for="liquidity">Liquidity:</label>
        <input type="text" name="liquidity" required><br>

        <label for="fullyDilutedMKC">Fully Diluted Market Cap:</label>
        <input type="text" name="fullyDilutedMKC" required><br>

        <label for="circulatingSupply">Circulating Supply:</label>
        <input type="text" name="circulatingSupply" required><br>

        <label for="maxSupply">Max Supply:</label>
        <input type="text" name="maxSupply" required><br>

        <label for="totalSupply">Total Supply:</label>
        <input type="text" name="totalSupply" required><br>

        <label for="price">Price:</label>
        <input type="text" name="price" required><br>

        <label for="graph">Graph:</label>
        <input type="text" name="graph" required><br>

        <label for="holders">Holders:</label>
        <input type="text" name="holders" required><br>

        <label for="tokenLogo">Token Logo:</label>
        <input type="text" name="tokenLogo" required><br>

        <label for="socialMedia">Social Media:</label>
        <input type="text" name="socialMedia" required><br>

        <label for="metamaskButton">Metamask Button:</label>
        <input type="text" name="metamaskButton" required>--><br>

        <label for="obs1">Obs1:</label>
        <input type="text" name="obs1" onblur="validateInput(this)"><br>

        <label for="obs2">Obs2:</label>
        <input type="text" name="obs2" onblur="validateInput(this)"><br>

        <label for="Telegram">Telegram:</label>
        <input type="text" name="telegram"><br>

        <label for="Email">Email:</label>
        <input type="email" name="zemail"><br>
        <label for="user_edit"></label>Who is insert:</label> <input type="user_edit" name="user_edit" value="<?php echo $userName; ?>"><br>
        <button type="submit">Add New</button> <a href="painel.php" class="btn btn-primary">Cancel</a>
    </form>
    <div class="container-fluid">
        <!-- Add a button or link to navigate to the insert.php page -->


        <?php
        // Your existing PHP code for displaying the table goes here
        ?>
    </div>
    <script>
        function validateInput(input) {
            var regex = /^[a-zA-Z0-9]*$/;
            if (!regex.test(input.value)) {
                alert("Please enter only alphanumeric characters.");
                input.value = '';
            }
        }
    </script>
</body>

</html>