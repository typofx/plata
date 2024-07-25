
<?php
// URL of the JSON
$url = 'https://plata.ie/plataforma/painel/tokenomics/liquidity_data.json';

// Get the JSON content from the URL
$json = file_get_contents($url);

// Decode the JSON into an associative array
$data = json_decode($json, true);

// Check if decoding was successful
if ($data === null) {
    echo 'Error decoding JSON.';
    exit;
}

// Function to compare liquidity for sorting in descending order
usort($data, function ($a, $b) {
    return $b['liquidity'] <=> $a['liquidity'];
});

// Iterate over the sorted data and print each item
$cont = 1;
foreach ($data as $item) {
    echo 'ID: ' . $cont . '<br>';
    echo 'Exchange: ' . $item['exchange'] . '<br>';
    echo 'Liquidity: ' . round($item['liquidity'],2) . '<br>';
    echo 'Percentage: ' . $item['percentage'] * 100 . '%<br>';
    echo 'PLT: ' . $item['plata'] . '<br><br>';
    $cont++;
}
?>


