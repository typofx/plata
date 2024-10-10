<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();
include('conexao.php');

// Definir fuso horário
date_default_timezone_set('UTC');

// Definir fuso horário para a conexão MySQL
mysqli_query($conn, "SET time_zone = '+00:00'");

// Verificar o estado atual da API
$query = "SELECT is_active FROM granna80_bdlinks.api_control WHERE id = 2";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$api_ativa = $row['is_active'];

// Alternar estado da API
if (isset($_POST['toggle_api'])) {
    $new_state = $api_ativa ? 0 : 1;
    $update_query = "UPDATE granna80_bdlinks.api_control SET is_active = ? WHERE id = 2";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('i', $new_state);
    $stmt->execute();
    $api_ativa = $new_state;
}

// Obter a data atual
$currentDate = (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d');

$currentDateTime = (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');


//$currentDate = '2024-10-11';  // Data simulada de amanhã
//$currentDateTime = '2024-10-11 12:00:00';  // Simulando um horário específico


// Verificar se já existe um registro para o dia atual
$query = "SELECT id FROM granna80_bdlinks.token_historical_data_eurusd WHERE DATE(date) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $currentDate);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Se já existir um registro para hoje, não buscar novos dados da API
    echo "Dados já inseridos para hoje.";
} else {
    // Se a API estiver ativa, buscar os dados da API
    if ($api_ativa) {
        $data = json_encode(array('currency' => 'USD', 'code' => 'USDT', 'meta' => true));

        $context_options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json\r\n" .
                            "x-api-key: 135b0af8-e18a-42a4-bce7-ed193b2932e6\r\n",
                'content' => $data
            )
        );

        $context = stream_context_create($context_options);
        $response = file_get_contents('https://api.livecoinwatch.com/coins/single', false, $context);

        if ($response === FALSE) {
            echo "Erro ao fazer a requisição da API.";
        } else {
            $json_data = json_decode($response, true);

            // Armazenar os dados da API no banco de dados
            $eur = floatval($EURUSD); // Substitua com o valor correto
            $conn->begin_transaction();

            // Inserir novos valores no banco
            $insertQuery = "INSERT INTO granna80_bdlinks.token_historical_data_eurusd (date, price) 
                            VALUES (?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param('sd', $currentDateTime, $eur);
            $stmt->execute();

            $conn->commit();

            echo "Dados da API inseridos com sucesso.";
        }
    } else {
        // Se a API estiver desativada, buscar os dados do banco de dados
        $query = "SELECT price FROM granna80_bdlinks.token_historical_data_eurusd ORDER BY date DESC LIMIT 1";
        $result = mysqli_query($conn, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            $eur = $row['price'];
        }
    }
}

?>

<?php
// Incluir a conexão com o banco de dados

// Consulta para selecionar os dados
$sql = "SELECT id, date, price FROM granna80_bdlinks.token_historical_data_eurusd ORDER BY date DESC";
$result = mysqli_query($conn, $sql);

// Armazenar os dados em um array
$data = [];
$json_cont = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'id' => $json_cont,
        'date' => $row['date'],
        'price' => round($row['price'], 10)
    ];
    $json_cont++;
}

// Gerar o JSON e salvar em um arquivo
$jsonFilePath = 'token_data.json';
file_put_contents($jsonFilePath, json_encode($data));
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Historical Data</title>
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

        table.dataTable th, table.dataTable td {
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
        $sql = "SELECT id, date, price FROM granna80_bdlinks.token_historical_data_eurusd ORDER BY date DESC";
        $result = mysqli_query($conn, $sql);

        // Store the data in an array
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
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php $cont = 1; ?>
                <?php foreach ($data_table as $row): ?>
                    <tr>
                        <td><?= $cont; ?></td>
                        <td><?= $row['date']; ?> UTC</td>
                        <td><?= number_format($row['price'], 4, '.', ','); ?></td>
                        <td><?php echo "<a href='edit.php?id={$row['id']}'>Edit</a>"; ?></td>
                    </tr>
                    <?php $cont++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tokenTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "lengthMenu": [10, 25, 50, 100]
            });
        });
    </script>
</body>
</html>
