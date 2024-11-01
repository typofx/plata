<?php
session_start();
$_SESSION['page_count'] = 1;

// Function to generate the 6-digit code
function generateVerificationCode()
{
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

// Function to send the verification code to the email
function sendVerificationCode($email, $code)
{
    $subject = "Your new verification code";
    $message = "Your new verification code is: " . $code;
    $headers = "From: Typo FX <no-reply@plata.ie>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";

    return mail($email, $subject, $message, $headers);
}


// Count resend attempts
if ($_SESSION['resend_count'] >= 3) {
    echo "<p style='color: red;'>You have reached the resubmission limit. The process has been restarted.</p>";
    session_destroy();
    echo '<script>window.location.href = "login.php";</script>';
    exit();
}


$verificationCode = generateVerificationCode();

if (sendVerificationCode($_SESSION['code_email'], $verificationCode)) {
    // Store the new code in the session
    $_SESSION['verification_code'] = $verificationCode;


    $_SESSION['resend_count']++;


    $_SESSION['resent_message'] = "New verification code sent to your email. Attempt {$_SESSION['resend_count']} of 3.";


    echo '<script>window.location.href = "login_verify_code.php";</script>';
   
    exit();
} else {
    echo "Error resending verification code.";
}
