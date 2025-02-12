<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Busca todas as colunas disponíveis
$columnQuery = "SELECT id, name FROM granna80_bdlinks.plata_header_columns ORDER BY order_number";
$columnResult = mysqli_query($conn, $columnQuery);
$columns = [];
while ($columnRow = mysqli_fetch_assoc($columnResult)) {
    $columns[$columnRow['id']] = $columnRow['name'];
}

// Busca todos os itens disponíveis para associar submenus
$itemQuery = "SELECT id, name, column_id FROM granna80_bdlinks.plata_header_items ORDER BY column_id, order_number";
$itemResult = mysqli_query($conn, $itemQuery);
$items = [];
while ($itemRow = mysqli_fetch_assoc($itemResult)) {
    $items[$itemRow['column_id']][] = $itemRow;
}

// Processa a adição de novos itens e submenus
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $columnId = intval($_POST['column_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $order = intval($_POST['order']);
    $isSubmenu = isset($_POST['parent_item_id']) && !empty($_POST['parent_item_id']);
    
    if ($isSubmenu) {
        // Adiciona um submenu
        $parentItemId = intval($_POST['parent_item_id']);
        $insertSubmenuQuery = "INSERT INTO granna80_bdlinks.plata_header_submenus (parent_item_id, name, url, order_number) 
                               VALUES ($parentItemId, '$name', '$url', $order)";
        mysqli_query($conn, $insertSubmenuQuery);
    } else {
        // Adiciona um item principal
        $insertItemQuery = "INSERT INTO granna80_bdlinks.plata_header_items (column_id, name, url, order_number) 
                            VALUES ($columnId, '$name', '$url', $order)";
        mysqli_query($conn, $insertItemQuery);
    }

    echo "<p style='color: green;'>Item adicionado com sucesso!</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add item</title>
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
  
    </style>
    <script>
        function toggleSubmenu() {
            let submenuCheckbox = document.getElementById("is_submenu");
            let parentItemSelect = document.getElementById("parent_item_id");
            let columnSelect = document.getElementById("column_id");

            if (submenuCheckbox.checked) {
                parentItemSelect.style.display = "block";
                columnSelect.disabled = true;
            } else {
                parentItemSelect.style.display = "none";
                columnSelect.disabled = false;
            }
        }
    </script>
</head>
<body>

<h2>Add new item</h2>

<form action="" method="POST">
    <label for="column_id">Choose column:</label><br><br>
    <select name="column_id" id="column_id" required>
        <option value="">Select a column</option>
        <?php foreach ($columns as $id => $name): ?>
            <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
        <?php endforeach; ?>
    </select>

    <label><br><br>
        <input type="checkbox" id="is_submenu" onclick="toggleSubmenu()"> This item is a submenu?
    </label>

    <label for="parent_item_id" style="display: none;">Choose Parent Item:</label><br><br>
    <select name="parent_item_id" id="parent_item_id" style="display: none;">
        <option value="">Select a parent item</option>
        <?php foreach ($items as $columnId => $columnItems): ?>
            <optgroup label="<?php echo htmlspecialchars($columns[$columnId]); ?>">
                <?php foreach ($columnItems as $item): ?>
                    <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></option>
                <?php endforeach; ?>
            </optgroup>
        <?php endforeach; ?>
    </select>

    <label for="name">Name</label><br><br>
    <input type="text" name="name" id="name" required><br><br>

    <label for="url">URL</label><br><br>
    <input type="text" name="url" id="url"><br><br>

    <label for="order">Order</label><br><br>
    <input type="number" name="order" id="order" required><br><br>

    <button type="submit">Add item</button>
</form>

<br>
<a href="index.php">Go to back</a>

</body>
</html>
