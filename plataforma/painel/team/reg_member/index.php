<?php
require_once 'SessionManager.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Typo FX</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }

        .header-top h1 {
            margin: 0;
            color: #333;
        }

        .logout-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }

        h1 {
            color: #333;
            margin-bottom: 5px;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }

        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .status.active {
            background-color: #d4edda;
            color: #155724;
        }

        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-top">
            <div>
                <h1>🔐 Member Registration System</h1>
                <p class="subtitle">Testing environment for member registration and validation</p>
            </div>
            <a href="process/logout.php" class="logout-btn">🚪 Logout</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Step</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>0️⃣ Setup Authenticator</td>
                    <td>Generate QR Code for Google Authenticator (first time)</td>
                    <td><span class="status active">✓ Active</span></td>
                    <td><a href="process/setup_authenticator.php">Configure</a></td>
                </tr>
                <tr>
                    <td>1️⃣ Login</td>
                    <td>Authenticate with TOTP (Google Authenticator) + reCAPTCHA</td>
                    <td><span class="status active">✓ Active</span></td>
                    <td><a href="views/login.php">Login</a></td>
                </tr>
                <tr>
                    <td>2️⃣ Form 1</td>
                    <td>Personal data, profile picture and social media</td>
                    <td><span class="status active">✓ Active</span></td>
                    <td><a href="views/forms/MemberForms1.php">Fill</a></td>
                </tr>
                <tr>
                    <td>3️⃣ Form 2</td>
                    <td>Ethereum wallets, banking data and documents</td>
                    <td><span class="status active">✓ Active</span></td>
                    <td><a href="views/forms/MemberForms2.php">Fill</a></td>
                </tr>
                <tr>
                    <td>4️⃣ Success</td>
                    <td>Registration confirmation with email sent</td>
                    <td><span class="status active">✓ Active</span></td>
                    <td><a href="views/success.php">View</a></td>
                </tr>
                <tr style="background-color: #e8f5e9;">
                    <td>📋 Info</td>
                    <td>Optimized system - No Composer, no vendor folder</td>
                    <td><span class="status active">✓ Ready</span></td>
                    <td><a href="#" onclick="alert('Structure consolidated in Lib/')">Structure</a></td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 30px; padding: 15px; background-color: #f0f0f0; border-radius: 5px;">
            <h3>📦 Technologies Used:</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li><strong>Authentication:</strong> TOTP (Google Authenticator v2.0) + reCAPTCHA v2 Checkbox</li>
                <li><strong>Email:</strong> PHPMailer v7.0.2 (SMTP HostGator)</li>
                <li><strong>Validation:</strong> Custom Ethereum Validator (no web3)</li>
                <li><strong>Files:</strong> Base64 encoding (no disk storage)</li>
                <li><strong>CSRF:</strong> Protection with randomized tokens</li>
                <li><strong>Security:</strong> Password hashing, input sanitization, session TTL 12h</li>
            </ul>
        </div>
    </div>
</body>
</html>