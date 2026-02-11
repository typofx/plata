<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';?>
<?php

$userNameUser = $userEmail;
include 'listingplatform.conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {



    if (!empty($_FILES['logo']['tmp_name'])) {
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
            $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/images/icolog/';
            $destPath = $uploadFileDir . $fileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
             
            } else {
                echo "Error moving file.";
                exit();
            }
        }
    }
    
    $logoFileName = isset($fileName) ? $fileName : '';




      if (!empty($_FILES['full_logo']['tmp_name'])) {
        $fileTmpPath = $_FILES['full_logo']['tmp_name'];
        $fileNameFullLogo = $_FILES['full_logo']['name'];
        $fileSizeFullLogo = $_FILES['full_logo']['size'];
        $fileTypeFullLogo = $_FILES['full_logo']['type'];
        $fileNameCmpsFullLogo = explode(".", $fileNameFullLogo);
        $fileExtensionFullLogo = strtolower(end($fileNameCmpsFullLogo));

        $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
        if ($fileSizeFullLogo > $maxFileSize) {
            echo "Error: Full logo file size exceeds 5MB.";
            exit();
        }

        $allowedFileExtensions = array('jpg', 'jpeg', 'png', 'ico', 'svg');
        if (in_array($fileExtensionFullLogo, $allowedFileExtensions)) {
            $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/images/icolog/';
            $destPathFullLogo = $uploadFileDir . $fileNameFullLogo;
            if (move_uploaded_file($fileTmpPath, $destPathFullLogo)) {

            } else {
                echo "Error moving full logo file.";
                exit();
            }
        } 
    } 
    $fullLogoFileName = isset($fileNameFullLogo) ? $fileNameFullLogo : '';
    
    $id = $_POST["id"];
    $desktop = $_POST["desktop"] ?? '';
    $mobile = $_POST["mobile"] ?? '';
    $score = $_POST["score"];
    $platform = $_POST["platform"];
    $type = $_POST["type"];
    $access = $_POST["access"];
    $country = $_POST["country"] ?? '';
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
    $tokenlogo = $_POST["tokenLogo"] ?? '';
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
        Obs1 = '$obs1',
        Obs2 = '$obs2',
        Email = '$email',
        Telegram = '$telegram',
        Listed = '$listed',
        last_updated = '$currentDateTime',
        editedBy = '$userNameUser'";

    if (($_POST["rank"]) == "NULL") { $sql .= ", Rank = NULL"; } else $sql .= ", Rank = '$rank'";
    if (($_POST["marketcap"]) == "NULL") { $sql .= ", MarketCap = NULL"; } else $sql .= ", MarketCap = '$marketcap'";
    if (($_POST["liquidity"]) == "NULL") { $sql .= ", Liquidity = NULL"; } else $sql .= ", Liquidity = '$liquidity'";
    if (($_POST["fullydilutedmkc"]) == "NULL") { $sql .= ", FullyDilutedMKC = NULL"; } else $sql .= ", FullyDilutedMKC = '$fullydilutedmkc'";
    if (($_POST["circulatingsupply"]) == "NULL") { $sql .= ", CirculatingSupply = NULL"; } else $sql .= ", CirculatingSupply = '$circulatingsupply'"; 
    if (($_POST["maxsupply"]) == "NULL") { $sql .= ", MaxSupply = NULL"; } else $sql .= ", MaxSupply = '$maxsupply'";
    if (($_POST["totalsupply"]) == "NULL") { $sql .= ", TotalSupply = NULL"; } else $sql .= ", TotalSupply = '$totalsupply'";
    if (($_POST["price"]) == "NULL") { $sql .= ", Price = NULL"; } else $sql .= ", Price = '$price'";
    if (($_POST["graph"]) == "NULL") { $sql .= ", Graph = NULL"; } else $sql .= ", Graph = '$graph'";
    if (($_POST["holders"]) == "NULL") { $sql .= ", Holders = NULL"; } else $sql .= ", Holders = '$holders'";
    if (($_POST["tokenLogo"]) == "NULL") { $sql .= ", TokenLogo = NULL"; } else $sql .= ", TokenLogo = '$tokenlogo'";
    if (($_POST["socialmedia"]) == "NULL") { $sql .= ", SocialMedia = NULL"; } else $sql .= ", SocialMedia = '$socialmedia'";
    if (($_POST["metamaskbutton"]) == "NULL") { $sql .= ", MetamaskButton = NULL"; } else $sql .= ", MetamaskButton = '$metamaskbutton'";
   

    if (!empty($_FILES['logo']['tmp_name'])) {
        $sql .= ", logo = '$logoFileName'";
    }


   if (!empty($_FILES['full_logo']['tmp_name'])) {
    $sql .= ", full_logo = '$fullLogoFileName'";
}


    $sql .= " WHERE ID = $id";


    if ($conn->query($sql) === TRUE) {
        //Get the action from the clicked button(edit.php) (defaults to 'save' if empty)
        $action = $_POST['action'] ?? 'save';
        // Logic: If 'save_close' was clicked -> go to index.php. Else -> stay on edit page.
        $destino = ($action == 'save_close') ? "index.php" : "listingplatform.edit.php?id=" . $id;
        echo 'updated successfully';
        echo '<script>
        setTimeout(function() {
            window.location.href = "' . $destino . '";
        }, 1000); // Redirect after 2 seconds
            </script>';
    
    } else {
        echo "Error updating record: ";
    }
}

$conn->close();
