<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['forgot_user_email'])) {
    $_SESSION['error_message'] = "Session expired. Please restart the password reset process.";
    exit();
}

if (!isset($_SESSION['forgot_verification_code_sent'])) {
    $code = $_SESSION['forgot_verification_code'];
    $to = $_SESSION['forgot_user_email'];
    $subject = "Your new verification code";
    $message = "Your new verification code is: " . $code;
    $headers = "From: Typo FX <no-reply@plata.ie>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";

    if (mail($to, $subject, $message, $headers)) {
        $_SESSION['forgot_verification_code_sent'] = true;
        $_SESSION['success_message'] = "Verification code sent to your email.";
        $_SESSION['resends'] = 1; // Iniciar contador de reenvios
    } else {
        $_SESSION['error_message'] = "Failed to send verification code. Please try again later.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = trim($_POST['verification_code']);

    if ($entered_code == $_SESSION['forgot_verification_code']) {
        echo "<script>window.location.href='forgot_reset_password.php';</script>";
        exit();
    } else {
        $error = "Invalid verification code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Code</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
<center>
    <h2>Enter Verification Code</h2>
    <form action="forgot_verify_code.php" method="POST">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<p style='color: green;'>".$_SESSION['success_message']."</p>";
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo "<p style='color: red;'>".$_SESSION['error_message']."</p>";
            unset($_SESSION['error_message']);
        }

        if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
        }
        ?>
        <label for="email"><?php echo $_SESSION['forgot_user_email'] ?></label><br><br>
        <label for="verification_code">Verification Code:</label><br>
        <input type="text" name="verification_code" id="verification_code" required>
        <br><br><button type="submit">Verify</button>
        <a href="forgot_logout.php">[ Cancel ]</a>
    </form>

    <?php if ($_SESSION['resends'] < 3): ?>
        <p><a href="forgot_resend.php">Resend Code (<?php echo $_SESSION['resends']; ?>/3)</a></p>
    <?php else: ?>
        <p style="color: red;">Maximum resends reached. Please restart the process.</p>
        <?php  echo "<script>window.location.href='forgot_logout.php';</script>"; ?>
    <?php endif; ?>
</center>
</body>
</html>
