<?php 
session_start();
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment</title>
</head>
<body>
    <?php
    include 'conexao.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date = date('Y-m-d H:i:s', strtotime($_POST['date'])); // Formatting to datetime format
        $bank = $_POST['bank'];
        $plata = $_POST['plata'];
        $amount = (float)$_POST['amount']; // Converting to float
        $asset = $_POST['asset'];
        $address = $_POST['address'];
        $txid = $_POST['txid'];
        $email = $_POST['email'];
        $status = $_POST['status'];

        // Inserting new record into database
        $sql = "INSERT INTO granna80_bdlinks.payments (date, bank, plata, amount, asset, address, txid, email, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // The type string and parameters must match
        $stmt->bind_param("sssssssss", $date, $bank, $plata, $amount, $asset, $address, $txid, $email, $status);

        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error creating record: " . $stmt->error;
        }
    }
    ?>

    <h2>Add Payment</h2>
    <form method="POST" action="">
        <label for="date">Date:</label><br>
        <input type="datetime-local" id="date" name="date" value=""><br>
        <label for="bank">Bank:</label><br>
        <input type="text" id="bank" name="bank" value=""><br>
        <label for="plata">Plata:</label><br>
        <input type="text" id="plata" name="plata" value=""><br>
        <label for="amount">Amount:</label><br>
        <input type="text" id="amount" name="amount" value=""><br>
        <label for="asset">Asset:</label><br>
        <input type="text" id="asset" name="asset" value=""><br>
        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address" value=""><br>
        <label for="txid">TxID:</label><br>
        <input type="text" id="txid" name="txid" value=""><br>
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" value=""><br>
        <label for="status">Status:</label><br>
        <select id="status" name="status">
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
        </select><br><br>
        <input type="submit" value="Add">
    </form>
</body>
</html>
