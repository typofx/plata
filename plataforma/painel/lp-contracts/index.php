<?php

ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../index.php");
    exit();
}
?>

<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/lp-contracts/assets.php';
ob_end_clean();

include 'search.php'; ?>
<!DOCTYPE html>
<html lang="en">

<style>
    body {
        font-family: 'Courier New', monospace;
        font-size: 14px;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
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
        }
    </style>
</head>

<body>
    <h1>LP Contracts</h1>

    <div class="container">
        <a href="https://plata.ie/plataforma/painel/menu.php">[Main Menu]</a>
        <a href="javascript:window.location.reload(true)">[Refresh]</a>
        <a href="add.php">[Add New Record]</a>
        <?php echo 'MATIC/USDT ' . number_format($MATICUSD, 5, '.', ',') . ' USD⠀⠀⠀⠀' ?>

        <?php echo 'PLT/USDT ' . number_format($PLTUSD, 10, '.', ',') . ' USD⠀⠀⠀⠀' ?>
        <?php
        function format_currency($value)
        {
            $value = str_replace(',', '', $value);
            return number_format((float)$value, 3);
        }

        echo '<br>';
        echo 'WBTC/USDT ' . format_currency($BTCUSD) . ' USD⠀⠀⠀⠀';
        echo 'WETH/USDT ' . format_currency($ETHUSD) . ' USD⠀⠀⠀⠀';
        ?>
    </div>
    <?php
    include 'conexao.php';
    $totalFinalBalance = 0;
    // SQL query to get data from the `payments` table
    $sql = "SELECT id, name, contract, asset_a, asset_b, contract_asset_a, contract_asset_b, liquidity, exchange FROM granna80_bdlinks.lp_contracts";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Start HTML table 
        echo "<table id='example' class='display'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pair</th>
                        <th>Contract</th>
                        <th>Exchange</th>
                        <th>Asset A</th>
                        <th>Price A</th>
                        <th>qtA</th>
                         <th>Asset B</th>
                        <th>Price B</th>
                        <th>qtB</th>
                      
                         <th>Liquidity</th>
                       
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";

        // Iterate over results and fill the table
        while ($row = $result->fetch_assoc()) {

            $walletAddress =  $row["contract"];
            $tokenContract_A = $row["contract_asset_a"];
            $tokenContract_B = $row["contract_asset_b"];

            echo "<tr>
            <td>" . $row["id"] . "</td>
            <td>" . $row["asset_a"] . "/" . $row["asset_b"] . "</td>
             <td><a href='https://polygonscan.com/address/" . $row["contract"] . "'>" . substr($row["contract"], 0, 6) . "..." . substr($row["contract"], -4) . "</a></td>
            <td>" . $row["exchange"] . "</td>";

            getTokenInfo($web3, $walletAddress, $tokenContract_A, $tokenBalance_A, $tokenDecimals);

            $tokenBalance_A  = floatval(htmlspecialchars($tokenBalance_A));
            $tokenADecimal = intval(htmlspecialchars($tokenDecimals));
            $tokenBalance_A_unformated = $tokenBalance_A / (10 ** $tokenADecimal);
            if ($tokenADecimal > 8) $tokenADecimal = 8;
            $tokenBalance_A = number_format($tokenBalance_A_unformated, $tokenADecimal, '.', ',');

            getTokenInfo($web3, $walletAddress, $tokenContract_B, $tokenBalance_B, $tokenDecimals);

            $tokenBalance_B  = floatval(htmlspecialchars($tokenBalance_B));
            $tokenBDecimal = intval(htmlspecialchars($tokenDecimals));
            $tokenBalance_B_unformated = $tokenBalance_B / (10 ** $tokenBDecimal);

            if ($tokenBDecimal > 8) $tokenBDecimal = 8;
            $tokenBalance_B = number_format($tokenBalance_B_unformated, $tokenBDecimal, '.', ',');

            sleep(1);

            echo "    <td style='text-align: right;'>" . $tokenBalance_A . " </td>";
            // echo "    <td>" . $row["asset_a"] . " </td>";


            echo "<td>";

            $contract_asset_a_lower = strtolower($row["contract_asset_a"]);

            // Loop through prices
            $prices_lower = array_change_key_case($prices, CASE_LOWER);

            // Check if it is present in the prices
            if (isset($prices_lower[$contract_asset_a_lower])) {
                $contract_price_a = $prices_lower[$contract_asset_a_lower];

                if ($contract_asset_a_lower === '0xc298812164bd558268f51cc6e3b8b5daaf0b6341') {
                    // Format the number to display with 10 decimal places for this specific contract
                    echo number_format($contract_price_a, 10, '.', '') . '';
                } else {
                    // Displays the number normally with 2 decimal places for other contracts
                    echo number_format($contract_price_a, 2, '.', ',') . '';
                }
            } else {
                echo '0'; // If there is no match, display 0
            }

            echo "</td>";

           

            $qtA = $tokenBalance_A_unformated * $contract_price_a;

            echo "<td>" . number_format($qtA, 2, '.', ',') . '' .  "</td>";

            /////////////////////////////////B////////////////////////////

            echo "    <td style='text-align: right;'>" . $tokenBalance_B . " </td>";
            // echo "    <td>" . $row["asset_b"] . " </td>";


            echo "<td>";
            $contract_asset_b_lower = strtolower($row["contract_asset_b"]);

            // Loop through prices
            $prices_lower_b = array_change_key_case($prices, CASE_LOWER);

            // Check if it is present in the prices
            if (isset($prices_lower_b[$contract_asset_b_lower])) {
                $contract_price_b = $prices_lower_b[$contract_asset_b_lower];

                if ($contract_asset_b_lower === '0xc298812164bd558268f51cc6e3b8b5daaf0b6341') {
                    // Format the number to display with 10 decimal places for this specific contract
                    echo number_format($contract_price_b, 10, '.', '') . '';
                } else {
                    // Displays the number normally with 2 decimal places for other contracts
                    echo number_format($contract_price_b, 2, '.', ',') . '';
                }
            } else {
                echo '0'; // If there is no match, display 0
            }

            echo "</td>";

            $qtB = $tokenBalance_B_unformated * $contract_price_b;


            echo "<td>" .  number_format($qtB, 2, '.', ',')  .  "</td>";


/////////////////////////////////////////////////////////////////////

            if ($tokenBalance_A !== null && $tokenDecimals !== null) {

                $FinalBalance = (($tokenBalance_A_unformated * $contract_price_a) + ($tokenBalance_B_unformated * $contract_price_b));
                echo '<td style="text-align: right;"><b>' . number_format($FinalBalance, 2, '.', ',') . ' USD</b></td>';
                $sqlInsert = "UPDATE granna80_bdlinks.lp_contracts SET liquidity = '$FinalBalance' WHERE id = " . $row['id'];

                if ($conn->query($sqlInsert) === TRUE) {
                   // echo "Liquidity updated successfully";
                } else {
                   // echo "Error updating liquidity: " . $conn->error;
                }

                $totalFinalBalance += $FinalBalance;
            } else {
                echo '<p>Token balance or decimals not available.</p>';
            }



           //echo "<td>" . number_format($liquidityFinal, 2, '.', ',') . ' USD' . "</td>";

            //////////////////////////////

            echo "   <td>
                <a href='edit.php?id=" . $row["id"] . "'><i class='fa-solid fa-pen-to-square'></i></a>
                <a href='#' onclick='confirmDelete(" . $row["id"] . ")'><i style='color: red;' class='fa-solid fa-trash'></i></a>
            </td>
        </tr>";
        }

        echo "</tbody></table>";
        echo "<tr> ⠀⠀⠀⠀</tr>";
        echo "<tr>⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀</tr>";
        echo "<tr><h3> Total : " . number_format($totalFinalBalance, 4, '.', ',') . " USD</h3></tr>";
        echo "Timestamp: " . gmdate("Y-m-d\TH:i:s\Z");
        echo "<tr>⠀⠀⠀⠀ </tr>";
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>

    <?php
    // Include the connection file
    include 'conexao.php';

    // Path to the JSON file
    $jsonFilePath = 'lp_contracts.json';

    // Call the function to generate the JSON file
    generateJsonFile($jsonFilePath);

    // Function to generate the JSON file
  
    
    function generateJsonFile($filePath) {
        // Initialize an empty array for liquidity contracts
        $lpContracts = array();
    
        // SQL to get data from the `lp_contracts` table
        global $conn; // Making the connection available inside the function
        $sql = "SELECT id, name, contract, asset_a, asset_b, contract_asset_a, contract_asset_b, liquidity, exchange FROM granna80_bdlinks.lp_contracts";
        $result = $conn->query($sql);
        $totalLiquidity = 0;
        if ($result->num_rows > 0) {
    
            while ($row = $result->fetch_assoc()) {
                // Check if values are empty and set to 0 if they are
                $id = !empty($row["id"]) ? (int)$row["id"] : 0;
                $pair = !empty($row["asset_a"]) && !empty($row["asset_b"]) ? $row["asset_a"] . "/" . $row["asset_b"] : "0/0";
                $contract = !empty($row["contract"]) ? $row["contract"] : "0";
                $exchange = !empty($row["exchange"]) ? $row["exchange"] : "0";
                $liquidity = !empty($row["liquidity"]) ?  $row["liquidity"] : 0.0;
                $liquidity = (float)$liquidity;
                $liquidity =  round($liquidity,5);
    
                // Add each liquidity contract to the array
                $lpContracts[] = array(
                    "id" => $id,
                    "pair" => $pair,
                    "contract" => $contract,
                    "exchange" => $exchange,
                    "liquidity" => $liquidity
                );
                $totalLiquidity += $liquidity;
            }
     
         
    
            $lpContracts[] = array(
                "total_liquidity" => $totalLiquidity,
                "timestamp" => gmdate("Y-m-d\TH:i:s\Z")
            );
    
            // Convert the array to JSON with numbers correctly handled
            $json_data = json_encode($lpContracts);
    
            // Replace escaped slashes with normal slashes
            $json_data = str_replace("\\/", "/", $json_data);
    
            // Save the JSON to a file
            if (file_put_contents($filePath, $json_data)) {
                echo "JSON file updated successfully.";
            } else {
                echo "Error saving data to file.";
            }
        } else {
            echo "No records found.";
        }
    
        // Close the database connection
        $conn->close();
    }
    ?>

    <a href="lp_contracts.json" target="_blank">LP-CONTRACTS-JSON</a>

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
    <script src="https://cdn.datatables.net/plug-ins/1.11.5/sorting/numeric-comma.js"></script>
    <script>
        jQuery.extend(jQuery.fn.dataTableExt.oSort, {
            "numeric-comma-pre": function(a) {
                // Remove todos os caracteres não numéricos exceto vírgulas e pontos
                var x = a.replace(/[^\d,.]/g, '');
                // Substitui vírgulas por nada para tratar como número
                x = x.replace(/,/g, '');
                // Converte para float
                return parseFloat(x);
            },
            "numeric-comma-asc": function(a, b) {
                return a - b;
            },
            "numeric-comma-desc": function(a, b) {
                return b - a;
            }
        });

        $(document).ready(function() {
            $('#example').dataTable({
                "lengthMenu": [
                    [25, 50, 75, -1],
                    [25, 50, 75, "All"]
                ],
                "pageLength": 50,
                "columnDefs": [{
                    "type": "numeric-comma",
                    "targets": [10,9,8,7,6,5,4]
                }],
                "order": [
                    [10, "desc"]
                ]
            });
        });
    </script>


</body>

</html>
