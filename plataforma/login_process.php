<?php


// Definir a duração da sessão para 8 horas (28800 segundos)
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();



include 'conexao.php';

// Include Google Authenticator classes
include_once('vendor/sonata-project/google-authenticator/src/FixedBitNotation.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php');
include_once('vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php');

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

// Define the secret key used for Google Authenticator
$secret = ''; // Replace with your secret key

if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["token"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $token = $_POST["token"];

    $query = "SELECT * FROM granna80_bdlinks.users WHERE email = ?";
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
            $_SESSION["user_user"] = $userRow["username"];
            $_SESSION["user_level"] = $userRow["level"];
            $_SESSION["user_uuid"] = $userRow["uuid"];



            $verificationCode = generateVerificationCode();

            // Send the verification code to the user's email
            if (sendVerificationCode($email, $verificationCode)) {
                // Store the code in the session
                $_SESSION['verification_code'] = $verificationCode;

                // Redirect to code verification page
                echo '<script>window.location.href = "verify_code.php";</script>';
                exit();
            } else {
                echo 'Error sending verification code to email.';
            }
        } else {

            echo '<script>window.location.href = "index.php?error=1";</script>';
            exit();
        }
    } else {

        echo '<script>window.location.href = "index.php?error=1";</script>';
        exit();
    }

    $stmt->close();
    $conn->close();
}

// Access the email of the logged-in user from the session
//if (isset($_SESSION["user_logged_in"]) && $_SESSION["user_logged_in"] === true) {
//  $loggedInUserEmail = $_SESSION["user_email"];
//   echo "Logged-in user's email: " . $loggedInUserEmail;
//}

error_reporting(E_ALL);
ini_set('display_errors', '1');


// Function to generate the 6-digit code
function generateVerificationCode()
{
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

// Function to send the verification code to the email
function sendVerificationCode($email, $code)
{
    $_SESSION["code_email"] = $email;

    $subject = "Your new verification code";
    $message = "Your verification code is: " . $code;
    $headers = "From: Typo FX <no-reply@plata.ie>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";

    // Send the email
    if (mail($email, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}
