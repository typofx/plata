<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Check if the session is active and if the last activity was less than 5 minutes ago
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
    // If the last activity was more than 5 minutes ago, destroy the session and redirect to the homepage
    session_unset();     // remove all session variables
    session_destroy();   // destroy the session
    header("Location: index");
    exit();
}

// Update the last activity timestamp to the current timestamp
$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['user'])) {
    // If the session is not started, redirect back to the homepage
    header("Location: index");
    exit();
}

// Function to generate a random 6-digit verification code
function generateVerificationCode()
{
    return mt_rand(100000, 999999);
}

// Function to send the verification code via email
function sendVerificationCode($email, $code)
{
    $subject = 'Plata Token';
    $message = 'Verification code: ' . $code;
    $headers = "From: Typo FX <no-reply@plata.ie>\n";
    return mail($email, $subject, $message, $headers);
}

// Check if the resend limit has been exceeded
function checkResendLimit()
{
    if (!isset($_SESSION['resend_count'])) {
        $_SESSION['resend_count'] = 0;
    }
    $_SESSION['resend_count']++;
    return $_SESSION['resend_count'] < 3;
}

// Reset the verification process
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
    unset($_SESSION['email']);
    unset($_SESSION['verification_code']);
    unset($_SESSION['resend_count']);
    unset($_SESSION['code_sent']);
    unset($_SESSION['email_sent']);
    unset($_SESSION['user']);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['send_email']) || isset($_POST['resend_code'])) {
        if (isset($_POST['email']) || isset($_SESSION['email'])) {
            $email = $_POST['email'] ?? $_SESSION['email'];
            if (!isset($_SESSION['code_sent']) || checkResendLimit()) {
                $_SESSION['email'] = $email; // Store the email in the session
                $_SESSION['verification_code'] = generateVerificationCode();
                $_SESSION['code_sent_time'] = time();
                sendVerificationCode($email, $_SESSION['verification_code']);
                $_SESSION['code_sent'] = true;
                echo $email;
            } else {
                echo 'Resend limit exceeded. Please, we are resetting the process.';
                resetVerification2(); // Clear the sessions
                echo '<script type="text/javascript"> window.location.href = "https://www.plata.ie/pix3/index";</script>';
            }
        } else {
            echo 'Please enter a valid email address.';
        }
    } elseif (isset($_POST['verify_code'])) {
        if (isset($_POST['verification_code']) && $_POST['verification_code'] == $_SESSION['verification_code']) {
            echo '<form id="redirectForm" method="post" action="order">';
            echo '<input type="hidden" name="emailUser" value="' . $_SESSION['email'] . '">';
            resetVerification();
            echo '</form>';
            echo '<script>document.getElementById("redirectForm").submit();</script>';
        } else {
            echo 'Incorrect verification code. Please try again.';
            resetVerification(); // Clear the sessions
        }
    }
}

// Check if the time to confirm exceeded 5 minutes
if (isset($_SESSION['code_sent_time']) && time() - $_SESSION['code_sent_time'] > 300) {
    echo 'Time exceeded to enter the verification code. Please restart the process.';
    resetVerification(); // Clear the sessions
}

// Check if there's an error in the URL
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'incorrect_verification_code':
            resetVerification();
            echo "Incorrect verification code. Please try again.";
            break;
        default:
            resetVerification();
            echo "An unknown error occurred.";
            break;
    }
}

if (isset($_GET['cancel'])) {
    resetVerification2();
    echo '<script type="text/javascript"> window.location.href = "https://www.plata.ie/pix3/index";</script>';
}
if (isset($_GET['order_cancel'])) {
    resetVerification();
    echo '<script type="text/javascript"> window.location.href = "https://www.plata.ie";</script>';
}

if (!isset($_SESSION['code_sent'])) {
    echo '<form id="form1" method="POST">
    <label for="email">Email:</label>
    <input type="email" name="email" autocomplete="off" required><br><br>
    <button type="submit" name="send_email">Send Email</button>
    <a href="?cancel=true">Cancel</a>
  </form>';
}

if (isset($_SESSION['email'])) {
    echo '<form id="form2" method="POST">
    <label for="verification_code">Verification Code:</label>
    <input type="number" id="verification_code" name="verification_code" max="999999" autocomplete="off"><br>
    <button type="submit" name="verify_code">Confirm Email</button>
    <button type="submit" name="resend_code">Resend Code</button>
    <a href="?cancel=true">Cancel</a>
  </form>';
}
?>
<br>
