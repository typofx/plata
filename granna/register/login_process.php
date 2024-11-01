<?php
session_start();
include 'conexao.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT password FROM granna80_bdlinks.granna_users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
           
            $verificationCode = generateVerificationCode();

         
            if (sendVerificationCode($email, $verificationCode)) {
         
                $_SESSION['verification_code'] = $verificationCode;
                $_SESSION['code_email'] = $email;

        
                echo '<script>window.location.href = "login_verify_code.php";</script>';
                exit();
            } else {
                echo 'Error sending verification code to email.';
            }
        } else {
            echo '<script>window.location.href = "login.php?error=1";</script>';
            exit();
        }
    } else {
        echo '<script>window.location.href = "login.php?error=1";</script>';
        exit();
    }

    $stmt->close();
    $conn->close();
}


function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}


function sendVerificationCode($email, $code) {
    $subject = "Your new verification code";
    $message = "Your verification code is: " . $code;
    $headers = "From: Typo FX <no-reply@plata.ie>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";

    // Envia o e-mail com o código de verificação
    return mail($email, $subject, $message, $headers);
}
?>
