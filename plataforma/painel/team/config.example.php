<?php
/**
 * Configuration Example File
 * 
 * Copy this file to config.php and fill in your actual values
 * OR use .env file for environment variables (recommended)
 */

// Load .env file
$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue; // Skip comments
        
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, '\'"');
            
            define($key, $value);
        }
    }
} else {
    // Fallback values if .env not found
    define('SMTP_HOST', 'smtp.gmail.com');
    define('SMTP_PORT', 587);
    define('SMTP_ENCRYPTION', 'tls');
    define('SMTP_USERNAME', 'your-email@gmail.com');
    define('SMTP_PASSWORD', 'your-app-password');
    define('SMTP_FROM_EMAIL', 'your-email@gmail.com');
    define('SMTP_FROM_NAME', 'Team Platform');
    
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'your-password');
    define('DB_NAME', 'plata_team');
    
    define('APP_NAME', 'Team Platform');
    define('APP_URL', 'http://127.0.0.1:8000');
}
?>
