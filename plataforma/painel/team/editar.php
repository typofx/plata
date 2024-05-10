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
    <title>Edit Team Member</title>
</head>
<body>
    <h2>Edit Team Member</h2>

    <?php
    
    // Include database configuration file
    include "conexao.php";

    // Define a variable to store error messages
    $error = "";

    // Check if a valid ID is provided in the URL
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if a new image file is uploaded
            if(isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
                $errors= array();
                $file_name = $_FILES['profilePicture']['name'];
                $file_size = $_FILES['profilePicture']['size'];
                $file_tmp = $_FILES['profilePicture']['tmp_name'];
                $file_type = $_FILES['profilePicture']['type'];
                $file_ext=strtolower(end(explode('.',$_FILES['profilePicture']['name'])));
                
                $extensions= array("jpeg","jpg","png");
                
                if(in_array($file_ext,$extensions)=== false){
                   $errors[]="Extension not allowed, please choose a JPEG or PNG file.";
                }
                
                if($file_size > 2097152) {
                   $errors[]='File size must be maximum 2 MB';
                }
                
                if(empty($errors)==true) {
                   move_uploaded_file($file_tmp,"uploads/".$file_name);
                   $profilePicture = "uploads/".$file_name;
                } else {
                   $error = implode(", ",$errors);
                }
            } else {
                $error = "Error uploading image.";
            }

            if(empty($error)) {
                // Get form data
                $position = $_POST["position"];
                $name = $_POST["name"];
                $socialMedia = $_POST["socialMedia"];
                $socialMedia1 = $_POST["socialMedia1"];
                $socialMedia2 = $_POST["socialMedia2"];

                // Update team member data in the database
                $sql = "UPDATE granna80_bdlinks.team SET teamProfilePicture='$profilePicture', teamPosition='$position', teamName='$name', teamSocialMedia0='$socialMedia', teamSocialMedia1='$socialMedia1', teamSocialMedia2='$socialMedia2' WHERE id=$id";

                if ($conn->query($sql) === TRUE) {
                    echo "Team member data updated successfully!";
                } else {
                    $error = "Error updating data: " . $conn->error;
                }
            }
        }

        // SQL query to select data of the member based on provided ID
        $sql = "SELECT * FROM granna80_bdlinks.team WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Display edit form with pre-filled member data
            $row = $result->fetch_assoc();
    ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="post" enctype="multipart/form-data">
                <label for="profilePicture">Profile Picture:</label><br>
                <?php if(!empty($row['teamProfilePicture'])) { ?>
                    <img src="<?php echo $row['teamProfilePicture']; ?>" alt="Current Profile Picture" width="150"><br>
                <?php } ?>
                <input type="file" id="profilePicture" name="profilePicture" required><br>
                
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" value="<?php echo $row['teamName']; ?>" required><br>

                <label for="position">Position:</label><br>
                <input type="text" id="position" name="position" value="<?php echo $row['teamPosition']; ?>" required><br>
                
                <label for="socialMedia">Social Media 1:</label><br>
                <input type="text" id="socialMedia" name="socialMedia" value="<?php echo $row['teamSocialMedia0']; ?>" required><br>
                
                <label for="socialMedia1">Social Media 2:</label><br>
                <input type="text" id="socialMedia1" name="socialMedia1" value="<?php echo $row['teamSocialMedia1']; ?>" required><br>
                
                <label for="socialMedia2">Social Media 3:</label><br>
                <input type="text" id="socialMedia2" name="socialMedia2" value="<?php echo $row['teamSocialMedia2']; ?>" required><br>
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

    // Display error message, if any
    if (!empty($error)) {
        echo "<p>$error</p>";
    }

    // Close database connection
    $conn->close();
    ?>
</body>
</html>
