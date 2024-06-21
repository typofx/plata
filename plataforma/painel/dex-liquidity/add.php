<?php
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

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
    <title>Add DEX</title>
</head>

<body>
    <h1>Add DEX</h1>

    <br>
    <?php
    include 'conexao.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $contract = $_POST['contract'];

        // Processar upload da imagem
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $imageFileType = strtolower(pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION));
            $unique_filename = uniqid('PLATA_', true) . '_' . rand(1000, 9999) . '.' . $imageFileType;
            $target_file = $target_dir . $unique_filename;

            $uploadOk = 1;
            $check = getimagesize($_FILES["logo"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            if ($_FILES["logo"]["size"] > 10000000) { // 10MB
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                    echo "The file " . basename($unique_filename) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    $uploadOk = 0;
                }
            }
        } else {
            $uploadOk = 0;
        }

        if ($uploadOk) {
            $logo = $target_file;

            // Inserting new record into database
            $sql = "INSERT INTO granna80_bdlinks.dex_liquidity (name, logo, contract) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

            // The type string and parameters must match
            $stmt->bind_param("sss", $name, $logo, $contract);

            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error creating record: " . $stmt->error;
            }
        }
    }
    ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value=""><br>

        <label for="contract">Contract:</label><br>
        <input type="text" id="contract" name="contract" value=""><br>

        <label for="logo">Logo:</label><br>
        <input type="file" id="logo" name="logo"><br>
        <br>

        <input type="submit" value="Add">
    </form>
    <a href='index.php'>Back to List</a>

</body>

</html>
