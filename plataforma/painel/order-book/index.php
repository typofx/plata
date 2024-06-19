<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

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
    <title>Edit Market Depth </title>
</head>

<body>
    <?php
    include 'conexao.php';

    
        $id = 1;

        // SQL query to get data of the specific payment
        $sql = "SELECT id, value, value1,  value2 FROM granna80_bdlinks.order_book WHERE id = ?";
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
    

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $value = $_POST['value'];
        $value1= $_POST['value1'];
        $value2 = $_POST['value2'];


        // Update the record in the database
        $sql = "UPDATE granna80_bdlinks.order_book SET value=?, value1=?, value2=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // The type string and parameters must match
        $stmt->bind_param("sssi", $value, $value1, $value2, $id);

        if ($stmt->execute()) {
            echo "Record updated successfully";

            echo "<script>window.location.href = 'index.php';</script>";
         
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    }

    // Converting status to lowercase
    $currentStatus = strtolower($row['status']);
    ?>

    <h2>Edit Market Depth </h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="date">Value:</label><br>
        <input type="text" id="date" name="value" value="<?php echo $row['value']; ?>"><br>
        <label for="bank">Value1:</label><br>
        <input type="text" id="bank" name="value1" value="<?php echo $row['value1']; ?>"><br>
        <label for="plata">Value2:</label><br>
        <input type="text" id="plata" name="value2" value="<?php echo $row['value2']; ?>"><br>
      
        <input type="submit" value="Update">
    </form>

    <a href="https://plata.ie/sandbox/balance/cex-price.php">view cex</a>
</body>

</html>
