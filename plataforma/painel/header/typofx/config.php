<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_header'])) {
    $logoLink = mysqli_real_escape_string($conn, $_POST['logo_link']);
    $backgroundColor = mysqli_real_escape_string($conn, $_POST['background_color']);
    $logoImage = $_POST['existing_logo'] ?? ''; // Use existing logo if no new file is uploaded

    // Handle logo upload
    if (isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/';
        $fileName = basename($_FILES['logo_image']['name']);
        $uploadFilePath = $uploadDir . $fileName;

        // Check file type (PNG, SVG, JPEG)
        $allowedTypes = ['image/png', 'image/svg+xml', 'image/jpeg'];
        $fileType = mime_content_type($_FILES['logo_image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['logo_image']['tmp_name'], $uploadFilePath)) {
                $logoImage = '/images/' . $fileName;
            } else {
                echo "<p style='color: red;'>Error uploading logo file.</p>";
            }
        } else {
            echo "<p style='color: red;'>Invalid file type. Only PNG, SVG, and JPEG are allowed.</p>";
        }
    }

    // Check if the record exists
    $checkQuery = "SELECT id FROM granna80_bdlinks.typofx_header_config_items WHERE id = 1";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Update the existing record
        $updateQuery = "UPDATE granna80_bdlinks.typofx_header_config_items 
                    SET logo_image = '$logoImage', logo_link = '$logoLink', background_color = '$backgroundColor' 
                    WHERE id = 1";
    } else {
        // Insert a new record
        $updateQuery = "INSERT INTO granna80_bdlinks.typofx_header_config_items 
                    (logo_image, logo_link, background_color) 
                    VALUES ('$logoImage', '$logoLink', '$backgroundColor')";
    }

    if (mysqli_query($conn, $updateQuery)) {
        echo "<p style='color: green;'>Header configuration updated successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error updating record: " . mysqli_error($conn) . "</p>";
    }
}


// Fetch current header configuration
$query = "SELECT logo_image, logo_link, background_color FROM granna80_bdlinks.typofx_header_config_items WHERE id = 1";
$result = mysqli_query($conn, $query);
$headerConfig = mysqli_fetch_assoc($result);

// If no configuration exists, initialize with default values
if (!$headerConfig) {
    $headerConfig = [
        'logo_image' => '',
        'logo_link' => '',
        'background_color' => '#ffffff', // Default background color
    ];
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

        .color-picker {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .color-preview {
            width: 50px;
            height: 50px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .file-upload {
            margin-bottom: 20px;
        }

        .file-upload label {
            display: block;
            margin-bottom: 5px;
        }

        .file-upload input[type="file"] {
            display: block;
        }

        .logo-preview {
            max-width: 200px;
            margin-top: 10px;
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
            padding: 3px;
            box-sizing: border-box;
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

    <form action="" method="POST" enctype="multipart/form-data">
        <!-- Logo Upload -->
        <div class="file-upload">
            <label for="logo_image">Upload Logo (PNG, SVG, JPEG):</label>
            <input type="file" name="logo_image" id="logo_image" accept=".png, .svg, .jpg, .jpeg">
            <?php if ($headerConfig['logo_image']): ?>
                <p>Current Logo:</p>
                <img src="<?php echo $headerConfig['logo_image']; ?>" alt="Current Logo" class="logo-preview">
                <input type="hidden" name="existing_logo" value="<?php echo $headerConfig['logo_image']; ?>">
            <?php endif; ?>
        </div>

        <!-- Logo Link -->
        <div class="form-group">
            <label for="logo_link">Logo Link:</label>
            <input type="text" name="logo_link" id="logo_link" value="<?php echo htmlspecialchars($headerConfig['logo_link']); ?>">
        </div>

        <!-- Background Color Picker -->
        <div class="form-group">
            <label for="background_color">Background Color:</label>
            <div class="color-picker">
                <input type="color" id="color_picker" value="<?php echo htmlspecialchars($headerConfig['background_color']); ?>">
                <input type="text" name="background_color" id="background_color" value="<?php echo htmlspecialchars($headerConfig['background_color']); ?>">

            </div>
        </div>

        <!-- Save Button -->
        <button type="submit" name="update_header" class="save-btn">Save Changes</button>
        <a href="index.php">Back</a>
    </form>

    <script>
        // Synchronize color picker and HEX input
        const colorPicker = document.getElementById('color_picker');
        const backgroundColorInput = document.getElementById('background_color');
        const colorPreview = document.getElementById('color_preview');

        colorPicker.addEventListener('input', () => {
            backgroundColorInput.value = colorPicker.value;
            colorPreview.style.backgroundColor = colorPicker.value;
        });

        backgroundColorInput.addEventListener('input', () => {
            const hexValue = backgroundColorInput.value;
            if (/^#[0-9A-F]{6}$/i.test(hexValue)) {
                colorPicker.value = hexValue;
                colorPreview.style.backgroundColor = hexValue;
            }
        });
    </script>

</body>

</html>