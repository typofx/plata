<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $link = isset($_POST['link']) ? trim($_POST['link']) : '';

    // Validate if the name and link were provided
    if (empty($name)) {
        echo "Please enter the e-shop name.";
    } elseif (empty($link)) {
        echo "Please enter the e-shop link.";
    } elseif (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
        echo "Please upload a valid logo or icon.";
    } else {
        // Validate file type and size
        $allowedTypes = ['image/png', 'image/jpeg'];
        $maxSize = 10 * 1024 * 1024; // 10 MB
        $fileType = $_FILES['logo']['type'];
        $fileSize = $_FILES['logo']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            echo "Invalid file type. Only PNG and JPEG are allowed.";
        } elseif ($fileSize > $maxSize) {
            echo "File size exceeds the maximum limit of 10MB.";
        } else {
            // Move the uploaded file to the target directory
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-scrapyard/';
            $fileName = basename($_FILES['logo']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.scrapyard_eshops (name, logo, link) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $fileName, $link);

                if ($stmt->execute()) {
                    echo "E-shop registered successfully!";
                } else {
                    echo "Error registering the e-shop: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Failed to upload the file.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register E-shop</title>
</head>
<body>
    <h1>Register E-shop</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">E-shop Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="link">Perma Link:</label>
        <input type="url" id="link" name="link" required>
        <br>
        <label for="logo">Logo/Icon (PNG or JPEG, max 10MB):</label>
        <input type="file" id="logo" name="logo" accept="image/png, image/jpeg" required>
        <br>
        <button type="submit">Register</button>
        <a href="index.php">[ Back ]</a>
    </form>
</body>
</html>
