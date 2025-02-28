<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';?>
<?php

$userNameUser = $userEmail;
include 'conexao.php';

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
            $uploadFileDir = '/home2/granna80/public_html/website_d7042c63/images/icolog/';
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
            $uploadFileDir = '/home2/granna80/public_html/website_d7042c63/images/icolog/';
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
        SocialMedia = '$socialmedia',
        MetamaskButton = '$metamaskbutton',
        Obs1 = '$obs1',
        Obs2 = '$obs2',
        Email = '$email',
        Telegram = '$telegram',
        Listed = '$listed',
        last_updated = '$currentDateTime',
        editedBy = '$userNameUser'";


    if (!empty($_FILES['logo']['tmp_name'])) {
        $sql .= ", logo = '$logoFileName'";
    }


   if (!empty($_FILES['full_logo']['tmp_name'])) {
    $sql .= ", full_logo = '$fullLogoFileName'";
}


    $sql .= " WHERE ID = $id";


    if ($conn->query($sql) === TRUE) {
        echo 'updated successfully';
        echo '<script>
        setTimeout(function() {
            window.location.href = "edit.php?id=' . $id . '";
        }, 1000); // Redirect after 2 seconds
    </script>';
    
    } else {
        echo "Error updating record: ";
    }
}

$conn->close();
