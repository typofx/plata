<?php
/**
 * RegistrationLogger Class
 * 
 * Creates audit trail of all team member registrations.
 * Logs registration details to a file for tracking and compliance purposes.
 */
class RegistrationLogger {
    private const LOG_DIR = __DIR__ . '/../logs/';
    private const LOG_FILE = 'registrations.log';

    /**
     * Write registration entry to audit log
     * 
     * Records registration details including timestamp, member name, email,
     * position, and unique identifier for tracking and compliance purposes.
     * Creates the logs directory if it doesn't exist.
     * 
     * @param array $formData - Registration form data containing name, last_name, email, position
     * @param string $uuid - Unique identifier for this registration
     * 
     * @return void
     */
    
    public function write($formData, $uuid) {
        // Create logs directory if it doesn't exist yet
        if (!is_dir(self::LOG_DIR)) {
            mkdir(self::LOG_DIR, 0755, true);
        }

        $logFile = self::LOG_DIR . self::LOG_FILE;
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] Registration - Name: {$formData['name']} {$formData['last_name']} | Email: {$formData['email']} | Position: {$formData['position']} | UUID: {$uuid}\n";

        // Append log message to file (FILE_APPEND preserves existing content)
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
?>
