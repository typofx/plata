<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Update visibility of fixed elements
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_visibility'])) {
    $languagesVisible = isset($_POST['languages_visible']) ? 1 : 0;
    $themesVisible = isset($_POST['themes_visible']) ? 1 : 0;

    // Save visibility in a table or configuration (example: `config` table)
    $updateQuery = "UPDATE granna80_bdlinks.config 
                    SET languages_visible = $languagesVisible, themes_visible = $themesVisible 
                    WHERE id = 1"; // Assuming there's only one record
    mysqli_query($conn, $updateQuery);

    echo "<p style='color: green;'>Visibility updated successfully!</p>";
}

// Add new custom option
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_custom_option'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $isHidden = isset($_POST['is_hidden']) ? 1 : 0;

    $insertQuery = "INSERT INTO granna80_bdlinks.granna_header_custom_config_options (name, url, is_hidden) 
                    VALUES ('$name', '$url', $isHidden)";
    mysqli_query($conn, $insertQuery);

    echo "<p style='color: green;'>Custom option added successfully!</p>";
}

// Edit custom option
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_custom_option'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $isHidden = isset($_POST['is_hidden']) ? 1 : 0;

    $updateQuery = "UPDATE granna80_bdlinks.granna_header_custom_config_options 
                    SET name = '$name', url = '$url', is_hidden = $isHidden 
                    WHERE id = $id";
    mysqli_query($conn, $updateQuery);

    echo "<p style='color: green;'>Custom option updated successfully!</p>";
}

// Delete custom option
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_custom_option'])) {
    $id = intval($_POST['id']);
    $deleteQuery = "DELETE FROM granna80_bdlinks.granna_header_custom_config_options WHERE id = $id";
    mysqli_query($conn, $deleteQuery);

    echo "<p style='color: red;'>Custom option deleted successfully!</p>";
}

// Get visibility of fixed elements
$queryVisibility = "SELECT languages_visible, themes_visible FROM granna80_bdlinks.config WHERE id = 1";
$resultVisibility = mysqli_query($conn, $queryVisibility);
$visibility = mysqli_fetch_assoc($resultVisibility);

// Get custom options
$queryCustomOptions = "SELECT id, name, url, is_hidden FROM granna80_bdlinks.granna_header_custom_config_options";
$resultCustomOptions = mysqli_query($conn, $queryCustomOptions);
$customOptions = [];

while ($row = mysqli_fetch_assoc($resultCustomOptions)) {
    $customOptions[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Configuration Options</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        button {
            padding: 5px 10px;
            margin-top: 5px;
            cursor: pointer;
            border: none;
            color: white;
        }

        .save-btn {
            background-color: green;
        }

        .delete-btn {
            background-color: red;
        }

        .edit-btn {
            background-color: blue;
        }
    </style>
</head>

<body>

    <h2>Edit Configuration Options</h2>

    <!-- Visibility of Fixed Elements -->

    <form action="" method="POST">
        <label for="languages_visible">
            <input type="checkbox" name="languages_visible" id="languages_visible" value="1" <?php echo $visibility['languages_visible'] ? 'checked' : ''; ?>>
            Show Languages
        </label>
        <br>
        <label for="themes_visible">
            <input type="checkbox" name="themes_visible" id="themes_visible" value="1" <?php echo $visibility['themes_visible'] ? 'checked' : ''; ?>>
            Show Themes
        </label>
        <br>
        <button type="submit" name="update_visibility" class="save-btn">Save Visibility</button>
    </form>

    <!-- Custom Options -->
    <h3>Custom Options</h3>
    <form action="" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <label for="url">URL:</label>
        <input type="text" name="url" id="url" required>
        <label for="is_hidden">
            <input type="checkbox" name="is_hidden" id="is_hidden" value="1">
            Hidden
        </label>
        <button type="submit" name="add_custom_option" class="save-btn">Add Option</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>URL</th>
                <th>Visible?</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customOptions as $option): ?>
                <tr>
                    <td><?php echo htmlspecialchars($option['name']); ?></td>
                    <td><?php echo htmlspecialchars($option['url']); ?></td>
                    <td><?php echo $option['is_hidden'] ? 'No' : 'Yes'; ?></td>
                    <td>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $option['id']; ?>">
                            <button type="submit" name="delete_custom_option" class="delete-btn">Delete</button>
                        </form>
                        <button onclick="openEditModal(<?php echo $option['id']; ?>, '<?php echo htmlspecialchars($option['name']); ?>', '<?php echo htmlspecialchars($option['url']); ?>', <?php echo $option['is_hidden']; ?>)" class="edit-btn">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Edit Modal -->
    <div id="editModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border:1px solid #ccc; z-index:1000;">
        <h3>Edit Custom Option</h3>
        <form action="" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <label for="edit_name">Name:</label>
            <input type="text" name="name" id="edit_name" required>
            <label for="edit_url">URL:</label>
            <input type="text" name="url" id="edit_url" required>
            <label for="edit_is_hidden">
                <input type="checkbox" name="is_hidden" id="edit_is_hidden" value="1">
                Hidden
            </label>
            <button type="submit" name="edit_custom_option" class="save-btn">Save Changes</button>
            <button type="button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>

    <script>
        function openEditModal(id, name, url, isHidden) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_url').value = url;
            document.getElementById('edit_is_hidden').checked = isHidden == 1;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>

    <a href="index.php">Back</a>
</body>

</html>