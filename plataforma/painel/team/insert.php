<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Include Google Authenticator classes
include_once('vendor/sonata-project/google-authenticator/src/FixedBitNotation.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php');

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

// Define the secret key used for Google Authenticator
$secret = ''; // Replace with your secret key

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate a unique identifier for linking the tables
    $uuid = uniqid('', true);

    // Get form data
    $email = $_POST["email"];
    $password = $_POST["password"];
    $token = $_POST["token"];
    $name = $_POST["name"];
    $lastName = $_POST["last_name"];
    $level = $_POST["level"];
    $username = $name . '_' . $lastName;

    if (!$g->checkCode($secret, $token)) {
        echo "Invalid token. Registration cannot be completed.";
        exit();
    }

    // Hash the password using the default PHP SHA256 password_hash() function
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into users table
    $sql_user = "INSERT INTO granna80_bdlinks.users (uuid, email, password, name, last_name, level, username) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_user = $conn->prepare($sql_user);

    if ($stmt_user) {
        $stmt_user->bind_param("sssssss", $uuid, $email, $hashed_password, $name, $lastName, $level, $username);

        if (!$stmt_user->execute()) {
            echo "Error registering user: " . $stmt_user->error;
            exit();
        }

        $stmt_user->close();
    } else {
        echo "Error preparing SQL statement for user: " . $conn->error;
        exit();
    }

    // Handle file upload
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

        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads';
        
        if (empty($errors)) {
            move_uploaded_file($file_tmp, $file_path . '/' . $file_name);
            echo "File " . $file_name . " uploaded successfully!";
        } else {
            print_r($errors);
        }
    }

    $profilePicture = "uploads/" . $_FILES['profilePicture']['name'];
    $position = htmlspecialchars($_POST["position"]);
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

    // Insert into team table
    $sql_team = "INSERT INTO granna80_bdlinks.team (uuid, teamProfilePicture, teamPosition, teamName, teamSocialMedia0, teamSocialMedia1, teamSocialMedia2, teamSocialMedia3, teamSocialMedia4, teamSocialMedia5, teamSocialMedia6, teamSocialMedia7, teamSocialMedia8, teamSocialMedia9) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_team = $conn->prepare($sql_team);

    if ($stmt_team) {
        $name = $name. " " .$lastName;
        $stmt_team->bind_param("ssssssssssssss", $uuid, $profilePicture, $position, $name, $socialMedia, $socialMedia1, $socialMedia2, $socialMedia3, $socialMedia4, $socialMedia5, $socialMedia6, $socialMedia7, $socialMedia8, $socialMedia9);

        if (!$stmt_team->execute()) {
            echo "Error adding team: " . $stmt_team->error;
            exit();
        }

        $stmt_team->close();
    } else {
        echo "Error preparing SQL statement for team: " . $conn->error;
        exit();
    }

    // Close the database connection
    $conn->close();
    echo "Registration and team addition completed successfully!";
} else {
    echo "The form was not submitted correctly.";
}
?>
