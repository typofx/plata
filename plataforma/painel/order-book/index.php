<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();
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
            border: 1px solid #ddd;
            padding: 8px;
        }

        .highlight {
            background-color: yellow;
            color: black;
            padding: 2px 4px;
            border-radius: 3px;
        }

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

    // SQL query to get data from the `order_book` table
    $sql = "SELECT id, name, url, pair_contract FROM granna80_bdlinks.order_book";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Start HTML table
        echo "<table id='example' class='display'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                             <th>Liquidity</th>
                             <th>Pair Contract</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";

        // Iterate over results and fill the table
        while ($row = $result->fetch_assoc()) {
            $name = $row["name"];
            $jsonUrl = $row['url'];

            try {
                // Fetch the JSON from the URL
                $json = file_get_contents($jsonUrl);
                if ($json === false) {
                    throw new Exception("Failed to fetch JSON from URL: $jsonUrl");
                }

                // Decode the JSON into an associative array
                $data = json_decode($json, true);

                // Normalize the asks from the JSON
                $asks = isset($data['asks']) ? normalizeAsks($data['asks']) : [];
                $bids = isset($data['bids']) ? normalizeAsks($data['bids']) : [];

                // Initialize variables for sum
                $totalPrice = 0;
                $totalAmount = 0;
                $totalPriceBids = 0;
                $totalAmountBids = 0;

                // Calculate the sum of each column for bids
                foreach ($bids as $bid) {
                    $totalPriceBids += $bid['price'] * $bid['amount']; // Sum of products of prices and amounts
                }

                // Calculate the sum of each column for asks
                foreach ($asks as $ask) {
                    $totalPrice += $ask['price']; // Sum of prices
                    $totalAmount += $ask['amount']; // Sum of amounts
                }

                $totalPLTUSD = ($PLTUSD * $totalAmount);
                $liquidity = $totalPLTUSD + $totalPriceBids;

            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
                die();
            }

            echo "<tr>
            <td>" . $row["id"] . "</td>
            <td><a href='https://plata.ie/sandbox/balance/cex-price.php?name=" . $row["name"] . "'>" . $row["name"] . "</a></td>
            <td>$ " . number_format($liquidity, 2, '.', ',') . " USD</td>
             <td>" . $row["pair_contract"] . "</td>
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

    // Function to normalize asks to the object format { "price": ..., "amount": ... }
    function normalizeAsks($asks)
    {
        $normalizedAsks = [];
        foreach ($asks as $ask) {
            if (isset($ask['price']) && isset($ask['amount'])) {
                // If it's an object with keys price and amount
                $normalizedAsks[] = [
                    'price' => convertScientificToFloat($ask['price']), // Convert scientific notation to float
                    'amount' => $ask['amount']
                ];
            } elseif (is_array($ask) && count($ask) === 2) {
                // If it's an array with two elements
                $normalizedAsks[] = [
                    'price' => convertScientificToFloat($ask[0]), // Convert scientific notation to float
                    'amount' => $ask[1]
                ];
            }
        }
        return $normalizedAsks;
    }

    // Function to convert scientific notation to float
    function convertScientificToFloat($value)
    {
        return floatval($value); // Convert to float
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
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
