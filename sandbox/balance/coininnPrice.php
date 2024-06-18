<?php
// URL of the JSON
$url = "https://www.coininn.com//api/v3/papi/spot/depth?symbol=PLT&baseCurrency=USDT";

// Get the JSON content from the URL
$json = file_get_contents($url);

// Check if the JSON retrieval was successful
if ($json === false) {
    die("Error retrieving JSON content");
}

// Decode the JSON into an associative array
$data = json_decode($json, true);

// Check if the JSON decoding was successful
if ($data === null) {
    die("Error decoding JSON");
}

// Access the timestamp
$timestamp = $data['timestamp'];

// Access the bids and asks data
$bids = $data['bids'];
$asks = $data['asks'];

// Variable to store the sum of ask prices
$sum_ask_prices = 0;

// Sum all ask prices
foreach ($asks as $ask) {
    $sum_ask_prices += $ask['price'];
}

// Current price of PLT (value provided in the example)
$current_price = 0.0000032232;

// Store the data in associative arrays and calculate liquidity
$order_book = [
    'timestamp' => $timestamp,
    'bids' => [],
    'asks' => [],
    'bids_liquidity' => 0,
    'asks_liquidity' => 0
];

$ask_index = 1;
foreach ($asks as $ask) {
    $order_book['asks'][$ask_index] = [
        'price' => $ask['price'],
        'amount' => $ask['amount']
    ];
    $order_book['asks_liquidity'] += $ask['amount']; // Sum the amounts of PLT
    $ask_index++;
}

// Calculate the total asks liquidity in USDT
$order_book['asks_liquidity'];

// Display the data in an HTML table
echo "<h2>Market Depth</h2>";

echo "<h3>Asks</h3>";
echo "<table border='1'>";
echo "<tr><th>#</th><th>Price</th><th>Amount</th></tr>";
foreach ($order_book['asks'] as $index => $ask) {
    echo "<tr><td>" . $index . "</td><td>" . number_format($ask['price'], 10, '.', '') . "</td><td>" . number_format($ask['amount'], 2, '.', '') . "</td></tr>";
}
echo "</table>";

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();

echo '<br>';
echo 'Sum: '. $order_book['asks_liquidity'];
echo '<br>';
echo 'Multiplied: '.  $order_book['asks_liquidity'] * $PLTUSD;

?>
