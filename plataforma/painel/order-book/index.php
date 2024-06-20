<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Depth</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
        table,
        th,
        td {
            text-align: center;
        }

        .highlight {
            background-color: yellow;
            color: black;
            padding: 2px 4px;

            border-radius: 3px;

        }
    </style>
    <style>
        .container {
            display: flex;
            align-items: center;
            gap: 20px;
          
        }
    </style>
</head>

<body>
    <h1>Market Depth</h1>

    <div class="container">
        <a href="add.php">Add new record</a>
        
    </div>
    <?php
    include 'conexao.php';

    // SQL query to get data from the `payments` table
    $sql = "SELECT id, value, value2, name, url
    FROM granna80_bdlinks.order_book";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Start HTML table
        echo "<table id='example' class='display'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>                
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
               ";
    
        // Iterate over results and fill the table
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td><a href='https://plata.ie/sandbox/balance/cex-price.php?name=" . $row["name"] . "'>" . $row["name"] . "</a></td>
                    <td>
                        <a href='edit.php?id=" . $row["id"] . "'><i class='fa-solid fa-pen-to-square'></i></a>
                       
                    </td>
                  </tr>";
        }
    
        echo "</tbody></table>";
    } else {
        echo "0 results";
    }
    
    $conn->close();
    
    ?>

    <script>
        function confirmDelete(id) {
            var confirmDelete = confirm("Are you sure you want to delete?");
            if (confirmDelete) {
                window.location.href = "delete.php?id=" + id;
            }
        }
    </script>


    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').dataTable({
                "lengthMenu": [
                    [25, 50, 75, -1],
                    [25, 50, 75, "All"]
                ],
                "pageLength": 50
            });
        });
    </script>
</body>

</html>