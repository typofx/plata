<?php

/**
 * Process Member Wallet Update
 * 
 * Simplified workflow:
 * 1. Validates form data
 * 2. Sends confirmation email only
 * 3. No database updates
 */

session_start();

require __DIR__ . '/../config.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../EmailNotificationService.php';

try {

    // Validate request method
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid request method.");
    }

    // Validate CSRF token
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        throw new Exception("CSRF token validation failed.");
    }

    // Validate and sanitize form data
    $formData = [
        'defi_wallet' => trim($_POST['defi_wallet'] ?? ''),
        'cex_wallet' => trim($_POST['cex_wallet'] ?? ''),
        'binance_id' => trim($_POST['binance_id'] ?? ''),
        'binanceName' => trim($_POST['binanceName'] ?? ''),
        'cpf' => preg_replace('/\D/', '', trim($_POST['cpf'] ?? '')),
        'pix' => trim($_POST['pix'] ?? ''),
        'location' => trim($_POST['location'] ?? ''),
        'passport' => trim($_POST['passport'] ?? ''),
        'recipient_email' => filter_var($_POST['recipient_email'] ?? '', FILTER_SANITIZE_EMAIL)
    ];

    // Validate email format
    if (!filter_var($formData['recipient_email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid recipient email format.");
    }

    // Validate CPF if provided
    if (!empty($formData['cpf'])) {
        if (!isValidCPF($formData['cpf'])) {
            throw new Exception("Invalid CPF format.");
        }
    }

    // Validate Ethereum wallets if provided
    if (!empty($formData['defi_wallet'])) {
        if (!isValidEthereumAddressFormat($formData['defi_wallet'])) {
            throw new Exception("Invalid DeFi wallet address format.");
        }
    }

    if (!empty($formData['cex_wallet'])) {
        if (!isValidEthereumAddressFormat($formData['cex_wallet'])) {
            throw new Exception("Invalid CEX wallet address format.");
        }
    }

    // Send confirmation email
    $emailService = new EmailNotificationService();
    $emailService->sendWalletUpdateEmail([], $formData, [], null);

    // Success message
    date_default_timezone_set("UTC");
    $_SESSION['success'] = 'Wallet information updated successfully! Email sent. ' . date("H:i:s d/m/Y");

    header('Location: ../update_member.php');
    exit();

} catch (Exception $e) {

    // Handle errors
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
    header('Location: ../update_member.php');
    exit();

}

// Helper functions

/**
 * Check if Ethereum address format is valid
 */

function isValidEthereumAddressFormat($address) {
    return preg_match('/^0x[a-fA-F0-9]{40}$/', $address) === 1;
}

/**
 * Validate CPF using Brazilian algorithm
 */
function isValidCPF($cpf) {
    // CPF must have exactly 11 digits
    if (strlen($cpf) !== 11) {
        return false;
    }

    // Check if all digits are the same
    if (preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }

    // Calculate first check digit
    $sum = 0;
    for ($i = 1; $i <= 9; $i++) {
        $sum += intval($cpf[$i - 1]) * (11 - $i);
    }

    $remainder = ($sum * 10) % 11;
    if ($remainder === 10 || $remainder === 11) {
        $remainder = 0;
    }

    if ($remainder !== intval($cpf[9])) {
        return false;
    }

    // Calculate second check digit
    $sum = 0;
    for ($i = 1; $i <= 10; $i++) {
        $sum += intval($cpf[$i - 1]) * (12 - $i);
    }

    $remainder = ($sum * 10) % 11;
    if ($remainder === 10 || $remainder === 11) {
        $remainder = 0;
    }

    if ($remainder !== intval($cpf[10])) {
        return false;
    }

    return true;
}

?>
