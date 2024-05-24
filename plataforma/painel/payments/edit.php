<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
</head>
<body>
    <?php
    include 'conexao.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // SQL query to get data of the specific payment
        $sql = "SELECT id, date, bank, plata, amount, asset, address, txid, email, status FROM granna80_bdlinks.payments WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "No record found";
            exit();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $date = $_POST['date'];
        $bank = $_POST['bank'];
        $plata = $_POST['plata'];
        $amount = (float)$_POST['amount']; // Converting to float
        $asset = $_POST['asset'];
        $address = $_POST['address'];
        $txid = $_POST['txid'];
        $email = $_POST['email'];
        $status = $_POST['status'];

        // Update the record in the database
        $sql = "UPDATE granna80_bdlinks.payments SET date=?, bank=?, plata=?, amount=?, asset=?, address=?, txid=?, email=?, status=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // The type string and parameters must match
        $stmt->bind_param("sssssssssi", $date, $bank, $plata, $amount, $asset, $address, $txid, $email, $status, $id);

        if ($stmt->execute()) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    }

    // Converting status to lowercase
    $currentStatus = strtolower($row['status']);
    ?>

    <h2>Edit Payment</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="date">Date:</label><br>
        <input type="text" id="date" name="date" value="<?php echo $row['date']; ?>"><br>
        <label for="bank">Bank:</label><br>
        <input type="text" id="bank" name="bank" value="<?php echo $row['bank']; ?>"><br>
        <label for="plata">Plata:</label><br>
        <input type="text" id="plata" name="plata" value="<?php echo $row['plata']; ?>"><br>
        <label for="amount">Amount:</label><br>
        <input type="text" id="amount" name="amount" value="<?php echo $row['amount']; ?>"><br>
        <label for="asset">Asset:</label><br>
        <input type="text" id="asset" name="asset" value="<?php echo $row['asset']; ?>"><br>
        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address" value="<?php echo $row['address']; ?>"><br>
        <label for="txid">TxID:</label><br>
        <input type="text" id="txid" name="txid" value="<?php echo $row['txid']; ?>"><br>
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" value="<?php echo $row['email']; ?>"><br>
        <label for="status">Status:</label><br>
        <select id="status" name="status">
            <option value="Pending" <?php if ($currentStatus == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="Completed" <?php if ($currentStatus == 'completed') echo 'selected'; ?>>Completed</option>
            <option value="Cancelled" <?php if ($currentStatus == 'cancelled') echo 'selected'; ?>>Cancelled</option>
        </select><br><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
