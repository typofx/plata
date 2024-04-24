<?php

session_start(); // start session

// Check if the user is authenticated
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // User is not authenticated, redirect back to login page
    header("Location: index.php");
    exit();
}

$userName = $_SESSION["user_email"];
include '../conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {


     if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['logo']['tmp_name'];
        $fileName = $_FILES['logo']['name'];
        $fileSize = $_FILES['logo']['size'];
        $fileType = $_FILES['logo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

 
        $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
        if ($fileSize > $maxFileSize) {
            echo "Error: File size exceeds 5MB.";
            exit();
        }


        $allowedFileExtensions = array('jpg', 'jpeg', 'png', 'gif', 'ico');
        if (in_array($fileExtension, $allowedFileExtensions)) {
           
            $uploadFileDir = '../../images/icolog/';
            $destPath = $uploadFileDir . $fileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
            
            } else {
                echo "Error moving file.";
                exit();
            }
        } else {
            echo "Error: Only JPG, JPEG, PNG, GIF and ICO files are allowed.";
            exit();
        }
    } else {
        echo "Error: No image files sent or upload error.";
        exit();
    }


    $logoFileName = isset($fileName) ? $fileName : '';
   
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
    $listed = $_POST["listed"];
   
    $utcZeroTimezone = new DateTimeZone('Etc/UTC');  
$currentDateTime = new DateTime('now', $utcZeroTimezone);
$currentDateTimeFormatted = $currentDateTime->format('Y-m-d H:i:s');
$currentDateTime = $currentDateTimeFormatted; 

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
        Telegram = '$telegram',
        Listed = '$listed',
       last_updated = '$currentDateTime',
       logo =  '$logoFileName',
       editedBy = '$userName'
        WHERE ID = $id";

    if ($conn->query($sql) === TRUE) {
        echo '<script>window.location.href = "painel.php";</script>';
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
