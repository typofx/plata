<?php
// Function to generate a 6-digit verification code
function generateVerificationCode() {
    return mt_rand(100000, 999999);
}

// Function to send the email with the verification code
function sendVerificationEmail($email, $code) {
    $subject = "Verification Code";
    $message = "Your verification code is: $code";
    // You can use the PHP mail() function to send the email
    // Replace the information between <> with your email settings
    $headers = "From: youremail@yourdomain.com" . "\r\n" .
               "Reply-To: youremail@yourdomain.com" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // Send the email
    mail($email, $subject, $message, $headers);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the email field is set
    if (isset($_POST["email"])) {
        // Generate a verification code
        $verification_code = generateVerificationCode();

        // Store the verification code in the session
        session_start();
        $_SESSION["verification_code"] = $verification_code;
        $_SESSION["email"] = $_POST["email"]; // Store the email in the session

        // Send the verification code via email
        sendVerificationEmail($_POST["email"], $verification_code);

        echo "An email with the verification code has been sent to {$_POST['email']}.";
    } elseif (isset($_POST["verification_code"])) {
        // Check if the verification code is correct
        session_start();
        if ($_POST["verification_code"] == $_SESSION["verification_code"]) {
            echo "Verification code correct. The email {$_SESSION['email']} has been successfully verified!";
            // Here you can add code to mark the email as verified in the database, for example.
            // Remember to clear the session variables after the verification is completed.
            unset($_SESSION["verification_code"]);
            unset($_SESSION["email"]);
        } else {
            echo "Incorrect verification code.";
        }
    } else {
        echo "Please provide an email address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <?php if (!isset($_SESSION["verification_code"])): ?>
    <h2>Email Verification</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br><br>
        <input type="submit" value="Send Verification Code">
    </form>
    <?php else: ?>
    <h2>Verify Verification Code</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="verification_code">Verification Code:</label><br>
        <input type="text" id="verification_code" name="verification_code"><br><br>
        <input type="submit" value="Verify Code">
    </form>
    <?php endif; ?>
</body>
</html>

