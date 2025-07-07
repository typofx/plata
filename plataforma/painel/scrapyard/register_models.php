<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php
include 'conexao.php';

$return_path = 'index.php'; 


$from = $_GET['from'] ?? null;

if ($from === 'add_new') {

    $return_path = 'add_new_equipment.php';
} elseif ($from === 'edit') {
  
    $return_id = intval($_GET['return_id'] ?? 0);
    if ($return_id > 0) {
        $return_path = "edit_equipment.php?id=$return_id";
    }
}

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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
      <a href="<?= htmlspecialchars($return_path) ?>">[ Back ]</a>
    </form>

    <h2>Manage Models</h2>
    <table id="modelsTable" class="display">
        <thead>
            <tr>
                <th>Model ID</th>
                <th>Model Name</th>
                <th>Brand Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $models = $conn->query("
                SELECT 
                    m.model_id, 
                    m.model_name, 
                    b.brand_name 
                FROM 
                    granna80_bdlinks.scrapyard_models m
                JOIN 
                    granna80_bdlinks.scrapyard_brands b 
                ON 
                    m.brand_id = b.brand_id
            ");

            $cont = 1;
            while ($model = $models->fetch_assoc()):
            ?>
                <tr>
                    <td><?= $cont ?></td>
                    <td><?= htmlspecialchars($model['model_name']) ?></td>
                    <td><?= htmlspecialchars($model['brand_name']) ?></td>
                    <td>
                        <a href="edit_model.php?id=<?= $model['model_id'] ?>">Edit</a> |
                        <a href="delete_model.php?id=<?= $model['model_id'] ?>" onclick="return confirm('Are you sure you want to delete this model?')">Delete</a>
                    </td>
                </tr>
            <?php
                $cont++;

            endwhile; ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#modelsTable').DataTable();
        });
    </script>
</body>

</html>