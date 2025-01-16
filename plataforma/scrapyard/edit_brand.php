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

// Fetch brand details
$stmt = $conn->prepare("SELECT * FROM granna80_bdlinks.scrapyard_brands WHERE brand_id = ?");
$stmt->bind_param("i", $brandId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Brand not found.";
    exit;
}

$brand = $result->fetch_assoc();
$stmt->close();

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';
    $imagePath = $brand['brand_image']; // Retain the existing image by default

    if (empty($brand_name)) {
        echo "Please enter the brand name.";
    } else {
        // Check if a new image is uploaded
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
                // Delete the old image if a new one is uploaded
                if ($brand['brand_image'] && file_exists($uploadDir . $brand['brand_image'])) {
                    unlink($uploadDir . $brand['brand_image']);
                }
                $imagePath = $newFileName;
            } else {
                echo "Error uploading the file.";
                exit;
            }
        }

        // Update brand details in the database
        $stmt = $conn->prepare("UPDATE granna80_bdlinks.scrapyard_brands SET brand_name = ?, brand_image = ? WHERE brand_id = ?");
        $stmt->bind_param("ssi", $brand_name, $imagePath, $brandId);

        if ($stmt->execute()) {
            echo "Brand updated successfully!";
            echo "<script>window.location.href='register_brands.php';</script>";
        } else {
            echo "Error updating the brand: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Brand</title>
</head>
<body>
    <h1>Edit Brand</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="brand_name">Brand Name:</label>
        <input type="text" id="brand_name" name="brand_name" value="<?= htmlspecialchars($brand['brand_name']) ?>" required>
        <br><br>
        <label for="brand_image">Brand Image (PNG/JPEG, max 10 MB):</label>
        <input type="file" id="brand_image" name="brand_image" accept="image/png, image/jpeg">
        <br><br>
        <p>Current Image:</p>
        <?php if ($brand['brand_image']) : ?>
            <img src="/images/uploads-scrapyard/brands/<?= htmlspecialchars($brand['brand_image']) ?>" alt="Brand Image" width="100">
        <?php else : ?>
            <p>No image uploaded.</p>
        <?php endif; ?>
        <br><br>
        <button type="submit">Update</button>
        <a href="register_brands.php">[ Back ]</a>
    </form>
</body>
</html>
