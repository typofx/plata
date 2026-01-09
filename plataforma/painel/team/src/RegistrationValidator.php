<?php
/**
 * RegistrationValidator Class
 * 
 * Validates all team member registration form data.
 * Ensures required fields are present, emails are valid, passwords match,
 * and collects social media information.
 */
class RegistrationValidator {
    private $formData = [];
    private $socialMedia = [];

    /**
     * Validate all registration form data
     * 
     * Performs comprehensive validation:
     * - Checks HTTP method is POST
     * - Validates all required fields are filled
     * - Validates email formats (platform and recipient)
     * - Validates password matching and minimum length
     * - Collects social media information
     * 
     * @return void
     * @throws Exception if any validation fails
     */
    public function validate() {
        
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            throw new Exception("Invalid request method.");
        }

        // Validate and sanitize required form fields
        $this->formData = [
            'name' => trim($_POST['name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'position' => trim($_POST['position'] ?? ''),
            'recipient_email' => filter_var($_POST['recipient_email'] ?? '', FILTER_SANITIZE_EMAIL)
        ];

        if (empty($this->formData['name']) || empty($this->formData['last_name']) || 
            empty($this->formData['email']) || empty($this->formData['password']) || 
            empty($this->formData['position']) || empty($this->formData['recipient_email'])) {
            throw new Exception("Please fill in all required fields.");
        }

        // Validate platform email format
        if (!filter_var($this->formData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid platform email format.");
        }

        // Validate recipient email format
        if (!filter_var($this->formData['recipient_email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid recipient email format.");
        }

        // Validate passwords match and meet minimum length requirement
        if ($this->formData['password'] !== $this->formData['confirm_password']) {
            throw new Exception("Passwords do not match!");
        }

        if (strlen($this->formData['password']) < 6) {
            throw new Exception("Password must be at least 6 characters.");
        }

        // Collect social media information from all available fields
        $socialFields = ['whatsapp', 'instagram', 'telegram', 'facebook', 'github', 'social_email', 'twitter', 'linkedin', 'twitch', 'medium'];
        foreach ($socialFields as $field) {
            $this->socialMedia[$field] = trim($_POST[$field] ?? '');
        }
    }

    /**
     * Get validated form data
     * 
     * @return array - Validated form data (name, last_name, email, password, position, recipient_email)
     */
    public function getFormData() {
        return $this->formData;
    }

    /**
     * Get validated social media information
     * 
     * @return array - Social media fields (whatsapp, instagram, telegram, facebook, etc.)
     */
    public function getSocialMedia() {
        return $this->socialMedia;
    }
}
?>
