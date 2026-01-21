<?php
require_once '../SessionManager.php';
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // reCAPTCHA validation
    $recaptcha_token = $_POST['g-recaptcha-response'] ?? '';
    
    // Localhost detection: bypass reCAPTCHA validation for testing
    $is_localhost = in_array($_SERVER['REMOTE_ADDR'], [
        '127.0.0.1',
        'localhost',
        '::1'
    ]);
    
    // Production: validate with Google reCAPTCHA API
    if (!$is_localhost) {
        // Validate with Google reCAPTCHA API
        $recaptcha_secret = defined('RECAPTCHA_SECRET_KEY') ? RECAPTCHA_SECRET_KEY : '';
        
        if (empty($recaptcha_secret)) {
            header('Location: ../views/login.php?error=recaptcha_config');
            exit();
        }
        
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_data = [
            'secret' => $recaptcha_secret,
            'response' => $recaptcha_token
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $recaptcha_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($recaptcha_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response === false) {
            header('Location: ../views/login.php?error=recaptcha_error');
            exit();
        }
        
        $result = json_decode($response, true);
        
        if (!$result['success'] || ($result['score'] ?? 1) < 0.5) {
            header('Location: ../views/login.php?error=recaptcha_failed');
            exit();
        }
    }

    // Extract TOTP token from form
    $token = trim($_POST['token'] ?? '');
    
    if (empty($token)) {
        header('Location: ../login.php?error=missing_fields');
        exit();
    }

    // Validate TOTP token
    try {
        require_once __DIR__ . '/../Lib/GoogleAuthenticator-2.x/src/RuntimeException.php';
        require_once __DIR__ . '/../Lib/GoogleAuthenticator-2.x/src/GoogleAuthenticatorInterface.php';
        require_once __DIR__ . '/../Lib/GoogleAuthenticator-2.x/src/FixedBitNotation.php';
        require_once __DIR__ . '/../Lib/GoogleAuthenticator-2.x/src/GoogleQrUrl.php';
        require_once __DIR__ . '/../Lib/GoogleAuthenticator-2.x/src/GoogleAuthenticator.php';
        
        $users_file = __DIR__ . '/../config/users.json';
        $users_data = file_exists($users_file) ? json_decode(file_get_contents($users_file), true) : ['users' => []];
        $auth_secret = $users_data['users']['default']['totp_secret'] ?? "JBSWY3DPEBLW64TMMQ======";
        
        $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        
        if (!$g->checkCode($auth_secret, $token)) {
            header('Location: ../views/login.php?error=invalid_token');
            exit();
        }
    } catch (Exception $e) {
        header('Location: ../views/login.php?error=auth_error');
        exit();
    }

    // All validations passed: create session
    SessionManager::set('user_logged_in', true);
    SessionManager::set('user_id', 1);
    SessionManager::set('login_time', time());

    $cookie_expiry = time() + (12 * 60 * 60);
    setcookie('user_session_1', SessionManager::getId(), $cookie_expiry, '/');

    header('Location: ../views/forms/MemberForms1.php');
    exit();
}

// Non-POST request: redirect to login
header('Location: ../views/login.php');
exit();
?>