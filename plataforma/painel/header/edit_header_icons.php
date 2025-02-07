<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Add new icon
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_icon'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $iconSrc = mysqli_real_escape_string($conn, $_POST['icon_src']);
    $order = intval($_POST['order']);
    $isHidden = isset($_POST['is_hidden']) ? 1 : 0;

    $insertQuery = "INSERT INTO granna80_bdlinks.plata_header_editable_items (name, url, icon_src, order_number, is_hidden) 
                    VALUES ('$name', '$url', '$iconSrc', $order, $isHidden)";
    mysqli_query($conn, $insertQuery);

    echo "<p style='color: green;'>Icon added successfully!</p>";
}

// Edit existing icon
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_icon'])) {
    $iconId = intval($_POST['icon_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $iconSrc = mysqli_real_escape_string($conn, $_POST['icon_src']);
    $order = intval($_POST['order']);
    $isHidden = isset($_POST['is_hidden']) ? 1 : 0;

    $updateQuery = "UPDATE granna80_bdlinks.plata_header_editable_items 
                    SET name = '$name', url = '$url', icon_src = '$iconSrc', order_number = $order, is_hidden = $isHidden 
                    WHERE id = $iconId";
    mysqli_query($conn, $updateQuery);

    echo "<p style='color: green;'>Icon updated successfully!</p>";
}

// Delete icon
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_icon'])) {
    $iconId = intval($_POST['icon_id']);

    $deleteQuery = "DELETE FROM granna80_bdlinks.plata_header_editable_items WHERE id = $iconId";
    mysqli_query($conn, $deleteQuery);

    echo "<p style='color: red;'>Icon deleted successfully!</p>";
}

// Get all icons
$queryIcons = "SELECT * FROM granna80_bdlinks.plata_header_editable_items ORDER BY order_number";
$resultIcons = mysqli_query($conn, $queryIcons);
$icons = [];

while ($row = mysqli_fetch_assoc($resultIcons)) {
    $icons[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Header Icons</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        input[type="text"], input[type="number"] {
            width: 100px;
        }
        button {
            padding: 5px 10px;
            margin-top: 5px;
            cursor: pointer;
            border: none;
            color: white;
        }
        .delete-btn {
            background-color: red;
        }
        .edit-btn {
            background-color: orange;
        }
        .save-btn {
            background-color: green;
        }
    </style>
</head>
<body>

<h2>Add New Icon</h2>
<form action="" method="POST">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <label for="url">URL:</label>
    <input type="text" name="url" id="url" required>
    <label for="icon_src">Icon Path:</label>
    <input type="text" name="icon_src" id="icon_src" required>
    <label for="order">Order:</label>
    <input type="number" name="order" id="order" required>
    <label for="is_hidden">Hidden?</label>
    <input type="checkbox" name="is_hidden" id="is_hidden">
    <button type="submit" name="add_icon" class="save-btn">Add Icon</button>
</form>

<h2>Existing Icons</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>URL</th>
            <th>Icon Path</th>
            <th>Order</th>
            <th>Hidden?</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($icons as $icon): ?>
            <tr>
                <td><?php echo $icon['id']; ?></td>
                <td><?php echo htmlspecialchars($icon['name']); ?></td>
                <td><?php echo htmlspecialchars($icon['url']); ?></td>
                <td><?php echo htmlspecialchars($icon['icon_src']); ?></td>
                <td><?php echo $icon['order_number']; ?></td>
                <td><?php echo $icon['is_hidden'] ? 'Yes' : 'No'; ?></td>
                <td>
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="icon_id" value="<?php echo $icon['id']; ?>">
                        <button type="submit" name="delete_icon" class="delete-btn">Delete</button>
                    </form>
                    <button onclick="editIcon(<?php echo $icon['id']; ?>, '<?php echo htmlspecialchars($icon['name']); ?>', '<?php echo htmlspecialchars($icon['url']); ?>', '<?php echo htmlspecialchars($icon['icon_src']); ?>', <?php echo $icon['order_number']; ?>, <?php echo $icon['is_hidden']; ?>)" class="edit-btn">Edit</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Edit Form (hidden by default) -->
<h2>Edit Icon</h2>
<form id="editForm" action="" method="POST" style="display:none;">
    <input type="hidden" name="icon_id" id="edit_icon_id">
    <label for="edit_name">Name:</label>
    <input type="text" name="name" id="edit_name" required>
    <label for="edit_url">URL:</label>
    <input type="text" name="url" id="edit_url" required>
    <label for="edit_icon_src">Icon Path:</label>
    <input type="text" name="icon_src" id="edit_icon_src" required>
    <label for="edit_order">Order:</label>
    <input type="number" name="order" id="edit_order" required>
    <label for="edit_is_hidden">Hidden?</label>
    <input type="checkbox" name="is_hidden" id="edit_is_hidden">
    <button type="submit" name="edit_icon" class="save-btn">Save Changes</button>
    <button type="button" onclick="cancelEdit()">Cancel</button>
</form>

<script>
    // Function to populate the edit form
    function editIcon(id, name, url, iconSrc, order, isHidden) {
        document.getElementById('editForm').style.display = 'block';
        document.getElementById('edit_icon_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_url').value = url;
        document.getElementById('edit_icon_src').value = iconSrc;
        document.getElementById('edit_order').value = order;
        document.getElementById('edit_is_hidden').checked = isHidden;
    }

    // Function to cancel editing
    function cancelEdit() {
        document.getElementById('editForm').style.display = 'none';
    }
</script>
<a href="index.php">To go back</a>
</body>
</html>