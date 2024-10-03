<?php
include 'conexao.php'; // Inclua o arquivo de conexão com o banco de dados

// Consulta SQL para buscar os dados da tabela wallets
$sql = "SELECT * FROM granna80_bdlinks.wallets ORDER BY score DESC";

$result = $conn->query($sql);

$walletsArray = [];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallets List</title>

    <!-- Inclua os arquivos CSS e JS do DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">


    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 50px;
            background-color: #fff;
        }

        table.dataTable thead th,
        table.dataTable thead td,
        table.dataTable tfoot th,
        table.dataTable tfoot td {
            text-align: center;
        }

        table.dataTable th,
        table.dataTable td,
        table.dataTable tr {
            padding: 8px 12px;
            text-align: center;
        }

        table.dataTable th {
            background-color: #fff;
            text-align: center;
        }



        table.dataTable tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0;
            margin: 0;
            border: none;
            background: none;
        }

        .icon-spacing {
            margin-right: 10px;

        }
    </style>

</head>

<body>

    <h1>Wallets List</h1>
    <br><br>
    <a href="add.php">[Add new wallet]</a>
    <a href="wallets.json" target="_blank">[JSON]</a>
    <br><br>
    <table id="walletsTable" class="display">
        <thead>
            <tr>
                <th>#</th>
                <th>Icon</th>
                <th>Wallet</th>
                <th>Logo</th>
                <th>Price</th>
                <th>Decimal</th>
                <th>Mobile</th>
                <th>Desktop</th>
                <th>MOD</th>
                <th>Tax</th>
                <th>Speed</th>
                <th>Connect</th>
                <th>Joining Fee</th>
                <th>API</th>
                <th>Done</th>
                <th>Score</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Exibe os resultados da consulta
            if ($result->num_rows > 0) {
                $cont = 1;
                while ($row = $result->fetch_assoc()) {
                   

                    $walletsArray[] = [
                        'id' => intval($cont),
                        'icon' => $row['icon'],
                        'wallet' => $row['wallet'],
                        'logo' => $row['logo'] ? 'Yes' : 'No',
                        'price' => $row['price'] ? 'Yes' : 'No',
                        'decimal_flag' => $row['decimal_flag'] ? 'Yes' : 'No',
                        'mobile' => $row['mobile'] ? 'Yes' : 'No',
                        'desktop' => $row['desktop'] ? 'Yes' : 'No',
                        'mod' => $row['mod_flag'] ? 'Yes' : 'No',
                        'tax' => $row['tax'] == 0 ? 'No' : $row['tax'], 
                        'speed' => $row['speed'], 
                        'connect' => $row['connect'], 
                        'joining_fee' => $row['joining_fee'] == 0 ? 'Free' : $row['joining_fee'],
                        'api' => empty($row['api']) || $row['api'] === null ? '-' : $row['api'], 
                        'date' => $row['date'], 
                        'score' =>round(floatval($row['score']), 2),
                    ];




                    echo "<tr>";
                    echo "<td>" . $cont . "</td>";
                    echo "<td><img src='uploads/" . $row['icon'] . "' alt='Icon' width='50' height='50'></td>";  // Exibe o ícone
                    echo "<td>" . $row['wallet'] . "</td>";
                    echo "<td>" . ($row['logo'] ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . ($row['price'] ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . ($row['decimal_flag'] ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . ($row['mobile'] ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . ($row['desktop'] ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . ($row['mod_flag'] ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . ($row['tax'] == 0 ? 'No' : $row['tax']) . "</td>";
                    echo "<td>" . ucfirst($row['speed']) . "</td>";
                    echo "<td>" . ucfirst($row['connect']) . "</td>";
                    echo "<td>" . ($row['joining_fee'] == 0 ? 'Free' : $row['joining_fee']) . "</td>";
                    echo "<td>" . (empty($row['api']) || $row['api'] === null ? '-' : $row['api']) . "</td>";

                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['score'] . "</td>";
                    echo "<td><a href='edit.php?id=" . $row['id'] . "'>Edit</a> <a href='delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this wallet?\");'>Delete</a></td>";
                    echo "</tr>";
                    $cont++;
                    
                }
                $jsonData = json_encode($walletsArray, JSON_NUMERIC_CHECK);


                file_put_contents('wallets.json', $jsonData);
            }
            ?>
        </tbody>
    </table>
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#walletsTable').DataTable({
                "pageLength": 50, // Define 50 registros por página
                "ordering": false // Desativa a ordenação
            });
        });
    </script>

</body>

</html>