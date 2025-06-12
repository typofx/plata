<?php

header('Content-Type: application/json');

// Validate if the date was received
if (!isset($_GET['price_date']) || empty($_GET['price_date'])) {
    echo json_encode(['success' => false, 'message' => 'The date (Price Date) was not provided.']);
    exit();
}

try {
    $target_date = new DateTime($_GET['price_date']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'The provided date format is invalid.']);
    exit();
}

// Fetch and decode the JSON data
$json_url = "https://typofx.ie/plataforma/panel/token-historical-data/token_data.json";
$json_data = @file_get_contents($json_url);

if ($json_data === false) {
    echo json_encode(['success' => false, 'message' => 'Error fetching the historical data file.']);
    exit();
}

$historical_data = json_decode($json_data, true);

if (!is_array($historical_data)) {
    echo json_encode(['success' => false, 'message' => 'The content from the URL is not valid JSON.']);
    exit();
}

// Find the most recent record on or before the provided date
$best_match = null;
$smallest_diff = PHP_INT_MAX; // A very large integer to start the comparison

foreach ($historical_data as $record) {
    if (isset($record['date']) && isset($record['price'])) {
        try {
            $record_date = new DateTime($record['date']);

            // Consider only dates BEFORE or EQUAL to the provided date
            if ($record_date <= $target_date) {
                // Calculate the time difference
                $diff = $target_date->getTimestamp() - $record_date->getTimestamp();

                // If the current difference is smaller than the smallest one found so far,
                // this becomes the best result.
                if ($diff < $smallest_diff) {
                    $smallest_diff = $diff;
                    $best_match = $record;
                }
            }
        } catch (Exception $e) {
            // Ignore records with invalid dates in the JSON
            continue;
        }
    }
}

// Return the best result found
if ($best_match !== null) {
    echo json_encode([
        'success' => true,
        'price'   => $best_match['price'],
        'date'    => $best_match['date'] // Return the actual date of the price found
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No historical price found on or before the selected date.']);
}

exit();
?>
