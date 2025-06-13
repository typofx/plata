<?php


$jsonFilePath = 'tokenomics_history.json';


// Set the response header to JSON, ensuring UTF-8 encoding.
header('Content-Type: application/json; charset=utf-8');



$yearToFilter = $_GET['year_filter'] ?? 0;
$monthToFilter = $_GET['month_filter'] ?? 0;

// If parameters are not set correctly, display an error.
if (empty($yearToFilter) || empty($monthToFilter)) {
    echo "[\n  {\n    \"error\": \"Year and month filters are required. Example: json.php?year_filter=2025&month_filter=6\"\n  }\n]";
    exit();
}



if (!file_exists($jsonFilePath)) {
    echo "[\n  {\n    \"error\": \"JSON data file not found on server.\"\n  }\n]";
    exit();
}

// Read the entire file content into a text variable.
$sourceText = file_get_contents($jsonFilePath);





preg_match_all('/\{.*?\}/s', $sourceText, $matches);
$allObjectStrings = $matches[0] ?? []; // Array containing the string of each object


$filteredFormattedObjects = [];


foreach ($allObjectStrings as $objectString) {
    // For each object, check if it contains the correct year and month.
    $isYearMatch  = preg_match('/"record_year":\s*' . $yearToFilter . '/', $objectString);
    $isMonthMatch = preg_match('/"record_month":\s*' . $monthToFilter . '/', $objectString);

    // If BOTH year and month are found in the object string...
    if ($isYearMatch && $isMonthMatch) {

        preg_match('/"id":\s*([0-9.]+)/', $objectString, $idMatch);
        preg_match('/"record_year":\s*([0-9.]+)/', $objectString, $recordYearMatch);
        preg_match('/"record_month":\s*([0-9.]+)/', $objectString, $recordMonthMatch);
        preg_match('/"exchange":\s*"(.*?)"/', $objectString, $exchangeMatch);
        preg_match('/"liquidity":\s*([0-9.]+)/', $objectString, $liquidityMatch);
        preg_match('/"percentage":\s*([0-9.]+)/', $objectString, $percentageMatch);
        preg_match('/"plata":\s*([0-9.]+)/', $objectString, $plataMatch);
        preg_match('/"plt_price":\s*([0-9.]+)/', $objectString, $pltPriceMatch);
        preg_match('/"price_date":\s*"(.*?)"/', $objectString, $priceDateMatch);

        // Assign to variables, with a default empty string if not found.
        $id           = $idMatch[1] ?? 'null';
        $recordYear   = $recordYearMatch[1] ?? 'null';
        $recordMonth  = $recordMonthMatch[1] ?? 'null';
        $exchange     = $exchangeMatch[1] ?? '';
        $liquidity    = $liquidityMatch[1] ?? '0';
        $percentage   = $percentageMatch[1] ?? '0';
        $plata        = $plataMatch[1] ?? '0';
        $pltPrice     = $pltPriceMatch[1] ?? '0';
        $priceDate    = $priceDateMatch[1] ?? 'null';


        $currentObjectString = "  {\n";
        $currentObjectString .= "    \"id\": " . $id . ",\n";
        $currentObjectString .= "    \"exchange\": \"" . addslashes($exchange) . "\",\n";
        $currentObjectString .= "    \"liquidity\": " . $liquidity . ",\n";
        $currentObjectString .= "    \"percentage\": " . $percentage . ",\n";
        $currentObjectString .= "    \"plata\": " . $plata . ",\n";
        $currentObjectString .= "  }";

        // Add the rebuilt and formatted object string to our results array.
        $filteredFormattedObjects[] = $currentObjectString;
    }
}



$finalOutputContent = "[\n";
$finalOutputContent .= implode(",\n", $filteredFormattedObjects);
$finalOutputContent .= "\n]";


echo $finalOutputContent;

?>