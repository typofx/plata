<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
include 'conexao.php';

// Directory for file uploads
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-scrapyard/brands/';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';

    // Validate brand name
    if (empty($brand_name)) {
        echo "Please enter the brand name.";
    } else {
        $imagePath = null; // Initialize the image path
        if (isset($_FILES['brand_image']) && $_FILES['brand_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['brand_image']['tmp_name'];
            $fileName = $_FILES['brand_image']['name'];
            $fileSize = $_FILES['brand_image']['size'];
            $fileType = $_FILES['brand_image']['type'];

            // Validate file type and size
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($fileType, $allowedTypes)) {
                echo "Only PNG and JPEG files are allowed.";
                exit;
            }
            if ($fileSize > 10 * 1024 * 1024) { // 10 MB
                echo "File size must not exceed 10 MB.";
                exit;
            }

            // Move the uploaded file
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

        // Insert brand and image path into the database
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
        <a href="index.php">[ Back ]</a>
        <a href="register_models.php">[ Register models ]</a>
    </form>
</body>
</html>
