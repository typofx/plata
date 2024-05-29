<?php 
session_start();
if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
} 

include 'conexao.php';

date_default_timezone_set('UTC'); 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT id, evm_wallet, vote_image, vote_number, time FROM granna80_bdlinks.votes WHERE id = ?";
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
    $evm_wallet = $_POST['evm_wallet'];
    $vote_image = $_POST['vote_image'];
    $vote_number = $_POST['vote_number'];

    // Update the record in the database
    $sql = "UPDATE granna80_bdlinks.votes SET evm_wallet=?, vote_image=?, vote_number=?, time=CURRENT_TIMESTAMP WHERE id=?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssii", $evm_wallet, $vote_image, $vote_number, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit CoinMarketCap Dexscan UpVote</title>
</head>
<body>
    <h1>Edit CoinMarketCap Dexscan UpVote</h1>
    
    <br>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="evm_wallet">EVM Wallet:</label><br>
        <input type="text" id="evm_wallet" name="evm_wallet" value="<?php echo $row['evm_wallet']; ?>"><br>
        <label for="vote_image">Vote Image:</label><br>
        <input type="text" id="vote_image" name="vote_image" value="<?php echo $row['vote_image']; ?>"><br>
        <label for="vote_number">Vote Number:</label><br>
        <input type="text" id="vote_number" name="vote_number" value="<?php echo $row['vote_number']; ?>"><br>
        <input type="submit" value="Update">
    </form>
    <a href='index.php'>Back to List</a>
</body>
</html>
