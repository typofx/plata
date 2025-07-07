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

// Directory for file uploads
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-scrapyard/brands/';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';

    if (empty($brand_name)) {
        echo "Please enter the brand name.";
    } else {
        $imagePath = null;
        if (isset($_FILES['brand_image']) && $_FILES['brand_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['brand_image']['tmp_name'];
            $fileName = $_FILES['brand_image']['name'];
            $fileSize = $_FILES['brand_image']['size'];
            $fileType = $_FILES['brand_image']['type'];

            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($fileType, $allowedTypes)) {
                echo "Only PNG and JPEG files are allowed.";
                exit;
            }
            if ($fileSize > 10 * 1024 * 1024) {
                echo "File size must not exceed 10 MB.";
                exit;
            }

            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid() . '.' . $fileExtension;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                $imagePath = $newFileName;
            } else {
                echo "Error uploading the file.";
                exit;
            }
        }

        $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.scrapyard_brands (brand_name, brand_image) VALUES (?, ?)");
        $stmt->bind_param("ss", $brand_name, $imagePath);

        if ($stmt->execute()) {
            echo "Brand registered successfully!";
        } else {
            echo "Error registering the brand: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register Brands</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <h1>Register Brand</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="brand_name">Brand Name:</label>
        <input type="text" id="brand_name" name="brand_name" required>
        <br><br>
        <label for="brand_image">Brand Image (PNG/JPEG, max 10 MB):</label>
        <input type="file" id="brand_image" name="brand_image" accept="image/png, image/jpeg">
        <br><br>
        <button type="submit">Register</button>
     <a href="<?= htmlspecialchars($return_path) ?>">[ Back ]</a>
        <a href="register_models.php">[ Register models ]</a>
    </form>

    <h2>Registered Brands</h2>
    <table id="brandsTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Brand Name</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT brand_id, brand_name, brand_image FROM granna80_bdlinks.scrapyard_brands";
            $result = $conn->query($query);
            $cont = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $cont . "</td>";
                echo "<td>" . htmlspecialchars($row['brand_name']) . "</td>";
                echo "<td>";
                if ($row['brand_image']) {
                    echo "<img src='/images/uploads-scrapyard/brands/" . htmlspecialchars($row['brand_image']) . "' alt='Brand Image' width='50'>";
                } else {
                    echo "No image";
                }
                echo "</td>";
                echo "<td>";
                echo "<a href='edit_brand.php?id=" . htmlspecialchars($row['brand_id']) . "'>Edit</a> | ";
                echo "<a href='delete_brand.php?id=" . htmlspecialchars($row['brand_id']) . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                echo "</td>";
                echo "</tr>";
                $cont++;
            }
            
            ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#brandsTable').DataTable({
                pageLength: 100,
                lengthChange: true,
                responsive: true

            });

        });
    </script>
</body>

</html>