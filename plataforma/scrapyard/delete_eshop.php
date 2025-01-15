<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Check if the ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("E-shop ID is required.");
}

$id = intval($_GET['id']);

// Fetch the logo file to delete it from the server
$stmt = $conn->prepare("SELECT logo FROM granna80_bdlinks.scrapyard_eshops WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("E-shop not found.");
}

$eshop = $result->fetch_assoc();
$stmt->close();

// Delete the e-shop record
$stmt = $conn->prepare("DELETE FROM granna80_bdlinks.scrapyard_eshops WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Delete the logo file if it exists
    $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-scrapyard/' . $eshop['logo'];
    if (file_exists($logoPath)) {
        unlink($logoPath);
    }

    echo "E-shop deleted successfully!";
    echo "<script>window.location.href='register_eshop.php';</script>";
} else {
    echo "Error deleting the e-shop: " . $stmt->error;
}

$stmt->close();
?>
<a href="register_eshop.php">[ Back to List ]</a>
