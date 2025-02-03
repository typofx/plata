<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include "conexao.php";

if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
    echo "<script>alert('No valid IDs received!'); window.location.href='index.php';</script>";
    exit;
}

$item_ids = array_map('intval', $_POST['ids']);
$placeholders = implode(',', array_fill(0, count($item_ids), '?'));

$query = "SELECT id, column_id, item_name, link, item_order FROM granna80_bdlinks.typofx_footer WHERE id IN ($placeholders)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, str_repeat('i', count($item_ids)), ...$item_ids);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}
mysqli_stmt_close($stmt);

if (empty($items)) {
    echo "<script>alert('Items not found!'); window.location.href='index.php';</script>";
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $update_queries = [];

    foreach ($_POST['items'] as $id => $data) {
        $column_id = (int)$data['column_id'];
        $item_name = trim($data['item_name']);
        $item_link = trim($data['item_link']);
        $item_order = (int)$data['item_order'];

        if (!empty($column_id) && !empty($item_name) && !empty($item_order)) {
            $update_queries[] = "UPDATE granna80_bdlinks.typofx_footer 
                                 SET column_id = $column_id, item_name = '" . mysqli_real_escape_string($conn, $item_name) . "', 
                                     link = '" . mysqli_real_escape_string($conn, $item_link) . "', item_order = $item_order 
                                 WHERE id = $id";
        }
    }

    if (!empty($update_queries)) {
        $full_query = implode(";", $update_queries);
        if (mysqli_multi_query($conn, $full_query)) {
            echo "<script>alert('Items updated successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error updating items.');</script>";
        }
    }
}


// Handle delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    $delete_query = "DELETE FROM granna80_bdlinks.typofx_footer WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $delete_query)) {
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Item deleted successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error deleting item.');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Database error.');</script>";
    }
}



// Fetch available columns
$columns_query = "SELECT id, column_name FROM granna80_bdlinks.typofx_footer_columns ORDER BY column_name";
$columns_result = mysqli_query($conn, $columns_query);
$columns = [];
while ($row = mysqli_fetch_assoc($columns_result)) {
    $columns[$row['id']] = $row['column_name'];
}

// Determine column name for the selected items
$column_name = "Unknown Column";
if (!empty($items)) {
    $first_column_id = $items[0]['column_id'];
    if (isset($columns[$first_column_id])) {
        $column_name = $columns[$first_column_id];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Footer Items: <?php echo htmlspecialchars($column_name); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        label {
            display: block;
            margin-bottom: 8px;
        }

        .item-container {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
<h2>Edit Footer Items: <?php echo htmlspecialchars($column_name); ?></h2>
    <form method="POST">
        <?php foreach ($items as $item) : ?>
            <div class="item-container">
                <input type="hidden" name="items[<?php echo $item['id']; ?>][id]" value="<?php echo $item['id']; ?>">

         
                <select id="column_id_<?php echo $item['id']; ?>" name="items[<?php echo $item['id']; ?>][column_id]" required style="display: none;">
                    <option value="">Select a column</option>
                    <?php foreach ($columns as $col_id => $col_name) : ?>
                        <option value="<?php echo $col_id; ?>" <?php echo ($col_id == $item['column_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($col_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="item_name_<?php echo $item['id']; ?>">Item Name:</label>
                <input type="text" id="item_name_<?php echo $item['id']; ?>" name="items[<?php echo $item['id']; ?>][item_name]" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>

                <label for="item_link_<?php echo $item['id']; ?>">Item Link:</label>
                <input type="text" id="item_link_<?php echo $item['id']; ?>" name="items[<?php echo $item['id']; ?>][item_link]" value="<?php echo htmlspecialchars($item['link']); ?>">

                <label for="item_order_<?php echo $item['id']; ?>">Item Order:</label>
                <input type="number" id="item_order_<?php echo $item['id']; ?>" name="items[<?php echo $item['id']; ?>][item_order]" value="<?php echo $item['item_order']; ?>" required>

     
            </div>
        <?php endforeach; ?>

        <input type="hidden" name="ids[]" value="<?php echo implode(',', $item_ids); ?>">
        <input type="hidden" name="update" value="1">
        <input type="submit" value="Update Items">
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Footer Management</a>
    </form>
</body>

</html>
