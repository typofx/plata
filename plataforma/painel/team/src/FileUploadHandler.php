<?php
/**
 * FileUploadHandler Class
 * 
 * Handles validation and processing of uploaded profile pictures.
 * Validates file type, size, and returns temporary file path for email attachment.
 * Does NOT store files to disk - uses PHP's temporary file system.
 */
class FileUploadHandler {
    
    private const ALLOWED_EXTS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const MAX_SIZE = 5 * 1024 * 1024; // 5MB

    /**
     * Handle file upload validation and processing
     * 
     * Validates that:
     * - A file was actually uploaded (not just an empty field)
     * - The file type is in the allowed list
     * - The file size does not exceed 5MB
     * 
     * Returns the original filename and temporary path for email attachment.
     * Does not store the file to disk - it will be automatically deleted
     * by PHP after the email is sent.
     * 
     * @return array|null - Array with 'originalName' and 'tmpPath' keys, or null if no file
     * @throws Exception - If file validation fails
     */
    public function handle() {
        // Check if file was submitted or if user selected no file
        if (!isset($_FILES['profilePicture']) || $_FILES['profilePicture']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        // Validate upload errors (network issues, server errors, etc.)
        if ($_FILES['profilePicture']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error: " . $_FILES['profilePicture']['error']);
        }

        // Extract file information from $_FILES superglobal
        $fileName = $_FILES['profilePicture']['name'];
        $fileSize = $_FILES['profilePicture']['size'];
        $fileTmp = $_FILES['profilePicture']['tmp_name'];
        // Get file extension in lowercase for comparison
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate file extension is in allowed list
        if (!in_array($fileExt, self::ALLOWED_EXTS)) {
            throw new Exception("Invalid file type. Allowed: " . implode(', ', self::ALLOWED_EXTS));
        }

        // Validate file size does not exceed maximum allowed
        if ($fileSize > self::MAX_SIZE) {
            throw new Exception("File size exceeds 5MB limit");
        }

        // Return original filename and temporary path for email attachment
        // The temporary file will be automatically deleted by PHP after use
        return [
            'originalName' => $fileName,
            'tmpPath' => $fileTmp
        ];
    }
}
?>

