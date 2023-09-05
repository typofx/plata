<?php
session_start(); // Start the session

// Check if the user is authenticated
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    // User is not authenticated, redirect back to the login page
    header("Location: ../../painel");
    exit();
}

include 'conexao.php';

// Include Google Authenticator classes
include_once('vendor/sonata-project/google-authenticator/src/FixedBitNotation.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php');

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

// Define the secret key used for Google Authenticator
$secret = 'XVQ2UIGO75XRUKJO'; // Replace with your secret key

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $token = $_POST["token"];

    if (!$g->checkCode($secret, $token)) {

        echo "Invalid token. Registration cannot be completed.";
        exit();
    }

    // Continue with user insertion

    // Hash the password using the default PHP password_hash() function
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert data into the "users" table
    $sql = "INSERT INTO granna80_bdlinks.users (email, password) VALUES (?, ?)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ss", $email, $hashed_password);

        // Execute the SQL statement to insert data
        if ($stmt->execute()) {
            echo "Registration done successfully!";
        } else {
            echo "Error registering user: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "The form was not submitted correctly.";
}
?>
