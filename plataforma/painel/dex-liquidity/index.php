<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEX Liquidity</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
        table,
        th,
        td {
            text-align: center;
            vertical-align: middle;
        }

        .router-link {
            font-size: 0.9em;
            color: #06c;
            word-break: break-all;
            max-width: 200px;
            display: inline-block;
        }

        .dex-icon {
            color: #4CAF50;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <h1>DEX Liquidity</h1>
    <a href="<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/main.php'; ?>">[Back]</a>
    <a href='add.php'>Add new record</a>
    <br>
    <?php
    include 'conexao.php';

    // SQL query to get data including the new router fields
    $sql = "SELECT id, name, logo, type, router_address, router_link FROM granna80_bdlinks.dex_liquidity";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Start HTML table
        echo "<table id='dexTable' class='display' style='width:100%'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Logo</th>
                        <th>Type</th>
                        <th>Router Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";

        // Iterate over results and fill the table
        while ($row = $result->fetch_assoc()) {
            $routerDisplay = ' ';
            if ($row["type"] === 'dex' && !empty($row["router_address"])) {
                $fullAddress = htmlspecialchars($row["router_address"]);
                $shortAddress = substr($fullAddress, 0, 6) . '...' . substr($fullAddress, -4); // Ex: 0x1234...abcd
                $routerDisplay = '<a class="router-link" href="https://polygonscan.com/address/' . $fullAddress . '" target="_blank">';
                $routerDisplay .= $shortAddress;
                $routerDisplay .= '</a>';
            }


            echo "<tr>
                    <td>" . htmlspecialchars($row["id"]) . "</td>
                    <td><b>" . htmlspecialchars($row["name"]) . "</b></td>
                    <td><img src='" . htmlspecialchars($row["logo"]) . "' style='height: 30px;' alt='logo'></td>
                    <td>";


            echo htmlspecialchars($row["type"]) . "</td>
                    <td>" . $routerDisplay . "</td>
                    <td>
                        <a href='edit.php?id=" . $row["id"] . "' title='Edit'><i class='fa-solid fa-pen-to-square'></i></a>
                        <a href='delete.php?id=" . $row["id"] . "' title='Delete' onclick='return confirm(\"Are you sure you want to delete this record?\")'>
                            <i style='color: red;' class='fa-solid fa-trash'></i>
                        </a>
                    </td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No records found</p>";
    }

    $conn->close();
    ?>

    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dexTable').DataTable({
                pageLength: 25,
                order: [
                    [0, 'asc']
                ]
            });
        });
    </script>
</body>

</html>