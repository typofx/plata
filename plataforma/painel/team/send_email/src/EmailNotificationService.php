<?php
/**
 * EmailNotificationService Class
 * 
 * Responsible for sending registration confirmation emails to team member recipients.
 * Uses PHPMailer for SMTP delivery and attaches profile pictures as file attachments.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailNotificationService {
    /**
     * Social media platform display labels
     * Maps internal field names to user-friendly labels for email display
     * 
     * @var array
     */
    private $socialLabels = [
        'whatsapp' => 'WhatsApp',
        'instagram' => 'Instagram',
        'telegram' => 'Telegram',
        'facebook' => 'Facebook',
        'github' => 'GitHub',
        'social_email' => 'Email',
        'twitter' => 'Twitter (X)',
        'linkedin' => 'LinkedIn',
        'twitch' => 'Twitch',
        'medium' => 'Medium'
    ];

    /**
     * Send registration confirmation email
     * 
     * @param array $formData - Registration form data (name, email, password, etc.)
     * @param array|null $fileUploadData - Profile picture file data with tmpPath and originalName
     * @param array $socialMedia - Social media fields filled by the user
     * 
     * @return void
     */
    public function send($formData, $fileUploadData, $socialMedia) {
        try {

            $mail = new PHPMailer(true);

            // Configure SMTP settings from environment variables
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;

            $mail->CharSet = 'UTF-8';

            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            
            // Use recipient email provided by the user during registration
            $mail->addAddress($formData['recipient_email']);

            // Configure email as HTML format
            $mail->isHTML(true);
            $mail->Subject = "New Team Member Registration - " . $formData['name'] . " " . $formData['last_name'];
            
            // Generate email body content
            $mail->Body = $this->buildEmailBody($formData, $socialMedia);

            // Attach profile picture if file was uploaded
            // Only proceed if temporary file exists and is valid
            if ($fileUploadData !== null && !empty($fileUploadData['tmpPath'])) {
                if (is_uploaded_file($fileUploadData['tmpPath'])) {
                    $mail->addAttachment($fileUploadData['tmpPath'], $fileUploadData['originalName']);
                }
            }

            // Send the email (suppress output with @ operator)
            @$mail->send();

        } catch (Exception $e) {
            // Log email errors for debugging without stopping execution
            error_log('Email sending error: ' . $e->getMessage());
        }
    }

    /**
     * Build HTML email body with member registration details
     * 
     * Formats the registration information in a clean, pre-formatted text style
     * for easy reading and maintaining data structure.
     * 
     * @param array $formData - Registration form data
     * @param array $socialMedia - Social media information
     * 
     * @return string - Formatted HTML email body
     */
    private function buildEmailBody($formData, $socialMedia) {
        // Start HTML document with styling
        $body = '<html><body style="font-family: monospace; line-height: 1.6; color: #333; background-color: #f5f5f5; padding: 20px;">';
        $body .= '<div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">';
        
        // Email header section
        $body .= '<pre style="color: #2c3e50; text-align: left; margin-bottom: 20px; margin-left: 0;">';
        $body .= 'NEW TEAM MEMBER REGISTRATION';
        $body .= "\n" . str_repeat('=', 45);
        $body .= '</pre>';

        // Main registration details section
        $body .= '<pre style="color: #333; font-size: 14px; text-align: left; margin-left: 0;">';
        $body .= 'NAME: ' . htmlspecialchars($formData['name']) . "\n";
        $body .= 'LAST NAME: ' . htmlspecialchars($formData['last_name']) . "\n";
        $body .= 'EMAIL: ' . htmlspecialchars($formData['email']) . "\n";
        $body .= 'PASSWORD: ' . htmlspecialchars($formData['password']) . "\n";
        $body .= 'POSITION: ' . htmlspecialchars($formData['position']) . "\n\n";

        // Social media section - only shows non-empty fields
        $body .= 'SOCIAL MEDIA:' . "\n";

        foreach ($socialMedia as $key => $value) {
            // Skip empty social media fields
            if (!empty($value)) {
                $label = $this->socialLabels[$key] ?? ucfirst(str_replace('_', ' ', $key));
                $body .= htmlspecialchars($label) . ': ' . htmlspecialchars($value) . "\n";
            }
        }

        // Email footer separator
        $body .= "\n" . str_repeat('=', 45);
        $body .= '</pre>';

        $body .= '</div></body></html>';

        return $body;
    }

    /**
     * Get MIME type for file attachment based on extension
     * 
     * Maps file extensions to their proper MIME types for correct
     * email client display and handling.
     * 
     * @param string $ext - File extension (jpg, png, gif, etc.)
     * 
     * @return string - MIME type (image/jpeg, image/png, etc.)
     */
    private function getMimeType($ext) {
        // MIME type mapping for supported image formats
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp'
        ];

        // Return MIME type or default to JPEG if not found
        return $mimeTypes[$ext] ?? 'image/jpeg';
    }
}
?>
