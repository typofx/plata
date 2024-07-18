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
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/lp-contracts/assets.php';
ob_end_clean();

include 'conexao.php';

// SQL query to get data from the `order_book` table
$sql = "SELECT * FROM granna80_bdlinks.order_book";
$result = $conn->query($sql);

$orderBookData = [];
$totalLiquidity = 0;

if ($result->num_rows >= 0) {
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

            // Determine which normalization function to use based on the URL
            if (strpos($jsonUrl, 'https://fcex.trade') === 0) {
                $asks = isset($data['asks']) ? normalizeFinanceXAsks($data['asks']) : [];
                $bids = isset($data['bids']) ? normalizeFinanceXBids($data['bids']) : [];
            } else {
                $asks = isset($data['asks']) ? normalizeAsks($data['asks']) : [];
                $bids = isset($data['bids']) ? normalizeBids($data['bids']) : [];
            }

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

            if ($row['claimed'] == 1) {
                $totalAmount = $row['value'];
                $totalPriceBids = $row['value2'];

                $totalPLTUSD = ($PLTUSD * $totalAmount);
                $liquidity = $totalPLTUSD + $totalPriceBids;
                $totalLiquidity += $liquidity;

                // Round liquidity to 5 decimal places
                $liquidity = round($liquidity, 5);
            } else {

                $totalPLTUSD = ($PLTUSD * $totalAmount);
                $liquidity = $totalPLTUSD + $totalPriceBids;
                $totalLiquidity += $liquidity;

                // Round liquidity to 5 decimal places
                $liquidity = round($liquidity, 5);
            }

            //A
            $contract_asset_a_lower = strtolower($row['pair_contractA']);

            // Loop through prices
            $prices_lower = array_change_key_case($prices, CASE_LOWER);

            // Check if it is present in the prices
            if (isset($prices_lower[$contract_asset_a_lower])) {
                $contract_price_a = $prices_lower[$contract_asset_a_lower];
                // echo $contract_price_a;
                if ($contract_asset_a_lower === '0xc298812164bd558268f51cc6e3b8b5daaf0b6341') {
                    // Format the number to display with 10 decimal places for this specific contract

                    number_format($contract_price_a, 10, '.', '');
                } else {
                    // Displays the number normally with 2 decimal places for other contracts

                    number_format($contract_price_a, 2, '.', ',');
                }
            } else {
                echo '0'; // If there is no match, display 0
            }
            $qtA =  ($totalAmount * $contract_price_a);
            //echo number_format($qtA, 2, '.', ',');

            //B
            $contract_asset_b_lower = strtolower($row["pair_contractB"]);

            // Loop through prices
            $prices_lower_b = array_change_key_case($prices, CASE_LOWER);

            // Check if it is present in the prices
            if (isset($prices_lower_b[$contract_asset_b_lower])) {
                $contract_price_b = $prices_lower_b[$contract_asset_b_lower];

                if ($contract_asset_b_lower === '0xc298812164bd558268f51cc6e3b8b5daaf0b6341') {
                    // Format the number to display with 10 decimal places for this specific contract
                    number_format($contract_price_b, 10, '.', '');
                } else {
                    // Displays the number normally with 2 decimal places for other contracts
                    number_format($contract_price_b, 2, '.', ',');
                }
            } else {
                echo '0'; // If there is no match, display 0
            }

            $qtB = ($totalPriceBids * $contract_price_b);


            $final_liquidity = ($totalAmount  * $contract_price_a) + ($totalPriceBids * $contract_price_b);

            

            $total_final_liquidity += $final_liquidity;

            //echo $liquidity .'<br>';
            // Add row data to the orderBookData array
            $orderBookData[] = array(
                'id' => (int)$row["id"],
                'pair' => $row["pair_contract1"] . '/' . $row["pair_contract2"],
                'contract' => $row["link_contract"],
                'exchange' => $name,
                'liquidity' => round($final_liquidity,5)
            );

            $tableData[] = array(
                'id' => (int)$row["id"],
                'exchange' => $name,
                'claimed_totalPrice' => $totalAmount ?? '',
                'claimed_totalPriceBids' => $totalPriceBids ?? '',
                'liquidity' => $liquidity,
                'contract_1' => $row["pair_contract1"],
                'contract_2' => $row["pair_contract2"],
                'contract_a' => $row["pair_contractA"],
                'contract_b' => $row["pair_contractB"],
                'pair' => $row["pair_contract1"] . '/' . $row["pair_contract2"],
                'contract' => $row["link_contract"]
            );
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            die();
        }
    }

    $orderBookData[] = array(
        "total_liquidity" => round($total_final_liquidity,5),
        "timestamp" => time()
    );
}

// Encode the order book data to JSON
$json_data = json_encode($orderBookData);

// Replace escaped slashes with regular slashes
$json_data = str_replace("\\/", "/", $json_data);

// Save the JSON data to a file
$jsonFilePath = 'order_book_data.json';
file_put_contents($jsonFilePath, $json_data);


$conn->close();

// Function to normalize asks for FinanceX to the object format { "price": ..., "amount": ... }
function normalizeFinanceXAsks($asks)
{
    $normalizedAsks = [];
    foreach ($asks as $ask) {
        $normalizedAsks[] = [
            'price' => floatval($ask['price']),
            'amount' => floatval($ask['origin_volume'])
        ];
    }
    return $normalizedAsks;
}

