<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php
include 'search.php';

$prices = [];
foreach (json_decode(file_get_contents('prices.json'), true) as $asset) {
    $prices[strtolower($asset['contract'])] = $asset['price'];
}
$PLTUSD = (float) json_decode(file_get_contents('https://www.typofx.ie/.scr/library/json/plata.token/USD.json'), true)['pc'];
$prices['0xc298812164bd558268f51cc6e3b8b5daaf0b6341'] = $PLTUSD;

$POLUSD = (float) json_decode(file_get_contents('https://www.typofx.ie/.scr/library/json/polygon/USD.json'), true)['pc'];
$prices['0x0000000000000000000000000000000000001010'] = $POLUSD;

$WPOLUSD = (float) json_decode(file_get_contents('https://www.typofx.ie/.scr/library/json/polygon/USD.json'), true)['pc'];
$prices['0x0d500b1d8e8ef31e21c99d1db9a6444d3adf1270'] = $WPOLUSD;

?>

<!DOCTYPE html>
<html lang="en">

<style>
    body {
        font-family: monospace;
        padding: 2px;
        padding-right: 250px;
        background-color: #fff;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LPC - Liquidity Pools Contracts</title>
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
        <a href="/plataforma/panel/">[Back]</a>
        <a href="https://typofx.ie/plataforma/panel/lp-contracts/chain/">[Chains]</a>
        <a href="https://typofx.ie/plataforma/panel/dex-liquidity/">[Dex]</a>
        <a href="javascript:window.location.reload(true)">[Refresh]</a>
        <a href="add.php">[Add New Record]</a>

        <?php echo 'POLUSD ' . number_format($POLUSD, 8, '.', ',') . '   '?>
        <?php echo 'PLTUSD ' . number_format($PLTUSD, 8, '.', ',') . '    '?>
    </div>
    <?php
    include 'conexao.php';
    $totalFinalBalance = 0;
    $calculatedValues = [];

    $dexs_sql = "SELECT id, name, router_address FROM granna80_bdlinks.dex_liquidity WHERE type = 'dex'";
    $dexs_result = $conn->query($dexs_sql);
    $dexs = [];
    if ($dexs_result->num_rows > 0) {
        while ($dex_row = $dexs_result->fetch_assoc()) {
            $dexs[$dex_row['id']] = [
                'name' => $dex_row['name'],
                'router' => $dex_row['router_address']
            ];
        }
    }

    $sql = "SELECT id, name, contract, asset_a, asset_b, contract_asset_a, contract_asset_b, liquidity, exchange, router, chain, visibletopublic FROM granna80_bdlinks.lp_contracts";

    $result = $conn->query($sql);

    $chainsSql = "SELECT id, networkName, rpcUrl FROM granna80_bdlinks.networks";
    $chainsResult = $conn->query($chainsSql);
    $chains = array();

    if ($chainsResult->num_rows > 0) {
        while ($chainRow = $chainsResult->fetch_assoc()) {
            $chains[$chainRow['id']] = $chainRow['networkName'];
            $chainsUrl[$chainRow['id']] = $chainRow['rpcUrl'];
        }
    }

    if ($result->num_rows > 0) {
        echo "<table id='example' class='display'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pair</th>
                <th>Contract</th>
                <th>&nbsp;&nbsp;Exchange&nbsp;&nbsp;</th>
                <th>Visible</th>
                <th>Asset A</th>
                <th>Rate A</th>
                <th>qtA</th>
                <th>Asset B</th>
                <th>Rate B</th>
                <th>qtB</th>
                <th>Liquidity&nbsp;($)</th>
                <th>Opt</th>
            </tr>
        </thead>
        <tbody>";

        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $tokenInfos = getTokenInfos($rows);
        $lpV2 = [];

        foreach ($rows as $row) {

            $chainId = $row["chain"];
            $chainName = isset($chains[$chainId]) ? $chains[$chainId] : '';
            $chainRpc = $chainsUrl[$chainId] ?? '';

            $exchangeName = '';
            $routerAddress = '';
            if (!empty($row["exchange"]) && isset($dexs[$row["exchange"]])) {
                $exchangeName = $dexs[$row["exchange"]]['name'];
                $routerAddress = $dexs[$row["exchange"]]['router'];
            }
            if (!empty($routerAddress)) {
                $updateStmt = $conn->prepare("UPDATE granna80_bdlinks.lp_contracts SET router = ? WHERE id = ?");
                $updateStmt->bind_param("si", $routerAddress, $row["id"]);
                $updateStmt->execute();
            }

            echo "<tr>
            <td>" . $row["id"] . "</td>
            <td>" . $row["asset_a"] . "/" . $row["asset_b"] . "</td>
            <td><a href='https://polygonscan.com/address/" . $row["contract"] . "' target='_blank'>" . substr($row["contract"], 0, 6) . "..." . substr($row["contract"], -4) . "</a></td>
            <td>" . htmlspecialchars($exchangeName) . "</td>
            <td>" . ($row["visibletopublic"] ? 'Yes' : 'No') . "</td>";

            [$tokenBalance_A, $tokenADecimal, $tokenBalance_B, $tokenBDecimal] = $tokenInfos[$row['id']];
            $rpcOk = !in_array(null, $tokenInfos[$row['id']], true);

            if ($rpcOk && in_array(trim($exchangeName), ['QuickSwap V2', 'CurveFi'])) {
                $lpV2[strtolower($row['contract'])]['assets'] = [
                    ['contract' => strtolower($row['contract_asset_a']), 'ticker' => $row['asset_a'], 'decimals' => (int)$tokenADecimal],
                    ['contract' => strtolower($row['contract_asset_b']), 'ticker' => $row['asset_b'], 'decimals' => (int)$tokenBDecimal],
                ];
            }

            $tokenBalance_A  = floatval($tokenBalance_A);
            $tokenADecimal = intval($tokenADecimal);
            $tokenBalance_A_unformated = $tokenBalance_A / (10 ** $tokenADecimal);
            if ($tokenADecimal > 8) $tokenADecimal = 8;
            $tokenBalance_A = number_format($tokenBalance_A_unformated, $tokenADecimal, '.', ',');

            $tokenBalance_B  = floatval($tokenBalance_B);
            $tokenBDecimal = intval($tokenBDecimal);
            $tokenBalance_B_unformated = $tokenBalance_B / (10 ** $tokenBDecimal);

            if ($tokenBDecimal > 8) $tokenBDecimal = 8;
            $tokenBalance_B = number_format($tokenBalance_B_unformated, $tokenBDecimal, '.', ',');

            echo "    <td style='text-align: right;'>" . $tokenBalance_A . " </td>";

            echo "<td>";

            $contract_asset_a_lower = strtolower($row["contract_asset_a"]);

            if (isset($prices[$contract_asset_a_lower])) {
                $contract_price_a = $prices[$contract_asset_a_lower];
                echo number_format($contract_price_a, 8, '.', ',');
            } else {
                $contract_price_a = 0;
                echo '0';
            }

            echo "</td>";

            $qtA = $tokenBalance_A_unformated * $contract_price_a;

            echo "<td>" . number_format($qtA, 4, '.', ',') . "</td>";

            echo "    <td style='text-align: right;'>" . $tokenBalance_B . " </td>";

            echo "<td>";
            $contract_asset_b_lower = strtolower($row["contract_asset_b"]);

            if (isset($prices[$contract_asset_b_lower])) {
                $contract_price_b = $prices[$contract_asset_b_lower];
                echo number_format($contract_price_b, 8, '.', ',');
            } else {
                $contract_price_b = 0;
                echo '0';
            }

            echo "</td>";

            $qtB = $tokenBalance_B_unformated * $contract_price_b;

            echo "<td>" .  number_format($qtB, 4, '.', ',')  .  "</td>";

            $calculatedValues[$row['id']] = [
                'tokenBalance_A' => $tokenBalance_A_unformated,
                'tokenBalance_B' => $tokenBalance_B_unformated,
                'priceA' => $contract_price_a,
                'priceB' => $contract_price_b,
                'qtA' => $qtA,
                'qtB' => $qtB
            ];

            if ($rpcOk) {

                $FinalBalance = (($tokenBalance_A_unformated * $contract_price_a) + ($tokenBalance_B_unformated * $contract_price_b));
                echo '<td style="text-align: right;"><b>' . number_format($FinalBalance, 2, '.', ',') . '</b></td>';
                $sqlInsert = "UPDATE granna80_bdlinks.lp_contracts SET liquidity = '$FinalBalance' WHERE id = " . $row['id'];

                $conn->query($sqlInsert);

                $totalFinalBalance += $FinalBalance;
            } else {
                echo '<td>-</td>';
            }

            echo "   <td>
                <a href='edit.php?id=" . $row["id"] . "'><i class='fa-solid fa-pen-to-square'></i></a>
                <a href='#' onclick='confirmDelete(" . $row["id"] . ")'><i style='color: red;' class='fa-solid fa-trash'></i></a>
            </td>
        </tr>";
        }

        echo "</tbody></table>";
        file_put_contents('lp.v2.json', json_encode($lpV2, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        echo "<tr><h3> Total : " . number_format($totalFinalBalance, 4, '.', ',') . " USD</h3></tr>";
        echo "Timestamp: " . gmdate("Y-m-d\TH:i:s\Z");
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>

    <?php
    include 'conexao.php';

    $jsonFilePath = 'lp_contracts.json';

    generateJsonFile($jsonFilePath, $calculatedValues);

    function generateJsonFile($filePath, $calculatedValues)
    {
        global $conn;
        $sql = "SELECT 
                        lc.id, 
                        lc.name, 
                        lc.contract, 
                        lc.asset_a, 
                        lc.asset_b, 
                        lc.contract_asset_a, 
                        lc.contract_asset_b, 
                        lc.liquidity, 
                        dl.name AS exchange,               
                        dl.router_address AS router,        
                        CONCAT(n.networkName, ',', n.rpcUrl) AS chain, 
                        lc.visibletopublic 
                    FROM granna80_bdlinks.lp_contracts lc
                    LEFT JOIN granna80_bdlinks.networks n ON lc.chain = n.id
                    LEFT JOIN granna80_bdlinks.dex_liquidity dl ON lc.exchange = dl.id AND dl.type = 'dex'
                    ORDER BY lc.id ASC";
        $result = $conn->query($sql);
        $totalLiquidity = 0;

        if ($result->num_rows > 0) {
            $lpContracts = array();

            while ($row = $result->fetch_assoc()) {
                $isVisible = isset($row["visibletopublic"]) ? (bool)$row["visibletopublic"] : false;

                $qtA = isset($calculatedValues[$row['id']]['qtA']) ? round($calculatedValues[$row['id']]['qtA'], 2) : null;
                $qtB = isset($calculatedValues[$row['id']]['qtB']) ? round($calculatedValues[$row['id']]['qtB'], 2) : null;

                $priceA = isset($calculatedValues[$row['id']]['priceA']) ? $calculatedValues[$row['id']]['priceA'] : null;
                $priceB = isset($calculatedValues[$row['id']]['priceB']) ? $calculatedValues[$row['id']]['priceB'] : null;

                $tokenBalance_A  = isset($calculatedValues[$row['id']]['tokenBalance_A']) ? $calculatedValues[$row['id']]['tokenBalance_A'] : null;
                $tokenBalance_B  = isset($calculatedValues[$row['id']]['tokenBalance_B']) ? $calculatedValues[$row['id']]['tokenBalance_B'] : null;

                $lpContracts[] = array(
                    "id" => !empty($row["id"]) ? (int)$row["id"] : null,
                    "name" => !empty($row["name"]) ? $row["name"] : null,
                    "pair" => (!empty($row["asset_a"]) && !empty($row["asset_b"])) ?
                        $row["asset_a"] . "/" . $row["asset_b"] : null,
                    "contract_a" => !empty($row["contract_asset_a"]) ? $row["contract_asset_a"] : null,
                    "contract_b" => !empty($row["contract_asset_b"]) ? $row["contract_asset_b"] : null,
                    "contract" => !empty($row["contract"]) ? $row["contract"] : null,
                    "exchange" => !empty($row["exchange"]) ? $row["exchange"] : null,
                    "router" => (isset($row["router"]) && trim($row["router"]) !== '') ? trim($row["router"]) : null,
                    "chain" => !empty($row["chain"]) ? $row["chain"] : null,
                    "visible" => $isVisible,
                    "tokenBalance_A" => $tokenBalance_A,
                    "tokenBalance_B" => $tokenBalance_B,
                    "priceA" => $priceA,
                    "priceB" => $priceB,
                    "QtA" => $qtA,
                    "QtB" => $qtB,
                    "liquidity" => isset($row["liquidity"]) ? round((float)$row["liquidity"], 5) : null
                );

                if ($isVisible && isset($row["liquidity"])) {
                    $totalLiquidity += (float)$row["liquidity"];
                }
            }

            $lpContracts[] = array(
                "total_liquidity" => $totalLiquidity,
                "timestamp" => time()
            );

            $json_data = json_encode($lpContracts);
            $json_data = str_replace("\\/", "/", $json_data);

            if (file_put_contents($filePath, $json_data)) {
                echo "JSON file updated successfully.";
            } else {
                echo "Error saving data to file.";
            }
        } else {
            echo "No records found.";
        }

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

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
                    "targets": [12, 10, 9, 8, 7, 6, 5, 4]
                }],
                "order": [
                    [12, "desc"]
                ]
            });
        });
    </script>

</body>

</html>
