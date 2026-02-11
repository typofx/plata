<?php 
/**
 * Team Member Registration Form
 * 
 * Presents a registration form for adding new team members with:
 * - Platform credentials (email, password)
 * - Member information (name, position, profile picture)
 * - Social media contacts
 * - Email recipient selection
 */

session_start();
// include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
</head>

<body>

    <!-- Feedback Messages -->
    <?php
    if (isset($_SESSION['success'])) {
        echo '<div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; margin-bottom: 20px; border-radius: 4px;">';
        echo '✓ ' . htmlspecialchars($_SESSION['success']);
        echo '</div>';
        unset($_SESSION['success']);
    }
    
    if (isset($_SESSION['error'])) {
        echo '<div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; margin-bottom: 20px; border-radius: 4px;">';
        echo '✗ ' . htmlspecialchars($_SESSION['error']);
        echo '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <form action="insert.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">

        <!-- Platform Data Section -->
        <h3>Add Platform Data</h3>

        <label for="name">First Name:</label><br>
        <input type="text" id="name" name="name" maxlength="50" required><br>

        <label for="last_name">Last Name:</label><br>
        <input type="text" id="last_name" name="last_name" maxlength="50" required><br>

        <label for="email">User Email (Login):</label><br>
        <input type="email" id="email" name="email" maxlength="100" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" maxlength="50" required onkeyup="validatePassword()"><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" maxlength="50" required onkeyup="validatePassword()"><br>
        <span id="password_status"></span><br>


        <h3>Add Member Data</h3>

        <label for="profilePicture">Profile Picture:</label><br>
        <input type="file" id="profilePicture" name="profilePicture" accept="image/*" required><br>

        <label for="position">Position:</label><br>
        <select name="position" required>
            <option value="">Select a position</option>
            <option value="INTERN">INTERN</option>
        </select><br>

        <label for="whatsapp">Whatsapp:</label><br>
        <input type="text" id="whatsapp" name="whatsapp" maxlength="20" placeholder="(XX) XXXXX-XXXX"><br>

        <label for="instagram">Instagram:</label><br>
        <input type="text" id="instagram" name="instagram" maxlength="50" placeholder="@user"><br>

        <label for="telegram">Telegram:</label><br>
        <input type="text" id="telegram" name="telegram" maxlength="50" placeholder="@user"><br>

        <label for="facebook">Facebook:</label><br>
        <input type="text" id="facebook" name="facebook" maxlength="100" placeholder="https://facebook.com/user"><br>

        <label for="github">GitHub:</label><br>
        <input type="text" id="github" name="github" maxlength="200" placeholder="https://github.com/user"><br>

        <label for="social_email">Social Email (Public):</label><br>
        <input type="email" id="social_email" name="social_email" maxlength="100" placeholder="contact@email.com"><br>

        <label for="twitter">Twitter (X):</label><br>
        <input type="text" id="twitter" name="twitter" maxlength="50" placeholder="user"><br>

        <label for="linkedin">LinkedIn:</label><br>
        <input type="text" id="linkedin" name="linkedin" maxlength="200" placeholder="https://linkedin.com/in/user"><br>

        <label for="twitch">Twitch:</label><br>
        <input type="text" id="twitch" name="twitch" maxlength="50" placeholder="username"><br>

        <label for="medium">Medium:</label><br>
        <input type="text" id="medium" name="medium" maxlength="50" placeholder="username"><br>

        <!-- Email Recipient Section -->
         
        <h3>Email Recipient</h3>

        <label for="recipient_email">Send confirmation email to:</label><br>
        <input type="email" id="recipient_email" name="recipient_email" maxlength="100" required placeholder="email@example.com"><br>

        <input type="submit" value="Submit">
    </form>

    <script>
        /**
         * Validate password strength and matching
         * Called on keyup event for real-time feedback to user
         * 
         * Checks:
         * - Password field is not empty
         * - Password and confirm password fields match
         * Updates visual indicator (green for match, red for mismatch)
         */
        function validatePassword() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;
            const passwordStatus = document.getElementById("password_status");

            // Clear status if password field is empty
            if (!password) {
                passwordStatus.innerHTML = "";
                return;
            }

            // Show match/mismatch feedback
            if (password === confirmPassword) {
                passwordStatus.innerHTML = "Passwords match";
                passwordStatus.style.color = "green";
            } else {
                passwordStatus.innerHTML = "Passwords do not match";
                passwordStatus.style.color = "red";
            }
        }
        
        /**
         * Validate entire form before submission
         * Server will also validate, but client-side validation provides instant feedback
         * 
         * Checks:
         * - Passwords match
         * 
         * @returns {boolean} - True if form passes validation, false to prevent submission
         */
        function validateForm() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match");
                return false;
            }

            return true;
        }
    </script>
</body>

</html>