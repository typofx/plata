<?php
session_start();
// Include database connection file
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email from the form
    $email = trim($_POST['email']);
    
    // Check if the email exists in the database
    $query = "SELECT id FROM granna80_bdlinks.granna_users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Email exists, proceed with two-step verification
        // Generate a verification code and send it to the user's email (to be implemented)
        $verificationCode = mt_rand(100000, 999999); // Example code generation

        // Start the session and store the verification code and email with unique session names
        session_start();
        $_SESSION['forgot_verification_code'] = $verificationCode;
        $_SESSION['code_email'] = $email;
        
        // Redirect to the updated verification page
        echo "<script>window.location.href='change_verify_code.php';</script>";
        exit();
    } else {
        // Email does not exist
        echo "Email not found. Please try again.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change password</title>
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
    <h2>Change password</h2>
    <form action="change_password.php" method="POST">
        <label for="email">Enter your email:</label><br><br>
        <input type="email" name="email" value="<?php echo  $_SESSION['code_email'] ?>" id="email" required>
    
        <br><br><button type="submit">Submit</button>
        <a href="index.php">[ Cancel ]</a>
    </form>
    </center>    
</body>
</html>
