<?php
/**
 * RegistrationService Class
 * 
 * Handles database operations for team member registration.
 * Inserts registration data into users and team tables with proper data hashing.
 */
class RegistrationService {
    /**
     * Database connection object
     * @var mysqli
     */
    private $conn;

    /**
     * Initialize service with database connection
     * 
     * @param mysqli $conn - Database connection object
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Register new team member in database
     * 
     * Creates new user account and team profile with all provided information.
     * Generates unique identifier (UUID) for tracking.
     * 
     * @param array $formData - Validated form data
     * @param array|null $fileUploadData - Uploaded profile picture information
     * @param array $socialMedia - Social media information
     * 
     * @return string - Generated UUID for the new registration
     * @throws Exception if database operations fail
     */
    public function register($formData, $fileUploadData, $socialMedia) {
        $uuid = bin2hex(random_bytes(16));

        $this->insertUserData($uuid, $formData);
        $this->insertTeamData($uuid, $formData, $socialMedia);

        return $uuid;
    }

    /**
     * Insert user account data into users table
     * 
     * Stores user credentials with BCRYPT hashed password.
     * Automatically generates username from first and last name.
     * 
     * @param string $uuid - Unique identifier for this user
     * @param array $formData - Form data containing email, password, name, last_name
     * 
     * @return void
     * @throws Exception if database operation fails
     */
    private function insertUserData($uuid, $formData) {
        $hashedPassword = password_hash($formData['password'], PASSWORD_BCRYPT);
        $username = strtolower(str_replace(' ', '.', $formData['name'] . '.' . $formData['last_name']));

        $sql = "INSERT INTO users (uuid, email, password, name, last_name, username) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("ssssss", $uuid, $formData['email'], $hashedPassword, $formData['name'], $formData['last_name'], $username);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();
    }

    /**
     * Insert team profile data into team table
     * 
     * Stores team member information including position and social media contacts.
     * Profile picture field is kept empty as images are sent via email attachment only.
     * 
     * @param string $uuid - Unique identifier (same as user UUID)
     * @param array $formData - Form data containing position and name information
     * @param array $socialMedia - Social media contact information
     * 
     * @return void
     * @throws Exception if database operation fails
     */
    private function insertTeamData($uuid, $formData, $socialMedia) {
        // Combine first and last name for full name
        $full_name = $formData['name'] . " " . $formData['last_name'];
        
        // Empty picture field - images are sent via email only, not stored on disk
        $emptyPicture = '';

        // Prepare SQL insert statement for team table with all social media fields
        $sql = "INSERT INTO team (uuid, teamProfilePicture, teamPosition, teamName, 
                teamSocialMedia0, teamSocialMedia1, teamSocialMedia2, teamSocialMedia3, 
                teamSocialMedia4, teamSocialMedia5, teamSocialMedia6, teamSocialMedia7, 
                teamSocialMedia8, teamSocialMedia9) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        // Bind all parameters (14 strings: uuid, picture, position, name, and 10 social media fields)
        $stmt->bind_param("ssssssssssssss",
            $uuid,
            $emptyPicture,
            $formData['position'],
            $full_name,
            $socialMedia['whatsapp'],
            $socialMedia['instagram'],
            $socialMedia['telegram'],
            $socialMedia['facebook'],
            $socialMedia['github'],
            $socialMedia['social_email'],
            $socialMedia['twitter'],
            $socialMedia['linkedin'],
            $socialMedia['twitch'],
            $socialMedia['medium']
        );

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();
    }
}
?>
