<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Adiciona uma nova coluna
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_column'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $order = intval($_POST['order']);
    $isDropdown = isset($_POST['is_dropdown']) ? 1 : 0;

    $insertQuery = "INSERT INTO granna80_bdlinks.granna_header_columns (name, order_number, is_dropdown) 
                    VALUES ('$name', $order, $isDropdown)";
    mysqli_query($conn, $insertQuery);

    echo "<p style='color: green;'>Coluna adicionada com sucesso!</p>";
}

// Edita uma coluna existente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_column'])) {
    $columnId = intval($_POST['column_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $order = intval($_POST['order']);
    $isDropdown = isset($_POST['is_dropdown']) ? 1 : 0;

    $updateQuery = "UPDATE granna80_bdlinks.granna_header_columns 
                    SET name = '$name', order_number = $order, is_dropdown = $isDropdown 
                    WHERE id = $columnId";
    mysqli_query($conn, $updateQuery);

    echo "<p style='color: green;'>Coluna atualizada com sucesso!</p>";
}

// Exclui uma coluna e todos os registros associados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_column'])) {
    $columnId = intval($_POST['column_id']);

    // Exclui os submenus associados aos itens da coluna
    $deleteSubmenusQuery = "DELETE FROM granna80_bdlinks.granna_header_submenus
WHERE parent_item_id IN (
    SELECT id FROM granna80_bdlinks.granna_header_items WHERE column_id = $columnId
);
";
    mysqli_query($conn, $deleteSubmenusQuery);

    // Exclui os itens da coluna
    $deleteItemsQuery = "DELETE FROM granna80_bdlinks.granna_header_items WHERE column_id = $columnId";
    mysqli_query($conn, $deleteItemsQuery);

    // Exclui a coluna
    $deleteColumnQuery = "DELETE FROM granna80_bdlinks.granna_header_columns WHERE id = $columnId";
    mysqli_query($conn, $deleteColumnQuery);

    echo "<p style='color: red;'>Coluna e todos os registros associados excluídos com sucesso!</p>";
}

// Obtém todas as colunas existentes
$columnsQuery = "SELECT * FROM granna80_bdlinks.granna_header_columns ORDER BY order_number";
$columnsResult = mysqli_query($conn, $columnsQuery);
$columns = [];

while ($row = mysqli_fetch_assoc($columnsResult)) {
    $columns[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Columns</title>
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

<h2>Add New Column</h2>
<form action="" method="POST">
    <label for="name">Name of column:</label>
    <input type="text" name="name" id="name" required>
    <label for="order">Order:</label>
    <input type="number" name="order" id="order" required>
    <label for="is_dropdown">is Dropdown?</label>
    <input type="checkbox" name="is_dropdown" id="is_dropdown">
    <button type="submit" name="add_column" class="save-btn">Add column</button>
</form>

<h2>Existing Columns</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Order</th>
            <th>Is Dropdown?</th>
            <th>Açtions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($columns as $column): ?>
            <tr>
                <td><?php echo $column['id']; ?></td>
                <td><?php echo htmlspecialchars($column['name']); ?></td>
                <td><?php echo $column['order_number']; ?></td>
                <td><?php echo $column['is_dropdown'] ? 'Sim' : 'Não'; ?></td>
                <td>
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="column_id" value="<?php echo $column['id']; ?>">
                        <button type="submit" name="delete_column" class="delete-btn">Delete</button>
                    </form>
                    <button onclick="editColumn(<?php echo $column['id']; ?>, '<?php echo htmlspecialchars($column['name']); ?>', <?php echo $column['order_number']; ?>, <?php echo $column['is_dropdown']; ?>)" class="edit-btn">Edit</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Formulário de Edição (oculto inicialmente) -->
<h2>Edit Column</h2>
<form id="editForm" action="" method="POST" style="display:none;">
    <input type="hidden" name="column_id" id="edit_column_id">
    <label for="edit_name">Name of column:</label>
    <input type="text" name="name" id="edit_name" required>
    <label for="edit_order">Order:</label>
    <input type="number" name="order" id="edit_order" required>
    <label for="edit_is_dropdown">Is Dropdown?</label>
    <input type="checkbox" name="is_dropdown" id="edit_is_dropdown">
    <button type="submit" name="edit_column" class="save-btn">Salve</button>
    <button type="button" onclick="cancelEdit()">Cancel</button>
</form>

<script>
    // Função para preencher o formulário de edição
    function editColumn(id, name, order, isDropdown) {
        document.getElementById('editForm').style.display = 'block';
        document.getElementById('edit_column_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_order').value = order;
        document.getElementById('edit_is_dropdown').checked = isDropdown;
    }

    // Função para cancelar a edição
    function cancelEdit() {
        document.getElementById('editForm').style.display = 'none';
    }
</script>
<a href="index.php">To go back</a>
</body>
</html>