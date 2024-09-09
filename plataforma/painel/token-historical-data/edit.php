<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
// Database connection
include 'conexao.php';

// Check if the ID was passed via URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Get the ID and convert it to an integer

    // Query to get the record data with the specific ID
    $sql = "SELECT * FROM granna80_bdlinks.token_historical_data WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    // Check if the record was found
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Record not found!";
        exit;
    }
} else {
    echo "ID not specified!";
    exit;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $price = $_POST['price'];
    $volume = $_POST['volume'];
    $market_cap = ($price * 11299000992); 

    // Query to update the record
    $sql = "UPDATE granna80_bdlinks.token_historical_data SET 
                date = '$date', 
                price = $price, 
                volume = $volume, 
                market_cap = $market_cap 
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully!";
        echo "<script>window.location.href='edit.php?id=" . $id . "';</script>";
    } else {
        echo "Error updating the record: " . mysqli_error($conn);
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
    <title>Edit Record</title>
</head>
<body>

<h2>Edit Record</h2>

<form method="POST" action="edit.php?id=<?= $id; ?>">
    <label for="date">Date:</label><br>
    <input type="datetime-local" id="date" name="date" value="<?= date('Y-m-d\TH:i', strtotime($row['date'])); ?>"><br><br>

    <label for="price">Price:</label><br>
    <input type="text" id="price" name="price" value="<?= number_format($row['price'], 10, '.', ''); ?>"><br><br>

    <label for="volume">Volume:</label><br>
    <input type="text" id="volume" name="volume" value="<?= number_format($row['volume'], 10, '.', ''); ?>"><br><br>

    <label for="market_cap">Market Cap:</label><br>
   <?= number_format($row['market_cap'], 10, '.', ''); ?><br><br>

    <input type="submit" value="Update">
</form>

<a href="index.php">Back</a> <!-- Return link to the main or listing page -->

</body>
</html>
