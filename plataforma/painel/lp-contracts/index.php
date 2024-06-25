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
        <a href="add.php">Add new record</a>
<?php echo 'MATIC/USDT ' . number_format($MATICUSD, 5, '.', ',') . ' USD⠀⠀⠀⠀' ?>

<?php echo 'PLT/USDT ' . number_format($PLTUSD , 8, '.', ',') . ' USD⠀⠀⠀⠀' ?>
<?php 
function format_currency($value) {
    $value = str_replace(',', '', $value);
    return number_format((float)$value, 3);
}

//echo 'BTC/USDT ' . format_currency($BTCUSD) . ' USD⠀⠀⠀⠀';

//echo 'ETH/USDT ' . format_currency($ETHUSD) . ' USD⠀⠀⠀⠀';
echo '<br>';
echo 'WBTC/USDT ' . format_currency($WBTCUSD) . ' USD⠀⠀⠀⠀';

echo 'WETH/USDT ' . format_currency($WETHUSD) . ' USD⠀⠀⠀⠀';
?>



    </div>
    <?php
    include 'conexao.php';

    // SQL query to get data from the `payments` table
    $sql = "SELECT id, name, contract, asset_a, asset_b, contract_asset_a, contract_asset_b, liquidity FROM granna80_bdlinks.lp_contracts";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Start HTML table 
        echo "<table id='example' class='display'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pair</th>
                        <th>Contract</th>
                        <!-- <th>Asset A</th> -->
                        <!-- <th>Asset B</th> -->
                        <!-- <th>Contract Asset B</th> -->
                        <th>Liquidity</th>
                   <th>Actions</th>
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
            <td>" . $row["contract"] . "</td>
            
            
            
            <td>";          // Get ERC20 token balance and decimals <td>" . $row["contract_asset_b"] . "</td> <td>" . $row["asset_a"] . "</td> <td>" . $row["asset_b"] . "</td>
            
            getTokenInfo($web3, $walletAddress, $tokenContract_A, $tokenBalance_A, $tokenDecimals);

            $tokenBalance_A  = floatval( htmlspecialchars($tokenBalance_A) );
            $tokenADecimal = intval( htmlspecialchars($tokenDecimals) );
            $tokenBalance_A = $tokenBalance_A / ( 10** $tokenADecimal);
            
            getTokenInfo($web3, $walletAddress, $tokenContract_B, $tokenBalance_B, $tokenDecimals);
            
            $tokenBalance_B  = floatval( htmlspecialchars($tokenBalance_B) );
            $tokenBDecimal = intval( htmlspecialchars($tokenDecimals) );
            $tokenBalance_B = $tokenBalance_B / ( 10** $tokenBDecimal);

            
            //$tokenBalance_B = $tokenBalance_B / (10 ^ $tokenDecimals);
            
            // Wait to ensure all asynchronous calls are completed before displaying results
            sleep(1);

             //Display results
            //echo '<h3>Results</h3>';
            //echo '<p>Wallet Address: ' . htmlspecialchars($walletAddress) . '</p>';
            //echo '<p>Token Contract A: ' . htmlspecialchars($tokenContract_A) . '</p>';
            //echo '<p>Token Contract B: ' . htmlspecialchars($tokenContract_B) . '</p>';
            //echo '<p>A Decimal: ' . $tokenADecimal . '</p>';
            //echo '<p>B Decimal: ' . $tokenDecimals . '</p>';
            //echo '<p>A Balance: ' . $tokenBalance_A . '</p>';
            //echo '<p>B Balance: ' . $tokenBalance_B . '</p>';
            //echo '<p>B Balance: ' . htmlspecialchars($tokenBalance_B / (10 ^ $tokenDecimals)) . '</p>';

            if ($tokenBalance_A !== null && $tokenDecimals !== null) {
                //$tokenDecimalsStr = (int) $tokenDecimals->toString();

                //$tokenBalanceFormatted = bcdiv($tokenBalance_A, bcpow(10, $tokenDecimalsStr), $tokenDecimalsStr); WHAT DOES IT DO

                //$formattedBalance = number_format((float) $tokenBalanceFormatted, $tokenDecimalsStr, '.', ',');

                $FinalBalance = ( ($PLTUSD * $tokenBalance_A) + (1 * $tokenBalance_B ) );
                $ConvertedBalance = $FinalBalance / 10000;
                echo '<b>' . number_format($FinalBalance, 2, '.', ','). ' USD</b>';
              //  echo '<br>';
               // echo '<b>' . $formattedBalance. '</b>';
            } else {
                echo '<p>Token balance or decimals not available.</p>';
            }

            echo "</td>";



            echo "   <td>
                <a href='edit.php?id=" . $row["id"] . "'><i class='fa-solid fa-pen-to-square'></i></a>
                <a href='#' onclick='confirmDelete(" . $row["id"] . ")'><i style='color: red;' class='fa-solid fa-trash'></i></a>
            </td>
        </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>
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