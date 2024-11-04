<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade</title>
</head>
<body>

    <h2>Trade</h2>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve values from POST data
        $euroValue = $_POST['euroValue'] ?? 0;
        $brlValue = $_POST['brlValue'] ?? 0;

        echo "<p>Amount in EUR: €" . htmlspecialchars($euroValue) . "</p>";
        echo "<p>Converted amount in BRL: R$ " . htmlspecialchars($brlValue) . "</p>";
    } else {
        echo "<p>No data received. Please return to the form and submit a value.</p>";
    }
    ?>

</body>
</html>
