<?php
session_start();

if (!isset($_SESSION['code_email']) || !isset($_SESSION['resends'])) {
    echo "Session expired. Please restart the process.";
    echo "<script>window.location.href='change_verify_code.php';</script>";
    exit();
}

if ($_SESSION['resends'] < 3) {
    $_SESSION['forgot_verification_code'] = rand(100000, 999999); 
    $_SESSION['resends']++;

    $to = $_SESSION['code_email'];
    $subject = "Your verification code is";
    $message = "Hello, your new verification code is: " . $_SESSION['forgot_verification_code'];
    $headers = "From: Typo FX <no-reply@plata.ie>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";

    if (mail($to, $subject, $message, $headers)) {
        $_SESSION['success_message'] = "Verification code resent to your email.";
        header("Location: change_verify_code.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to resend email. Please try again.";
        header("Location: change_verify_code.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "Maximum number of resends reached. Session expired.";
    session_destroy();
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
