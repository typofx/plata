<?php
/**
 * Team Member Registration Orchestrator
 * 
 * Coordinates the complete registration workflow:
 * 1. Validates form data
 * 2. Processes file upload
 * 3. Saves data to database
 * 4. Logs the registration
 * 5. Sends confirmation email
 */

// Start session to manage messages across page redirects
session_start();
include 'conexao.php';
include 'config.php';

require __DIR__ . '/../../../vendor/autoload.php';
// Include service classes
require __DIR__ . '/src/RegistrationValidator.php';
require __DIR__ . '/src/FileUploadHandler.php';
require __DIR__ . '/src/RegistrationService.php';
require __DIR__ . '/src/RegistrationLogger.php';
require __DIR__ . '/src/EmailNotificationService.php';

try {
    // Validate form data (required fields, email format, password matching)
    $validator = new RegistrationValidator();
    $validator->validate();

    // Retrieve validated form data and social media information
    $formData = $validator->getFormData();
    $socialMedia = $validator->getSocialMedia();

    // Handle file upload (validate type, size, return temp path)
    $uploader = new FileUploadHandler();
    $fileUploadData = $uploader->handle();

    //  Save registration to database (users and team tables)
    $service = new RegistrationService($conn);
    $uuid = $service->register($formData, $fileUploadData, $socialMedia);

    // Close database connection after use
    $conn->close();

    // Write registration to audit log
    $logger = new RegistrationLogger();
    $logger->write($formData, $uuid);

    // Send confirmation email with profile picture attachment
    $emailService = new EmailNotificationService();
    $emailService->send($formData, $fileUploadData, $socialMedia);

    // Set success message and redirect
    $_SESSION['success'] = 'Team member registered successfully!';
    header('Location: add.php');
    exit();

} catch (Exception $e) {
    // Handle any errors during the registration process
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
    error_log('Registration error: ' . $e->getMessage());
    header('Location: add.php');
    exit();
}
?>
