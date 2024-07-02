<?php 
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../../index.php");
    exit();
} ?>

<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();

// Function to fetch token price by symbol
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

// Function to update or create JSON file
function updateJsonFile($data) {
    $jsonFile = 'assets_data.json';
    $data['timestamp'] = gmdate("Y-m-d\TH:i:s\Z"); // Add UTC timestamp to the data
    if (file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT))) {
        return "JSON file updated successfully. <a href='assets_data.json'>Assets-JSON</a> ";
    } else {
        return "Error writing JSON file.";
    }
}

// Database connection settings
include 'conexao.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from assets table
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
          "price" => (float) str_replace(',', '.', $price) // Converte $price para float
        ];
    }

    $message = updateJsonFile($assetsData);
} else {
    $message = "No records found to update JSON.";
}


?>

<style>
    body {
        font-family: 'Courier New', monospace;
        font-size: 14px;
    }
</style>

<!DOCTYPE html>
<html>
<head>
   <!-- DataTables CSS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <title>Assets List</title>
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

</head>
<body>
<a href="https://plata.ie/plataforma/painel/menu.php">[Main Menu]</a>
<a href="add.php">[Add New Record]</a>

<h1>Assets List</h1>
<p><?php echo $message; ?></p>
<p>Timestamp: <?php echo gmdate("Y-m-d\TH:i:s\Z"); // Display UTC timestamp ?></p>
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
        // Display table data
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["ticker_symbol"] . "</td>";
                echo "<td>" . $row["contract_name"] . "</td>";
                echo "<td>" . $row["decimal_value"] . "</td>";
                echo "<td>" . $row["network"] . "</td>";

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
