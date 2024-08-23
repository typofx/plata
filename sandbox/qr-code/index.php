<?php
// Initialize a variable for the QR Code URL
$qrCodeUrl = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the link from the form
    $link = filter_var($_POST['link'], FILTER_SANITIZE_URL);

    // Validate the link
    if (filter_var($link, FILTER_VALIDATE_URL)) {
        // Generate the QR Code URL using the QuickChart API
        $qrCodeUrl = "https://quickchart.io/qr?text=" . urlencode($link);
    } else {
        $error = "Please enter a valid link.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
</head>
<body>
    <h1>QR Code Generator</h1>
    
    <form method="post" action="">
        <label for="link">Enter a link:</label>
        <input type="text" id="link" name="link" required>
        <input type="submit" value="Generate QR Code">
    </form>
    
    <?php
    // Display error message if there is one
    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    
    // Display the QR Code if generated
    if ($qrCodeUrl) {
        echo '<h2>Your QR Code:</h2>';
        echo '<img src="' . $qrCodeUrl . '" alt="QR Code">';
    }
    ?>
</body>
</html>
