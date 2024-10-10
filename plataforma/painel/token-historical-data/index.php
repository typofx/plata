<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();
include('conexao.php');

// Definir o fuso horário no PHP
date_default_timezone_set('UTC'); // Ajuste conforme necessário

// Verifica o estado atual da API
$query = "SELECT is_active FROM granna80_bdlinks.api_control WHERE id = 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$api_ativa = $row['is_active'];

// Se o formulário foi enviado para ativar/desativar a API
if (isset($_POST['toggle_api'])) {
    $novo_estado = $api_ativa ? 0 : 1; // Inverte o estado atual
    $update_query = "UPDATE granna80_bdlinks.api_control SET is_active = ? WHERE id = 1";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('i', $novo_estado);
    $stmt->execute();

    // Atualiza o estado após a mudança
    $api_ativa = $novo_estado;
}

// Get the current date in UTC
$utcDate = new DateTime('now', new DateTimeZone('UTC'));
$currentDate = $utcDate->format('Y-m-d');
$currentDateTime = $utcDate->format('Y-m-d H:i:s');

// Verifica se já existe um registro para o dia atual
$query = "SELECT id, price, volume, market_cap FROM granna80_bdlinks.token_historical_data WHERE DATE(date) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $currentDate);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Se já existe, pegue os dados existentes e NÃO FAÇA REQUISIÇÃO À API
    $row = $result->fetch_assoc();
    $plt = $row['price'];
    $volume = $row['volume'];
    $market_cap = $row['market_cap'];
} else {
    // Se a API estiver ativada e não há dados para o dia atual, faça a requisição
    if ($api_ativa) {
        // Dados da requisição
        $data = json_encode(array('currency' => 'USD', 'code' => '______PLT', 'meta' => true));

        // Configurações do contexto da requisição
        $context_options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json\r\n" .
                            "x-api-key: 135b0af8-e18a-42a4-bce7-ed193b2932e6\r\n",
                'content' => $data
            )
        );

        // Cria o contexto
        $context = stream_context_create($context_options);

        // Faz a requisição à API
        $response = file_get_contents('https://api.livecoinwatch.com/coins/single', false, $context);

        if ($response === FALSE) {
            echo "Ocorreu um erro ao fazer a requisição.";
        } else {
            $json_data = json_decode($response, true);
            // Pega o volume da API
            $api_volume = isset($json_data['volume']) && $json_data['volume'] != 0 ? $json_data['volume'] : 1;

            // Armazena os dados no banco de dados
            $plt = floatval($PLTUSD);
            $market_cap = floatval(str_replace(',', '', $PLTmarketcapUSD));

            // Insere novos valores
            $insertQuery = "INSERT INTO granna80_bdlinks.token_historical_data (date, price, volume, market_cap) 
                            VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param('sdds', $currentDateTime, $plt, $api_volume, $market_cap);
            $stmt->execute();
        }
    } else {
        // Se a API estiver desativada, buscar apenas do banco de dados
        $query = "SELECT price, volume, market_cap FROM granna80_bdlinks.token_historical_data ORDER BY date DESC LIMIT 1";
        $result = mysqli_query($conn, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            $plt = $row['price'];
            $api_volume = $row['volume'];
            $market_cap = $row['market_cap'];
        }
    }
}

// SQL query to select the data
$sql = "SELECT id, date, price, volume, market_cap FROM granna80_bdlinks.token_historical_data ORDER BY date DESC";
$result = mysqli_query($conn, $sql);

// Store the data in an array
$data = [];
$json_cont = 1;
while ($row = mysqli_fetch_assoc($result)) {
    // Map the data to the desired structure
    $data[] = [
        'id' => $json_cont,
        'date' => $row['date'],
        'price' => round($row['price'], 10),
        'volume' => round($row['volume'], 10),
        'market_cap' => floatval($row['market_cap'])
    ];
    $json_cont++;
}

// Generate the JSON and save it to a file
$jsonFilePath = 'token_data.json';
file_put_contents($jsonFilePath, json_encode($data));
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Historical Data</title>
    <!-- Import DataTables CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 150px;
            background-color: #fff;
        }

        table.dataTable {
            width: auto;
            margin: 0 auto;
            border-collapse: collapse;
        }

        table.dataTable th,
        table.dataTable td {
            padding: 8px 12px;
            text-align: left;
        }

        table.dataTable th {
            background-color: #fff;
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
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>

<body>


    <div class="container">
        <h2>Token Historical Data</h2>
        <br>
        <a href="https://www.plata.ie/plataforma/painel/menu.php">[Control Panel]</a>
        <a href="add.php">[Add new record]</a>
        <a href="token_data.json">[Json]</a>
        <a href="fetch.php">[Fetch]</a>
        <h4>API Status: <?php echo $api_ativa ? 'Activated' : 'Disabled'; ?></h4>

        <form method="post">
            <button type="submit" name="toggle_api">
                <?php echo $api_ativa ? 'Disable API' : 'Enable API'; ?>
            </button>
        </form>
        <br>
        <br>
        <br>

        <?php
        $sql = "SELECT id, date, price, volume, market_cap FROM granna80_bdlinks.token_historical_data ORDER BY date DESC";
        $result = mysqli_query($conn, $sql);

        // Armazena os dados em um array
        $data_table = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data_table[] = $row;
        }
        ?>

        <table id="tokenTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Price</th>
                    <th>Volume</th>
                    <th>Market Cap</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php $cont = 1; ?>
                <?php foreach ($data_table as $row): ?>
                    <tr>
                        <td><?= $cont; ?></td>
                        <td><?= $row['date']; ?> UTC</td>
                        <td><?= number_format($row['price'], 10, '.', ','); ?></td>
                        <td><?= number_format($row['volume'], 4, '.', ','); ?></td>
                        <td><?= number_format($row['market_cap'], 4, '.', ','); ?></td>
                        <td><?php echo "<a href='edit.php?id={$row['id']}'>Edit</a>"; ?></td>

                    </tr>
                <?php $cont++;
                endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Import jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Import DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tokenTable').DataTable({
                "lengthMenu": [
                    [50, 100, 150, 250],
                    [50, 100, 150, 250]
                ],
                "columnDefs": [{
                        "width": "20px",
                        "targets": 0
                    },
                    {
                        "width": "20px",
                        "targets": 5
                    }
                ]
            });
        });
    </script>
</body>

</html>