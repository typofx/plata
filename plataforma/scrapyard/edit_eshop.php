<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Check if the ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("E-shop ID is required.");
}

$id = intval($_GET['id']);

// Fetch the current data for the e-shop
$stmt = $conn->prepare("SELECT name, link, logo FROM granna80_bdlinks.scrapyard_eshops WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("E-shop not found.");
}

$eshop = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $link = isset($_POST['link']) ? trim($_POST['link']) : '';
    $logoFileName = $eshop['logo']; // Default to current logo

    // Validate input
    if (empty($name)) {
        echo "Please enter the e-shop name.";
    } elseif (empty($link)) {
        echo "Please enter the e-shop link.";
    } else {
        // Handle logo upload if provided
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/png', 'image/jpeg'];
            $maxSize = 10 * 1024 * 1024; // 10 MB
            $fileType = $_FILES['logo']['type'];
            $fileSize = $_FILES['logo']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                echo "Invalid file type. Only PNG and JPEG are allowed.";
            } elseif ($fileSize > $maxSize) {
                echo "File size exceeds the maximum limit of 10MB.";
            } else {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-scrapyard/';
                $logoFileName = basename($_FILES['logo']['name']);
                $targetFile = $uploadDir . $logoFileName;

                if (!move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                    die("Failed to upload the new logo.");
                }
            }
        }

        // Update the e-shop
        $stmt = $conn->prepare("UPDATE granna80_bdlinks.scrapyard_eshops SET name = ?, link = ?, logo = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $link, $logoFileName, $id);

        if ($stmt->execute()) {
            echo "E-shop updated successfully!";
        } else {
            echo "Error updating the e-shop: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit E-shop</title>
</head>
<body>
    <h1>Edit E-shop</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">E-shop Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($eshop['name']) ?>" required>
        <br>
        <label for="link">Perma Link:</label>
        <input type="url" id="link" name="link" value="<?= htmlspecialchars($eshop['link']) ?>" required>
        <br>
        <label for="logo">Logo/Icon (PNG or JPEG, max 10MB):</label>
        <input type="file" id="logo" name="logo" accept="image/png, image/jpeg">
        <br>
        <img src="/images/uploads-scrapyard/<?= htmlspecialchars($eshop['logo']) ?>" alt="Current Logo" style="width:100px;">
        <br>
        <button type="submit">Update</button>
        <a href="register_eshop.php">[ Back ]</a>
    </form>
</body>
</html>
