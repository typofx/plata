<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
// Include database connection
include 'conexao.php';

// Get ID from the URL
$id = $_GET['id'];

// Delete bank account record
$query = "DELETE FROM granna80_bdlinks.granna_bank_accounts WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
} else {
    echo "Error deleting record: " . $con->error;
}
?>
