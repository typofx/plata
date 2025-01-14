<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Function to sanitize input data
function sanitizeInput($data)
{
    return htmlspecialchars(trim($data));
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    print_r($_POST);
    // Retrieve and sanitize form data
    $conditions = sanitizeInput($_POST['conditions'] ?? null);
    $column_4 = sanitizeInput($_POST['column_4'] ?? null);
    $equipment = sanitizeInput($_POST['equipment'] ?? null);

    $equipmentId = sanitizeInput($_POST['equipment'] ?? null);


    if ($equipmentId) {
        $stmt = $conn->prepare("SELECT name FROM granna80_bdlinks.scrapyard_equipment WHERE id = ?");
        $stmt->bind_param("i", $equipmentId);
        $stmt->execute();
        $stmt->bind_result($equipmentName);
        $stmt->fetch();
        $stmt->close();
    }



    $brand_id = intval($_POST['brand_id'] ?? 0);
    $model_id = intval($_POST['model_id'] ?? 0);
    $config = sanitizeInput($_POST['config'] ?? null);
    $code = sanitizeInput($_POST['code'] ?? null);
    $description = sanitizeInput($_POST['description'] ?? null);
    $price = sanitizeInput($_POST['price'] ?? null);
    $ire = sanitizeInput($_POST['ire'] ?? null);
    $eur = sanitizeInput($_POST['eur'] ?? null);
    $returns = sanitizeInput($_POST['returns'] ?? null);

    $eshop_ids = $_POST['eshops'];
    $product_codes = $_POST['product_codes'] ?? []; 
    $eshop_data = [];
    
    foreach ($eshop_ids as $eshop_id) {

        $eshop_product_code = sanitizeInput($product_codes[$eshop_id] ?? '');
    
   
        $eshop_data[] = "$eshop_id:$eshop_product_code";
    }
    
    $eshop_data_string = implode(',', $eshop_data);
    

    echo $eshop_data_string;

    // Fetch brand and model names based on IDs
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

    // Insert data into scrapyard table
    $query = "INSERT INTO granna80_bdlinks.scrapyard 
              (Conditions, Column_4, Equipment, Brand, Model, Config, Code, Description, Price, IRE, EUR, Returns, brand_id, model_id, eshop_data) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param(
            "sssssssssssssss",
            $conditions,
            $column_4,
            $equipmentName,
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
            $eshop_data_string
        );

        if ($stmt->execute()) {
            echo "Equipment added successfully!";
        } else {
            echo "Error adding the equipment: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing the query: " . $conn->error;
    }
}

// Fetch brands for the dropdown
$brands = $conn->query("SELECT brand_id, brand_name FROM granna80_bdlinks.scrapyard_brands ORDER BY brand_name");

// Fetch models for the dropdown
$models = $conn->query("SELECT model_id, model_name FROM granna80_bdlinks.scrapyard_models ORDER BY model_name");

// Fetch e-shops for checkboxes
$eshops = $conn->query("SELECT id, name FROM granna80_bdlinks.scrapyard_eshops ORDER BY name");

$equipaments = $conn->query("SELECT id, name FROM granna80_bdlinks.scrapyard_equipment ORDER BY name");
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Equipment</title>
</head>

<body>
    <h1>Add New Equipment</h1>
    <form method="POST">


        <label for="conditions">Condition:</label>
        <select id="conditions" name="conditions">
            <option value="">Select Condition</option>
            <option value="Used">Used</option>
            <option value="Used Working">Used Working</option>
            <option value="Broken">Broken</option>
        </select><br><br>

        <label for="column_4">OEM:</label>
        <select id="column_4" name="column_4">
            <option value="OEM">OEM</option>
        </select><br><br>


        <label for="equipment">Select equipment:</label><br>
        <select id="equipment" name="equipment">
            <option value="">-- Select an equipment --</option>
            <?php while ($equipament = $equipaments->fetch_assoc()): ?>
                <option value="<?= $equipament['id'] ?>" data-name="<?= htmlspecialchars($equipament['name']) ?>">
                    <?= htmlspecialchars($equipament['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>



        <br><br><label for="brand_id">Brand:</label>
        <select id="brand_id" name="brand_id" required>
            <option value="">Select Brand</option>
            <?php while ($brand = $brands->fetch_assoc()): ?>
                <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="model_id">Model:</label>
        <select id="model_id" name="model_id" required>
            <option value="">Select Model</option>
            <?php while ($model = $models->fetch_assoc()): ?>
                <option value="<?= $model['model_id'] ?>"><?= htmlspecialchars($model['model_name']) ?></option>
            <?php endwhile; ?>
        </select><br><br>



        <label>Select E-shops:</label><br>
        <?php while ($eshop = $eshops->fetch_assoc()): ?>
            <input type="checkbox" name="eshops[]" value="<?= $eshop['id'] ?>">
            <?= htmlspecialchars($eshop['name']) ?>
            <input type="text" name="product_codes[<?= $eshop['id'] ?>]" placeholder="Product Code <?= $eshop['id'] ?>"><br>
        <?php endwhile; ?>




        <br><label for="config">Configuration:</label>
        <input type="text" id="config" name="config"><br><br>

        <label for="code">Code:</label>
        <input type="text" id="code" name="code"><br><br>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description"><br><br>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price"><br><br>

        <label for="ire">IRE:</label>
        <input type="text" id="ire" name="ire"><br><br>

        <label for="eur">EUR:</label>
        <input type="text" id="eur" name="eur"><br><br>

        <label for="returns">Returns:</label>
        <input type="text" id="returns" name="returns"><br><br>

        <button type="submit">Add Equipment</button>
        <a href="index.php">[ Back ]</a>
    </form>
</body>

</html>