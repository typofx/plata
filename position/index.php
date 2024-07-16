<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liquidity Position</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://www.plata.ie/es/styleMain.css" media="screen">
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            border-style: none;
        }

        /* Styles to center the table */
        .table-container,
        .td {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Adjust as necessary */
        }

        #example th,
        #example td {
            text-align: center;
            /* Center content horizontally */
            vertical-align: middle;
            /* Center content vertically */
        }

        .container {
            display: flex;
            align-items: center;
            gap: 20px;
        }
    </style>
</head>

<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/languages/languages.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/es/desktop-header.php'; ?>

    <div>
        <h2><b>   PLT Liquidity Position</b></h2>
    </div>


        <?php
        ob_start();
        include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
        ob_end_clean();
        ?>

        <?php
        // URLs dos JSONs
        $json_url0 = 'https://plata.ie/plataforma/painel/lp-contracts/lp_contracts.json';
        $json_url1 = 'https://plata.ie/plataforma/painel/order-book/order_book_data.json';

        // Função para buscar e decodificar dados JSON
        function fetch_json_data($url)
        {
            $json_data = file_get_contents($url);
            return json_decode($json_data, true);
        }

        // Fetch data from both URLs
        $contracts0 = fetch_json_data($json_url0);
        $contracts1 = fetch_json_data($json_url1);

        // Merge both contract arrays
        $all_contracts = array_merge($contracts0, $contracts1);

        // Function to sort contracts by liquidity in descending order
        function sort_by_liquidity($a, $b)
        {
            return $b['liquidity'] - $a['liquidity'];
        }

        // Sort the merged contracts by liquidity
        usort($all_contracts, 'sort_by_liquidity');

        $total_liquidity = 0;
        if ($contracts0) {
            foreach ($contracts0 as $contract0) {
                if (isset($contract0['total_liquidity'])) {
                    $liquidity_lp = $contract0['total_liquidity'];
                    $total_liquidity += (float)$liquidity_lp;
                }
            }
        } else {
            echo '<b>Unable to load data from first JSON.</b>';
        }

        if ($contracts1) {
            foreach ($contracts1 as $contract1) {
                if (isset($contract1['total_liquidity'])) {
                    $liquidity_orderbook = $contract1['total_liquidity'];
                    $total_liquidity += (float)$liquidity_orderbook;
                }
            }
        } else {
            echo '<b>Unable to load data from second JSON.</b>';
        }

        $total_liquidity_formatted = number_format($total_liquidity, 4, '.', ',');

        date_default_timezone_set('UTC');
        $lastupdate = date("D, jS F Y H:i:s", $contract1['timestamp']) . " (UTC)";
        ?>

<?php
echo '<div style="margin-bottom: 10px;"><b> Plata Token Price  : ' . $PLTUSD . ' USD</b></div>'
   . '<div style="margin-bottom: 10px;"><b> Market Capitalization : ' . $PLTmarketcap . ' USD</b></div>'
   . '<div style="margin-bottom: 10px;"><b> Total Asset Liquidity : ' . $total_liquidity_formatted . ' USD</b></div>'
   . '<div style="margin-bottom: 10px;"><b> Market-to-Book Ratio  : ' . number_format($total_liquidity/$PLTmarketcap, 2, '.', ' ') . '</b></div>'
   . '<div style="margin-bottom: 10px;"><b> Last Updated on : ' . $lastupdate . '</b></div>';
?>



   

    <br><br><br>

    <table id="example" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pair</th>
                <th>Contract</th>
                <th>Exchange</th>
                <th>Liquidity</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $cont = 1;
            foreach ($all_contracts as $contract) {
                if (isset($contract['id'])) {
                    echo '<tr>';
                    echo '<td>' . $cont  . '</td>';
                    echo '<td>' . htmlspecialchars($contract['pair']) . '</td>';
                    if (filter_var($contract['contract'], FILTER_VALIDATE_URL)) {
                        echo '<td><a href="' . $contract['contract'] . '" target="_blank">' . substr($contract["contract"], 0, 6) . "..." . substr($contract["contract"], -4) . '</a></td>';
                    } else {
                        echo '<td><a href="https://polygonscan.com/address/' . $contract["contract"] . '" target="_blank">' . substr($contract["contract"], 0, 6) . "..." . substr($contract["contract"], -4) . '</a></td>';
                    }
                    echo '<td>' . htmlspecialchars($contract['exchange']) . '</td>';
                    echo '<td><b>' . number_format($contract['liquidity'], 2, '.', ',') . ' USD</b></td>';
                    echo '</tr>';
                    $cont++;
                }
            }
            ?>
        </tbody>
    </table>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.5/sorting/numeric-comma.js"></script>
    <script>
        jQuery.extend(jQuery.fn.dataTableExt.oSort, {
            "numeric-comma-pre": function(a) {
                var x = a.replace(/[^\d,.]/g, '');
                x = x.replace(/,/g, '');
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
                [50, 100, 150, 200, -1],
                [50, 100, 150, 200, "All"]
            ],
                "columnDefs": [{
                    "type": "numeric-comma",
                    "targets": [4]
                }],
                "order": [
                    [4, "desc"]
                ]
            });
        });
    </script>


    <?php include $_SERVER['DOCUMENT_ROOT'] . '/es/desktop-footer.php'; ?>

</body>

</html>