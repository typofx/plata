<?php
session_start();


if (!isset($_SESSION['page_count'])) {
    $_SESSION['page_count'] = 1;
} else if ($_SESSION['page_count'] !== 1) {

    session_destroy();
    header("Location: login.php?ordem");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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
        /* Centralize the form on the page */
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>

    <div class="form-container">
    <center>
        <form method="post" action="login_process.php" onsubmit="return get_action();">
            <label for="email">Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" required><br><br>

            <input type="hidden" id="recaptchaChecked" name="recaptchaChecked" value="0">
            <div id="recaptcha-container" class="g-recaptcha" data-callback="recaptchaChecked" data-sitekey=""></div><br>

           <input type="submit" value="Login"><br><br>
                <a href='https://www.granna.ie/register/'>[ Sign Up ]</a>
                <a href='forgot_password.php'>[ Forgot Password ]</a>
                <a href='https://www.granna.ie/'>[ Go Back ]</a>
            </center>


        </form>
    </div>

</body>

</html>