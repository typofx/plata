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
require '_config_form.php';
// include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h2>Add Team Member</h2>

    <!-- Display success or error messages from previous form submission -->
    <?php include '_messages.php'; ?>

    <form action="insert.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">

        <!-- Platform Data Section -->
        <h3>Platform Information</h3>

        <ul class="form-list">
            <li>
                <label for="name">First Name:</label>
                <input type="text" id="name" name="name" maxlength="50" required>
            </li>

            <li>
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" maxlength="50" required>
            </li>

            <li>
                <label for="email">User Email (Login):</label>
                <input type="email" id="email" name="email" maxlength="100" required>
            </li>

            <li>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" maxlength="50" required onkeyup="validatePassword()">
            </li>

            <li>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" maxlength="50" required onkeyup="validatePassword()">
                <span id="password_status"></span>
            </li>
        </ul>

        <hr>

        <!-- Member Data Section -->
        <h3>Member Information</h3>

        <ul class="form-list">
            <li>
                <label for="profilePicture">Profile Picture:</label>
                <input type="file" id="profilePicture" name="profilePicture" accept="image/*" required>
            </li>

            <li>
                <label for="position">Position:</label>
                <select name="position" required>
                    <option value="">Select a position</option>
                    <?php foreach ($positions as $position): ?>
                        <option value="<?php echo htmlspecialchars($position); ?>">
                            <?php echo strtoupper($position); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </li>
        </ul>

        <hr>

        <!-- Social Networks and Contacts Section -->
        <h3>Social Media</h3>

        <ul class="form-list">
            <?php foreach ($socialMedias as $key => $field): ?>
                <li>
                    <label for="<?php echo $key; ?>"><?php echo $field['label']; ?>:</label>
                    <input 
                        type="<?php echo $field['type'] ?? 'text'; ?>" 
                        id="<?php echo $key; ?>" 
                        name="<?php echo $key; ?>" 
                        maxlength="<?php echo $field['maxlength']; ?>" 
                        placeholder="<?php echo $field['placeholder']; ?>"
                        <?php echo isset($field['pattern']) ? 'pattern="' . $field['pattern'] . '"' : ''; ?>
                    >
                </li>
            <?php endforeach; ?>
        </ul>

        <hr>

        <!-- Email Recipient Section -->
        <h3>Email Recipient</h3>

        <ul class="form-list">
            <li>
                <label for="recipient_email">Send confirmation email to:</label>
                <input type="email" id="recipient_email" name="recipient_email" maxlength="100" required placeholder="email@example.com">
            </li>
        </ul>

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
                passwordStatus.className = "senha-status senha-match";
            } else {
                passwordStatus.innerHTML = "Passwords do not match";
                passwordStatus.className = "senha-status senha-error";
            }
        }
        
        /**
         * Validate entire form before submission
         * Server will also validate, but client-side validation provides instant feedback
         * 
         * Checks:
         * - Passwords match
         * - Password is at least 6 characters long
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

            // Verify minimum password length
            if (password.length < 6) {
                alert("Password must be at least 6 characters long");
                return false;
            }

            return true;
        }
    </script>
</body>

</html>