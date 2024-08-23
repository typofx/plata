<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
// Include the database connection
include 'conexao.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the name
    $name = trim($_POST['name']);
    if (empty($name)) {
        $error = "Name is required.";
    }

    // Validate the description
    $description = trim($_POST['description']);
    if (empty($description)) {
        $error = "Description is required.";
    }

    // Validate the link
    $link = trim($_POST['link']);
    if (empty($link)) {
        $link = '';
    }

    // Check if there is any validation error
    if (!isset($error)) {
        // Check if a file was uploaded and is a valid image
        if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
            $fileType = $_FILES['icon']['type'];

            if (in_array($fileType, $allowedTypes)) {
                // Check the file size
                if ($_FILES['icon']['size'] <= 10485760) { // 10 MB
                    // Generate a unique name for the image
                    $iconName = uniqid('icon_', true) . '.' . pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
                    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/';

                    // Move the file to the upload directory
                    if (move_uploaded_file($_FILES['icon']['tmp_name'], $uploadDir . $iconName)) {
                        // Insert into the database
                        $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.payment_methods (name, icon, description, link, visibled, enabled) VALUES (?, ?, ?, ?, ?, ?)");
                        $visibled = 1;
                        $enabled = 1;
                        $stmt->bind_param('ssssii', $name, $iconName, $description, $link, $visibled, $enabled);

                        if ($stmt->execute()) {
                            $success = "Payment method added successfully!";
                        } else {
                            $error = "Error inserting into the database: " . $stmt->error;
                        }

                        $stmt->close();
                    } else {
                        $error = "Error moving the file.";
                    }
                } else {
                    $error = "The file must be at most 10 MB.";
                }
            } else {
                $error = "Only JPG, PNG, GIF, and SVG files are allowed.";
            }
        } else {
            $error = "Error uploading the icon.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment Method</title>
</head>
<body>
    <h1>Add Payment Method</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="add.php" method="post" enctype="multipart/form-data">
        <div>
            <label for="name">Method Name:</label><br>
            <input type="text" name="name" id="name" required><br>
        </div>
        <div>
            <label for="description">Description:</label><br>
            <textarea name="description" id="description" required></textarea><br>
        </div>
        <div>
            <label for="link">Link:</label><br>
            <input type="url" name="link" id="link"><br>
        </div>
        <div>
            <label for="icon">Icon (JPG, PNG, GIF, SVG | Max: 10 MB):</label><br>
            <input type="file" name="icon" id="icon" accept=".jpg,.jpeg,.png,.gif,.svg" required><br><br><br>
        </div>
        <br><a href="index.php">[back]</a>
        <button type="submit">Add Payment Method</button><br>
    </form>
</body>
</html>
