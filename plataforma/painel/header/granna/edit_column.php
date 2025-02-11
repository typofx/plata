<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php'; // Include your database connection file

// Get the header item ID from the URL
$headerItemId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the header item
$queryItem = "SELECT * FROM granna80_bdlinks.granna_header_items WHERE id = $headerItemId";
$resultItem = mysqli_query($conn, $queryItem);
$headerItem = mysqli_fetch_assoc($resultItem);

// Fetch sub-items for the header item
$querySubitems = "SELECT * FROM granna80_bdlinks.granna_header_subitems WHERE parent_item_id = $headerItemId ORDER BY order_number";
$resultSubitems = mysqli_query($conn, $querySubitems);
$subItems = [];

while ($row = mysqli_fetch_assoc($resultSubitems)) {
    $subItems[] = $row;
}

// Handle form submission for updating the header item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_header_item'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $isDropdown = isset($_POST['is_dropdown']) ? 1 : 0;
    $orderNumber = intval($_POST['order_number']);

    $updateQuery = "UPDATE granna80_bdlinks.granna_header_items 
                    SET name = '$name', url = '$url', is_dropdown = $isDropdown, order_number = $orderNumber 
                    WHERE id = $headerItemId";
    mysqli_query($conn, $updateQuery);

    echo "<p style='color: green;'>Header item updated successfully!</p>";
}

// Handle form submission for adding a new sub-item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subitem'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $orderNumber = intval($_POST['order_number']);

    $insertQuery = "INSERT INTO granna80_bdlinks.granna_header_subitems (parent_item_id, name, url, order_number) 
                    VALUES ($headerItemId, '$name', '$url', $orderNumber)";
    mysqli_query($conn, $insertQuery);

    echo "<p style='color: green;'>Sub-item added successfully!</p>";
}

// Handle form submission for updating a sub-item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_subitem'])) {
    $subItemId = intval($_POST['subitem_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $orderNumber = intval($_POST['order_number']);

    $updateQuery = "UPDATE granna_header_subitems 
                    SET name = '$name', url = '$url', order_number = $orderNumber 
                    WHERE id = $subItemId";
    mysqli_query($conn, $updateQuery);

    echo "<p style='color: green;'>Sub-item updated successfully!</p>";
}

// Handle form submission for deleting a single sub-item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subitem'])) {
    $subItemId = intval($_POST['subitem_id']);

    $deleteQuery = "DELETE FROM granna80_bdlinks.granna_header_subitems WHERE id = $subItemId";
    mysqli_query($conn, $deleteQuery);

    echo "<p style='color: green;'>Sub-item deleted successfully!</p>";
}

// Handle form submission for deleting the entire column
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_column'])) {
    // Delete all sub-items of the column
    $deleteSubitemsQuery = "DELETE FROM granna80_bdlinks.granna_header_subitems WHERE parent_item_id = $headerItemId";
    mysqli_query($conn, $deleteSubitemsQuery);

    // Delete the header item
    $deleteHeaderItemQuery = "DELETE FROM granna80_bdlinks.granna_header_items WHERE id = $headerItemId";
    mysqli_query($conn, $deleteHeaderItemQuery);

    echo "<p style='color: green;'>Column and all sub-items deleted successfully!</p>";
    // Redirect to the index page or another appropriate page
    header("Location: index.php");
    exit();
}

// Handle form submission for deleting only the sub-items
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subitems'])) {
    // Delete all sub-items of the column
    $deleteSubitemsQuery = "DELETE FROM granna80_bdlinks.granna_header_subitems WHERE parent_item_id = $headerItemId";
    mysqli_query($conn, $deleteSubitemsQuery);

    echo "<p style='color: green;'>All sub-items deleted successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Column</title>
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
            width: 300px;
            padding: 8px;
            box-sizing: border-box;
        }
        .save-btn {
            background-color: green;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .save-btn:hover {
            background-color: darkgreen;
        }
        .delete-btn {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            margin-left: 10px;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

    <h2>Edit Column: <?php echo htmlspecialchars($headerItem['name']); ?></h2>

    <!-- Form to update the header item -->
    <form action="" method="POST">
        <div class="form-group">
            <label for="name">Header Item Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($headerItem['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="url">Header Item URL:</label>
            <input type="text" name="url" id="url" value="<?php echo htmlspecialchars($headerItem['url']); ?>">
        </div>
        <div class="form-group">
            <label for="is_dropdown">
                <input type="checkbox" name="is_dropdown" id="is_dropdown" value="1" <?php echo $headerItem['is_dropdown'] ? 'checked' : ''; ?>> Is Dropdown?
            </label>
        </div>
        <div class="form-group">
            <label for="order_number">Order Number:</label>
            <input type="number" name="order_number" id="order_number" value="<?php echo $headerItem['order_number']; ?>" required>
        </div>
        <button type="submit" name="update_header_item" class="save-btn">Update Header Item</button>
    </form>

    <!-- List of sub-items -->
    <h3>Sub-items</h3>
    <ul>
        <?php foreach ($subItems as $subItem): ?>
            <li>
                <form action="" method="POST" style="display:inline;">
                    <input type="hidden" name="subitem_id" value="<?php echo $subItem['id']; ?>">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($subItem['name']); ?>" required>
                    <input type="text" name="url" value="<?php echo htmlspecialchars($subItem['url']); ?>">
                    <input type="number" name="order_number" value="<?php echo $subItem['order_number']; ?>" required>
                    <button type="submit" name="update_subitem" class="save-btn">Update</button>
                </form>
                <form action="" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this sub-item?');">
                    <input type="hidden" name="subitem_id" value="<?php echo $subItem['id']; ?>">
                    <button type="submit" name="delete_subitem" class="delete-btn">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Form to add a new sub-item -->
    <h3>Add New Sub-item</h3>
    <form action="" method="POST">
        <div class="form-group">
            <label for="name">Sub-item Name:</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="url">Sub-item URL:</label>
            <input type="text" name="url" id="url">
        </div>
        <div class="form-group">
            <label for="order_number">Order Number:</label>
            <input type="number" name="order_number" id="order_number" required>
        </div>
        <button type="submit" name="add_subitem" class="save-btn">Add Sub-item</button>
    </form>

    <!-- Form to delete the entire column -->
    <h3>Delete Column</h3>
    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this column and all its sub-items?');">
        <button type="submit" name="delete_column" class="delete-btn">Delete Entire Column</button>
    </form>

    <!-- Form to delete only the sub-items -->
    <h3>Delete Sub-items</h3>
    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete all sub-items?');">
        <button type="submit" name="delete_subitems" class="delete-btn">Delete All Sub-items</button>
    </form>

</body>
<br>
<a href="index.php">[Back]</a>
</html>