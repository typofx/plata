<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_SESSION['page_count'] = 1;
}

// Check if the session variable is set
if (!isset($_SESSION['page_count'])) {
    session_destroy();
    echo '<script>window.location.href = "login.php?error";</script>';
    exit();
}


 $_SESSION['page_count'] = $_SESSION['page_count'] + 1;


if ($_SESSION['page_count'] !== 2) {

    session_destroy();
    echo '<script>window.location.href = "login.php?error";</script>';
    exit();
}
include 'conexao.php';

if (!isset($_SESSION['verification_code'])) {
    echo '<script>window.location.href = "login.php?error=verification_required";</script>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputCode = $_POST['verification_code'];

   
    if ($inputCode === $_SESSION['verification_code']) {
   
        unset($_SESSION['verification_code']);
        $_SESSION['user_logged_in'] = true; 
        
        //echo '<script>window.location.href = "home.php";</script>';
echo 'You are logged in';
        exit();
    } else {
 
        if (!isset($_SESSION['attempt_count'])) {
            $_SESSION['attempt_count'] = 1;
        } else {
            $_SESSION['attempt_count']++;
        }

        if ($_SESSION['attempt_count'] >= 3) {
      
            echo "<p style='color: red;'>You have reached the attempt limit. Please start the process again.</p>";
            session_destroy();
            echo '<script>window.location.href = "login.php?error=too_many_attempts";</script>';
            exit();
        } else {
            $error = "<p style='color: red;'>Incorrect code. Please try again. Attempt {$_SESSION['attempt_count']} of 3.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Verification</title>
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
        label, input {
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
    <h1>Code Verification</h1>

    <?php
    if (isset($error)) {
        echo $error;
    }


    if (isset($_SESSION['resent_message'])) {
        echo "<p style='color: green;'>{$_SESSION['resent_message']}</p>";
        unset($_SESSION['resent_message']);
    }
    ?>

    <form method="post">
        <label for="email"><?php echo $_SESSION['code_email']; ?></label>
        <label for="verification_code">Enter verification code:</label>
        <input type="text" name="verification_code" required>
        <button type="submit">Verify</button>
    </form>

    <p>
        <a href="login_resend_code.php">Resend code</a>
    </p>
</body>
</html>
