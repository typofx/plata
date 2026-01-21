<?php
// Determine the base path dynamically
$basePath = dirname(dirname(__FILE__));
require_once $basePath . '/SessionManager.php';

// Destroy session
SessionManager::destroy();

// Redirect to login page
header('Location: ' . $basePath . '/views/login.php');
exit;
?>
