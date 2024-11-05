<?php
// Start session
session_start();

// Destroy all sessions
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session itself

// Redirect to the login or home page (adjust URL as needed)
echo "<script>window.location.href='login.php';</script>";
exit();
?>
