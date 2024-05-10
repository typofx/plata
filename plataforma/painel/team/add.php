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
        <input type="file" id="profilePicture" name="profilePicture" required ><br>
        
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="position">Position:</label><br>
        <input type="text" id="position" name="position" required><br>
        
        <label for="socialMedia">Social Media 1:</label><br>
        <input type="text" id="socialMedia" name="socialMedia" required><br>
        
        <label for="socialMedia1">Social Media 2:</label><br>
        <input type="text" id="socialMedia1" name="socialMedia1" required><br>
        
        <label for="socialMedia2">Social Media 3:</label><br>
        <input type="text" id="socialMedia2" name="socialMedia2" required><br>
        
        <input type="submit" value="Submit">
    </form>

    <?php
    
    // Check if form data is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Include database configuration file
        include "conexao.php";

        // Check if file is uploaded
        if(isset($_FILES['profilePicture'])){
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
               echo "File ".$file_name." uploaded successfully!";
            }else{
               print_r($errors);
            }
         }
        

        // Get form data
        $profilePicture = "uploads/".$_FILES['profilePicture']['name'];
        $position = $_POST["position"];
        $name = $_POST["name"];
        $socialMedia = $_POST["socialMedia"];
        $socialMedia1 = $_POST["socialMedia1"];
        $socialMedia2 = $_POST["socialMedia2"];

        // Prepare and execute SQL query to insert data into the table
        $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.team (teamProfilePicture, teamPosition, teamName, teamSocialMedia0, teamSocialMedia1, teamSocialMedia2) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $profilePicture, $position, $name, $socialMedia, $socialMedia1, $socialMedia2);
        $stmt->execute();

        // Check if insertion was successful
        if ($stmt->affected_rows > 0) {
            echo "Team added successfully!";
        } else {
            echo "Error adding team.";
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
