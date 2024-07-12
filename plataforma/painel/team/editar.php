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
    <title>Edit Team Member</title>
</head>
<body>
    <h2>Edit Team Member</h2>

    <?php
    
    include "conexao.php";
    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads';
    $error = "";

    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
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
                
                $file_path = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads';

                if (empty($errors)) {
                   $new_file_name = uniqid() . '.' . $file_ext;
                   move_uploaded_file($file_tmp, $file_path . '/' . $new_file_name);
                   $profilePicture = 'uploads/' . $new_file_name;
                } else {
                   $error = implode(", ", $errors);
                }
            }

            if(empty($error)) {
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
                $active = isset($_POST["active"]) ? 1 : 0;

                if (isset($profilePicture)) {
                    $sql = "UPDATE granna80_bdlinks.team SET teamProfilePicture=?, teamPosition=?, teamName=?, teamSocialMedia0=?, teamSocialMedia1=?, teamSocialMedia2=?, teamSocialMedia3=?, teamSocialMedia4=?, teamSocialMedia5=?, teamSocialMedia6=?, teamSocialMedia7=?, teamSocialMedia8=?, teamSocialMedia9=?, active=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssssssssssssi", $profilePicture, $position, $name, $socialMedia, $socialMedia1, $socialMedia2, $socialMedia3, $socialMedia4, $socialMedia5, $socialMedia6, $socialMedia7, $socialMedia8, $socialMedia9, $active, $id);
                } else {
                    $sql = "UPDATE granna80_bdlinks.team SET teamPosition=?, teamName=?, teamSocialMedia0=?, teamSocialMedia1=?, teamSocialMedia2=?, teamSocialMedia3=?, teamSocialMedia4=?, teamSocialMedia5=?, teamSocialMedia6=?, teamSocialMedia7=?, teamSocialMedia8=?, teamSocialMedia9=?, active=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssssssssssi", $position, $name, $socialMedia, $socialMedia1, $socialMedia2, $socialMedia3, $socialMedia4, $socialMedia5, $socialMedia6, $socialMedia7, $socialMedia8, $socialMedia9, $active, $id);
                }

                if ($stmt->execute()) {
                    echo "Team member data updated successfully!";
                } else {
                    $error = "Error updating data: " . $conn->error;
                }
            }
        }

        $sql = "SELECT * FROM granna80_bdlinks.team WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
    ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="post" enctype="multipart/form-data">
                <label for="profilePicture">Profile Picture:</label><br>
                <?php if (!empty($row['teamProfilePicture'])) { ?>
    <img src="<?php echo htmlspecialchars('/images/' . $row['teamProfilePicture']); ?>" alt="Current Profile Picture" width="150"><br>
<?php } ?>

                <input type="file" id="profilePicture" name="profilePicture"><br>
                
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['teamName']); ?>" required><br>

                <label for="position">Position:</label><br>
                <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($row['teamPosition']); ?>" required><br>
                
                <label for="socialMedia">Whatsapp:</label><br>
                <input type="text" id="socialMedia" name="socialMedia" value="<?php echo htmlspecialchars($row['teamSocialMedia0']); ?>"><br>
                
                <label for="socialMedia1">Instagram:</label><br>
                <input type="text" id="socialMedia1" name="socialMedia1" value="<?php echo htmlspecialchars($row['teamSocialMedia1']); ?>"><br>
                
                <label for="socialMedia2">Telegram:</label><br>
                <input type="text" id="socialMedia2" name="socialMedia2" value="<?php echo htmlspecialchars($row['teamSocialMedia2']); ?>"><br>

                <label for="socialMedia3">Facebook:</label><br>
                <input type="text" id="socialMedia3" name="socialMedia3" value="<?php echo htmlspecialchars($row['teamSocialMedia3']); ?>"><br>

                <label for="socialMedia4">Github:</label><br>
                <input type="text" id="socialMedia4" name="socialMedia4" value="<?php echo htmlspecialchars($row['teamSocialMedia4']); ?>"><br>

                <label for="socialMedia5">Email:</label><br>
                <input type="text" id="socialMedia5" name="socialMedia5" value="<?php echo htmlspecialchars($row['teamSocialMedia5']); ?>"><br>

                <label for="socialMedia6">Twitter:</label><br>
                <input type="text" id="socialMedia6" name="socialMedia6" value="<?php echo htmlspecialchars($row['teamSocialMedia6']); ?>"><br>
                
                <label for="socialMedia7">LinkedIn:</label><br>
                <input type="text" id="socialMedia7" name="socialMedia7" value="<?php echo htmlspecialchars($row['teamSocialMedia7']); ?>"><br>

                <label for="socialMedia8">Twitch:</label><br>
                <input type="text" id="socialMedia8" name="socialMedia8" value="<?php echo htmlspecialchars($row['teamSocialMedia8']); ?>"><br>
        
                <label for="socialMedia9">Medium:</label><br>
                <input type="text" id="socialMedia9" name="socialMedia9" value="<?php echo htmlspecialchars($row['teamSocialMedia9']); ?>"><br>

                <label for="active">Currently work here:</label><br>
                <input type="checkbox" id="active" name="active" <?php echo $row['active'] == 1 ? 'checked' : ''; ?>><br>

                <a href="index.php">Back</a>
                <input type="submit" value="Save">
            </form>
    <?php
        } else {
            $error = "No team member found with provided ID.";
        }
    } else {
        $error = "Invalid member ID.";
    }

    if (!empty($error)) {
        echo "<p>$error</p>";
    }

    $conn->close();
    ?>
</body>
</html>
