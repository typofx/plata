<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $id = $_POST['id'];
    $symbol = $_POST['symbol'];
    $name = $_POST['name'];
    $decimals = $_POST['decimals'];
    $balance = $_POST['balance'];
    $walletname = $_POST['walletname'];

    $sql = "UPDATE granna80_bdlinks.tokenomics SET symbol = ?, name = ?, decimals = ?, balance = ?, walletname = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $symbol, $name, $decimals, $balance, $walletname, $id);

    if ($stmt->execute()) {
        echo "Data updated successfully!";

        echo "<script>window.location.href = 'edit.php?id=$id';</script>";
    } else {
        echo "Error updating data: " . $conn->error;
    }

    // Close the connection
    $conn->close();
} else {
    // Retrieve the ID from the GET parameter
    $id = $_GET['id'];

    // Query to fetch data for the specific ID
    $sql = "SELECT * FROM granna80_bdlinks.tokenomics WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    // Close the connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Tokenomics</title>
</head>

<body>
    <h1>Edit Tokenomics</h1>
    <form action="edit.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">
        Symbol: <input type="text" name="symbol" value="<?php echo htmlspecialchars($data['symbol']); ?>"><br>
        Name: <input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>"><br>
        Decimals: <input type="text" name="decimals" value="<?php echo htmlspecialchars($data['decimals']); ?>"><br>
        Balance: <input type="text" name="balance" value="<?php echo htmlspecialchars($data['balance']); ?>"><br>
        Wallet Name: <input type="text" name="walletname" value="<?php echo htmlspecialchars($data['walletname']); ?>"><br>
        <a href="menu.php">Back</a>
        <input type="submit" value="Update">
    </form>
</body>

</html>
