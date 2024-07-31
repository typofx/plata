<?php
session_start();

// Function to generate a random 6-digit code
function generateVerificationCode()
{
    return mt_rand(100000, 999999);
}

// Function to send the email with the verification code
function sendVerificationCode($email, $code)
{
    $subject = 'Plata Token';
    $message = 'Verification code : ' . $code;
    $headers  = "From: Typo FX <no-reply@plata.ie>\n";

    return mail($email, $subject, $message, $headers);
}

// Verifies if the verification code entered by the user is correct
function verifyCode($userCode, $expectedCode)
{
    return $userCode == $expectedCode;
}

// Checks if the resend limit has been exceeded
function checkResendLimit()
{
    if (!isset($_SESSION['resend_count'])) {
        $_SESSION['resend_count'] = 0;
    }
    $_SESSION['resend_count']++;

    return $_SESSION['resend_count'] < 3;
}

// Resets the verification process
function resetVerification()
{
    unset($_SESSION['email']);
    unset($_SESSION['verification_code']);
    unset($_SESSION['resend_count']);
    unset($_SESSION['code_sent']);
    unset($_SESSION['email_sent']);
}

function resetVerification2()
{

    unset($_SESSION['resend_count']);
    unset($_SESSION['code_sent']);
    unset($_SESSION['email_sent']);
}

// Checks if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['send_email']) && !isset($_SESSION['code_sent'])) {
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $_SESSION['email'] = $email; // Stores the email in the session
            $_SESSION['verification_code'] = generateVerificationCode();
            $_SESSION['code_sent_time'] = time();
            sendVerificationCode($email, $_SESSION['verification_code']);
            $_SESSION['code_sent'] = true;
            echo 'Email sent successfully! Please check your inbox.';
        } else {
            echo 'Please enter a valid email address.';
        }
    } elseif (isset($_POST['confirm_code'])) {
        if (isset($_POST['verification_code'])) {
            $userCode = $_POST['verification_code'];
            $expectedCode = $_SESSION['verification_code'];
            if (verifyCode($userCode, $expectedCode)) {
                echo 'Verification code correct. Email confirmed!';
                resetVerification2(); // Clears the sessions
            } else {
                echo 'Incorrect verification code. Please try again.';
            }
        } else {
            echo 'Please enter the verification code.';
        }
    } elseif (isset($_POST['resend_code'])) {
        if (checkResendLimit()) {
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $_SESSION['verification_code'] = generateVerificationCode();
                $_SESSION['code_sent_time'] = time();
                sendVerificationCode($email, $_SESSION['verification_code']);
                echo 'Email resent successfully! Please check your inbox.';
            } else {
                echo 'An error occurred while resending the email. Please send the email again.';
            }
        } else {
            echo 'Resend limit exceeded. Please, we are resetting the process.';
            resetVerification(); // Clears the sessions
        }
    }
}
// Checks if the confirmation time has exceeded 3 minutes
if (isset($_SESSION['code_sent_time']) && time() - $_SESSION['code_sent_time'] > 300) {
    echo 'Time exceeded to enter the verification code. Please restart the process.';
    resetVerification(); // Clears the sessions
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Confirmation</title>
</head>

<body>
    <h2>Email Confirmation</h2>
    <form method="POST">

        <label for="email">Email:</label> <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>"><br>
        <label for="verification_code">Verification Code:</label> <input type="number" id="verification_code" name="verification_code" max="999999" value="<?php echo $userCode  ?>"><br>
        <br>
        <button type="submit" name="send_email">Send Email</button>
        <button type="submit" name="resend_code">Resend Code</button>
        <button type="submit" name="confirm_code">Confirm Code</button>
    </form>
</body>

</html>