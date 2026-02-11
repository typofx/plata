<?php 
require_once '../../SessionManager.php';

// Check if user is logged in
if (!SessionManager::isLoggedIn()) {
    header('Location: ../login.php?error=not_logged_in');
    exit();
}

// Generate CSRF token
if (!SessionManager::has('csrf_token')) {
    SessionManager::set('csrf_token', bin2hex(random_bytes(32)));
}

// If POST request, process the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        $_SESSION['error'] = "Security error. Please try again.";
        header('Location: MemberForms1.php');
        exit();
    }

    // Collect form data
    $form1_data = [
        'name' => trim($_POST['name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'confirm_password' => $_POST['confirm_password'] ?? '',
        'position' => trim($_POST['position'] ?? ''),
        'whatsapp' => trim($_POST['whatsapp'] ?? ''),
        'instagram' => trim($_POST['instagram'] ?? ''),
        'telegram' => trim($_POST['telegram'] ?? ''),
        'facebook' => trim($_POST['facebook'] ?? ''),
        'github' => trim($_POST['github'] ?? ''),
        'social_email' => trim($_POST['social_email'] ?? ''),
        'twitter' => trim($_POST['twitter'] ?? ''),
        'linkedin' => trim($_POST['linkedin'] ?? ''),
        'tiktok' => trim($_POST['tiktok'] ?? '')
    ];

    // Validate required fields
    if (empty($form1_data['name']) || empty($form1_data['email']) || empty($form1_data['password']) || empty($form1_data['position'])) {
        $_SESSION['error'] = "Please fill all required fields.";
        header('Location: MemberForms1.php');
        exit();
    }

    // Validate email format
    if (!filter_var($form1_data['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header('Location: MemberForms1.php');
        exit();
    }

    // Validate passwords match
    if ($form1_data['password'] !== $form1_data['confirm_password']) {
        $_SESSION['error'] = "Passwords do not match.";
        header('Location: MemberForms1.php');
        exit();
    }

    // Validate password strength
    if (strlen($form1_data['password']) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long.";
        header('Location: MemberForms1.php');
        exit();
    }

    // Handle profile picture upload - encode to base64 and store in session
    if (!empty($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
        $file_info = pathinfo($_FILES['profilePicture']['name']);
        $file_ext = strtolower($file_info['extension']);
        
        // Validate file extension
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($file_ext, $allowed_ext)) {
            $_SESSION['error'] = "Only image files (JPG, PNG, GIF, WebP) are allowed.";
            header('Location: MemberForms1.php');
            exit();
        }
        
        // Validate file size (max 5MB)
        if ($_FILES['profilePicture']['size'] > 5 * 1024 * 1024) {
            $_SESSION['error'] = "Profile picture size must not exceed 5MB.";
            header('Location: MemberForms1.php');
            exit();
        }
        
        // Read file and encode to base64
        $file_content = file_get_contents($_FILES['profilePicture']['tmp_name']);
        $file_base64 = base64_encode($file_content);
    }

    // Save to SESSION
    SessionManager::set('form1_data', $form1_data);
    SessionManager::set('success', "Step 1 data saved successfully!");

    // Redirect to next form
    header('Location: MemberForms2.php');
    exit();
}

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

    <form action="MemberForms1.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <h3>Platform Data</h3>

        <label for="name">First Name:</label><br>
        <input type="text" id="name" name="name" maxlength="50" required><br>

        <label for="last_name">Last Name:</label><br>
        <input type="text" id="last_name" name="last_name" maxlength="50" required><br>

        <label for="email">Email (Login):</label><br>
        <input type="email" id="email" name="email" maxlength="100" required><br>



        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" maxlength="50" required onkeyup="validatePassword()"><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" maxlength="50" required onkeyup="validatePassword()"><br>
        <span id="password_status"></span><br>

        <h3>Additional Information</h3>

        <label for="profilePicture">Profile Picture:</label><br>
        <input type="file" id="profilePicture" name="profilePicture" accept="image/*" required><br>

        <label for="position">Position:</label><br>
        <select name="position" required>
            <option value="">Select a position</option>
            <option value="INTERN">INTERN</option>
        </select><br>

        <label for="whatsapp">WhatsApp:</label><br>
        <input type="text" id="whatsapp" name="whatsapp" maxlength="20"><br>

        <label for="instagram">Instagram:</label><br>
        <input type="text" id="instagram" name="instagram" maxlength="50"><br>

        <label for="telegram">Telegram:</label><br>
        <input type="text" id="telegram" name="telegram" maxlength="50"><br>

        <label for="facebook">Facebook:</label><br>
        <input type="text" id="facebook" name="facebook" maxlength="100"><br>

        <label for="github">GitHub:</label><br>
        <input type="text" id="github" name="github" maxlength="200"><br>

        <label for="social_email">Social Email:</label><br>
        <input type="email" id="social_email" name="social_email" maxlength="100"><br>

        <label for="twitter">Twitter/X:</label><br>
        <input type="text" id="twitter" name="twitter" maxlength="50"><br>



        <label for="linkedin">LinkedIn:</label><br>

        <input type="text" id="linkedin" name="linkedin" maxlength="200"><br>



        <label for="twitch">Twitch:</label><br>

        <input type="text" id="twitch" name="twitch" maxlength="50"><br>



        <label for="medium">Medium:</label><br>

        <input type="text" id="medium" name="medium" maxlength="50"><br>

        <br>

        <input type="submit" value="Next" style="padding: 3px 30px; cursor: pointer;">

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

            // Check minimum length (8 characters)
            if (password.length < 8) {
                passwordStatus.innerHTML = "Password must be at least 8 characters";
                passwordStatus.style.color = "orange";
                return;
            }

            // Show match/mismatch feedback

            if (password === confirmPassword) {

                passwordStatus.innerHTML = "✓";

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