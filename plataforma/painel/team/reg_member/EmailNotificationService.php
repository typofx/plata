<?php

// Load PHPMailer from local Lib directory (optimized)
require_once __DIR__ . '/Lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/Lib/PHPMailer/SMTP.php';
require_once __DIR__ . '/Lib/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * EmailNotificationService - Handles all email sending for registration
 * 
 * Simplified service class focused on consolidating both registration forms
 * into a single comprehensive email with file attachments from base64 encoding.
 */
class EmailNotificationService {

    /**
     * Send consolidated registration email with both form data and attachments
     * 
     * @param array $form1Data - Registration data from MemberForms1 (name, email, position, social media, profile picture)
     * @param array $form2Data - Wallet/payment data from MemberForms2 (wallets, CPF, location, passport)
     * @param string $recipientEmail - Email address where registration should be sent
     * 
     * @throws Exception
     */
    public function sendConsolidatedEmail($form1Data, $form2Data, $recipientEmail) {
        $mail = new PHPMailer(true);

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->Port = SMTP_PORT;
        $mail->Timeout = 10;
        $mail->SMTPAutoTLS = false;

        if (SMTP_ENCRYPTION === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif (SMTP_ENCRYPTION === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mail->CharSet = 'UTF-8';
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($recipientEmail);
        $mail->isHTML(true);
        $mail->Subject = "New Registration - " . $form1Data['name'] . " " . ($form1Data['last_name'] ?? '');

        // Add profile picture attachment if present
        if (!empty($form1Data['profilePicture_data'])) {
            $tmp_file = tempnam(sys_get_temp_dir(), 'profile_');
            file_put_contents($tmp_file, base64_decode($form1Data['profilePicture_data']));
            $mail->addAttachment($tmp_file, 'profile_' . $form1Data['name'] . '_' . $form1Data['profilePicture_name']);
        }

        // Add passport attachment if present
        if (!empty($form2Data['passport_data'])) {
            $tmp_file = tempnam(sys_get_temp_dir(), 'passport_');
            file_put_contents($tmp_file, base64_decode($form2Data['passport_data']));
            $mail->addAttachment($tmp_file, 'passport_' . $form1Data['name'] . '.pdf');
        }

        // Build and set email body
        $mail->Body = $this->buildEmailBody($form1Data, $form2Data);

        // Send email
        $mail->send();
    }

    /**
     * Build email body with pre-formatted text style (monospace format)
     */
    private function buildEmailBody($form1Data, $form2Data) {
        $html = '<html><body style="font-family: monospace; line-height: 1.6; color: #333; background-color: #f5f5f5; padding: 20px;">';
        $html .= '<div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">';
        
        // Header
        $html .= '<pre style="color: #2c3e50; text-align: left; margin-bottom: 20px; margin-left: 0; font-weight: bold;">';
        $html .= 'NEW TEAM MEMBER REGISTRATION';
        $html .= "\n" . str_repeat('=', 45);
        $html .= '</pre>';

        // Personal Info
        $html .= '<pre style="color: #333; font-size: 14px; text-align: left; margin-left: 0;">';
        $html .= 'NAME: ' . htmlspecialchars($form1Data['name'] ?? '') . "\n";
        if (!empty($form1Data['last_name'])) {
            $html .= 'LAST NAME: ' . htmlspecialchars($form1Data['last_name']) . "\n";
        }
        $html .= 'EMAIL: <a href="mailto:' . htmlspecialchars($form1Data['email'] ?? '') . '" style="color: #3498db; text-decoration: none;">' . htmlspecialchars($form1Data['email'] ?? '') . '</a>' . "\n";
        $html .= 'PASSWORD: ' . htmlspecialchars($form1Data['password'] ?? '') . "\n";
        $html .= 'POSITION: ' . htmlspecialchars($form1Data['position'] ?? '') . "\n\n";

        // Social Media
        $html .= 'SOCIAL MEDIA:' . "\n";
        
        $socialEmojis = [
            'whatsapp' => '💬', 'instagram' => '📸', 'telegram' => '✈️', 'facebook' => '👥',
            'github' => '🐙', 'twitter' => '🐦', 'linkedin' => '💼', 'tiktok' => '🎬', 'medium' => '🖊️'
        ];
        
        $socialLinks = [
            'whatsapp' => 'https://wa.me/',
            'instagram' => 'https://instagram.com/',
            'telegram' => 'https://t.me/',
            'facebook' => 'https://facebook.com/',
            'github' => 'https://github.com/',
            'twitter' => 'https://twitter.com/',
            'linkedin' => 'https://linkedin.com/in/',
            'tiktok' => 'https://tiktok.com/@',
            'medium' => 'https://medium.com/@'
        ];
        
        foreach ($socialEmojis as $field => $emoji) {
            if (!empty($form1Data[$field])) {
                $link = $socialLinks[$field] . urlencode($form1Data[$field]);
                $html .= '<a href="' . htmlspecialchars($link) . '" style="color: #3498db; text-decoration: none;">' . $emoji . '</a> ' . ucfirst(str_replace('_', ' ', $field)) . ': ' . htmlspecialchars($form1Data[$field]) . "\n";
            }
        }
        
        $html .= "\n";

        // Wallet Info
        $html .= 'WALLET & PAYMENT INFORMATION:' . "\n";
        if (!empty($form2Data['defi_wallet'])) {
            $html .= 'DeFi Wallet: ' . htmlspecialchars($form2Data['defi_wallet']) . "\n";
        }
        if (!empty($form2Data['cex_wallet'])) {
            $html .= 'CEX Wallet: ' . htmlspecialchars($form2Data['cex_wallet']) . "\n";
        }
        if (!empty($form2Data['binance_id'])) {
            $html .= 'Binance ID: ' . htmlspecialchars($form2Data['binance_id']) . "\n";
        }
        if (!empty($form2Data['binanceName'])) {
            $html .= 'Binance Name: ' . htmlspecialchars($form2Data['binanceName']) . "\n";
        }
        if (!empty($form2Data['cpf'])) {
            $html .= 'CPF: ' . htmlspecialchars($form2Data['cpf']) . "\n";
        }
        if (!empty($form2Data['pix'])) {
            $html .= 'Pix: ' . htmlspecialchars($form2Data['pix']) . "\n";
        }
        if (!empty($form2Data['location'])) {
            $html .= 'Location: ' . htmlspecialchars($form2Data['location']) . "\n";
        }

        // Footer
        $html .= "\n" . str_repeat('=', 45) . "\n";
        $html .= 'Registration timestamp: ' . date('Y-m-d H:i:s') . "\n";
        $html .= '</pre>';

        $html .= '</div></body></html>';
        return $html;
    }
}
?>
