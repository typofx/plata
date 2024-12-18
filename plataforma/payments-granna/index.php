<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>granna_payments</title>
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
            /* Ajuste o valor do gap conforme necessário */
        }
    </style>
</head>

<body>
    <h1>granna_payments</h1>

    <div class="container">
        <a href="add.php">Add new record</a>
        <button onclick="confirmDelete2();">Delete test users</button>
    </div>
    <?php
    include 'conexao.php';

    // SQL query to get data from the `granna_payments` table
    $sql = "SELECT id, date, bank, plata, amount, asset, address, txid, email, status 
    FROM granna80_bdlinks.granna_payments 
    WHERE trash = 1";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Start HTML table
        echo "<table id='example' class='display'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Bank</th>
                        <th>BRL Amount</th>
                        <th>EUR Amount</th>
                        <th>Asset</th>
                        <th>Address</th>
                 
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";

        // Iterate over results and fill the table
        while ($row = $result->fetch_assoc()) {
            $email1 = "uarloque@live.com";
            $email2 = "softgamebr4@gmail.com";
            $highlight_class = "";
            if ($row["email"] == $email1 || $row["email"] == $email2) {
                $highlight_class = "highlight";
            } else {
                $highlight_class = "";
            }
            $plata = str_replace(',', '', $row["plata"]);
            echo "<tr>
            <td>" . $row["id"] . "</td>
            <td><b>" . date('d/m/Y H:i:s', strtotime($row["date"])) . "</b></td>
            <td>" . $row["bank"] . "</td>
            <td>" .number_format((float)$plata, 2, '.', ',') . "</td>
            <td>" . $row["amount"] . "</td>
            <td>" . $row["asset"] . "</td>
           <td class='" . $highlight_class . "'>" . $row["email"] . "</td>
            <td>" . $currentStatus = strtolower($row['status']) . "</td>
            <td>
                <a href='edit.php?id=" . $row["id"] . "'><i class='fa-solid fa-pen-to-square'></i></a>
                <a href='#' onclick='confirmDelete(" . $row["id"] . ")'><i style='color: red;' class='fa-solid fa-trash'></i></a>
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
    <script>
        function confirmDelete2() {
            var confirmDelete = confirm("Are you sure you want to delete the test records? It will not be possible to recover this data.");
            if (confirmDelete) {
                window.location.href = "delete2.php";
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