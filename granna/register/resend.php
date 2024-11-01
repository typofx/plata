<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "Session expired. Please come back and enter your email.";
    echo "<script>window.location.href='index.php';</script>";
    
}

if ($_SESSION['resends'] < 3) {
    $_SESSION['verification_code'] = rand(100000, 999999); 
    $_SESSION['resends']++;
    $_SESSION['attempts'] = 0;  


    $to = $_SESSION['email'];
    $subject = "Your verification code is";
    $message = "Hello, your new verification code is:" . $_SESSION['verification_code'];
    $headers = "From: Typo FX <no-reply@plata.ie>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";

    if (mail($to, $subject, $message, $headers)) {
        header("Location: verify.php");
        exit();
    } else {
        echo "Failed to send email. Please try again.";
    }
} else {
    echo "Maximum number of resubmissions reached. Session expired.";
    session_destroy();
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
