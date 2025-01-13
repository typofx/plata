<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php';

// Fetch the ID from the GET parameter
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "Invalid equipment ID.";
    exit;
}

// Handle the deletion
$stmt = $conn->prepare("DELETE FROM granna80_bdlinks.scrapyard WHERE ID = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script> window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Error deleting equipment: " . $stmt->error . "'); window.location.href='index.php';</script>";
}

$stmt->close();
?>
