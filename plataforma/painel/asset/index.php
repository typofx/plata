<?php
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../../index.php");
    exit();
}

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();
$ETHUSD; // variável $ETHUSD definida em price.php

// Função para buscar o preço do token pelo símbolo
function getTokenPriceBySymbol($symbol) {
    $api_key = 
    $priceEndpoint = "https://min-api.cryptocompare.com/data/price?fsym={$symbol}&tsyms=USD&api_key={$api_key}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $priceEndpoint);
    $response = curl_exec($ch);
    curl_close($ch);
    $priceData = json_decode($response, true);

    return $priceData['USD'] ?? 0; // Retorna o preço em USD ou 0 se não encontrado
}

// Função para atualizar ou criar arquivo JSON
function updateJsonFile($data) {
    $jsonFile = 'assets_data.json';
    $data['timestamp'] = gmdate("Y-m-d\TH:i:s\Z"); // Adiciona timestamp UTC aos dados
    if (file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT))) {
        return "JSON file updated successfully. <a href='assets_data.json' target='_blank'>Assets-JSON</a>";
    } else {
        return "Error writing JSON file.";
    }
}

// Conexão com o banco de dados
include 'conexao.php';

// Verifica conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL para buscar dados da tabela de ativos
$sql = "SELECT id, contract_name, ticker_symbol, decimal_value, network, timestamp_value, name FROM granna80_bdlinks.assets";
$result = $conn->query($sql);

$assetsData = [];
$message = "";

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        if ($price >= number_format(0.01)) {
            $num_decimals = 4;
        } else {
            $num_decimals = 10;
        }

        switch ($row["ticker_symbol"]) {
            case "PLT":
                $price = $PLTUSD;
                break;
            case "aPolWBTC":
                $price = $BTCUSD;
                break;
            case "WETH":
                $price = $ETHUSD;
                break;
            case "WMATIC":
                $price = $MATICUSD;
                break;
            case "BRZ":
                $price = number_format(getTokenPriceBySymbol("BRL"), $num_decimals, '.', '');
                break;
            default:
                $price = number_format(getTokenPriceBySymbol($row["ticker_symbol"]), $num_decimals, '.', '');
        }

        $assetsData[$row["id"]] = [
            "name" => $row["name"],
            "ticker" => $row["ticker_symbol"],
            "contract" => $row["contract_name"],
            "decimals" => (int)$row["decimal_value"],
            "network" => $row["network"],
            "price" =>  $price
        ];
    }

    // Chama a função updateJsonFile e define $message
    $message = updateJsonFile($assetsData);
} else {
    $message = "No records found to update JSON.";
}
?>

<!DOCTYPE html>
<html>
<head>
   <!-- DataTables CSS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <title>Assets List</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }

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

</head>
<body>
<a href="https://plata.ie/plataforma/painel/menu.php">[Main Menu]</a>
<a href="add.php">[Add New Record]</a>

<h1>Assets List</h1>

<table id='example' class='display'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Ticker</th>
            <th>Contract</th>
            <th>Decimals</th>
            <th>Network</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Mostra os dados da tabela
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($price >= number_format(0.01)) {
                    $num_decimals = 4;
                } else {
                    $num_decimals = 10;
                }

                switch ($row["ticker_symbol"]) {
                    case "PLT":
                        $price = $PLTUSD;
                        break;
                    case "aPolWBTC":
                        $price = $BTCUSD;
                        break;
                    case "WETH":
                        $price = $ETHUSD;
                        break;
                    case "WMATIC":
                        $price = $MATICUSD;
                        break;
                    case "BRZ":
                        $price = number_format(getTokenPriceBySymbol("BRL"), $num_decimals, '.', '');
                        break;
                    default:
                        $price = number_format(getTokenPriceBySymbol($row["ticker_symbol"]), $num_decimals, '.', '');
                }

                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["ticker_symbol"] . "</td>";
                echo "<td>" . $row["contract_name"] . "</td>";
                echo "<td>" . $row["decimal_value"] . "</td>";
                echo "<td>" . $row["network"] . "</td>";
                echo "<td>" . $price . "</td>";
                echo "<td>
                    <a href='edit.php?id=" . $row["id"] . "'><i class='fa-solid fa-pen-to-square'></i></a>
                    <a href='#' onclick='confirmDelete(" . $row["id"] . ")'><i style='color: red;' class='fa-solid fa-trash'></i></a>
                  </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>

<p><?php echo $message; ?></p> <!-- Mostra a mensagem de sucesso ou erro aqui -->
<p>Timestamp: <?php echo gmdate("Y-m-d\TH:i:s\Z"); ?></p>

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
