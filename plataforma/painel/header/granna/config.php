<?php
include 'conexao.php'; // Include your database connection file

// Fetch the current configuration
$query = "SELECT * FROM granna80_bdlinks.granna_header_config WHERE id = 1"; // Assuming there's only one configuration
$result = mysqli_query($conn, $query);
$config = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logoLink = mysqli_real_escape_string($conn, $_POST['logo_link']);
    $backgroundColor = mysqli_real_escape_string($conn, $_POST['background_color']);

    // Handle logo upload
    if (isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/';
        $fileName = basename($_FILES['logo_image']['name']);
        $uploadFilePath = $uploadDir . $fileName;

        // Check file type (e.g., SVG, PNG, JPEG)
        $allowedTypes = ['image/svg+xml', 'image/png', 'image/jpeg'];
        $fileType = mime_content_type($_FILES['logo_image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['logo_image']['tmp_name'], $uploadFilePath)) {
                $logoImage = '/images/' . $fileName;
            } else {
                echo "<p style='color: red;'>Error uploading logo file.</p>";
                $logoImage = null;
            }
        } else {
            echo "<p style='color: red;'>Invalid file type. Only SVG, PNG, and JPEG are allowed.</p>";
            $logoImage = null;
        }
    } else {
        // If no new file is uploaded, keep the existing logo
        $logoImage = $config['logo_image'];
    }

    // Update the configuration in the database
    if ($logoImage) {
        $updateQuery = "UPDATE granna80_bdlinks.granna_header_config 
                        SET logo_image = '$logoImage', logo_link = '$logoLink', background_color = '$backgroundColor' 
                        WHERE id = 1";
        mysqli_query($conn, $updateQuery);

        echo "<p style='color: green;'>Configuration updated successfully!</p>";
        echo "<script>window.location.href='config.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Header Configuration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"] {
            width: 100px;
            padding: 8px;
            box-sizing: border-box;
        }
        .color-picker-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .color-picker-group input[type="color"] {
            width: 50px;
            height: 50px;
            padding: 0;
            border: none;
        }
        .save-btn {
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .save-btn:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>

    <h2>Edit Header Configuration</h2>

    <!-- Form to update the logo, logo link, and background color -->
    <form action="" method="POST" enctype="multipart/form-data">
        <!-- Logo Upload -->
        <div class="form-group">
            <label for="logo_image">Logo Image (SVG, PNG, JPEG):</label>
            <input type="file" name="logo_image" id="logo_image">
            <?php if ($config['logo_image']): ?>
                <p>Current Logo: <img src="<?php echo $config['logo_image']; ?>" alt="Current Logo" style="max-width: 100px;"></p>
            <?php endif; ?>
        </div>

        <!-- Logo Link -->
        <div class="form-group">
            <label for="logo_link">Logo Link:</label>
            <input type="text" name="logo_link" id="logo_link" value="<?php echo htmlspecialchars($config['logo_link']); ?>" required>
        </div>

        <!-- Background Color -->
        <div class="form-group">
            <label for="background_color">Background Color:</label>
            <div class="color-picker-group">
                <input type="color" name="background_color" id="background_color" value="<?php echo htmlspecialchars($config['background_color']); ?>" required>
                <input type="text" name="background_color_hex" id="background_color_hex" value="<?php echo htmlspecialchars($config['background_color']); ?>" required>
            </div>
        </div>

        <!-- Save Button -->
        <button type="submit" name="update_config" class="save-btn">Save Changes</button>
    </form>

    <script>
        // Synchronize color picker and HEX input
        const colorPicker = document.getElementById('background_color');
        const hexInput = document.getElementById('background_color_hex');

        // Update HEX input when color picker changes
        colorPicker.addEventListener('input', () => {
            hexInput.value = colorPicker.value;
        });

        // Update color picker when HEX input changes
        hexInput.addEventListener('input', () => {
            const hexValue = hexInput.value;
            if (/^#[0-9A-F]{6}$/i.test(hexValue)) {
                colorPicker.value = hexValue;
            }
        });
    </script>

</body>
<a href="index.php">[Back]</a>
</html>