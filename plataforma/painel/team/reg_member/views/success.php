<?php
require_once '../SessionManager.php';

if (!SessionManager::isLoggedIn()) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }
        .container {
            padding: 30px;
        }
        h1 {
            color: #27ae60;
        }
        .message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 3px;
            margin: 20px 0;
        }
        p {
            color: #555;
            line-height: 1.6;
        }
        .buttons {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        .btn-secondary:hover {
            background: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✓ Registration Successful!</h1>
        
        <div class="message">
            Your registration has been completed and submitted successfully.
        </div>

        <p>A confirmation email has been sent with all your information. Our team will process your registration and contact you shortly with further instructions.</p>

        <div class="buttons">
            <a href="forms/MemberForms1.php" class="btn btn-primary">← Back to Form</a>
            <a href="../process/logout.php" class="btn btn-secondary">Logout</a>
        </div>
    </div>
</body>
</html>
