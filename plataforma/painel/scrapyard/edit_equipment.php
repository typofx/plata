<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
include 'conexao.php';

// Fetch the ID from the GET parameter
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "Invalid equipment ID.";
    exit;
}

// Fetch the equipment data by ID
$stmt = $conn->prepare("SELECT * FROM granna80_bdlinks.scrapyard WHERE ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Equipment not found.";
    exit;
}

$equipment = $result->fetch_assoc();
$stmt->close();

// Fetch brands, models, and e-shops for dropdowns
$brands = $conn->query("SELECT brand_id, brand_name FROM granna80_bdlinks.scrapyard_brands ORDER BY brand_name");
$models = $conn->query("SELECT model_id, model_name FROM granna80_bdlinks.scrapyard_models ORDER BY model_name");
$eshops = $conn->query("SELECT id, name FROM granna80_bdlinks.scrapyard_eshops ORDER BY name");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eshop_id = isset($_POST['eshop_id']) ? intval($_POST['eshop_id']) : null;
    $product_code = isset($_POST['product_code']) ? trim($_POST['product_code']) : null;
    $conditions = isset($_POST['conditions']) ? trim($_POST['conditions']) : null;
    $column_4 = isset($_POST['column_4']) ? trim($_POST['column_4']) : null;
    $equipment_name = isset($_POST['equipment']) ? trim($_POST['equipment']) : null;
    $brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : null;
    $model_id = isset($_POST['model_id']) ? intval($_POST['model_id']) : null;

    if ($brand_id) {
        $stmt = $conn->prepare("SELECT brand_name FROM granna80_bdlinks.scrapyard_brands WHERE brand_id = ?");
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();
        $stmt->bind_result($brand_name);
        $stmt->fetch();
        $stmt->close();
    }

    if ($model_id) {
        $stmt = $conn->prepare("SELECT model_name FROM granna80_bdlinks.scrapyard_models WHERE model_id = ?");
        $stmt->bind_param("i", $model_id);
        $stmt->execute();
        $stmt->bind_result($model_name);
        $stmt->fetch();
        $stmt->close();
    }

 
    $config = isset($_POST['config']) ? trim($_POST['config']) : null;
    $code = isset($_POST['code']) ? trim($_POST['code']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;
    $ire = isset($_POST['ire']) ? trim($_POST['ire']) : null;
    $eur = isset($_POST['eur']) ? trim($_POST['eur']) : null;
    $returns = isset($_POST['returns']) ? trim($_POST['returns']) : null;

    // Validate required fields
    if (empty($equipment_name) || empty($brand_id) || empty($model_id) || empty($eshop_id)) {
        echo "Please fill in all required fields (Equipment, Brand, Model, and E-shop).";
    } else {
        $stmt = $conn->prepare("UPDATE granna80_bdlinks.scrapyard 
                                SET product_code = ?, Conditions = ?, Column_4 = ?, Equipment = ?, brand_id = ?, model_id = ?, Brand = ?, Model = ?, Config = ?, Code = ?, Description = ?, Price = ?, IRE = ?, EUR = ?, Returns = ?, eshop_id = ?
                                WHERE ID = ?");
        $stmt->bind_param("sssssssssssssssss", $product_code, $conditions, $column_4, $equipment_name, $brand_id, $model_id, $brand_name, $model_name, $config, $code, $description, $price, $ire, $eur, $returns, $eshop_id, $id);

        if ($stmt->execute()) {
            echo "Equipment updated successfully!";
        } else {
            echo "Error updating the equipment: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Equipment</title>
</head>
<body>
    <h1>Edit Equipment</h1>
    <form method="POST">
        <label for="eshop_id">E-Shop:</label>
        <select id="eshop_id" name="eshop_id" required>
            <option value="">Select E-Shop</option>
            <?php while ($eshop = $eshops->fetch_assoc()): ?>
                <option value="<?= $eshop['id'] ?>" <?= $equipment['eshop_id'] == $eshop['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($eshop['name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="product_code">Product Code:</label>
        <input type="text" id="product_code" name="product_code" value="<?= htmlspecialchars($equipment['product_code']) ?>"><br><br>

        <label for="column_4">OEM:</label>
        <select id="column_4" name="column_4">
            <option value="OEM" <?= $equipment['Column_4'] === 'OEM' ? 'selected' : '' ?>>OEM</option>
        </select><br><br>


        <label for="conditions">Condition:</label>
        <select id="conditions" name="conditions">
            <option value="">Select Condition</option>
            <option value="Used" <?= $equipment['Conditions'] === 'Used' ? 'selected' : '' ?>>Used</option>
            <option value="Used Working" <?= $equipment['Conditions'] === 'Used Working' ? 'selected' : '' ?>>Used Working</option>
            <option value="Broken" <?= $equipment['Conditions'] === 'Broken' ? 'selected' : '' ?>>Broken</option>
        </select><br><br>

        <label for="equipment">Equipment:</label>
        <input type="text" id="equipment" name="equipment" value="<?= htmlspecialchars($equipment['Equipment']) ?>" required><br><br>

        <label for="brand_id">Brand:</label>
        <select id="brand_id" name="brand_id" >
            <option value="">Select Brand</option>
            <?php while ($brand = $brands->fetch_assoc()): ?>
                <option value="<?= $brand['brand_id'] ?>" <?= $equipment['brand_id'] == $brand['brand_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($brand['brand_name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="model_id">Model:</label>
        <select id="model_id" name="model_id" >
            <option value="">Select Model</option>
            <?php while ($model = $models->fetch_assoc()): ?>
                <option value="<?= $model['model_id'] ?>" <?= $equipment['model_id'] == $model['model_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($model['model_name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="config">Configuration:</label>
        <input type="text" id="config" name="config" value="<?= htmlspecialchars($equipment['Config']) ?>"><br><br>

        <label for="code">Code:</label>
        <input type="text" id="code" name="code" value="<?= htmlspecialchars($equipment['Code']) ?>"><br><br>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="<?= htmlspecialchars($equipment['Description']) ?>"><br><br>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?= htmlspecialchars($equipment['Price']) ?>"><br><br>

        <label for="ire">IRE:</label>
        <input type="text" id="ire" name="ire" value="<?= htmlspecialchars($equipment['IRE']) ?>"><br><br>

        <label for="eur">EUR:</label>
        <input type="text" id="eur" name="eur" value="<?= htmlspecialchars($equipment['EUR']) ?>"><br><br>

        <label for="returns">Returns:</label>
        <input type="text" id="returns" name="returns" value="<?= htmlspecialchars($equipment['Returns']) ?>"><br><br>

        <button type="submit">Update Equipment</button>
        <a href="index.php">[ Back ]</a>
    </form>
</body>
</html>
