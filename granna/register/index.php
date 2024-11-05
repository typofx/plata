<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $_SESSION['email'] = $_POST['email'];
    $_SESSION['verification_code'] = rand(100000, 999999);  
    $_SESSION['attempts'] = 0;         
    $_SESSION['resends'] = 0;         

   
    $to = $_SESSION['email'];
    $subject = "Your verification code is";
    $message = "Hello, your verification code is:" . $_SESSION['verification_code'];
    $headers = "From: Typo FX <no-reply@plata.ie>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";

    if (mail($to, $subject, $message, $headers)) {
        
        echo "<script>window.location.href='verify.php';</script>";
        exit();
    } else {
        echo "Failed to send email. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>2-Step Verification</title>
</head>
<body>
    <h2>2-Step Verification</h2>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Send</button>
    </form>
</body>
</html>
