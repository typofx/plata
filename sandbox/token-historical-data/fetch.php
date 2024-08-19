<?php
// JSON URL
$jsonUrl = 'https://plata.ie/sandbox/token-historical-data/token_data.json';

// Get the JSON content from the URL
$jsonContent = file_get_contents($jsonUrl);

// Decode the JSON into a PHP array
$data = json_decode($jsonContent, true);

// Check if the decoding was successful
if (json_last_error() === JSON_ERROR_NONE) {
    // Process the JSON data
    $cont = 1;
    foreach ($data as $item) {
        // Format the numbers with 10 decimal places
        $priceFormatted = number_format((float)$item['price'], 10, '.', ',');
        $volumeFormatted = number_format((float)$item['volume'], 10, '.', ',');
        $marketCapFormatted = number_format((float)$item['market_cap'], 10, '.', ',');

        echo "ID: " .  $cont . "<br>";
        echo "Date: " . $item['date'] . "<br>";
        echo "Price: " . $priceFormatted . "<br>";
        echo "Volume: " . $volumeFormatted . "<br>";
        echo "Market Cap: " . $marketCapFormatted . "<br><br>";
        $cont++;
    }
} else {
    echo "Error decoding JSON: " . json_last_error_msg();
}
?>

