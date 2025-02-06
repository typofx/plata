<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

if (!isset($_GET['column_id'])) {
    die("ID da coluna não fornecido.");
}

$columnId = intval($_GET['column_id']);

// Processa a exclusão de itens e submenus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item_id'])) {
    $deleteItemId = intval($_POST['delete_item_id']);

    // Verifica se o item tem submenus
    $checkSubmenusQuery = "SELECT COUNT(*) AS total FROM granna80_bdlinks.plata_submenus WHERE parent_item_id = $deleteItemId";
    $checkSubmenusResult = mysqli_query($conn, $checkSubmenusQuery);
    $checkSubmenusRow = mysqli_fetch_assoc($checkSubmenusResult);

    // Se o item tiver submenus, apaga primeiro os submenus
    if ($checkSubmenusRow['total'] > 0) {
        $deleteSubmenusQuery = "DELETE FROM granna80_bdlinks.plata_submenus WHERE parent_item_id = $deleteItemId";
        mysqli_query($conn, $deleteSubmenusQuery);
    }

    // Apaga o próprio item
    $deleteItemQuery = "DELETE FROM granna80_bdlinks.plata_header_items WHERE id = $deleteItemId";
    mysqli_query($conn, $deleteItemQuery);

    echo "<p style='color: red;'>Item excluído com sucesso!</p>";
}

// Processa a exclusão de submenus individualmente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_submenu_id'])) {
    $deleteSubmenuId = intval($_POST['delete_submenu_id']);
    $deleteSubmenuQuery = "DELETE FROM granna80_bdlinks.plata_submenus WHERE id = $deleteSubmenuId";
    mysqli_query($conn, $deleteSubmenuQuery);

    echo "<p style='color: red;'>Submenu excluído com sucesso!</p>";
}

// Processa a atualização dos itens e submenus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_item_id']) && !isset($_POST['delete_submenu_id'])) {
    foreach ($_POST['items'] as $itemId => $item) {
        $name = mysqli_real_escape_string($conn, $item['name']);
        $url = mysqli_real_escape_string($conn, $item['url']);
        $order = intval($item['order']);

        $updateItemQuery = "UPDATE granna80_bdlinks.plata_header_items 
                            SET name = '$name', url = '$url', order_number = $order 
                            WHERE id = $itemId";
        mysqli_query($conn, $updateItemQuery);
    }

    if (isset($_POST['submenus'])) {
        foreach ($_POST['submenus'] as $submenuId => $submenu) {
            $submenuName = mysqli_real_escape_string($conn, $submenu['name']);
            $submenuUrl = mysqli_real_escape_string($conn, $submenu['url']);
            $submenuOrder = intval($submenu['order']);

            $updateSubmenuQuery = "UPDATE granna80_bdlinks.plata_submenus 
                                   SET name = '$submenuName', url = '$submenuUrl', order_number = $submenuOrder 
                                   WHERE id = $submenuId";
            mysqli_query($conn, $updateSubmenuQuery);
        }
    }

    echo "<p style='color: green;'>Alterações salvas com sucesso!</p>";
}

// Obtém o nome da coluna
$columnQuery = "SELECT name FROM granna80_bdlinks.plata_header_columns WHERE id = $columnId";
$columnResult = mysqli_query($conn, $columnQuery);
$columnRow = mysqli_fetch_assoc($columnResult);

if (!$columnRow) {
    die("Coluna não encontrada.");
}

$columnName = $columnRow['name'];

// Obtém os itens e submenus da coluna
$query = "
    SELECT hi.id AS item_id, hi.name AS item_name, hi.url AS item_url, hi.order_number AS item_order,
           sm.id AS submenu_id, sm.name AS submenu_name, sm.url AS submenu_url, sm.order_number AS submenu_order
    FROM granna80_bdlinks.plata_header_items hi
    LEFT JOIN granna80_bdlinks.plata_submenus sm ON hi.id = sm.parent_item_id
    WHERE hi.column_id = $columnId
    ORDER BY hi.order_number, sm.order_number
";

$result = mysqli_query($conn, $query);
$items = [];

while ($row = mysqli_fetch_assoc($result)) {
    if (!isset($items[$row['item_id']])) {
        $items[$row['item_id']] = [
            'name' => $row['item_name'],
            'url' => $row['item_url'],
            'order' => $row['item_order'],
            'submenus' => []
        ];
    }

    if (!empty($row['submenu_name'])) {
        $items[$row['item_id']]['submenus'][] = [
            'id' => $row['submenu_id'],
            'name' => $row['submenu_name'],
            'url' => $row['submenu_url'],
            'order' => $row['submenu_order']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Items - <?php echo htmlspecialchars($columnName); ?></title>
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
        .save-btn {
            background-color: green;
        }
    </style>
</head>
<body>

<h2>Edit Column Items: <?php echo htmlspecialchars($columnName); ?></h2>

<form action="" method="POST">
    <table>
        <thead>
            <tr>
                <th>Order</th>
                <th>Name</th>
                <th>URL</th>
                <th>Submenus (Order, Name, URL)</th>
                <th>Açtions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $itemId => $item): ?>
                <tr>
                    <td><input type="number" name="items[<?php echo $itemId; ?>][order]" value="<?php echo $item['order']; ?>"></td>
                    <td><input type="text" name="items[<?php echo $itemId; ?>][name]" value="<?php echo htmlspecialchars($item['name']); ?>"></td>
                    <td><input type="text" name="items[<?php echo $itemId; ?>][url]" value="<?php echo htmlspecialchars($item['url']); ?>"></td>
                    <td>
                        <?php foreach ($item['submenus'] as $submenu): ?>
                            <input type="number" name="submenus[<?php echo $submenu['id']; ?>][order]" value="<?php echo $submenu['order']; ?>">
                            <input type="text" name="submenus[<?php echo $submenu['id']; ?>][name]" value="<?php echo htmlspecialchars($submenu['name']); ?>">
                            <input type="text" name="submenus[<?php echo $submenu['id']; ?>][url]" value="<?php echo htmlspecialchars($submenu['url']); ?>">
                            <button type="submit" name="delete_submenu_id" value="<?php echo $submenu['id']; ?>" class="delete-btn">Delete Submenu</button>
                            <br>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <button type="submit" name="delete_item_id" value="<?php echo $itemId; ?>" class="delete-btn">Delete Item</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" class="save-btn">Save Changes</button>
</form>

<br>
<a href="index.php">To go back</a>

</body>
</html>
