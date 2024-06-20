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
    <title>Edit Market Depth</title>
</head>

<body>
    <?php
    include 'conexao.php';

    // Get the ID from the URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = intval($_GET['id']);
    } else {
        echo "Invalid ID";
        exit();
    }

    // SQL query to get data of the specific record
    $sql = "SELECT id, value, value2, name, url FROM granna80_bdlinks.order_book WHERE id = ?";
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
      
        $value2 = $_POST['value2'];
        $name = $_POST['name'];
        $url = $_POST['url'];

        // Update the record in the database
        $sql = "UPDATE granna80_bdlinks.order_book SET value=?, value2=?, name=?, url=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // The type string and parameters must match
        $stmt->bind_param("ssssi", $value, $value2, $name, $url, $id);

        if ($stmt->execute()) {
            echo "Record updated successfully";
            echo "<script>window.location.href = 'index.php';</script>";
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    }
    ?>

    <h2>Edit Market Depth</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="value">Value:</label><br>
        <input type="text" id="value" name="value" value="<?php echo $row['value']; ?>"><br>
        <label for="value2">Value2:</label><br>
        <input type="text" id="value2" name="value2" value="<?php echo $row['value2']; ?>"><br>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>"><br>
        <label for="url">JSON URL:</label><br>
        <input type="text" id="url" name="url" value="<?php echo $row['url']; ?>"><br>
        <input type="submit" value="Update">
    </form>

    <a href="https://plata.ie/sandbox/balance/cex-price.php?name=<?php echo $row['name']; ?>">view cex</a>

</body>

</html>
