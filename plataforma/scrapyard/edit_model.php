<?php
include 'conexao.php';

$model_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($model_id <= 0) {
    echo "Invalid model ID.";
    exit;
}

// Fetch the model details
$stmt = $conn->prepare("SELECT model_name, brand_id FROM granna80_bdlinks.scrapyard_models WHERE model_id = ?");
$stmt->bind_param("i", $model_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Model not found.";
    exit;
}

$model = $result->fetch_assoc();
$stmt->close();

// Fetch the brands for the dropdown
$brands = $conn->query("SELECT brand_id, brand_name FROM granna80_bdlinks.scrapyard_brands ORDER BY brand_name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model_name = isset($_POST['model_name']) ? trim($_POST['model_name']) : '';
    $brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;

    if (empty($model_name)) {
        echo "Please enter the model name.";
    } elseif ($brand_id <= 0) {
        echo "Please select a valid brand.";
    } else {
        $update_stmt = $conn->prepare("UPDATE granna80_bdlinks.scrapyard_models SET model_name = ?, brand_id = ? WHERE model_id = ?");
        $update_stmt->bind_param("sss", $model_name, $brand_id, $model_id);

        if ($update_stmt->execute()) {
            echo "Model updated successfully!";
            echo "<script>window.location.href='register_models.php';</script>";
        } else {
            echo "Error updating the model: " . $update_stmt->error;
        }

        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Model</title>
</head>
<body>
    <h1>Edit Model</h1>
    <form method="POST">
        <label for="model_name">Model Name:</label>
        <input type="text" id="model_name" name="model_name" value="<?= htmlspecialchars($model['model_name']) ?>" required>
        
        <label for="brand_id">Brand:</label>
        <select id="brand_id" name="brand_id" required>
            <option value="">Select a Brand</option>
            <?php while ($brand = $brands->fetch_assoc()): ?>
                <option value="<?= $brand['brand_id'] ?>" <?= $brand['brand_id'] == $model['brand_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($brand['brand_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <button type="submit">Update</button>
        <a href="register_models.php">[ Back ]</a>
    </form>
</body>
</html>
