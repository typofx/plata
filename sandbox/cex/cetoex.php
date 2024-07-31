<?php
include 'conexao.php';

if (isset($_GET['name'])) {
    $name = $_GET['name'];
} else {
    $sql = "SELECT DISTINCT name FROM granna80_bdlinks.order_book";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = htmlspecialchars($row['name']);
            echo "<a href='cex-price.php?name=$name'>$name</a><br>";
        }
    } else {
        echo "No names found in the database.";
    }
    exit();
}


// SQL query to get data of the specific name

$sql = "SELECT id, value, value1, value2, name, url FROM granna80_bdlinks.order_book WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ob_start();
        include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
        ob_end_clean();

        // URL of the JSON (replace with dynamic logic to obtain via database)
        $jsonUrl = $row['url'];

        // Function to fetch JSON content from a URL
        function fetchJsonFromUrl($url)
        {
            $json = file_get_contents($url);
            if ($json === false) {
                throw new Exception("Failed to fetch JSON from URL: $url");
            }
            return $json;
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

        // Function to convert scientific notation to float
        function convertScientificToFloat($value)
        {
            return floatval($value); // Convert to float
        }

        try {
            // Fetch the JSON from the URL
            $json = fetchJsonFromUrl($jsonUrl);

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


            // Calculate the sum of each column
            foreach ($asks as $ask) {
                $totalPrice += $ask['price']; // Sum of prices
                $totalAmount += $ask['amount']; // Sum of amounts
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            die();
        }
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>JSON Table</title>
        </head>

        <body>

            <h3>Market Depth (<?php echo  $row['name']; ?>)</h3>

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
                        <?php if ($cont > 5) break; ?> <!-- Add this line to stop the foreach at 5 -->

                        <tr>
                            <td>
                                <center><?php echo $cont ?></center>
                            </td>
                            <td>
                                <center><?php echo number_format($ask['price'], 8); ?></center>
                            </td>
                            <td>
                                <center><?php echo $ask['amount'] ?></center>
                            </td>
                        </tr>
                        <?php $cont++ ?>
                    <?php endforeach; ?>

                    <tr>
                        <td><strong>
                            </strong></td>
                        <td><strong>
                                <center><?php // echo number_format($totalPrice, 8); 
                                        ?> </center>
                            </strong></td>
                    </tr>
                </tbody>
            </table>

            <p>Plata Token (PLT) Price: <?php echo $PLTUSD ?> USDT</p>
            <br>
            <strong>Total Amount ASK (PLT):</strong>
            <strong><?php echo $totalAmountF = number_format($totalAmount, 4, '.', ','); ?></strong>
            <br>
            <strong>Total ASK (USDT):</strong>
            <?php $totalPLTUSD = ($PLTUSD * $totalAmount)  ?>
            <strong><?php echo $totalPLTUSDF = number_format($totalPLTUSD, 2, '.', ','); ?></strong>
            <br>
            <strong>
                Total BID (USDT):

                <?php echo number_format($totalPriceBids, 2, '.', ',')   ?>
            </strong>
            <br>
            <strong>
                Liquidity:
                <?php $liquidity = $totalPLTUSD + $totalPriceBids  ?>
                <?php echo number_format($liquidity, 2, '.', ',')   ?>
            </strong>

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
    echo "No record found for the name: " . htmlspecialchars($name);
}
?>