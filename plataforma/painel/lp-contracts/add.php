<?php
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

include 'conexao.php'; // Assuming 'conexao.php' includes your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $contract = $_POST['contract'];
    $asset_a = $_POST['asset_a'];
    $tokenContract_A = $_POST["contract_asset_a"];
    $asset_b = $_POST['asset_b'];
    $tokenContract_B = $_POST["contract_asset_b"];
    $exchange = $_POST["exchange"];
  

    // Inserting new record into database
    $sql = "INSERT INTO granna80_bdlinks.lp_contracts ( contract, asset_a, asset_b,contract_asset_a, exchange) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param("sssss",  $contract, $asset_a, $asset_b, $tokenContract_A, $exchange);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error creating record: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contract</title>
</head>
<body>
    <h2>Add Contract</h2>
    <form method="POST" action="">
 
        <label for="contract">Contract:</label><br>
        <input type="text" id="contract" name="contract" size="42" required><br>
        <label for="asset_a">Asset A:</label><br>
        <input type="text" id="asset_a" name="asset_a" required><br>
        <label for="contract_asset_a">Contract Asset A:</label><br>
        <input type="text" id="contract_asset_a" name="contract_asset_a" required><br>
        <label for="asset_b">Asset B:</label><br>
        <input type="text" id="asset_b" name="asset_b" required><br>
        <label for="contract_asset_a">Contract Asset B:</label><br>
        <input type="text" id="contract_asset_b" name="contract_asset_b" required><br>
        <label for="exchange">Exchange:</label><br>
        <input type="text" id="exchange" name="exchange" ><br>
    
        <input type="submit" value="Add">
    </form>
    <a href='index.php'>Back to List</a>
</body>
</html>
