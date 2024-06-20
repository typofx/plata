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
    <title>Add Market Depth</title>
</head>

<body>
    <?php
    include 'conexao.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $value = $_POST['value'];

        $value2 = $_POST['value2'];
        $name = $_POST['name'];
        $url = $_POST['url'];

        // Insert the new record into the database
        $sql = "INSERT INTO granna80_bdlinks.order_book (value, value2, name, url) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // The type string and parameters must match
        $stmt->bind_param("ssss", $value, $value2, $name, $url);

        if ($stmt->execute()) {
            echo "Record added successfully";
            echo "<script>window.location.href = 'index.php';</script>";
        } else {
            echo "Error adding record: " . $stmt->error;
        }
    }
    ?>

    <h2>Add Market Depth</h2>
    <form method="POST" action="">
        <label for="value">Value:</label><br>
        <input type="text" id="value" name="value"><br>
   
        <label for="value2">Value2:</label><br>
        <input type="text" id="value2" name="value2"><br>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="url">JSON URL:</label><br>
        <input type="text" id="url" name="url"><br>
        <input type="submit" value="Add">
    </form>


</body>

</html>
