<?php
include 'conexao.php';

$model_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($model_id <= 0) {
    echo "Invalid model ID.";
    exit;
}

$stmt = $conn->prepare("DELETE FROM granna80_bdlinks.scrapyard_models WHERE model_id = ?");
$stmt->bind_param("i", $model_id);

if ($stmt->execute()) {
    echo "Model deleted successfully!";
} else {
    echo "Error deleting the model: " . $stmt->error;
}

$stmt->close();

echo "<script>window.location.href='index.php';</script>";
exit;
?>
