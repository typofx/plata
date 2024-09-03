<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql = "SELECT * FROM  granna80_bdlinks.payout WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $employee = $row['employee'];
        $rate = $row['rate'];
        $pay_type = $row['pay_type'];
    } else {
        echo "Record not found!";
        exit();
    }
} else {
    echo "ID not specified!";
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee = $_POST['employee'];
    $rate = $_POST['rate'];
    $pay_type = $_POST['pay_type'];


    $sql = "UPDATE payout SET employee='$employee', rate='$rate', pay_type='$pay_type' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Registration updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payout</title>
</head>
<body>
    <h1>Edit Payout</h1>
    <form action="edit.php?id=<?php echo $id; ?>" method="post">
        <label for="employee">Employee:</label><br>
        <input type="text" id="employee" name="employee" value="<?php echo $employee; ?>" required><br><br>

        <label for="rate">Rate:</label><br>
        <input type="text" id="rate" name="rate" value="<?php echo $rate; ?>" required><br><br>

        <label for="pay_type">Pay Type:</label><br>
        <select id="pay_type" name="pay_type" required>
            <option value="Hourly" <?php if ($pay_type == 'Hourly') echo 'selected'; ?>>Hourly</option>
            <option value="Weekly" <?php if ($pay_type == 'Weekly') echo 'selected'; ?>>Weekly</option>
            <option value="Biweekly" <?php if ($pay_type == 'Biweekly') echo 'selected'; ?>>Biweekly</option>
        </select><br><br>

        <input type="submit" value="Update Payout">
    </form>
    <br>
    <a href="index.php">Back to Payout List</a>
</body>
</html>