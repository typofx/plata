<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
// Include the database connection
include 'conexao.php';

// Initialize variables
$message = '';

// Fetch the item to edit
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM granna80_bdlinks.typofx_companies WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();
    } else {
        $message = "Error: Unable to fetch item.";
    }
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $device = $_POST['device'] ?? null;
    $name = $_POST['name'] ?? null;
    $platform_link = $_POST['platform_link'] ?? null;
    $country = $_POST['country'] ?? null;
    $last_update_by = $userEmail;

    // Handle file uploads (optional)
    $logo = uploadFile('logo', $item['logo'] ?? null);
    $full_logo = uploadFile('full_logo', $item['full_logo'] ?? null);

    // Update the item in the database
    $sql = "UPDATE granna80_bdlinks.typofx_companies SET logo = ?, full_logo = ?, device = ?, name = ?, platform_link = ?, country = ?, last_update_by = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssssssi", $logo, $full_logo, $device, $name, $platform_link, $country, $last_update_by, $id);
        if ($stmt->execute()) {
            $message = "Item updated successfully!";
            echo "<script>window.location.href='index.php';</script>";
        } else {
            $message = "Error: Unable to update item.";
        }
        $stmt->close();
    } else {
        $message = "Error: Unable to prepare the SQL statement.";
    }
}

// Function to handle file uploads (optional)
function uploadFile($inputName, $currentFile) {
    if (!isset($_FILES[$inputName])) {
        return $currentFile; // Keep the current file if no new file is uploaded
    }

    $file = $_FILES[$inputName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return $currentFile; // Keep the current file if there's an error
    }

    $targetDir = "/home2/granna80/public_html/images/typofx-uploads/";
    $allowedTypes = ['png', 'jpeg', 'jpg', 'svg', 'ico'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    $fileName = basename($file['name']);
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate file type and size
    if (!in_array($fileExt, $allowedTypes)) {
        return $currentFile; // Keep the current file if the type is invalid
    }
    if ($fileSize > $maxFileSize) {
        return $currentFile; // Keep the current file if the size is too large
    }

    // Generate a unique file name to avoid conflicts
    $uniqueFileName = uniqid() . "." . $fileExt;
    $targetFilePath = $targetDir . $uniqueFileName;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($fileTmp, $targetFilePath)) {
        // Delete the old file if it exists
        if ($currentFile && file_exists($targetDir . $currentFile)) {
            unlink($targetDir . $currentFile);
        }
        return $uniqueFileName; // Return the new file name
    } else {
        return $currentFile; // Keep the current file if the upload fails
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <style>
        .message {
            text-align: center;
            margin-bottom: 20px;
            color: #dc3545;
        }

    

   
    </style>
</head>
<body>

    <h1>Edit Item</h1>
    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">

        <label for="logo">Logo (PNG, JPEG, SVG, ICO):</label>
        <input type="file" id="logo" name="logo" accept=".png,.jpeg,.jpg,.svg,.ico">
        <?php if ($item['logo']): ?>
            <div class="image-preview" id="logo-preview">
                <img src="/images/typofx-uploads/<?php echo htmlspecialchars($item['logo']); ?>" alt="Logo Preview">
            </div>
        <?php endif; ?>
        <br><br>

        <label for="full_logo">Full Logo (PNG, JPEG, SVG, ICO):</label>
        <input type="file" id="full_logo" name="full_logo" accept=".png,.jpeg,.jpg,.svg,.ico">
        <?php if ($item['full_logo']): ?>
            <div class="image-preview" id="full-logo-preview">
                <img src="/images/typofx-uploads/<?php echo htmlspecialchars($item['full_logo']); ?>" alt="Full Logo Preview">
            </div>
        <?php endif; ?>
        <br><br>

        <label for="device">Device:</label>
        <select id="device" name="device">
            <option value="desktop" <?php echo $item['device'] == 'desktop' ? 'selected' : ''; ?>>Desktop</option>
            <option value="mobile" <?php echo $item['device'] == 'mobile' ? 'selected' : ''; ?>>Mobile</option>
        </select><br><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>"><br><br>

        <label for="platform_link">Platform Link:</label>
        <input type="text" id="platform_link" name="platform_link" value="<?php echo htmlspecialchars($item['platform_link']); ?>"><br><br>

        <label for="country">Country:</label>
        <select id="country" name="country">
            <?php
            $query = "SELECT country_code, country_name FROM granna80_bdlinks.all_country ORDER BY country_name ASC";
            $result = $conn->query($query);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $country_code = strtolower($row['country_code']);
                    $country_name = $row['country_name'];
                    $image_path = "/images/all_flags/{$country_code}.png";
                    $selected = $item['country'] == $country_code ? 'selected' : '';
                    echo "<option value='{$country_code}' data-image='{$image_path}' {$selected}>{$country_name}</option>";
                }
            }
            ?>
        </select><br><br>

        <input type="hidden" name="last_update_by" value="<?php echo $userEmail; ?>"><br><br>

        <button type="submit">Update Item</button>
        <a href="index.php">[Back]</a>
    </form>

    <script>
        // JavaScript to preview images
        document.getElementById('logo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('logo-preview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview">`;
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('full_logo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('full-logo-preview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Full Logo Preview">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</body>
</html>