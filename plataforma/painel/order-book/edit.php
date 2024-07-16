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
    $sql = "SELECT id, value, value2, name, url, pair_contract, link_contract, claimed FROM granna80_bdlinks.order_book WHERE id = ?";
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
        $pair_contract = $_POST['pair_contract'];
        $link_contract = $_POST['link_contract'];
        $claimed = isset($_POST['use_claimed']) ? 1 : 0;

        // Update the record in the database
        $sql = "UPDATE granna80_bdlinks.order_book SET value=?, value2=?, name=?, url=?, pair_contract=?, link_contract=?, claimed=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // The type string and parameters must match
        $stmt->bind_param("ssssssii", $value, $value2, $name, $url, $pair_contract, $link_contract, $claimed, $id);

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
        <input type="checkbox" id="use_claimed" name="use_claimed" value="1" <?php echo $row['claimed'] == 1 ? 'checked' : ''; ?>>
        <label for="use_claimed">Use claimed values</label><br>
        <label for="value">Claimed ASK (PLT):</label><br>
        <input type="text" id="value" name="value" value="<?php echo $row['value']; ?>"><br>
        <label for="value2">Total BID (USDT):</label><br>
        <input type="text" id="value2" name="value2" value="<?php echo $row['value2']; ?>"><br>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>"><br>
        <label for="url">JSON URL:</label><br>
        <input type="text" id="url" name="url" value="<?php echo $row['url']; ?>"><br>
        <label for="pair_contract">Pair Contract:</label><br>
        <input type="text" id="pair_contract" name="pair_contract" value="<?php echo $row['pair_contract']; ?>"><br>
        <label for="link_contract">Link Contract:</label><br>
        <input type="text" id="link_contract" name="link_contract" value="<?php echo $row['link_contract']; ?>"><br>
        <input type="submit" value="Update">
    </form>

    <a href="https://plata.ie/sandbox/balance/cex-price.php?name=<?php echo $row['name']; ?>">view cex</a>

</body>

</html>
