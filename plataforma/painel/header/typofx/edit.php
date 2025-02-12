<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

if (!isset($_GET['column_id'])) {
    die("ID da coluna não fornecido.");
}

$columnId = intval($_GET['column_id']);

// Função para verificar se a data é "New" (dentro de 2 meses)
function isNew($date) {
    $currentDate = new DateTime(); // Data atual
    $itemDate = new DateTime($date); // Data do item/submenu
    $interval = $currentDate->diff($itemDate); // Diferença entre as datas

    // Verifica se a diferença é menor ou igual a 2 meses
    return ($interval->y == 0 && $interval->m <= 2 && $interval->invert == 1);
}

// Processa a exclusão de itens e submenus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item_id'])) {
    $deleteItemId = intval($_POST['delete_item_id']);

    // Verifica se o item tem submenus
    $checkSubmenusQuery = "SELECT COUNT(*) AS total FROM granna80_bdlinks.typofx_header_submenus WHERE parent_item_id = $deleteItemId";
    $checkSubmenusResult = mysqli_query($conn, $checkSubmenusQuery);
    $checkSubmenusRow = mysqli_fetch_assoc($checkSubmenusResult);

    // Se o item tiver submenus, apaga primeiro os submenus
    if ($checkSubmenusRow['total'] > 0) {
        $deleteSubmenusQuery = "DELETE FROM granna80_bdlinks.typofx_header_submenus WHERE parent_item_id = $deleteItemId";
        mysqli_query($conn, $deleteSubmenusQuery);
    }

    // Apaga o próprio item
    $deleteItemQuery = "DELETE FROM granna80_bdlinks.typofx_header_items WHERE id = $deleteItemId";
    mysqli_query($conn, $deleteItemQuery);

    echo "<p style='color: red;'>Item excluído com sucesso!</p>";
}

// Processa a exclusão de submenus individualmente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_submenu_id'])) {
    $deleteSubmenuId = intval($_POST['delete_submenu_id']);
    $deleteSubmenuQuery = "DELETE FROM granna80_bdlinks.typofx_header_submenus WHERE id = $deleteSubmenuId";
    mysqli_query($conn, $deleteSubmenuQuery);

    echo "<p style='color: red;'>Submenu excluído com sucesso!</p>";
}

// Processa a atualização dos itens e submenus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_item_id']) && !isset($_POST['delete_submenu_id'])) {
    foreach ($_POST['items'] as $itemId => $item) {
        $name = mysqli_real_escape_string($conn, $item['name']);
        $url = mysqli_real_escape_string($conn, $item['url']);
        $order = intval($item['order']);
        $isHidden = isset($item['is_hidden']) ? 1 : 0;
        $date = mysqli_real_escape_string($conn, $item['date']);

        $updateItemQuery = "UPDATE granna80_bdlinks.typofx_header_items 
                            SET name = '$name', url = '$url', order_number = $order, is_hidden = $isHidden, date = '$date'
                            WHERE id = $itemId";
        mysqli_query($conn, $updateItemQuery);
    }

    if (isset($_POST['submenus'])) {
        foreach ($_POST['submenus'] as $submenuId => $submenu) {
            $submenuName = mysqli_real_escape_string($conn, $submenu['name']);
            $submenuUrl = mysqli_real_escape_string($conn, $submenu['url']);
            $submenuOrder = intval($submenu['order']);
            $isHiddenSubmenu = isset($submenu['is_hidden']) ? 1 : 0;
            $submenuDate = mysqli_real_escape_string($conn, $submenu['date']);

            $updateSubmenuQuery = "UPDATE granna80_bdlinks.typofx_header_submenus 
                                   SET name = '$submenuName', url = '$submenuUrl', order_number = $submenuOrder, is_hidden = $isHiddenSubmenu, date = '$submenuDate'
                                   WHERE id = $submenuId";
            mysqli_query($conn, $updateSubmenuQuery);
        }
    }

    echo "<p style='color: green;'>Alterações salvas com sucesso!</p>";
}

