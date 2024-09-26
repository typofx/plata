<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
 include "conexao.php"; // Include database connection

 ob_start();
 include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php'; // Include price data
 ob_end_clean();

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get the name of the month in English
function getMonthName($date) {
    return date('F', strtotime($date)); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $date = $_POST['date']; // Date in the format YYYY-MM-DD
    $year = date('Y', strtotime($date));
    $month = getMonthName($date);
    $day = date('d', strtotime($date));
    $good_service = $_POST['good_service'];
    $company = $_POST['company'];
    $status = $_POST['status'];
    $cost_eur = $_POST['cost_eur'];

    
    // Insert the data into the table
    $sql = "INSERT INTO granna80_bdlinks.spends (year, month, day, good_service, company, status, cost_eur, pltusd, eurusdt, created_at, updated_at)
            VALUES ('$year', '$month', '$day', '$good_service', '$company', '$status', '$cost_eur', '$PLTUSD', '$EURUSD', NOW(), NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "New spend added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Spend</title>
</head>
<body>
    <h2>Add New Spend</h2>
    <form method="POST" action="">
        <label for="date">Date:</label><br>
        <input type="date" id="date" name="date" required><br><br>

        <label for="good_service">Good/Service:</label><br>
        <input type="text" id="good_service" name="good_service" required><br><br>

        <label for="company">Company:</label><br>
        <input type="text" id="company" name="company" required><br><br>

        <label for="status">Status:</label><br>
        <select id="status" name="status" required>
            <option value="Paid">Paid</option>
            <option value="Processing">Processing</option>
            <option value="Pending">Pending</option>
        </select><br><br>

        <label for="cost_eur">Cost (EUR):</label><br>
        <input type="number" step="0.01" id="cost_eur" name="cost_eur" required><br><br>

        <label for="pltusd">PLTUSD:</label><br>
        <?php echo $PLTUSD ?><br><br>

        <label for="eurusdt">EURUSD:</label><br>
        <?php echo $EURUSD ?><br><br>

        <input type="submit" value="Add Spend">
        <a href="index.php">[back]</a>
    </form>
</body>
</html>
