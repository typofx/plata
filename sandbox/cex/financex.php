<?php
//FinanceX
ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();

function fetchJsonFromUrl($url)
{
    $json = file_get_contents($url);
    if ($json === false) {
        throw new Exception("Failed to fetch JSON from URL: $url");
    }
    return $json;
}

function normalizeAsks($asks)
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

function normalizeBids($bids)
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

try {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        echo "No ID provided.";
        exit();
    }

    if (isset($_GET['name'])) {
        $nome = $_GET['name'];
    } else {
        echo "No name provided.";
        exit();
    }

    include 'conexao.php'; // Certifique-se de que este arquivo inclua a conexão com o banco de dados

    $sql = "SELECT id, value, value1, value2, name, url FROM granna80_bdlinks.order_book WHERE id = ? AND name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id, $nome); // Assuming ID is an integer and name is a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $jsonUrl = $row['url'];
            $json = fetchJsonFromUrl($jsonUrl);
            $data = json_decode($json, true);

            $asks = isset($data['asks']) ? normalizeAsks($data['asks']) : [];
            $bids = isset($data['bids']) ? normalizeBids($data['bids']) : [];

            $totalAmountAsks = 0;
            $totalPriceAsks = 0;
            foreach ($asks as $ask) {
                $totalAmountAsks += $ask['amount'];
                $totalPriceAsks += $ask['price'] * $ask['amount'];
            }

            $totalPriceBids = 0;
            foreach ($bids as $bid) {
                $totalPriceBids += $bid['price'] * $bid['amount'];
            }

            // Supõe-se que $PLTUSD esteja definido no arquivo incluído
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinanceX Market Depth</title>
</head>

<body>

    <h3>FinanceX Market Depth</h3>

    <h3>Asks</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $cont = 1; ?>
            <?php foreach ($asks as $ask) : ?>
                <?php if ($cont > 5) break; ?>
                <tr>
                    <td><center><?php echo $cont ?></center></td>
                    <td><center><?php echo number_format($ask['price'], 8); ?></center></td>
                    <td><center><?php echo $ask['amount'] ?></center></td>
                </tr>
                <?php $cont++ ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Bids</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $cont = 1; ?>
            <?php foreach ($bids as $bid) : ?>
                <?php if ($cont > 5) break; ?>
                <tr>
                    <td><center><?php echo $cont ?></center></td>
                    <td><center><?php echo number_format($bid['price'], 8); ?></center></td>
                    <td><center><?php echo $bid['amount'] ?></center></td>
                </tr>
                <?php $cont++ ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>Plata Token (PLT) Price: <?php echo $PLTUSD ?> USDT</p>
    <br>
    <strong>Total Amount ASK (PLT):</strong>
    <strong><?php echo number_format($totalAmountAsks, 4, '.', ','); ?></strong>
    <br>
    <strong>Total ASK (USDT):</strong>
    <?php $totalPLTUSD = $PLTUSD * $totalAmountAsks; ?>
    <strong><?php echo number_format($totalPLTUSD, 2, '.', ','); ?></strong>
    <br>
    <strong>Total BID (USDT):</strong>
    <strong><?php echo number_format($totalPriceBids, 2, '.', ','); ?></strong>
    <br>
    <strong>Liquidity:</strong>
    <?php $liquidity = $totalPLTUSD + $totalPriceBids; ?>
    <strong><?php echo number_format($liquidity, 2, '.', ','); ?></strong>
    <br><br>


    <br>
            <br>
            <p> Claimed ASK (PLT) : <strong>
                    <?php
                    echo number_format(empty($row["value"]) ? 0 : $row["value"], 4, '.', ',');
                    ?>
                </strong> (Self Reported)</p>

            <?php
            $askf = (empty($row["value"]) ? 0 : $row["value"]) * $PLTUSD;
            ?>

            <p> Claimed ASK (USDT) : <strong>
                    <?php
                    echo number_format($askf, 2, '.', ',');
                    ?>
                </strong> (Self Reported)</p>

            <p> Claimed BID (USDT) : <strong>
                    <?php
                    echo number_format(empty($row["value2"]) ? 0 : $row["value2"], 2, '.', ',');
                    ?>
                </strong> (Self Reported)</p>
</body>

</html>

<?php
        }
    } else {
        echo "No record found for the ID and name: " . htmlspecialchars($id) . " - " . htmlspecialchars($nome);
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    die();
}
?>