// Obtém o nome da coluna
$columnQuery = "SELECT name FROM granna80_bdlinks.typofx_header_columns WHERE id = $columnId";
$columnResult = mysqli_query($conn, $columnQuery);
$columnRow = mysqli_fetch_assoc($columnResult);

if (!$columnRow) {
    die("Coluna não encontrada.");
}

$columnName = $columnRow['name'];

// Obtém os itens e submenus da coluna
$query = "
    SELECT hi.id AS item_id, hi.name AS item_name, hi.url AS item_url, hi.order_number AS item_order, hi.is_hidden AS item_hidden, hi.date AS item_date,
           sm.id AS submenu_id, sm.name AS submenu_name, sm.url AS submenu_url, sm.order_number AS submenu_order, sm.is_hidden AS submenu_hidden, sm.date AS submenu_date
    FROM granna80_bdlinks.typofx_header_items hi
    LEFT JOIN granna80_bdlinks.typofx_header_submenus sm ON hi.id = sm.parent_item_id
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
            'is_hidden' => $row['item_hidden'],
            'date' => $row['item_date'],
            'submenus' => []
        ];
    }

    if (!empty($row['submenu_name'])) {
        $items[$row['item_id']]['submenus'][] = [
            'id' => $row['submenu_id'],
            'name' => $row['submenu_name'],
            'url' => $row['submenu_url'],
            'order' => $row['submenu_order'],
            'is_hidden' => $row['submenu_hidden'],
            'date' => $row['submenu_date']
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
        input[type="datetime-local"] {
            width: 160px;
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
        .new-label {
            color: green;
            font-weight: bold;
            margin-left: 5px;
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
                <th>Hidden</th>
                <th>Date</th>
                <th>Submenus (Order, Name, URL, Hidden, Date)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $itemId => $item): ?>
                <tr>
                    <td><input type="number" name="items[<?php echo $itemId; ?>][order]" value="<?php echo $item['order']; ?>"></td>
                    <td><input type="text" name="items[<?php echo $itemId; ?>][name]" value="<?php echo htmlspecialchars($item['name']); ?>"></td>
                    <td><input type="text" name="items[<?php echo $itemId; ?>][url]" value="<?php echo htmlspecialchars($item['url']); ?>"></td>
                    <td>
                        <input type="checkbox" name="items[<?php echo $itemId; ?>][is_hidden]" value="1" <?php echo $item['is_hidden'] ? 'checked' : ''; ?>>
                    </td>
                    <td>
                        <input type="datetime-local" name="items[<?php echo $itemId; ?>][date]" value="<?php echo date('Y-m-d\TH:i', strtotime($item['date'])); ?>">
                        <?php if (isNew($item['date'])): ?>
                            <span class="new-label">New</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php foreach ($item['submenus'] as $submenu): ?>
                            <input type="number" name="submenus[<?php echo $submenu['id']; ?>][order]" value="<?php echo $submenu['order']; ?>">
                            <input type="text" name="submenus[<?php echo $submenu['id']; ?>][name]" value="<?php echo htmlspecialchars($submenu['name']); ?>">
                            <input type="text" name="submenus[<?php echo $submenu['id']; ?>][url]" value="<?php echo htmlspecialchars($submenu['url']); ?>">
                            <input type="checkbox" name="submenus[<?php echo $submenu['id']; ?>][is_hidden]" value="1" <?php echo $submenu['is_hidden'] ? 'checked' : ''; ?>>
                            <input type="datetime-local" name="submenus[<?php echo $submenu['id']; ?>][date]" value="<?php echo date('Y-m-d\TH:i', strtotime($submenu['date'])); ?>">
                            <?php if (isNew($submenu['date'])): ?>
                                <span class="new-label">New</span>
                            <?php endif; ?>
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