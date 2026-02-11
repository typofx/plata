<?php
require_once '../../SessionManager.php';

// Check if user is logged in
if (!SessionManager::isLoggedIn()) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Authentication Required</title>
        <style>
            body { font-family: Arial; margin: 50px; background: #f0f0f0; }
            .container { background: white; padding: 30px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); max-width: 500px; }
            .error { color: #d32f2f; font-weight: bold; }
            a { color: #1976d2; text-decoration: none; }
            a:hover { text-decoration: underline; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2><span class="error">⚠️ Error:</span> User Not Logged In</h2>
            <p>You must be logged in to access this form.</p>
            <p><a href="../login.php">← Go back to Login</a></p>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Check if form1_data exists in session
if (!SessionManager::has('form1_data')) {
    SessionManager::set('error', "Please complete Step 1 first.");
}

// Generate CSRF token for form protection
if (!SessionManager::has('csrf_token')) {
    SessionManager::set('csrf_token', bin2hex(random_bytes(32)));
}

// Display success/error messages if any
if (SessionManager::has('success')) {
    echo '<div style="color: green; padding: 10px; margin-bottom: 15px;">' . htmlspecialchars(SessionManager::get('success')) . '</div>';
    SessionManager::remove('success');
}
if (SessionManager::has('error')) {
    echo '<div style="color: red; padding: 10px; margin-bottom: 15px;">' . htmlspecialchars(SessionManager::get('error')) . '</div>';
    SessionManager::remove('error');
}

// If POST request, process the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Load Ethereum validator
    require_once __DIR__ . '/../../Lib/eth/EthereumValidator.php';
    
    // Validate CSRF token
    if (empty($_POST['csrf_token']) || !hash_equals(SessionManager::get('csrf_token', '') ?? '', $_POST['csrf_token'])) {
        SessionManager::set('error', "Security error. Please try again.");
        header('Location: MemberForms2.php');
        exit();
    }

    // Collect wallet data from form
    $form2_data = [
        'defi_wallet' => trim($_POST['defi_wallet'] ?? ''),
        'cex_wallet' => trim($_POST['cex_wallet'] ?? ''),
        'binance_id' => trim($_POST['binance_id'] ?? ''),
        'binanceName' => trim($_POST['binanceName'] ?? ''),
        'cpf' => trim($_POST['cpf'] ?? ''),
        'passport_code' => trim($_POST['passport_code'] ?? ''),
        'pix' => trim($_POST['pix'] ?? ''),
        'location' => trim($_POST['location'] ?? ''),
        'recipient_email' => trim($_POST['recipient_email'] ?? '')
    ];

    // Validate recipient email
    if (empty($form2_data['recipient_email']) || !filter_var($form2_data['recipient_email'], FILTER_VALIDATE_EMAIL)) {
        SessionManager::set('error', "Please provide a valid recipient email.");
        header('Location: MemberForms2.php');
        exit();
    }

    // Validate DeFi Wallet using EthereumValidator
    if (!empty($form2_data['defi_wallet'])) {
        if (!EthereumValidator::isValid($form2_data['defi_wallet'])) {
            SessionManager::set('error', "Invalid DeFi Wallet Ethereum address format.");
            header('Location: MemberForms2.php');
            exit();
        }
    }

    // Validate CEX Wallet using EthereumValidator
    if (!empty($form2_data['cex_wallet'])) {
        if (!EthereumValidator::isValid($form2_data['cex_wallet'])) {
            SessionManager::set('error', "Invalid CEX Wallet Ethereum address format.");
            header('Location: MemberForms2.php');
            exit();
        }
    }

    // Save form2 data to session
    SessionManager::set('form2_data', $form2_data);

    // Handle file upload if passport file is provided - encode to base64 and store in session
    if (!empty($_FILES['passport']) && $_FILES['passport']['error'] === UPLOAD_ERR_OK) {
        $file_info = pathinfo($_FILES['passport']['name']);
        $file_ext = strtolower($file_info['extension']);
        
        // Validate file extension
        if ($file_ext !== 'pdf') {
            SessionManager::set('error', "Only PDF files are allowed for passport.");
            header('Location: MemberForms2.php');
            exit();
        }
        
        // Validate file size (max 5MB)
        if ($_FILES['passport']['size'] > 5 * 1024 * 1024) {
            SessionManager::set('error', "Passport file size must not exceed 5MB.");
            header('Location: MemberForms2.php');
            exit();
        }
        
        // Read file and encode to base64
        $file_content = file_get_contents($_FILES['passport']['tmp_name']);
        $file_base64 = base64_encode($file_content);
        
        $form2_data['passport_data'] = $file_base64;
        $form2_data['passport_name'] = $_FILES['passport']['name'];
        $form2_data['passport_size'] = $_FILES['passport']['size'];
        SessionManager::set('form2_data', $form2_data);
    }

    // Combine both form datasets
    $form1_data = SessionManager::get('form1_data', []);

    // Send consolidated email
    try {
        require_once __DIR__ . '/../../config.php';
        require_once __DIR__ . '/../../EmailNotificationService.php';

        $email_service = new EmailNotificationService();
        $email_service->sendConsolidatedEmail($form1_data, $form2_data, $form2_data['recipient_email']);

        // Clear session data after successful submission
        SessionManager::remove('form1_data');
        SessionManager::remove('form2_data');

        // Redirect to success page
        header('Location: ../success.php');
        exit();
    } catch (Exception $e) {
        SessionManager::set('error', "Error sending email: " . $e->getMessage());
        header('Location: ./MemberForms2.php');
        exit();
    }
}

// If form1_data doesn't exist, show redirect link
if (!SessionManager::has('form1_data')) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Error</title>
        <style>
            body { font-family: Arial; margin: 50px; }
            .error { color: red; padding: 15px; background: #ffe6e6; border: 1px solid red; border-radius: 3px; }
            a { color: #667eea; font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="error">
            <strong>Error:</strong> Please complete Step 1 (Member Form 1) first.
        </div>
        <p><a href="MemberForms1.php">← Go back to Step 1</a></p>
    </body>
    </html>
    <?php
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Member</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <form action="MemberForms2.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(SessionManager::get('csrf_token', '')); ?>">

        <h3>Wallet & Payment Information</h3>

        <label for="defi_wallet">DeFi ETH Wallet:</label><br>
        <input type="text" name="defi_wallet" id="defi_wallet" class="camp" style="width: 100%; max-width: 320px; padding: 2px;" onkeyup="validateEthereumWallet()">
        <span id="defi_wallet_status"></span>

        <br><br>

        <label for="cex_wallet">CEX ETH Wallet:</label><br>
        <input type="text" name="cex_wallet" id="cex_wallet" class="camp" style="width: 100%; max-width: 320px; padding: 2px;" onkeyup="validateEthereumWallet()">
        <span id="cex_wallet_status"></span>

        <br><br>

        <label for="binance_id">Binance ID:</label><br>
        <input type="text" name="binance_id" id="binance_id">

        <br><br>

        <label for="binanceName">Binance Nickname:</label><br>
        <input type="text" name="binanceName" id="binanceName">

        <br><br>

        <label for="cpf">CPF:</label><br>
        <input type="text" name="cpf" id="cpf" onkeyup="validateCPF()">
        <span id="cpf_status"></span>

        <br><br>

        <label for="passport_code">Passport Code:</label><br>
        <input type="text" name="passport_code" id="passport_code">

        <br><br>

        <label for="passport">Passport (PDF):</label><br>
        <input type="file" name="passport" id="passport" accept=".pdf" onchange="validateFileUpload(this, 'passport_status', 'Passport PDF')">
        <span id="passport_status"></span>

        <br><br>

        <label for="pix">PIX:</label><br>
        <input type="text" name="pix" id="pix">

        <br><br>

        <label for="location">Location (Town/City):</label><br>
        <input type="text" name="location" id="location">

        <br><br>

        <label for="recipient_email">Email Recipient (for confirmation):</label><br>
        <input type="email" name="recipient_email" id="recipient_email" required placeholder="your-email@example.com" style="width: 100%; max-width: 320px; padding: 8px; box-sizing: border-box;">

        <br><br>

        <input type="submit" name="submit" value="Submit">
    </form>

    <script>
            function validateEthereumWallet() {
                const defiWallet = document.getElementById("defi_wallet").value;
                const cexWallet = document.getElementById("cex_wallet").value;
                const defiStatus = document.getElementById("defi_wallet_status");
                const cexStatus = document.getElementById("cex_wallet_status");
                
                // Validate DeFi Wallet
                if (defiWallet) {
                    if (isValidEthereumAddress(defiWallet)) {
                        defiStatus.innerHTML = "✓";
                        defiStatus.style.color = "green";
                    } else {
                        defiStatus.innerHTML = "Must be an Ethereum wallet";
                        defiStatus.style.color = "red";
                    }
                } else {
                    defiStatus.innerHTML = "";
                }
                
                // Validate CEX Wallet
                if (cexWallet) {
                    if (isValidEthereumAddress(cexWallet)) {
                        cexStatus.innerHTML = "✓";
                        cexStatus.style.color = "green";
                    } else {
                        cexStatus.innerHTML = "Must be an Ethereum wallet";
                        cexStatus.style.color = "red";
                    }
                } else {
                    cexStatus.innerHTML = "";
                }
            }
            
            function isValidEthereumAddress(address) {
                return /^0x[a-fA-F0-9]{40}$/.test(address);
            }
            function isValidCPF(cpf) {
                cpf = cpf.replace(/\D/g, '');
                
                if (cpf.length !== 11) {
                    return false;
                }
                
                if (/^(\d)\1{10}$/.test(cpf)) {
                    return false;
                }
                
                // Calculate first check digit
                let sum = 0;
                let remainder;
                
                for (let i = 1; i <= 9; i++) {
                    sum += parseInt(cpf.substring(i - 1, i)) * (11 - i);
                }
                
                remainder = (sum * 10) % 11;
                if (remainder === 10 || remainder === 11) {
                    remainder = 0;
                }
                
                if (remainder !== parseInt(cpf.substring(9, 10))) {
                    return false;
                }
                
                // Calculate second check digit
                sum = 0;
                for (let i = 1; i <= 10; i++) {
                    sum += parseInt(cpf.substring(i - 1, i)) * (12 - i);
                }
                
                remainder = (sum * 10) % 11;
                if (remainder === 10 || remainder === 11) {
                    remainder = 0;
                }
                
                if (remainder !== parseInt(cpf.substring(10, 11))) {
                    return false;
                }
                
                return true;
            }

            /**
             * Validate file upload - size and type
             * Called on file change event for real-time feedback to user
             * 
             * Checks:
             * - File type (only .pdf allowed)
             * - File size (max 5MB)
             * - MIME type validation
             */
            function validateFileUpload(inputElement, statusElementId, fileLabel = 'PDF') {
                const file = inputElement.files[0];
                const statusElement = document.getElementById(statusElementId);
                
                if (!file) {
                    statusElement.innerHTML = '';
                    return;
                }
                
                // Check file extension
                const fileName = file.name.toLowerCase();
                const isValidExtension = fileName.endsWith('.pdf');
                
                // Check file size (5MB = 5 * 1024 * 1024 bytes)
                const maxSize = 5 * 1024 * 1024;
                const isValidSize = file.size <= maxSize;
                
                // Check MIME type
                const isValidMimeType = file.type === 'application/pdf';
                
                // Provide feedback
                if (!isValidExtension) {
                    statusElement.innerHTML = "Invalid file type. Only PDF files are allowed.";
                    statusElement.style.color = "red";
                    inputElement.value = '';
                } else if (!isValidMimeType) {
                    statusElement.innerHTML = "Invalid PDF format. Please upload a valid PDF file.";
                    statusElement.style.color = "red";
                    inputElement.value = '';
                } else if (!isValidSize) {
                    statusElement.innerHTML = "File size exceeds 5MB limit.";
                    statusElement.style.color = "red";
                    inputElement.value = '';
                } else {
                    const fileSizeKB = (file.size / 1024).toFixed(2);
                    statusElement.innerHTML = "✓";
                    statusElement.style.color = "green";
                }
            }

            /**
             * Validate entire form before submission
             */
            function validateForm() {
                // Validation can be extended here if needed
                return true;
            }

            /**
             * Copy text to clipboard
             */
            function copyTextToClipboard(text) {
                if (text) {
                    navigator.clipboard.writeText(text).then(function() {
                        alert('Copied to clipboard!');
                    }, function(err) {
                        console.error('Could not copy text: ', err);
                    });
                }
            }
        </script>
    </form>

</body>
</html>