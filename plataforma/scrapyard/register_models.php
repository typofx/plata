<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model_name = isset($_POST['model_name']) ? trim($_POST['model_name']) : '';
    $brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;

    // Validate if the model name and brand ID were provided
    if (empty($model_name)) {
        echo "Please enter the model name.";
    } elseif ($brand_id <= 0) {
        echo "Please select a valid brand.";
    } else {
        $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.scrapyard_models (model_name, brand_id) VALUES (?, ?)");
        $stmt->bind_param("si", $model_name, $brand_id);

        if ($stmt->execute()) {
            echo "Model registered successfully!";
        } else {
            echo "Error registering the model: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch brands for the dropdown
$brands = $conn->query("SELECT brand_id, brand_name FROM granna80_bdlinks.scrapyard_brands ORDER BY brand_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Models</title>
</head>
<body>
    <h1>Register Model</h1>
    <form method="POST">
        <label for="model_name">Model Name:</label>
        <input type="text" id="model_name" name="model_name" required>
        
        <label for="brand_id">Brand:</label>
        <select id="brand_id" name="brand_id" required>
            <option value="">Select a Brand</option>
            <?php while ($brand = $brands->fetch_assoc()): ?>
                <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
            <?php endwhile; ?>
        </select>
        
        <button type="submit">Register</button>
        <a href="index.php">[ Back ]</a>
    </form>
</body>
</html>
