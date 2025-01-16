<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Directory for file uploads
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-scrapyard/brands/';

// Check if the brand ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Brand ID is required.";
    exit;
}

$brandId = intval($_GET['id']);

// Fetch brand details to delete the image
$stmt = $conn->prepare("SELECT brand_image FROM granna80_bdlinks.scrapyard_brands WHERE brand_id = ?");
$stmt->bind_param("i", $brandId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Brand not found.";
    exit;
}

$brand = $result->fetch_assoc();
$stmt->close();

// Delete the brand record
$stmt = $conn->prepare("DELETE FROM granna80_bdlinks.scrapyard_brands WHERE brand_id = ?");
$stmt->bind_param("i", $brandId);

if ($stmt->execute()) {
    // Delete the image file if it exists
    if ($brand['brand_image'] && file_exists($uploadDir . $brand['brand_image'])) {
        unlink($uploadDir . $brand['brand_image']);
    }
    echo "Brand deleted successfully!";
    echo "<script>window.location.href='register_brands.php';</script>";
} else {
    echo "Error deleting the brand: " . $stmt->error;
}

$stmt->close();
?>
