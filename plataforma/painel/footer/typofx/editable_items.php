<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include "conexao.php";

$success_message = $error_message = "";

// Check if there is an existing record in the table
$sql = "SELECT * FROM granna80_bdlinks.typofx_footer_editable_items LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// If no record exists, insert a default one
if (!$row) {
    $sql = "INSERT INTO granna80_bdlinks.typofx_footer_editable_items (folder_path, background_color, terms_conditions_link, slogan, privacy_link, year, icon_background_color)
            VALUES ('', '#ffffff', '', '', '', '', '')";
    if ($conn->query($sql)) {
        $row = [
            'folder_path' => '',
            'background_color' => '#ffffff',
            'terms_conditions_link' => '',
            'slogan' => '',
            'privacy_link' => '',
            'year' => '',
            'icon_background_color' => ''
        ];
    } else {
        $error_message = "Error initializing default values.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $folder_path = $_POST['folder_path'];
    $background_color = $_POST['background_color'];
    $terms_conditions_link = $_POST['terms_conditions_link'];
    $slogan = $_POST['slogan'];
    $privacy_link = $_POST['privacy_link'];
    $year = $_POST['year'];
    $icon_background_color = $_POST['icon_background_color'];

    // Update the record in the database
    $sql = "UPDATE granna80_bdlinks.typofx_footer_editable_items 
            SET folder_path = ?, background_color = ?, terms_conditions_link = ?, slogan = ?, privacy_link = ?, year = ?, icon_background_color = ? 
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $folder_path, $background_color, $terms_conditions_link, $slogan, $privacy_link, $year, $icon_background_color);
    
    if ($stmt->execute()) {
        $success_message = "Settings updated successfully!";
        $row['folder_path'] = $folder_path;
        $row['background_color'] = $background_color;
        $row['terms_conditions_link'] = $terms_conditions_link;
        $row['slogan'] = $slogan;
        $row['privacy_link'] = $privacy_link;
        $row['year'] = $year;
        $row['icon_background_color'] = $icon_background_color;
    } else {
        $error_message = "Error updating settings.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Footer Settings</title>
    <style>
        .color-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .color-box {
            width: 40px;
            height: 40px;
            border: 1px solid #000;
        }
        .message {
            padding: 10px;
            margin-bottom: 10px;
        }
        .success {
            color: #155724;
        }
        .error {
            color: #721c24;
        }
    </style>
</head>
<body>

<h2>Edit Footer Settings</h2>

<!-- Display success or error messages -->
<?php if ($success_message): ?>
    <div class="message success"><?php echo $success_message; ?></div>
<?php endif; ?>
<?php if ($error_message): ?>
    <div class="message error"><?php echo $error_message; ?></div>
<?php endif; ?>

<form method="post">
    <label>Folder Path:</label><br>
    <input type="text" name="folder_path" value="<?php echo htmlspecialchars($row['folder_path']); ?>">
    <br><br>

    <label>Background Color:</label>
    <div class="color-container">
        <input type="color" id="colorPicker" name="background_color" value="<?php echo htmlspecialchars($row['background_color']); ?>">
        <input type="text" id="colorHex" name="background_color" value="<?php echo htmlspecialchars($row['background_color']); ?>" style="width: 80px;">
    </div>
    <br>

    <label>Terms & Conditions Link:</label><br>
    <input type="url" name="terms_conditions_link" value="<?php echo htmlspecialchars($row['terms_conditions_link']); ?>">
    <br><br>

    <label>Slogan:</label><br>
    <input type="text" name="slogan" value="<?php echo htmlspecialchars($row['slogan']); ?>">
    <br><br>

    <label>Privacy Link:</label><br>
    <input type="url" name="privacy_link" value="<?php echo htmlspecialchars($row['privacy_link']); ?>">
    <br><br>

    <label>Year:</label><br>
    <input type="text" name="year" value="<?php echo htmlspecialchars($row['year']); ?>">
    <br><br>

    <label>Icon Background Color:</label>
    <div class="color-container">
        <input type="color" id="iconColorPicker" name="icon_background_color" value="<?php echo htmlspecialchars($row['icon_background_color']); ?>">
        <input type="text" id="iconColorHex" name="icon_background_color" value="<?php echo htmlspecialchars($row['icon_background_color']); ?>" style="width: 80px;">
    </div>
    <br>

    <input type="submit" value="Save Changes">
    <a href="index.php">[Back]</a>
</form>

<script>
    function syncColorInput(colorPickerId, colorHexId) {
        const colorPicker = document.getElementById(colorPickerId);
        const colorHex = document.getElementById(colorHexId);

        colorPicker.addEventListener("input", function() {
            colorHex.value = this.value;
        });

        colorHex.addEventListener("input", function() {
            if(/^#([0-9A-F]{3}){1,2}$/i.test(this.value)) {
                colorPicker.value = this.value;
            }
        });
    }

    syncColorInput("colorPicker", "colorHex");
    syncColorInput("iconColorPicker", "iconColorHex");
</script>

</body>
</html>
