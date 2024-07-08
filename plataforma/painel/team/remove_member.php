<?php
session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

// Checks if remove_member parameter was passed in the URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['remove_member'])) {
    $memberIdToRemove = $_GET['remove_member'];
    
    // Loads the content of the current JSON file
    $json_file = 'team_members.json';
    $json_data = file_get_contents($json_file);

    // Replaces all escaped slashes (\) with regular slashes (/)
    $json_data = str_replace("\\/", "/", $json_data);

    // Decodes the JSON
    $members = json_decode($json_data, true);
    
    // Finds the index of the member in the JSON by ID
    $indexToRemove = array_search($memberIdToRemove, array_column($members, 'id'));

    if ($indexToRemove !== false) {
        // Removes the member from the JSON
        array_splice($members, $indexToRemove, 1);
        
        // Encodes the JSON again
        $json_data = json_encode($members, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        // Saves the JSON back into the file
        file_put_contents($json_file, $json_data);
        
        // Redirects back to the main page after removal
        header("Location: form.php");
        exit();
    } else {
        // If member was not found, redirect back to the main page
        header("Location: form.php");
        exit();
    }
} else {
    // If remove_member parameter was not passed correctly, redirect to the main page
    header("Location: form.php");
    exit();
}
?>
