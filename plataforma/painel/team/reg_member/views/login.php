<?php
require_once '../SessionManager.php';

// Redirect if already authenticated
if (SessionManager::isLoggedIn()) {
    header('Location: forms/MemberForms1.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Typo FX</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo img {
            width: 150px;
            height: auto;
        }

        h1 {
            color: #333;
            text-align: center;
            font-size: 24px;
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }

        .recaptcha-wrapper {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #5568d3;
        }

        button:active {
            background-color: #4a5cc0;
        }

        .error {
            color: #d32f2f;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }

        .info {
            color: #666;
            font-size: 13px;
            text-align: center;
            margin-top: 20px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="https://typofx.ie/images/typo-fx-icon.svg" alt="Typo FX">
        </div>

        <h1>Typo FX</h1>

        <form action="../process/login_process.php" method="POST" onsubmit="return validateForm();">
            <div class="form-group">
                <label for="token">Authenticator Token:</label>
                <input 
                    type="text" 
                    id="token" 
                    name="token" 
                    placeholder="Enter your 6-digit code"
                    required 
                    autocomplete="off"
                    maxlength="6"
                    pattern="[0-9]{6}"
                >
            </div>

            <div class="recaptcha-wrapper">
                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
            </div>

            <button type="submit">Login</button>

            <?php
            if (isset($_GET['error'])) {
                $error_messages = [
                    'invalid_token' => 'Invalid or expired code',
                    'recaptcha_required' => 'Please validate reCAPTCHA',
                    'missing_fields' => 'Please fill all fields',
                    'database_error' => 'Database connection error',
                    'recaptcha_failed' => 'reCAPTCHA validation failed',
                    'recaptcha_error' => 'reCAPTCHA error',
                    'recaptcha_config' => 'reCAPTCHA not configured',
                    'auth_error' => 'Authentication error'
                ];
                $error_key = $_GET['error'];
                $error_msg = $error_messages[$error_key] ?? 'Login error';
                echo '<div class="error">❌ ' . htmlspecialchars($error_msg) . '</div>';
            }
            ?>
        </form>

        <div class="info">
            <p>Use Google Authenticator to generate your 6-digit code.</p>
            <p>Don't have authenticator? <a href="../process/setup_authenticator.php" style="color: #667eea; text-decoration: none;">Click here to set it up</a></p>
        </div>
    </div>

    <script>
        function validateForm() {
            const token = document.getElementById('token').value.trim();
            
            if (!token || token.length !== 6 || !/^[0-9]{6}$/.test(token)) {
                alert('Please enter a valid 6-digit token');
                return false;
            }

            const recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                alert('Please confirm reCAPTCHA');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
