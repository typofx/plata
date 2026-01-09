<?php
/**
 * Team Member Registration - Simplified Flow
 * 
 * Coordinates the complete registration workflow:
 * 1. Validates form data
 * 2. Processes file upload
 * 3. Sends confirmation email
 */

session_start();
require __DIR__ . '/config.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/EmailNotificationService.php';

try {
    // Validate request method
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid request method.");
    }

    // Validate and sanitize form data
    $formData = [
        'name' => trim($_POST['name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'email' => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
        'password' => $_POST['password'] ?? '',
        'confirm_password' => $_POST['confirm_password'] ?? '',
        'position' => trim($_POST['position'] ?? ''),
        'recipient_email' => filter_var($_POST['recipient_email'] ?? '', FILTER_SANITIZE_EMAIL)
    ];

    // Check required fields
    if (empty($formData['name']) || empty($formData['last_name']) || 
        empty($formData['email']) || empty($formData['password']) || 
        empty($formData['position']) || empty($formData['recipient_email'])) {
        throw new Exception("Please fill in all required fields.");
    }

    // Validate email formats
    if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid platform email format.");
    }
    if (!filter_var($formData['recipient_email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid recipient email format.");
    }

    // Validate passwords match
    if ($formData['password'] !== $formData['confirm_password']) {
        throw new Exception("Passwords do not match!");
    }

    // Validate password length
    if (strlen($formData['password']) < 6) {
        throw new Exception("Password must be at least 6 characters.");
    }

    // Collect social media information
    $socialMedia = [];
    $socialFields = ['whatsapp', 'instagram', 'telegram', 'facebook', 'github', 'social_email', 'twitter', 'linkedin', 'twitch', 'medium'];
    foreach ($socialFields as $field) {
        $socialMedia[$field] = trim($_POST[$field] ?? '');
    }

    // Handle file upload
    $fileUploadData = null;
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['profilePicture']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error: " . $_FILES['profilePicture']['error']);
        }

        $fileName = $_FILES['profilePicture']['name'];
        $fileSize = $_FILES['profilePicture']['size'];
        $fileTmp = $_FILES['profilePicture']['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate file type
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($fileExt, $allowedExts)) {
            throw new Exception("Invalid file type. Allowed: " . implode(', ', $allowedExts));
        }

        // Validate file size (5MB max)
        if ($fileSize > 5 * 1024 * 1024) {
            throw new Exception("File size exceeds 5MB limit");
        }

        $fileUploadData = [
            'originalName' => $fileName,
            'tmpPath' => $fileTmp
        ];
    }

    // Send confirmation email
    $emailService = new EmailNotificationService();
    $emailService->send($formData, $fileUploadData, $socialMedia);

    // Success message
    $_SESSION['success'] = 'Team member registered successfully!';
    header('Location: add.php');
    exit();

} catch (Exception $e) {
    // Handle errors
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
    error_log('Registration error: ' . $e->getMessage());
    header('Location: add.php');
    exit();
}
?>
