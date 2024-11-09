<?php
session_start();

// Check if the user is logged in and if the code email is set
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || !isset($_SESSION['code_email'])) {
    // If the user is not logged in or the code email is missing, redirect to the login page
    echo '<script>window.location.href = "https://www.granna.ie/register/login.php";</script>'; // Replace with the correct path to the login page
    exit();
}

// Protected page content for logged-in users
echo "Welcome to the dashboard! <br>";
echo "user email : <b>" . $_SESSION['code_email'] . "</b>";

include 'conexao.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Granna Panel</title>


    <style>
        /* Initially hide the additional links */
        .extra-links {
            display: none;
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <br>
    <br>
<a href="javascript:void(0);" onclick="toggleLinks()">[ Recipients ]</a>

<div class="extra-links" id="extraLinks">
        <ul>
            <a href="manage_accounts.php">[ Manage Added accounts ]</a><br>
            <a href="add_pix.php">[ Add PIX key ]</a><br>
            <a href="add_bank.php">[ Add Brazilian bank account ]</a>
        </ul>
    </div>
<br>
<a href="change_password.php">[ Change password ]</a><br>
--------<br>
<a href="logout.php">[ logout ]</a>

<script>
        // Function to toggle the visibility of the extra links
        function toggleLinks() {
            var extraLinks = document.getElementById('extraLinks');
            if (extraLinks.style.display === "none" || extraLinks.style.display === "") {
                extraLinks.style.display = "block"; // Show the links
            } else {
                extraLinks.style.display = "none"; // Hide the links
            }
        }
    </script>
</body>
</html>