<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';

include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receive the form data
    $name = $_POST['name'];
    $ticker = $_POST['ticker'];
    $decimals = 2;
    $network = "fiduciary coin";
    // Insert data into the child table
    $sql = "INSERT INTO granna80_bdlinks.assets (name, ticker_symbol, decimal_value, network) 
            VALUES (?, ?, ?, ?)"; // Use prepared statements to prevent SQL injection.

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssis', $name, $ticker, $decimals, $network);

    // Execute the query and check for errors
    if ($stmt->execute()) {
        echo "New record inserted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Fiduciary Coin</title>
</head>

<body>

    <h1>Add Fiduciary Coin</h1>
    <form method="POST" action="add_fiduciary_coin.php">
        <label for="name">Coin Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="ticker">Symbol (Ticker):</label>
        <input type="text" id="ticker" name="ticker" required><br><br>



        <input type="submit" value="Add Coin">
    </form>

</body>

</html>