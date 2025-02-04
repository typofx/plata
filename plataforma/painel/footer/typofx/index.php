<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include "conexao.php";



$sql = "SELECT folder_path FROM granna80_bdlinks.typofx_footer_editable_items LIMIT 1";
$result = mysqli_query($conn, $sql);


if ($row = mysqli_fetch_assoc($result)) {
    $folder_path = htmlspecialchars($row['folder_path']);
} else {
    $folder_path = "https://plata.ie/";
}



?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TypoFX Footer Management</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            align-items: center;
        }

        tr,th,
        td {
            padding: 8px;
            text-align: center;
        }

        .edit-form {
            display: inline;
        }

        .edit-button {
            background: none;
            border: none;
            cursor: pointer;
            color: blue;
        }

        table.dataTable thead th, table.dataTable thead td, table.dataTable tfoot th, table.dataTable tfoot td {
     text-align: center !important; 
}
    </style>
</head>

<body>
    <h2>TypoFX Footer Management</h2>
    <a href="https://plata.ie/plataforma/painel/menu.php">[Back]</a>
    <a href="add.php">[Add New Item]</a>
    <a href="editable_items.php">[editable items]</a>
    <a href="add_columns.php">[Add New Column]</a><br><br>
   <p>Folder: <?php echo $folder_path  ?></p> 
    <br>
    <br>

    <?php
    $query = "SELECT fc.column_name, pf.id, pf.item_name, pf.link 
              FROM granna80_bdlinks.typofx_footer pf 
              INNER JOIN granna80_bdlinks.typofx_footer_columns fc 
              ON pf.column_id = fc.id 
              ORDER BY fc.column_order, pf.item_order";
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
                <th>Line</th>
                <?php
                $letters = range('A', 'Z'); 
                $index = 0; 

                foreach ($columns as $column_name => $items) {
                    $letter = $letters[$index % count($letters)]; 

                    echo "<th><center>{$letter}</center><br>{$column_name}
          <form class='edit-form' method='POST' action='edit.php'>";

                    foreach ($items as $item) {
                        echo "<input type='hidden' name='ids[]' value='{$item['id']}'>";
                    }

                    echo "<button type='submit' class='edit-button'><i class='fas fa-edit'></i></button>
          </form>
          </th>";

                    $index++;
                }
                ?>

            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < $maxRows; $i++) {
                echo "<tr>";
                echo "<td>" . ($i + 1) . "</td>";

                foreach ($columns as $column_items) {
                    if (isset($column_items[$i])) {
                        $item = $column_items[$i];
                        $item_name = !empty($item['link']) ? "<a href='{$item['link']}' target='_blank'>{$item['name']}</a>" : $item['name'];
                        echo "<td>{$item_name}</td>";
                    } else {
                        echo "<td></td>";
                    }
                }
                echo "</tr>";
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
        });
    </script>
</body>

</html>