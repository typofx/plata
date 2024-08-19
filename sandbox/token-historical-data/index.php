<?php
// Include the database connection file
include('conexao.php');

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';

ob_end_clean();
// Set the variable with the price value
$plt = $PLTUSD; // Replace with the actual value

// Get the current date in UTC
$utcDate = new DateTime('now', new DateTimeZone('UTC'));
$currentDate = $utcDate->format('Y-m-d');
$currentDateTime = $utcDate->format('Y-m-d H:i:s');

// Prepare the query to check if a record for the current day already exists
$query = "SELECT id FROM granna80_bdlinks.token_historical_data WHERE DATE(date) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $currentDate);
$stmt->execute();
$result = $stmt->get_result();

// Check if a record for the current day already exists
if ($result->num_rows > 0) {
    // If the record exists, update the price value
    $updateQuery = "UPDATE granna80_bdlinks.token_historical_data SET price = ?, date = ? WHERE DATE(date) = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('dss', $plt, $currentDateTime, $currentDate);
    $updateStmt->execute();
    echo "Record successfully updated!";
} else {
    // If the record does not exist, insert a new record
    // For simplicity, I'll set dummy values for volume and market_cap
    $volume = 0.0; // Replace with the actual value
    $market_cap = 0.0; // Replace with the actual value

    $insertQuery = "INSERT INTO granna80_bdlinks.token_historical_data (date, price, volume, market_cap) VALUES (?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param('sddd', $currentDateTime, $plt, $volume, $market_cap);
    $insertStmt->execute();
    echo "New record successfully inserted!";
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
        'market_cap' => round($row['market_cap'], 10)
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
        <a href="add.php">[Add new record]</a>
        <a href="token_data.json">[Json]</a>
        <a href="fetch.php">[Fetch]</a>
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
                        <td><?= number_format($row['volume'], 10, '.', ','); ?></td>
                        <td><?= number_format($row['market_cap'], 10, '.', ','); ?></td>
                        <td><?php echo "<a href='edit.php?id=$id'>Edit</a>"; ?></td>
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