<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include "conexao.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM granna80_bdlinks.plata_footer WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $column_name = $_POST['column_name'];
    $item_name = $_POST['item_name'];
    $item_order = $_POST['item_order'];
    $item_link = $_POST['item_link'];

    $query = "UPDATE granna80_bdlinks.plata_footer SET column_name = '$column_name', item_name = '$item_name', item_order = '$item_order', link = '$item_link' WHERE id = $id";
    mysqli_query($conn, $query);

    echo "<script>window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Footer Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <h2>Edit Footer Item</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        
        <label for="column_name">Column:</label><br>
        <input type="text" id="column_name" name="column_name" value="<?php echo $row['column_name']; ?>" ><br>
        
        <label for="item_name">Item Name:</label><br>
        <input type="text" id="item_name" name="item_name" value="<?php echo $row['item_name']; ?>" ><br>

        <label for="item_link">Link:</label><br>
        <input type="text" id="item_link" name="item_link" value="<?php echo $row['link']; ?>" ><br>
        
        <label for="item_order">Item column line:</label><br>
        <input type="number" id="item_order" name="item_order" value="<?php echo $row['item_order']; ?>" ><br><br>
        

        <a href="index.php">[Back]</a>
        <input type="submit" value="Update">
    </form>
</body>
</html>