<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Check if the ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. Equipment ID is required.");
}

$id = intval($_GET['id']);

// Delete the equipment
$stmt = $conn->prepare("DELETE FROM granna80_bdlinks.scrapyard_equipment WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Equipment deleted successfully!";
    echo "<script>window.location.href='register_equipament.php';</script>";
    exit;
} else {
    echo "Error deleting equipment: " . $stmt->error;
}

$stmt->close();
?>
