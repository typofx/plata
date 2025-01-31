<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include "conexao.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plata Footer Management</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .action-icons a {
            margin-right: 10px;
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h2>Plata Footer Management</h2>
    <a href="https://plata.ie/plataforma/painel/menu.php">[Back]</a>
    <a href="add.php">[Add New Item]</a>
    <a href="add_columns.php">[Add New Column]</a>

    <?php
    $query = "SELECT fc.column_name, pf.id, pf.item_name, pf.link FROM granna80_bdlinks.plata_footer pf INNER JOIN granna80_bdlinks.plata_footer_columns fc ON pf.column_id = fc.id ORDER BY fc.column_name, pf.item_order";
    $result = mysqli_query($conn, $query);

    $columns = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[$row['column_name']][] = [
            'id' => $row['id'],
            'name' => $row['item_name'],
            'link' => $row['link']
        ];
      
    }

    $maxRows = !empty($columns) ? max(array_map('count', $columns)) : 0;
    ?>

    <table id="footerTable" class="display">
        <thead>
            <tr>
                <th>Line Order</th>
                <?php 
                foreach (array_keys($columns) as $column_name) {
                    echo "<th>{$column_name}</th>";
                }
                echo "<th>Actions</th>";
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $cont = 1;
            for ($i = 0; $i < $maxRows; $i++) {
                echo "<tr>";
                echo "<td>$cont</td>";

                $item_ids = [];
                foreach ($columns as $column_items) {
                    if (isset($column_items[$i])) {
                        $item = $column_items[$i];
                        $item_name = !empty($item['link']) ? "<a href='{$item['link']}' target='_blank'>{$item['name']}</a>" : $item['name'];
                        echo "<td>{$item_name}</td>";
                        $item_ids[] = $item['id'];
                    } else {
                        echo "<td></td>";
                    }
                }

                echo "<td>";
                if (!empty($item_ids)) {
                    echo "<form class='edit-form' method='POST' action='edit.php'>";
                    foreach ($item_ids as $item_id) {
                        if (!empty($item_id)) {
                            echo "<input type='hidden' name='ids[]' value='{$item_id}'>";
                        }
                    }
                    echo "<button type='submit' class='edit-button'><i class='fas fa-edit'></i> Edit</button>";
                    echo "</form>";
                }
                echo "</td>";
                echo "</tr>";
                $cont++;
            }
            ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#footerTable').DataTable({
                "pageLength": 10,
                "ordering": false
            });

            $('.edit-button').click(function(event) {
                event.preventDefault();
                $(this).closest('.edit-form').submit();
            });
        });
    </script>

</body>
</html>