// Function to normalize bids for FinanceX to the object format { "price": ..., "amount": ... }
function normalizeFinanceXBids($bids)
{
    $normalizedBids = [];
    foreach ($bids as $bid) {
        $normalizedBids[] = [
            'price' => floatval($bid['price']),
            'amount' => floatval($bid['origin_volume'])
        ];
    }
    return $normalizedBids;
}

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

// Function to normalize bids to the object format { "price": ..., "amount": ... }
function normalizeBids($bids)
{
    $normalizedBids = [];
    foreach ($bids as $bid) {
        if (isset($bid['price']) && isset($bid['amount'])) {
            // If it's an object with keys price and amount
            $normalizedBids[] = [
                'price' => convertScientificToFloat($bid['price']), // Convert scientific notation to float
                'amount' => $bid['amount']
            ];
        } elseif (is_array($bid) && count($bid) === 2) {
            // If it's an array with two elements
            $normalizedBids[] = [
                'price' => convertScientificToFloat($bid[0]), // Convert scientific notation to float
                'amount' => $bid[1]
            ];
        }
    }
    return $normalizedBids;
}

// Function to convert scientific notation to float
function convertScientificToFloat($value)
{
    return floatval($value); // Convert to float
}
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
        <a href="add.php">[Add New Record]</a>
        <a href="https://plata.ie/plataforma/painel/order-book/order_book_data.json" target="_blank">[JSON File]</a>
    </div>

    <?php
    if (!empty($tableData)) {
        // Start HTML table
        echo "<table id='example' class='display'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Asset A</th>
                        <th>Price A</th>
                        <th>qtA</th>
                        <th>Asset B</th>
                        <th>Price B</th>
                        <th>qtB</th>
                        <th>Liquidity</th>
                    
                        <th>Pair Contract</th>
                        <th>Link Contract</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";

        // Iterate over the orderBookData array and fill the table
        foreach ($tableData as $row) {
            if (isset($row["id"])) {
                echo "<tr>
                <td>" . $row["id"] . "</td>
                <td><a href='https://plata.ie/sandbox/cex/cex-price.php?id=" . $row["id"] . "&name=" . urlencode($row["exchange"]) . "'>" . $row["exchange"] . "</a></td>
                <td> " . $row['claimed_totalPrice'] . "</td>";


                $contract_asset_a_lower = strtolower($row['contract_a']);

                // Loop through prices
                $prices_lower = array_change_key_case($prices, CASE_LOWER);

                // Check if it is present in the prices
                if (isset($prices_lower[$contract_asset_a_lower])) {
                    $contract_price_a = $prices_lower[$contract_asset_a_lower];
                    // echo $contract_price_a;
                    if ($contract_asset_a_lower === '0xc298812164bd558268f51cc6e3b8b5daaf0b6341') {
                        // Format the number to display with 10 decimal places for this specific contract

                        echo " <td> " . number_format($contract_price_a, 10, '.', '') . "</td>";
                    } else {
                        // Displays the number normally with 2 decimal places for other contracts

                        echo " <td> " . number_format($contract_price_a, 2, '.', ',') . "</td>";
                    }
                } else {
                    echo '0'; // If there is no match, display 0
                }
                $qtA =  ($row['claimed_totalPrice']   * $contract_price_a);
                echo "<td> " . number_format($qtA, 2, '.', ',') . "</td>
                 <td> " . $row['claimed_totalPriceBids'] . "</td>";

                $contract_asset_b_lower = strtolower($row["contract_b"]);

                // Loop through prices
                $prices_lower_b = array_change_key_case($prices, CASE_LOWER);

                // Check if it is present in the prices
                if (isset($prices_lower_b[$contract_asset_b_lower])) {
                    $contract_price_b = $prices_lower_b[$contract_asset_b_lower];

                    if ($contract_asset_b_lower === '0xc298812164bd558268f51cc6e3b8b5daaf0b6341') {
                        // Format the number to display with 10 decimal places for this specific contract
                        echo " <td> " . number_format($contract_price_b, 10, '.', '') . "</td>";
                    } else {
                        // Displays the number normally with 2 decimal places for other contracts
                        echo " <td> " . number_format($contract_price_b, 2, '.', ',') . "</td>";
                    }
                } else {
                    echo '0'; // If there is no match, display 0
                }

                $qtB = ($row['claimed_totalPriceBids'] * $contract_price_b);


                $final_liquidity = ($row['claimed_totalPrice']   * $contract_price_a) + ($row['claimed_totalPriceBids'] * $contract_price_b);
                echo "<td> " . number_format($qtB, 2, '.', ',') . "</td>

                 
                <td>$ " . number_format($final_liquidity, 2, '.', ',') . " USD</td>

                <td>" . $row["pair"] . "</td>
                <td>" . $row["contract"] . "</td>
                <td>
                    <a href='edit.php?id=" . $row["id"] . "'><i class='fa-solid fa-pen-to-square'></i></a>
                </td>
              </tr>";
            }
        }

        echo "</tbody></table>";
    } else {
        echo "0 results";
    }
    ?>
    <!-- DataTables JS -->
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
