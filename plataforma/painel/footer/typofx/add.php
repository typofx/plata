<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include "conexao.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $column_id = $_POST['column_id']; // Now using column_id instead of column_name
    $item_name = trim($_POST['item_name']);
    $item_link = trim($_POST['item_link']);
    $item_order = (int) $_POST['item_order'];

    // Validate inputs
    if (!empty($column_id) && !empty($item_name) && !empty($item_order)) {
        // Insert into `typofx_footer` table
        $query = "INSERT INTO granna80_bdlinks.typofx_footer (column_id, item_name, link, item_order) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "issi", $column_id, $item_name, $item_link, $item_order);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Item added successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error adding item.');</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Please fill all required fields.');</script>";
    }
}

// Fetch available columns from `typofx_footer_columns`
$columns_query = "SELECT id, column_name FROM granna80_bdlinks.typofx_footer_columns ORDER BY column_name";
$columns_result = mysqli_query($conn, $columns_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Footer Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        label {
            display: block;
            margin-bottom: 8px;
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
    <h2>Add New Footer Item</h2>
    <form method="POST">
        <label for="column_id">Column:</label>
        <select id="column_id" name="column_id" required>
            <option value="">Select a column</option>
            <?php while ($row = mysqli_fetch_assoc($columns_result)) : ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['column_name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" placeholder="Enter item name" required>

        <label for="item_link">Item Link:</label>
        <input type="text" id="item_link" name="item_link" placeholder="Enter item link">

        <label for="item_order">Item Order:</label>
        <input type="number" id="item_order" name="item_order" placeholder="Enter item order" required><br><br>

        <input type="submit" value="Add Item">
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Footer Management</a>
    </form>

</body>

</html>
