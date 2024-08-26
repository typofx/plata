<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee = $_POST['employee'];
    $rate = $_POST['rate'];
    $pay_type = $_POST['pay_type'];


    $sql = "INSERT INTO  granna80_bdlinks.payout (employee, rate, pay_type) VALUES ('$employee', '$rate', '$pay_type')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
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
    <title>Add Payout</title>
</head>
<body>
    <h1>Add New Payout</h1>
    <form action="add.php" method="post">
        <label for="employee">Employee:</label><br>
        <input type="text" id="employee" name="employee" required><br><br>

        <label for="rate">Rate:</label><br>
        <input type="text" id="rate" name="rate" required><br><br>

        <label for="pay_type">Pay Type:</label><br>
        <select id="pay_type" name="pay_type" required>
            <option value="Hourly">Hourly</option>
            <option value="Weekly">Weekly</option>
            <option value="Biweekly">Biweekly</option>
        </select><br><br>

        <input type="submit" value="Add Payout">
    </form>
    <br>
    <a href="index.php">Back to Payout List</a>
</body>
</html>
