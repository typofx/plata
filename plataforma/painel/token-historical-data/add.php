<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
// Database connection
include 'conexao.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $price = $_POST['price'];
    $volume = $_POST['volume'];
    $market_cap = $_POST['market_cap'];

    // Query to insert the new record
    $sql = "INSERT INTO granna80_bdlinks.token_historical_data (date, price, volume, market_cap) 
            VALUES ('$date', $price, $volume, $market_cap)";

    if (mysqli_query($conn, $sql)) {
        echo "New record added successfully!";
    } else {
        echo "Error adding the record: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Record</title>
</head>
<body>

<h2>Add New Record</h2>

<form method="POST" action="add.php">
    <label for="date">Date:</label><br>
    <input type="datetime-local" id="date" name="date" required><br><br>

    <label for="price">Price:</label><br>
    <input type="text" id="price" name="price" required><br><br>

    <label for="volume">Volume:</label><br>
    <input type="text" id="volume" name="volume" required><br><br>

    <label for="market_cap">Market Cap:</label><br>
    <input type="text" id="market_cap" name="market_cap" required><br><br>

    <input type="submit" value="Add">
</form>

<a href="index.php">Back</a> <!-- Return link to the main or listing page -->

</body>
</html>
