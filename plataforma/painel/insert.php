<?php
session_start(); 

// Check if user is logged in
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: index.php");
    exit();
}

$userName = $_SESSION["user_email"];

$currentDateTime = (new DateTime('now', new DateTimeZone('Etc/UTC')))->format('Y-m-d H:i:s');

include '../conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // Check if an image file is uploaded
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['logo']['tmp_name'];
        $fileName = $_FILES['logo']['name'];
        $fileSize = $_FILES['logo']['size'];
        $fileType = $_FILES['logo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Maximum file size allowed (5MB)
        $maxFileSize = 5 * 1024 * 1024; 
        if ($fileSize > $maxFileSize) {
            echo "Error: File size exceeds 5MB.";
            exit();
        }

        // Check if the uploaded file is an image
        $allowedFileExtensions = array('jpg', 'jpeg', 'png', 'gif', 'ico');
        if (in_array($fileExtension, $allowedFileExtensions)) {
            // Move the file to the desired directory
            $uploadFileDir = '../../images/icolog/';
            $destPath = $uploadFileDir . $fileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // File moved successfully
            } else {
                echo "Error moving file.";
                exit();
            }
        } else {
            echo "Error: Only JPG, JPEG, PNG, GIF, and ICO files are allowed.";
            exit();
        }
    } else {
        echo "Error: No image file uploaded or upload error.";
        exit();
    }

    $logoFileName = isset($fileName) ? $fileName : '';

    // Get values of other form fields
    $platform = isset($_POST['platform_name']) ? $_POST['platform_name'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $access = isset($_POST['access']) ? $_POST['access'] : '';
    $country = isset($_POST['country']) ? $_POST['country'] : '';
    $link = isset($_POST['link']) ? $_POST['link'] : '';
    $obs1 = isset($_POST['obs1']) ? $_POST['obs1'] : '';
    $obs2 = isset($_POST['obs2']) ? $_POST['obs2'] : '';
    $telegram = isset($_POST['telegram']) ? $_POST['telegram'] : '';
    $email = isset($_POST['zemail']) ? $_POST['zemail'] : '';

    // Prepare and execute SQL statement to insert data into database
    $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.links (Platform, Link, Type, Access, Country, Obs1, Obs2, Telegram, Email, logo, InsertDateTime, insertBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $platform, $link, $type, $access, $country, $obs1, $obs2, $telegram, $email, $logoFileName, $currentDateTime, $userName);
    if ($stmt->execute()) {
        echo "New record created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
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
    <form method="POST" action="" enctype="multipart/form-data">
        <!--<label for="desktop">Desktop:</label>
        <input type="text" name="desktop" required><br>
        
        <label for="mobile">Mobile:</label>
        <input type="text" name="mobile" required><br>

        <label for="score">Score:</label>
        <input type="text" name="score" required><br>-->

        <label for="logo">Logo (max 5MB):</label>
        <input type="file" name="logo" id="logo" accept="image/*" required><br>

        <label for="platform">Platform (Name):</label>
        <input type="text" name="platform_name" required><br>

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