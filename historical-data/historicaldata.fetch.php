<?php

$url = 'https://typofx.ie/plataforma/panel/token-historical-data/live_plt.json';

// Make the request to the URL and get the content as a string
$json_data = file_get_contents($url);

// Check if the request failed
if ($json_data === false) {
    die('Error fetching data from the URL.');
}

// Decode the JSON string into a PHP object
$data_object = json_decode($json_data);

// Decode the JSON string into a PHP associative array
$data_array = json_decode($json_data, true);

// Check if the JSON decoding failed
if ($data_object === null && json_last_error() !== JSON_ERROR_NONE) {
    die('Error decoding the JSON.');
}

// Displaying the data from the object
echo "<h2>Data as Object:</h2>";
echo "Date: " . $data_object->date . "<br>";
echo "Price: " . number_format($data_object->price, 10, '.', ',') . "<br>";
echo "Volume: " . number_format($data_object->volume, 4, '.', ',') . "<br>";
echo "Market Cap: " . number_format($data_object->market_cap, 4, '.', ',') . "<br>";

echo "<hr>";

// Displaying the data from the associative array
echo "<h2>Data as Associative Array:</h2>";
echo "Date: " . $data_array['date'] . "<br>";
echo "Price: " . number_format($data_array['price'], 10, '.', ',') . "<br>";
echo "Volume: " . number_format($data_array['volume'], 4, '.', ',') . "<br>";
echo "Market Cap: " . number_format($data_array['market_cap'], 4, '.', ',') . "<br>";

echo "<hr>";

// Displaying the full structure for debugging
echo "<h2>Object Structure:</h2>";
echo "<pre>";
print_r($data_object);
echo "</pre>";

echo "<h2>Array Structure:</h2>";
echo "<pre>";
print_r($data_array);
echo "</pre>";

?>