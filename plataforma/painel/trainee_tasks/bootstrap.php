<?php
/**
 * Bootstrap — resolves external platform paths.
 * Edit ONLY this file when deploying to a different server structure.
 */
$docRoot = $_SERVER['DOCUMENT_ROOT'];

// --- is_logged.php (auth) ---
$auth_paths = [
    $docRoot . '/plataforma/panel/is_logged.php',
    $docRoot . '/.scr/is_logged.php',
];
$auth_file = null;
foreach ($auth_paths as $p) {
    if (file_exists($p)) { $auth_file = $p; break; }
}
if (!$auth_file) die('Error: is_logged.php not found. Edit bootstrap.php.');

// --- conexao.php (database) ---
$db_paths = [
    $docRoot . '/.scr/conexao.php',
    __DIR__ . '/conexao.php',
];
$db_file = null;
foreach ($db_paths as $p) {
    if (file_exists($p)) { $db_file = $p; break; }
}
if (!$db_file) die('Error: conexao.php not found. Edit bootstrap.php.');

define('AUTH_FILE', $auth_file);
define('DB_FILE', $db_file);
