<?php
session_start(); // start session

// Terminate the session (logout)
session_unset(); // Clear all session variables
session_destroy(); // Destroy session

// Redirect back to the login page or another page of your choice
header("Location: index.php");
exit();
?>
