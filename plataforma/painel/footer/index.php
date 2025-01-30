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
    <table id="footerTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Column</th>
                <th>Item Name</th>
                <th>Line Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM granna80_bdlinks.plata_footer ORDER BY column_name, item_order";
            $result = mysqli_query($conn, $query);

            $cont = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$cont}</td>";
                echo "<td>{$row['column_name']}</td>";
                
                if (!empty($row['link'])) {
                    echo "<td><a href='{$row['link']}'>{$row['item_name']}</a></td>";
                } else {
                    echo "<td>{$row['item_name']}</td>";
                }
                
                echo "<td>Line {$row['item_order']}</td>";
                echo "<td class='action-icons'>
                        <a href='edit.php?id={$row['id']}' title='Edit'><i class='fas fa-edit'></i></a>
                        <a href='delete.php?id={$row['id']}' title='Delete'><i class='fas fa-trash'></i></a>
                    </td>";
                echo "</tr>";
                $cont++;
                
            }

            ?>
        </tbody>
    </table>

    <script>
    $(document).ready(function() {
        $('#footerTable').DataTable({
            "pageLength": 100
        });
    });
</script>

</body>
</html>
