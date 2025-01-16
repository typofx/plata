<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Function to sanitize input data
function sanitizeInput($data)
{
    return htmlspecialchars(trim($data));
}


$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die('Invalid ID');
}


$equipment_query = $conn->query("SELECT * FROM granna80_bdlinks.scrapyard WHERE ID = $id");
$equipment = $equipment_query->fetch_assoc();

if (!$equipment) {
    die('Equipment not found');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conditions = sanitizeInput($_POST['conditions'] ?? null);
    $column_4 = sanitizeInput($_POST['column_4'] ?? null);
    $equipment_name = sanitizeInput($_POST['equipment'] ?? null);

    $brand_id = intval($_POST['brand_id'] ?? 0);
    $model_id = intval($_POST['model_id'] ?? 0);
    $config = sanitizeInput($_POST['config'] ?? null);
    $code = sanitizeInput($_POST['code'] ?? null);
    $description = sanitizeInput($_POST['description'] ?? null);
    $price = sanitizeInput($_POST['price'] ?? null);
    $ire = sanitizeInput($_POST['ire'] ?? null);
    $eur = sanitizeInput($_POST['eur'] ?? null);
    $returns = sanitizeInput($_POST['returns'] ?? null);


    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-scrapyard/equipaments/';
    $imagePaths = [];
    for ($i = 0; $i < 5; $i++) {
        if (!empty($_FILES['images']['tmp_name'][$i])) {
            $fileName = basename($_FILES['images']['name'][$i]);
            $fileSize = $_FILES['images']['size'][$i];
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validate file type and size
            if (in_array($fileType, ['png', 'jpg', 'jpeg']) && $fileSize <= 10 * 1024 * 1024) {
                $newFileName = uniqid() . '.' . $fileType;
                $filePath = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $filePath)) {
                    $imagePaths[] = $newFileName;
                } else {
                    $imagePaths[] = $equipment["image" . ($i + 1)] ?? null; // Keep existing file
                }
            } else {
                $imagePaths[] = $equipment["image" . ($i + 1)] ?? null; // Keep existing file
            }
        } else {
            $imagePaths[] = $equipment["image" . ($i + 1)] ?? null; // Keep existing file
        }
    }

    $eshop_ids = array_keys($_POST['eshops'] ?? []);
    $product_codes = $_POST['product_codes'] ?? [];
    $eshop_data = [];

    foreach ($eshop_ids as $eshop_id) {

        $eshop_product_code = sanitizeInput($product_codes[$eshop_id] ?? '');


        $eshop_data[] = "$eshop_id:$eshop_product_code";
    }

    $eshop_data_string = implode(',', $eshop_data);


    $brand_name = '';
    $model_name = '';

    if ($brand_id > 0) {
        $brand_result = $conn->query("SELECT brand_name FROM granna80_bdlinks.scrapyard_brands WHERE brand_id = $brand_id");
        $brand_name = $brand_result->fetch_assoc()['brand_name'] ?? '';
    }

    if ($model_id > 0) {
        $model_result = $conn->query("SELECT model_name FROM granna80_bdlinks.scrapyard_models WHERE model_id = $model_id");
        $model_name = $model_result->fetch_assoc()['model_name'] ?? '';
    }

    $query = "UPDATE granna80_bdlinks.scrapyard 
              SET Conditions = ?, Column_4 = ?, Equipment = ?, Brand = ?, Model = ?, Config = ?, Code = ?, Description = ?, Price = ?, IRE = ?, EUR = ?, Returns = ?, brand_id = ?, model_id = ?, eshop_data = ?, image1 = ?, image2 = ?, image3 = ?, image4 = ?, image5 = ?
              WHERE ID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param(
            "ssssssssssssssssssssi",
            $conditions,
            $column_4,
            $equipment_name,
            $brand_name,
            $model_name,
            $config,
            $code,
            $description,
            $price,
            $ire,
            $eur,
            $returns,
            $brand_id,
            $model_id,
            $eshop_data_string,
            $imagePaths[0],
            $imagePaths[1],
            $imagePaths[2],
            $imagePaths[3],
            $imagePaths[4],
            $id
        );

        if ($stmt->execute()) {
            echo "Equipment updated successfully!";
        } else {
            echo "Error updating the equipment: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the query: " . $conn->error;
    }
}


$brands = $conn->query("SELECT brand_id, brand_name FROM granna80_bdlinks.scrapyard_brands ORDER BY brand_name");
$models = $conn->query("SELECT model_id, model_name FROM granna80_bdlinks.scrapyard_models ORDER BY model_name");
$eshops = $conn->query("SELECT id, name FROM granna80_bdlinks.scrapyard_eshops ORDER BY name");
$equipments = $conn->query("SELECT id, name FROM granna80_bdlinks.scrapyard_equipment ORDER BY name");



