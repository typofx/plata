<?php
require_once __DIR__ . '/../Lib/GoogleAuthenticator-2.x/src/RuntimeException.php';
require_once __DIR__ . '/../Lib/GoogleAuthenticator-2.x/src/GoogleAuthenticatorInterface.php';
require_once __DIR__ . '/../Lib/GoogleAuthenticator-2.x/src/FixedBitNotation.php';
require_once __DIR__ . '/../Lib/GoogleAuthenticator-2.x/src/GoogleQrUrl.php';
require_once __DIR__ . '/../Lib/GoogleAuthenticator-2.x/src/GoogleAuthenticator.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

$g = new GoogleAuthenticator();
$qr_code = null;
$secret = null;
$test_result = null;
$test_message = null;

$users_file = __DIR__ . '/../config/users.json';
$users_data = file_exists($users_file) ? json_decode(file_get_contents($users_file), true) : ['users' => []];

if ($_SERVER["REQUEST_METHOD"] == "GET" || !isset($users_data['users']['default']['totp_secret'])) {
    $secret = $g->generateSecret();
    $users_data['users']['default']['totp_secret'] = $secret;
    $users_data['users']['default']['created_at'] = date('c');
    file_put_contents($users_file, json_encode($users_data, JSON_PRETTY_PRINT));
} else {
    $secret = $users_data['users']['default']['totp_secret'];
}

$qr_code = GoogleQrUrl::generate('typofx@teste', $secret, 'Typofx');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['test_token'])) {
    $test_token = trim($_POST['test_token']);
    
    if (empty($test_token)) {
        $test_message = "Enter the token";
        $test_result = false;
    } elseif (strlen($test_token) !== 6 || !ctype_digit($test_token)) {
        $test_message = "Token must contain 6 digits";
        $test_result = false;
    } else {
        if ($g->checkCode($secret, $test_token)) {
            $test_message = "Valid token!";
            $test_result = true;
        } else {
            $test_message = "Invalid token";
            $test_result = false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Authenticator Setup</title>
</head>
<body>
    <div>
        <h1>🔐 Google Authenticator Setup</h1>
        
        <h2>Step 1: Scan QR Code</h2>
        <p>Open Google Authenticator on your phone and scan this code:</p>
        <img src="<?php echo htmlspecialchars($qr_code); ?>" alt="QR Code">
        
        <h2>Step 2: Manual Code (if scan fails)</h2>
        <p>Enter this code manually in Google Authenticator:</p>
        <code><?php echo htmlspecialchars($secret); ?></code>
        
        <h2>Step 3: Test Token</h2>
        <p>Enter the 6-digit code from Google Authenticator to verify setup:</p>
        <form method="POST">
            <input type="text" name="test_token" placeholder="000000" maxlength="6" pattern="[0-9]{6}" required autofocus>
            <button type="submit">Validate Token</button>
        </form>
        
        <?php if ($test_result !== null): ?>
            <div>
                <?php echo htmlspecialchars($test_message); ?>
            </div>
        <?php endif; ?>
        
        <button onclick="window.location.href='../views/login.php'">Go to Login</button>
    </div>
</body>
</html>
