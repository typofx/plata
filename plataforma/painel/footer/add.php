<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $column_name = $_POST['column_name'];
    $item_name = $_POST['item_name'];
    $item_link = $_POST['item_link'];
    $item_order = $_POST['item_order'];


    $query = "INSERT INTO granna80_bdlinks.plata_footer (column_name, item_name, link, item_order) VALUES ('$column_name', '$item_name', '$item_link' ,'$item_order')";
    mysqli_query($conn, $query);


echo 'Added successfully! ';
}
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
        <label for="column_name">Column:</label>
        <select id="column_name" name="column_name" required>
            <option value="">Select a column</option>
            <option value="ABOUT">ABOUT</option>
            <option value="PRODUCTS">PRODUCTS</option>
            <option value="PLATA (PLT)">PLATA (PLT)</option>
            <option value="CONTACT">CONTACT</option>
        </select>

        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" placeholder="Enter item name" required>

        <label for="item_link">Item Link:</label>
        <input type="text" id="item_link" name="item_link" placeholder="Enter item link" required>

        <label for="item_order">Item line:</label>
        <input type="number" id="item_order" name="item_order" placeholder="Enter item order" required><br><br>



        <input type="submit" value="Add Item">
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Footer Management</a>
    </form>

</body>

</html>