$current_eshop_data = [];
if (!empty($equipment['eshop_data'])) {
    $eshop_entries = explode(',', $equipment['eshop_data']);
    foreach ($eshop_entries as $entry) {
        list($eshop_id, $product_code) = explode(':', $entry);
        $current_eshop_data[$eshop_id] = $product_code;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Equipment</title>
</head>

<body>
    <h1>Edit Equipment</h1>
    <form method="POST" enctype="multipart/form-data">

        <label>Upload Images (max 5 images, 10MB each):</label><br>
        <?php for ($i = 0; $i < 5; $i++): ?>
            <label for="image<?= $i ?>">Image <?= $i + 1 ?>:</label>
            <input type="file" name="images[]" id="image<?= $i ?>"><br>
            <p>
                Current Image:
                <a href="/images/uploads-scrapyard/equipaments/<?= !empty($equipment["image" . ($i + 1)]) ? $equipment["image" . ($i + 1)] : 'no-image-icon.png' ?>" target="_blank">
                    <img src="/images/uploads-scrapyard/equipaments/<?= !empty($equipment["image" . ($i + 1)]) ? $equipment["image" . ($i + 1)] : 'no-image-icon.png' ?>"
                        alt="Current Image"
                        style="width: 30px; height: 30px; object-fit: cover;">
                </a>
            </p>
        <?php endfor; ?>


        <br><br><label for="conditions">Condition:</label>
        <select id="conditions" name="conditions">
            <option value="Used" <?= $equipment['Conditions'] === 'Used' ? 'selected' : '' ?>>Used</option>
            <option value="Used Working" <?= $equipment['Conditions'] === 'Used Working' ? 'selected' : '' ?>>Used Working</option>
            <option value="Broken" <?= $equipment['Conditions'] === 'Broken' ? 'selected' : '' ?>>Broken</option>
        </select><br><br>

        <!-- OEM -->
        <label for="column_4">OEM:</label>
        <select id="column_4" name="column_4" required>
            <option value="" disabled <?= empty($equipment['Column_4']) ? 'selected' : '' ?>>-- Select --</option>
            <option value="yes" <?= $equipment['Column_4'] === 'yes' ? 'selected' : '' ?>>YES</option>

        </select>
        <br><br>



        <label for="equipment">Select Equipment:</label><br>
        <select name="equipment" id="equipment" required>
            <option value="">-- Select Equipment --</option>
            <?php while ($equip_item = $equipments->fetch_assoc()): ?>
                <!-- Comparando pelo nome do equipamento -->
                <option value="<?= $equip_item['id'] ?>" <?= $equip_item['id'] == $equipment['Equipment'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($equip_item['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>


        <br><br> <label for="brand_id">Brand:</label>
        <select id="brand_id" name="brand_id" required>
            <?php while ($brand = $brands->fetch_assoc()): ?>
                <option value="<?= $brand['brand_id'] ?>" <?= $brand['brand_id'] == $equipment['brand_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($brand['brand_name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>


        <label for="model_id">Model:</label>
        <select id="model_id" name="model_id" required>
            <?php while ($model = $models->fetch_assoc()): ?>
                <option value="<?= $model['model_id'] ?>" <?= $model['model_id'] == $equipment['model_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($model['model_name']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <!-- E-shops -->
        <label>Select E-shops:</label><br>
        <?php while ($eshop = $eshops->fetch_assoc()): ?>
            <input type="checkbox" name="eshops[<?= $eshop['id'] ?>]" <?= isset($current_eshop_data[$eshop['id']]) ? 'checked' : '' ?>>
            <?= htmlspecialchars($eshop['name']) ?>
            <label> - Product Code:</label>
            <input type="text" name="product_codes[<?= $eshop['id'] ?>]" value="<?= htmlspecialchars($current_eshop_data[$eshop['id']] ?? '') ?>" placeholder="Product Code"><br>
        <?php endwhile; ?>


        <br><label for="config">Configuration:</label>
        <input type="text" id="config" name="config" value="<?= htmlspecialchars($equipment['Config']) ?>"><br><br>


        <label for="code">Code:</label>
        <input type="text" id="code" name="code" value="<?= htmlspecialchars($equipment['Code']) ?>"><br><br>


        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="<?= htmlspecialchars($equipment['Description']) ?>"><br><br>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?= htmlspecialchars($equipment['Price']) ?>"><br><br>

        <!-- IRE -->
        <label for="ire">IRE:</label>
        <input type="text" id="ire" name="ire" value="<?= htmlspecialchars($equipment['IRE']) ?>"><br><br>

        <!-- EUR -->
        <label for="eur">EUR:</label>
        <input type="text" id="eur" name="eur" value="<?= htmlspecialchars($equipment['EUR']) ?>"><br><br>

        <!-- Returns -->
        <label for="returns">Returns:</label>
        <input type="text" id="returns" name="returns" value="<?= htmlspecialchars($equipment['Returns']) ?>"><br><br>


        <button type="submit">Update Equipment</button>
        <a href="index.php">[ Back ]</a>
    </form>
</body>

</html>