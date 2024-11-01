<?php
session_start();


if (!isset($_SESSION['email']) || !isset($_SESSION['verification_code'])) {
    echo "<p style='color: red;'>Session expired. Please come back and enter your email.</p>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}

$message = ""; 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];

    if ($_SESSION['attempts'] < 3) {
        if ($code == $_SESSION['verification_code']) {
           
            $message = "<p style='color: green;'>Verification successful!</p>";
            
      
            $userEmail = $_SESSION['email'];
            session_unset();  
            session_destroy();  
            
           
            session_start();
            $_SESSION['user_email'] = $userEmail;

            echo $message;
            echo "<script>window.location.href = 'inscribe.php';</script>";
            exit();
        } else {
            $_SESSION['attempts']++;
            $message = "<p style='color: red;'>Incorrect code. Attempt {$_SESSION['attempts']} of 3.</p>";
        }
    } else {
        $message = "<p style='color: red;'>Maximum number of attempts reached. Session expired.</p>";
        session_destroy();
        echo $message;
        echo "<script>window.location.href = 'index.php';</script>";
        exit();
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
    <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>

    <?php

    if ($message) {
        echo $message;
    }
    ?>

    <form method="POST" action="">
        <label for="code">Verification Code:</label>
        <input type="text" name="code" required>
        <button type="submit">Check</button>
    </form>

    <?php if ($_SESSION['resends'] < 3): ?>
        <p><a href="resend.php">Resend Code</a> <br>(Attempted resend: <?php echo $_SESSION['resends']; ?> of 3)</p>
    <?php else: ?>
        <p style="color: red;">Maximum number of resubmissions reached.</p>
        <?php session_destroy(); ?>
        <script>
            window.location.href = 'index.php';
        </script>
    <?php endif; ?>
</body>
</html>
