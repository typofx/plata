<?php session_start();
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
</head>
<body>
    <h2>Add Member</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="profilePicture">Profile Picture:</label><br>
        <input type="file" id="profilePicture" name="profilePicture" required><br>
        
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="position">Position:</label><br>
        <input type="text" id="position" name="position" required><br>
        
        <label for="socialMedia">Whatsapp:</label><br>
        <input type="text" id="socialMedia" name="socialMedia"><br>
        
        <label for="socialMedia1">Instagram:</label><br>
        <input type="text" id="socialMedia1" name="socialMedia1"><br>
        
        <label for="socialMedia2">Telegram:</label><br>
        <input type="text" id="socialMedia2" name="socialMedia2"><br>

        <label for="socialMedia3">Facebook:</label><br>
        <input type="text" id="socialMedia3" name="socialMedia3"><br>

        <label for="socialMedia4">Github:</label><br>
        <input type="text" id="socialMedia4" name="socialMedia4"><br>

        <label for="socialMedia5">Email:</label><br>
        <input type="text" id="socialMedia5" name="socialMedia5"><br>

        <label for="socialMedia6">Twitter:</label><br>
        <input type="text" id="socialMedia6" name="socialMedia6"><br>
        
        <label for="socialMedia7">LinkedIn:</label><br>
        <input type="text" id="socialMedia7" name="socialMedia7"><br>

        <label for="socialMedia8">Twitch:</label><br>
        <input type="text" id="socialMedia8" name="socialMedia8"><br>
        
        <label for="socialMedia9">Medium:</label><br>
        <input type="text" id="socialMedia9" name="socialMedia9"><br>
        
        <a href="index.php">Back</a>
        <input type="submit" value="Submit">
    </form>

    <?php
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include "conexao.php";

        if (isset($_FILES['profilePicture'])) {
            $errors = array();
            $file_name = $_FILES['profilePicture']['name'];
            $file_size = $_FILES['profilePicture']['size'];
            $file_tmp = $_FILES['profilePicture']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            $extensions = array("jpeg", "jpg", "png");
            
            if (!in_array($file_ext, $extensions)) {
               $errors[] = "Extension not allowed, please choose a JPEG or PNG file.";
            }
            
            if ($file_size > 2097152) {
               $errors[] = 'File size must be maximum 2 MB';
            }
            
            if (empty($errors)) {
               move_uploaded_file($file_tmp, "uploads/" . $file_name);
               echo "File " . $file_name . " uploaded successfully!";
            } else {
               print_r($errors);
            }
        }
        
        $profilePicture = "uploads/" . $_FILES['profilePicture']['name'];
        $position = htmlspecialchars($_POST["position"]);
        $name = htmlspecialchars($_POST["name"]);
        $socialMedia = htmlspecialchars($_POST["socialMedia"]);
        $socialMedia1 = htmlspecialchars($_POST["socialMedia1"]);
        $socialMedia2 = htmlspecialchars($_POST["socialMedia2"]);
        $socialMedia3 = htmlspecialchars($_POST["socialMedia3"]);
        $socialMedia4 = htmlspecialchars($_POST["socialMedia4"]);
        $socialMedia5 = htmlspecialchars($_POST["socialMedia5"]);
        $socialMedia6 = htmlspecialchars($_POST["socialMedia6"]);
        $socialMedia7 = htmlspecialchars($_POST["socialMedia7"]);
        $socialMedia8 = htmlspecialchars($_POST["socialMedia8"]);
        $socialMedia9 = htmlspecialchars($_POST["socialMedia9"]);

        $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.team (teamProfilePicture, teamPosition, teamName, teamSocialMedia0, teamSocialMedia1, teamSocialMedia2, teamSocialMedia3, teamSocialMedia4, teamSocialMedia5, teamSocialMedia6, teamSocialMedia7, teamSocialMedia8, teamSocialMedia9) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssss", $profilePicture, $position, $name, $socialMedia, $socialMedia1, $socialMedia2, $socialMedia3, $socialMedia4, $socialMedia5, $socialMedia6, $socialMedia7, $socialMedia8, $socialMedia9);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Team added successfully!";
        } else {
            echo "Error adding team.";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
