<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contracts Data</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
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

    <h2>Contracts Data</h2>

    <div class="container">
        <?php
        $json_url0 = 'https://plata.ie/plataforma/painel/lp-contracts/lp_contracts.json';
        $json_data0 = file_get_contents($json_url0);
        $contracts0 = json_decode($json_data0, true);

        if ($contracts0) {
            foreach ($contracts0 as $contract0) {
                // Check if contract has 'total_liquidity' key
                if (isset($contract0['total_liquidity'])) {
                    echo '<b>Total Liquidity: ' . number_format($contract0['total_liquidity'], 2, '.', ',') . ' USD</b><br>';
                    echo '<b>Timestamp: ' . $contract0['timestamp'] . '</b><br>';
                }
            }
        } else {
            echo '<b>Unable to load data.</b>';
        }
        ?>
    </div>

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
            $json_url = 'https://plata.ie/plataforma/painel/lp-contracts/lp_contracts.json';
            $json_data = file_get_contents($json_url);
            $contracts = json_decode($json_data, true);

            if ($contracts) {
                foreach ($contracts as $contract) {
                    // Check if contract has 'id' key
                    if (isset($contract['id'])) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($contract['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($contract['pair']) . '</td>';
                        echo '<td><a href="https://polygonscan.com/address/' . $contract["contract"] . '">' . substr($contract["contract"], 0, 6) . "..." . substr($contract["contract"], -4) . '</a></td>';
                        echo '<td>' . htmlspecialchars($contract['exchange']) . '</td>';
                        echo '<td><b>' . number_format($contract['liquidity'], 2, '.', ',') . ' USD</b></td>';
                        echo '</tr>';
                    }
                }
            } else {
                echo '<tr><td colspan="5">Unable to load data.</td></tr>';
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
                // Remove all non-numeric characters except commas and dots
                var x = a.replace(/[^\d,.]/g, '');
                // Replace commas with nothing to treat as number
                x = x.replace(/,/g, '');
                // Convert to float
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
                    "targets": [4]
                }],
                "order": [
                    [4, "desc"]
                ]
            });
        });
    </script>

</body>

</html>
