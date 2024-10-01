<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();
include('conexao.php');

// Set timezone
date_default_timezone_set('UTC');

// Set timezone for MySQL connection
mysqli_query($conn, "SET time_zone = '+00:00'");

// Check current API status
$query = "SELECT is_active FROM granna80_bdlinks.api_control WHERE id = 2";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$api_ativa = $row['is_active'];

// Toggle API status
if (isset($_POST['toggle_api'])) {
    $new_state = $api_ativa ? 0 : 1;
    $update_query = "UPDATE granna80_bdlinks.api_control SET is_active = ? WHERE id = 2";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('i', $new_state);
    $stmt->execute();
    $api_ativa = $new_state;
}

// If API is active, fetch data from the API
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
        echo "Error making API request.";
    } else {
        $json_data = json_decode($response, true);

        // Store the data into the database
        $eur = floatval($EURUSD);
        $currentDateTime = (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $currentDate = (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d');

        $conn->begin_transaction();

        $query = "SELECT id FROM granna80_bdlinks.token_historical_data_eurusd WHERE DATE(date) = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $currentDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing data
            $updateQuery = "UPDATE granna80_bdlinks.token_historical_data_eurusd 
                            SET price = ?, date = ? WHERE DATE(date) = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param('dss', $eur, $currentDateTime, $currentDate);
            $stmt->execute();
        } else {
            // Insert new data
            $insertQuery = "INSERT INTO granna80_bdlinks.token_historical_data_eurusd (date, price) 
                            VALUES (?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param('sd', $currentDateTime, $eur);
            $stmt->execute();
        }

        $conn->commit();

        $eur = floatval($EURUSD);
    }
} else {
    // Fetch data from the database if API is inactive
    $query = "SELECT price FROM granna80_bdlinks.token_historical_data_eurusd ORDER BY date DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $eur = $row['price'];
    }
}

?>

<?php
// Include the database connection

// Get current date in UTC
$utcDate = new DateTime('now', new DateTimeZone('UTC'));
$currentDate = $utcDate->format('Y-m-d');
$currentDateTime = $utcDate->format('Y-m-d H:i:s');

// Check if a record for today exists
$query = "SELECT id FROM granna80_bdlinks.token_historical_data_eurusd WHERE DATE(date) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $currentDate);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update price if record exists
    $updateQuery = "UPDATE granna80_bdlinks.token_historical_data_eurusd SET price = ?, date = ? WHERE DATE(date) = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('dss', $eur, $currentDateTime, $currentDate);
    $updateStmt->execute();
    echo "Registration updated successfully!";
} else {
    // Insert new record if no record exists
    $insertQuery = "INSERT INTO granna80_bdlinks.token_historical_data_eurusd (date, price) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param('sd', $currentDateTime, $eur);
    $insertStmt->execute();
    echo "New record inserted successfully!";
}

// SQL query to select data
$sql = "SELECT id, date, price FROM granna80_bdlinks.token_historical_data_eurusd ORDER BY date DESC";
$result = mysqli_query($conn, $sql);

// Store the data in an array
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

// Generate JSON and save to a file
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
