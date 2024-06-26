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
    <title>Edit Contract</title>
</head>
<body>
    <?php
    include 'conexao.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // SQL query to get data of the specific contract
        $sql = "SELECT contract, asset_a, asset_b, exchange FROM granna80_bdlinks.lp_contracts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

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
        $contract = $_POST['contract'];
        $asset_a = $_POST['asset_a'];
        $asset_b = $_POST['asset_b'];
        $exchange = $_POST['exchange'];

        // Update the record in the database
        $sql = "UPDATE granna80_bdlinks.lp_contracts SET contract=?, asset_a=?, asset_b=?, exchange=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssssi", $contract, $asset_a, $asset_b, $exchange, $id);

        if ($stmt->execute()) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    }
    ?>

    <h2>Edit Contract</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="contract">Contract:</label><br>
        <input type="text" id="contract" name="contract" value="<?php echo $row['contract']; ?>"><br>
        <label for="asset_a">Asset A:</label><br>
        <input type="text" id="asset_a" name="asset_a" value="<?php echo $row['asset_a']; ?>"><br>
        <label for="asset_b">Asset B:</label><br>
        <input type="text" id="asset_b" name="asset_b" value="<?php echo $row['asset_b']; ?>"><br><br>
        <label for="asset_b">Exchange:</label><br>
        <input type="text" id="exchange" name="exchange" value="<?php echo $row['exchange']; ?>"><br><br>
        <input type="submit" value="Update">
    </form>
    <a href='index.php'>Back to List</a>
</body>
</html>
