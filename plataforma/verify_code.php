<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_SESSION['page_count'] = 1;
}

// Check if the session variable is set
if (!isset($_SESSION['page_count'])) {
    session_destroy();
    header("Location: index.php?error");
    exit();
}


 $_SESSION['page_count'] = $_SESSION['page_count'] + 1;


if ($_SESSION['page_count'] !== 2) {

    session_destroy();
    echo '<script>window.location.href = "index.php?error";</script>';
    exit();
}





if (!isset($_SESSION['attempt_count'])) {
    $_SESSION['attempt_count'] = 0;
}

if (!isset($_SESSION['resend_count'])) {
    $_SESSION['resend_count'] = 0;
}

// Restart the process after 3 incorrect attempts
if ($_SESSION['attempt_count'] >= 3) {
    echo "<p style='color: red;'>You have reached the attempt limit. The process has been restarted.</p>";
    session_destroy();
    echo '<script>
    window.location.href = "index.php?error";
</script>';
    exit();
}

if (isset($_POST['verification_code'])) {
    $inputCode = $_POST['verification_code'];


    if ($inputCode === $_SESSION['verification_code']) {
       // Correct code, redirect to menu
        unset($_SESSION['verification_code']);
        unset($_SESSION['code_email']);
        unset($_SESSION['page_count']);
        $_SESSION["user_logged_in"] = true;
        echo 'logged in successfully';
        echo '<script>
    window.location.href = "menu.php";
</script>';
        exit();
    } else {

       // Bad code, increment the retry count
        $_SESSION['attempt_count']++;

        $error = "<p style='color: red;'>Incorrect verification code. Try again. {$_SESSION['attempt_count']} of 3.</p>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>2FA Verification</title>
    <!-- reCAPTCHA Enterprise Script -->
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>

    <script>
        function get_action() {
            // Check if reCAPTCHA is checked
            if (document.getElementById('recaptchaChecked').value === '1') {
                console.log("reCAPTCHA checked.");
                return true; // Allow form submission
            } else {
                console.log("reCAPTCHA not checked.");
                alert("Please check the reCAPTCHA.");
                return false; // Prevent form submission
            }
        }

        // Function to update reCAPTCHA state when checked
        function recaptchaChecked() {
            document.getElementById('recaptchaChecked').value = '1';
        }
    </script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 300px;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 8px;
        }

        img {
            width: 200px;
            height: auto;
            margin-bottom: 20px;
        }

        label,
        input {
            margin-bottom: 10px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <img src="https://plata.ie/images/platatoken1kpx.png" alt="Logo">




    <h1>2FA Verification</h1>


    <?php // Display message if code was resent (using session)
    if (isset($_SESSION['resent_message'])) {
        echo "<p style='color: green;'>{$_SESSION['resent_message']}</p>";
        unset($_SESSION['resent_message']); // Limpar a mensagem após exibição
    } 
    
    echo $error;
    
    ?>


    <form method="post" onsubmit="return get_action();">
        <b><?php echo $_SESSION['code_email']; ?></b><br>
        <label for="verification_code">Enter verification code:</label>
        <input type="text" name="verification_code" required>
        <input type="hidden" id="recaptchaChecked" name="recaptchaChecked" value="0">
        <div id="recaptcha-container" class="g-recaptcha" data-callback="recaptchaChecked" data-sitekey=""></div><br>
        <button type="submit">Check</button>
    </form>


    <p>
        <a href="resend_code.php">Resend code</a>
    </p>



</body>

</html>