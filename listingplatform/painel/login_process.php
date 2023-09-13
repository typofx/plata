<?php
session_start();
include '../conexao.php';

// Include Google Authenticator classes
include_once('vendor/sonata-project/google-authenticator/src/FixedBitNotation.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php');

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

// Define the secret key used for Google Authenticator
$secret = ' '; // Replace with your secret key

if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["token"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $token = $_POST["token"];

    $query = "SELECT email, password FROM granna80_bdlinks.users WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $userRow = $result->fetch_assoc();
        $validEmail = $userRow["email"];
        $hashedPassword = $userRow["password"];

        if (password_verify($password, $hashedPassword) && $g->checkCode($secret, $token)) {
            // User is authenticated, store the email in the session
            $_SESSION["user_email"] = $email;
            $_SESSION["user_logged_in"] = true;

            ob_end_clean();
            echo '<script>window.location.href = "painel.php";</script>';
            exit();
        } else {
            ob_end_clean();
            echo '<script>window.location.href = "index.php?error=1";</script>';
            exit();
        }
    } else {
        ob_end_clean();
        echo '<script>window.location.href = "index.php?error=1";</script>';
        exit();
    }

    $stmt->close();
    $conn->close();
}

// Access the email of the logged-in user from the session
if (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true) {
    $loggedInUserEmail = $_SESSION["user_email"];
    echo "Logged-in user's email: " . $loggedInUserEmail;
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

?>
