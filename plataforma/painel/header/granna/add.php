<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php'; // Include your database connection file

// Handle form submission for adding a new header item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $isDropdown = isset($_POST['is_dropdown']) ? 1 : 0;
    $orderNumber = intval($_POST['order_number']);

    $insertQuery = "INSERT INTO granna80_bdlinks.granna_header_items (name, url, is_dropdown, order_number) 
                    VALUES ('$name', '$url', $isDropdown, $orderNumber)";
    mysqli_query($conn, $insertQuery);

    echo "<p style='color: green;'>Header item added successfully!</p>";
}

// Handle form submission for adding a new sub-item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subitem'])) {
    $parentItemId = intval($_POST['parent_item_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $orderNumber = intval($_POST['order_number']);

    $insertQuery = "INSERT INTO granna80_bdlinks.granna_header_subitems (parent_item_id, name, url, order_number) 
                    VALUES ($parentItemId, '$name', '$url', $orderNumber)";
    mysqli_query($conn, $insertQuery);

    echo "<p style='color: green;'>Sub-item added successfully!</p>";
}

// Fetch all header items
$queryItems = "SELECT * FROM granna80_bdlinks.granna_header_items ORDER BY order_number";
$resultItems = mysqli_query($conn, $queryItems);
$headerItems = [];

while ($row = mysqli_fetch_assoc($resultItems)) {
    $headerItems[] = $row;
}

// Fetch all sub-items
$querySubitems = "SELECT * FROM granna80_bdlinks.granna_header_subitems ORDER BY parent_item_id";
$resultSubitems = mysqli_query($conn, $querySubitems);
$subItems = [];

while ($row = mysqli_fetch_assoc($resultSubitems)) {
    $subItems[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Granna Header Configuration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
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

        .section {
            margin-bottom: 40px;
        }

        .section h3 {
            color: #333;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
        }
    </style>
</head>

<body>

    <h2>Granna Header Configuration</h2>

    <!-- Section: Add Header Item -->
    <div class="section">
        <h3>Add New Header Item</h3>
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Item Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="url">Item URL:</label>
                <input type="text" name="url" id="url">
            </div>
            <div class="form-group">
                <label for="is_dropdown">
                    <input type="checkbox" name="is_dropdown" id="is_dropdown" value="1"> Is Dropdown?
                </label>
            </div>
            <div class="form-group">
                <label for="order_number">Order Number:</label>
                <input type="number" name="order_number" id="order_number" required>
            </div>
            <button type="submit" name="add_item" class="save-btn">Add Header Item</button>
        </form>
    </div>

    <!-- Section: Add Sub-item -->
    <div class="section">
        <h3>Add New Sub-item</h3>
        <form action="" method="POST">
            <div class="form-group">
                <label for="parent_item_id">Parent Item:</label>
                <select name="parent_item_id" id="parent_item_id" required>
                    <?php foreach ($headerItems as $item): ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
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
    </div>

    <!-- Section: List Header Items -->
    <div class="section">
        <h3>Header Items</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Is Dropdown?</th>
                    <th>Order Number</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($headerItems as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['url']); ?></td>
                        <td><?php echo $item['is_dropdown'] ? 'Yes' : 'No'; ?></td>
                        <td><?php echo $item['order_number']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Section: List Sub-items -->
    <div class="section">
        <h3>Sub-items</h3>
        <table>
            <thead>
                <tr>
                    <th>Parent Item</th>
                    <th>Sub-items</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Group sub-items by parent_item_id
                $groupedSubItems = [];
                foreach ($subItems as $subItem) {
                    $parentItemId = $subItem['parent_item_id'];
                    if (!isset($groupedSubItems[$parentItemId])) {
                        $groupedSubItems[$parentItemId] = [];
                    }
                    $groupedSubItems[$parentItemId][] = $subItem;
                }

                // Display sub-items grouped by parent item
                foreach ($headerItems as $headerItem):
                    if ($headerItem['is_dropdown']): // Only show dropdown items
                        $parentItemId = $headerItem['id'];
                        $parentItemName = htmlspecialchars($headerItem['name']);
                ?>
                        <tr>
                            <td><strong><?php echo $parentItemName; ?></strong></td>
                            <td>
                                <?php if (isset($groupedSubItems[$parentItemId])): ?>
                                    <ul style="list-style-type: none; padding-left: 0;">
                                        <?php foreach ($groupedSubItems[$parentItemId] as $subItem): ?>
                                            <li>
                                                <strong>Order:</strong> <?php echo $subItem['order_number']; ?> |
                                                <strong>Name:</strong> <?php echo htmlspecialchars($subItem['name']); ?> 
                                               

                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>No sub-items found for this parent item.</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                <?php endif;
                endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
<a href="index.php">[Back]</a>

</html>