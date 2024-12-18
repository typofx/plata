<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
?>

<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert data into granna_payments_trash
        $sqlInsert = "INSERT INTO granna80_bdlinks.granna_payments_trash (id, date, bank, plata, amount, asset, address, txid, email, status)
                      SELECT id, date, bank, plata, amount, asset, address, txid, email, status 
                      FROM granna80_bdlinks.granna_payments WHERE id = ?";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("i", $id);

        if (!$stmtInsert->execute()) {
            throw new Exception("Error inserting payment to trash: " . $stmtInsert->error);
        }

        // Update the "trash" column to false for the payment with the specified ID
        $sqlUpdate = "UPDATE granna80_bdlinks.granna_payments SET trash = 0 WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("i", $id);

        if (!$stmtUpdate->execute()) {
            throw new Exception("Error updating payment: " . $stmtUpdate->error);
        }

        // Commit the transaction
        $conn->commit();

        // Clear output buffer
        ob_clean();
        // Redirect back to the home page (index.php)
        echo "<script>window.location.href = 'index.php';</script>";
        exit(); // Ensure script doesn't continue after redirection

    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }

} else {
    echo "ID not specified";
}

$conn->close();
?>
