<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include "conexao.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Header Items List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: 8%;
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

        .actions {
            margin-bottom: 20px;
        }

        .actions a {
            display: inline-block;
      
            margin-right: 10px;
         
            
           
         
        }

      
    </style>
</head>

<body>
    <div class="actions">
        <a href="https://plata.ie/plataforma/painel/menu.php">[Back]</a>
        <a href="add.php">[Add new item]</a>
        <a href="edit_header_icons.php">[Config Icons]</a>
        <a href="edit_config_items.php">[Config Button]</a>
        <a href="add_new_column.php">[Config Columns]</a>
        <a href="config.php">[Config Logo]</a>
        <a href="https://typofx.ie/desktop-header.php">[TEST HEADER]</a>
    </div>

    <table id="headerItemsTable">
        <thead>
            <tr>
                <?php
                $columnQuery = "SELECT id, name FROM granna80_bdlinks.typofx_header_columns ORDER BY order_number";
                $columnResult = mysqli_query($conn, $columnQuery);
                $columns = [];

                echo "<th>Order</th>";
                while ($columnRow = mysqli_fetch_assoc($columnResult)) {
                    $editLink = "<a href='edit.php?column_id={$columnRow['id']}' style='margin-left:5px;'><i class='fa-solid fa-pen-to-square'></i></a>";
                    echo "<th>{$columnRow['name']} {$editLink}</th>";
                    $columns[$columnRow['id']] = $columnRow['name'];
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $items = [];
            $query = " 
            SELECT hi.column_id, hi.name AS item_name, hi.url AS item_url, hi.is_hidden AS item_hidden,
                   sm.name AS submenu_name, sm.url AS submenu_url, hi.id AS item_id
            FROM granna80_bdlinks.typofx_header_items hi
            LEFT JOIN granna80_bdlinks.typofx_header_submenus sm ON hi.id = sm.parent_item_id
            ORDER BY hi.column_id, hi.order_number, sm.order_number
            ";

            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $items[$row['column_id']][$row['item_id']]['name'] = $row['item_name'];
                $items[$row['column_id']][$row['item_id']]['url'] = $row['item_url'];
                $items[$row['column_id']][$row['item_id']]['hidden'] = $row['item_hidden'];
                if (!empty($row['submenu_name'])) {
                    $submenuLink = !empty($row['submenu_url'])
                        ? "<a href='{$row['submenu_url']}' target='_blank'>{$row['submenu_name']}</a>"
                        : $row['submenu_name'];
                    $items[$row['column_id']][$row['item_id']]['submenus'][] = $submenuLink;
                }
            }

            $maxRows = max(array_map('count', $items));

            $cont = 1;

            for ($i = 0; $i < $maxRows; $i++) {
                echo "<tr>";
                echo "<td>$cont</td>";
                foreach ($columns as $columnId => $columnName) {
                    if (isset($items[$columnId])) {
                        $item = array_values($items[$columnId])[$i] ?? null;
                        if ($item) {
                            $itemName = !empty($item['url']) ? "<a href='{$item['url']}' target='_blank'>{$item['name']}</a>" : $item['name'];
                            $submenuList = !empty($item['submenus']) ? " | " . implode(" | ", $item['submenus']) : "";

                            echo "<td>{$itemName}{$submenuList}</td>";
                        } else {
                            echo "<td></td>";
                        }
                    } else {
                        echo "<td></td>";
                    }
                }
                echo "</tr>";
                $cont++;
            }
            ?>
        </tbody>
    </table>
</body>

</html>