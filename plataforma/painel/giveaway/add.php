<?php 
session_start();
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add CoinMarketCap Dexscan UpVote</title>
</head>
<body>
    <h1>Add CoinMarketCap Dexscan UpVote</h1>
    
    <br>
    <?php
    include 'conexao.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $evm_wallet = $_POST['evm_wallet'];
        
        // Processar upload da imagem
        if (isset($_FILES['vote_image']) && $_FILES['vote_image']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "../../../giveaway/uploads/";
            $imageFileType = strtolower(pathinfo($_FILES["vote_image"]["name"], PATHINFO_EXTENSION));
            $unique_filename = uniqid('PLATA_', true) . '_' . rand(1000, 9999) . '.' . $imageFileType;
            $target_file = $target_dir . $unique_filename;

            $uploadOk = 1;
            $check = getimagesize($_FILES["vote_image"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
            
            if ($_FILES["vote_image"]["size"] > 10000000) { // 10MB
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["vote_image"]["tmp_name"], $target_file)) {
                    echo "The file ". basename($unique_filename). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    $uploadOk = 0;
                }
            }
        } else {
            $uploadOk = 0;
        }

        if ($uploadOk) {
            $vote_image = $target_file;
            $vote_number = $_POST['vote_number'];
            $time = date('Y-m-d H:i:s', strtotime($_POST['time'])); // Formatting to datetime format

            // Inserting new record into database
            $sql = "INSERT INTO granna80_bdlinks.votes (evm_wallet, vote_image, vote_number, time) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

            // The type string and parameters must match
            $stmt->bind_param("ssss", $evm_wallet, $vote_image, $vote_number, $time);

            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error creating record: " . $stmt->error;
            }
        }
    }
    ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label for="evm_wallet">EVM Wallet:</label><br>
        <input type="text" id="evm_wallet" name="evm_wallet" value=""><br>
        <label for="vote_image">Vote Image:</label><br>
        <input type="file" id="vote_image" name="vote_image"><br>
        <label for="vote_number">Vote Number:</label><br>
        <input type="text" id="vote_number" name="vote_number" value=""><br>
        <label for="time">Time:</label><br>
        <input type="datetime-local" id="time" name="time" value=""><br><br>
        <input type="submit" value="Add">
    </form>
    <a href='index.php'>Back to List</a>

</body>
</html>
