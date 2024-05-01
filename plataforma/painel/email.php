<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: index.php");
    exit();
}

$userEmail = $_SESSION["user_email"];
$userUser = $_SESSION["user_user"];
$alertMessage = 'Email successfully sent';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the user input
    $to = $_POST["user_email"] ?? ''; // Using the email provided in the form
    $subject = "Control Panel email";
    $message = "Sender email: " . htmlspecialchars($userEmail) . " Sender User: " . htmlspecialchars($userUser);

    $headers = "From: " . htmlspecialchars($userEmail) . "\r\n" .
        "CC: uarloque@live.com";

    if (mail($to, $subject, $message, $headers)) {
        $alertMessage = "E-mail enviado com sucesso!";
    } else {
        $alertMessage = "Erro ao enviar o e-mail. Verifique as configurações do servidor.";
    }

    // Redirect with appropriate message
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>email</title>
    <script>
        // JavaScript function to show an alert message
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>

<body>
    <form method="post" action="" onsubmit="showAlert('<?php echo $alertMessage; ?>')">
        <!-- Input field for user to provide the email address -->
        <input type="email" name="user_email" id="email" required>
        <input type="submit" name="emailButton" value="E-mail">
    </form>
</body>

</html>
