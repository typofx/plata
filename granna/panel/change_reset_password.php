<?php
// Start the session at the very top
session_start();

// Include database connection file
include 'conexao.php';

// Check if the email and verification code are set in the session
if (!isset($_SESSION['code_email']) || !isset($_SESSION['forgot_verification_code'])) {
    $_SESSION['error_message'] = "Session expired. Please restart the password reset process.";
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if the new passwords match
    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $query = "UPDATE granna80_bdlinks.granna_users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $hashed_password, $_SESSION['code_email']);
        
        if ($stmt->execute()) {
           
           $_SESSION['success_message'] = "Password reset successfully. You can now log in with your new password.";
            // Clear session data related to password reset
            unset($_SESSION['code_email']);
            unset($_SESSION['forgot_verification_code']);
            unset($_SESSION['forgot_verification_code_sent']);
            
            // Redirect to login page or display success message
            //echo "<script>window.location.href='login.php';</script>";
        } else {
            echo "Error updating password. Please try again later.";
        }

        $stmt->close();
    } else {
        echo "Passwords do not match. Please try again.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

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
    <center>
    <h2>Reset Password</h2>

    <?php
        if (isset($_SESSION['success_message'])) {
            echo "<p style='color: green;'>".$_SESSION['success_message']."</p>";
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo "<p style='color: red;'>".$_SESSION['error_message']."</p>";
            unset($_SESSION['error_message']);
        }
        ?>
    <form action="change_reset_password.php" method="POST" onsubmit="return get_action();">
    <label for="new_password">New Password:</label><br>
    <input type="password" name="new_password" id="new_password" required><br>
    
    <label for="confirm_password">Confirm Password:</label><br>
    <input type="password" name="confirm_password" id="confirm_password" required><br>
    
    <!-- Element to show if passwords match -->
    <span id="passwordMessage"></span><br><br>
    
    <input type="hidden" id="recaptchaChecked" name="recaptchaChecked" value="0">
    <div id="recaptcha-container" class="g-recaptcha" data-callback="recaptchaChecked" data-sitekey=""></div><br>

    <button type="submit">Confirm</button><br>
    <a href="index.php">[ Cancel ]</a>
</form>

<script>
    const newPassword = document.getElementById("new_password");
    const confirmPassword = document.getElementById("confirm_password");
    const passwordMessage = document.getElementById("passwordMessage");

    function checkPasswordMatch() {
        if (newPassword.value === confirmPassword.value) {
            passwordMessage.textContent = "Passwords match.";
            passwordMessage.style.color = "green";
        } else {
            passwordMessage.textContent = "Passwords do not match.";
            passwordMessage.style.color = "red";
        }
    }

    // Add event listeners to check passwords as the user types
    newPassword.addEventListener("input", checkPasswordMatch);
    confirmPassword.addEventListener("input", checkPasswordMatch);
</script>

    </center>
</body>
</html>
