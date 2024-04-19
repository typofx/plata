<?php
session_start();

// Function to generate a random 6-digit code
function generateRandomCode($length = 6)
{
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Check if the form was submitted and if the code has not been sent in this session yet
if (isset($_POST['email']) && !isset($_SESSION['code_sent'])) {
    $email = $_POST['email'];

    // Generate a random code
    $code = generateRandomCode();

    // Save the code and time of sending in the session
    $_SESSION['confirmation_code'] = $code;
    $_SESSION['email'] = $email;
    $_SESSION['time_sent'] = time();
    $_SESSION['attempts'] = 0; // Initialize the attempts counter
    $_SESSION['resend_count'] = 0; // Initialize the resend counter
    $_SESSION['code_sent'] = true; // Mark that the code has been sent

    // Send the email with the confirmation code
    $subject = "Email Confirmation";
    $message = "Your confirmation code is: $code";
    $headers  = "From: No-reply@plata.ie <no-reply@plata.ie>\n";
    $headers .= "Cc: Plata.ie <no-reply@plata.ie>\n";
    $headers .= "X-Sender: Plata.ie <no-reply@plata.ie>\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();
    $headers .= "X-Priority: 1\n"; // Urgent message!
    $headers .= "Return-Path: Plata.ie\n"; // Return path for errors
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
    mail($email, $subject, $message, $headers);

    // Redirect to the confirmation page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Check if there is a code in the session
if (isset($_SESSION['confirmation_code'])) {
    $confirmation_code = $_SESSION['confirmation_code'];
    $email = $_SESSION['email'];
    $time_sent = $_SESSION['time_sent'];

    // Check if the 3-minute time limit has been exceeded
    if (time() - $time_sent > 180) {
        // Time expired, clear the session and redirect to the email sending form
        unset($_SESSION['confirmation_code']);
        unset($_SESSION['email']);
        unset($_SESSION['time_sent']);
        unset($_SESSION['attempts']); // Clear the attempts counter
        unset($_SESSION['resend_count']); // Clear the resend counter
        unset($_SESSION['code_sent']); // Mark that the code has not been sent anymore
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Check if the form with the code was submitted
    if (isset($_POST['code'])) {
        $user_code = $_POST['code'];

        // Check if the user-entered code is correct
        if ($user_code == $confirmation_code) {
            // Correct code, display success message
            echo "<h2>Email Confirmation</h2>";
            echo "<p>Your email ($email) has been successfully confirmed!</p>";
            // Clear the session
            unset($_SESSION['confirmation_code']);
            unset($_SESSION['email']);
            unset($_SESSION['time_sent']);
            unset($_SESSION['attempts']); // Clear the attempts counter
            unset($_SESSION['resend_count']); // Clear the resend counter
            unset($_SESSION['code_sent']); // Mark that the code has not been sent anymore
        } else {
            // Increment the attempts counter
            if (isset($_SESSION['attempts'])) {
                $_SESSION['attempts']++;
            } else {
                $_SESSION['attempts'] = 1;
            }

            // Check if the attempts limit has been exceeded
            if ($_SESSION['attempts'] >= 3) {
                // Clear the session and restart the process
                unset($_SESSION['confirmation_code']);
                unset($_SESSION['email']);
                unset($_SESSION['time_sent']);
                unset($_SESSION['attempts']); // Clear the attempts counter
                unset($_SESSION['resend_count']); // Clear the resend counter
                unset($_SESSION['code_sent']); // Mark that the code has not been sent anymore
                echo "<h2>Email Confirmation</h2>";
                echo "<p>You have exceeded the attempts limit. Please restart the process.</p>";
                exit();
            }

            // Incorrect code, display error message
            echo "<h2>Email Confirmation</h2>";
            echo "<p>The confirmation code entered is incorrect. Please try again.</p>";
            // Form to restart the process
            echo '<form method="post">';
            echo '<label for="code">Confirmation Code:</label><br>';
            echo '<input type="text" id="code" name="code" required><br><br>';
            echo '<button type="submit">Confirm</button>';
            echo '</form>';
            // Button to resend code
            if ($_SESSION['resend_count'] < 3) {
                echo '<form method="post"><button type="submit" name="resend">Resend Code</button></form>';
            }
        }
    } else {
        // If the form with the code was not submitted, display the field for code insertion
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Confirmation</title>
        </head>
        <body>
            <h2>Email Confirmation</h2>
            <form method="post">
                <label for="code">Email:</label><br>
                <input type="text" id="code" value="<?php echo $email ?>" readonly><br><br>
                <label for="code">Confirmation Code:</label><br>
                <input type="text" id="code" name="code" required><br><br>
                <button type="submit">Confirm</button>
            </form>
            <!-- Button to resend code -->
            <?php if ($_SESSION['resend_count'] < 3) { ?>
                <form method="post"><button type="submit" name="resend">Resend Code</button></form>
            <?php } ?>
            <script>
                // Function to cancel the process and clear the session
                function cancelProcess() {
                    if (confirm('Are you sure you want to cancel the process?')) {
                        window.location.href = "index.php?cancel=1";
                    }
                }
            </script>
        </body>
        </html>
<?php
    }
} else {
    // If there is no code in the session, display the email sending form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email Confirmation</title>
    </head>
    <body>
        <h2>Email Confirmation</h2>
        <form method="post">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <?php
            // Check if the sending time has expired
            if (isset($_SESSION['time_sent']) && time() - $_SESSION['time_sent'] > 180) {
                echo "<p>The time to send the email has expired. Please reload the page and try again.</p>";
            } else {
                echo '<button type="submit">Send Confirmation Code</button>';
            }
            ?>
        </form>
    </body>
    </html>
    <?php
}

// Check if the resend code button was clicked
if (isset($_POST['resend'])) {
    // Check if the resend counter is within the limit
    if (isset($_SESSION['resend_count']) && $_SESSION['resend_count'] < 3) {
        // Increment the resend counter
        $_SESSION['resend_count']++;

        // Generate a new random code
        $new_code = generateRandomCode();

        // Save the new code in the session
        $_SESSION['confirmation_code'] = $new_code;

        // Send the email with the new confirmation code
        $subject = "Email Confirmation";
        $message = "Your new confirmation code is: $new_code";
        $headers  = "From: No-reply@plata.ie <no-reply@plata.ie>\n";
        $headers .= "Cc: Plata.ie <no-reply@plata.ie>\n";
        $headers .= "X-Sender: Plata.ie <no-reply@plata.ie>\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $headers .= "X-Priority: 1\n"; // Urgent message!
        $headers .= "Return-Path: Plata.ie\n"; // Return path for errors
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
        mail($email, $subject, $message, $headers);
    } else {
        // Clear the session and restart the process
        unset($_SESSION['confirmation_code']);
        unset($_SESSION['email']);
        unset($_SESSION['time_sent']);
        unset($_SESSION['attempts']); // Clear the attempts counter
        unset($_SESSION['resend_count']); // Clear the resend counter
        unset($_SESSION['code_sent']); // Mark that the code has not been sent anymore
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Check if the cancel button was clicked
if (isset($_GET['cancel']) || isset($_POST['cancel'])) {
    // Clear the session and redirect to the home page
    unset($_SESSION['confirmation_code']);
    unset($_SESSION['email']);
    unset($_SESSION['time_sent']);
    unset($_SESSION['attempts']); // Clear the attempts counter
    unset($_SESSION['resend_count']); // Clear the resend counter
    unset($_SESSION['code_sent']); // Mark that the code has not been sent anymore
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
