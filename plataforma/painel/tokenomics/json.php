<?php

$jsonFilePath = 'tokenomics_history_agrupado.json';

header('Content-Type: application/json; charset=utf-8');

$yearToFilter = $_GET['year_filter'] ?? 0;
$monthToFilter = $_GET['month_filter'] ?? 0;

if (empty($yearToFilter) || empty($monthToFilter)) {
    echo json_encode([
        "error" => "Error"
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit();
}

if (!file_exists($jsonFilePath)) {
    echo json_encode([
        "error" => "Error."
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit();
}

$sourceText = file_get_contents($jsonFilePath);

preg_match_all('/\{.*?\}/s', $sourceText, $matches);
$allObjectStrings = $matches[0] ?? [];



$regularFormattedObjects = []; 
$othersObjectString = null;  

$finalPltPrice = null;
$finalPriceDate = null;

foreach ($allObjectStrings as $objectString) {

    $isYearMatch  = preg_match('/"record_year":\s*' . $yearToFilter . '/', $objectString);
    $isMonthMatch = preg_match('/"record_month":\s*' . $monthToFilter . '/', $objectString);

    if ($isYearMatch && $isMonthMatch) {
        
        preg_match('/"exchange":\s*"(.*?)"/', $objectString, $exchangeMatch);
        $exchange = $exchangeMatch[1] ?? '';

       
        if ($finalPltPrice === null) {
            preg_match('/"plt_price":\s*([0-9e.-]+)/', $objectString, $pltPriceMatch);
            preg_match('/"price_date":\s*"(.*?)"/', $objectString, $priceDateMatch);
            $finalPltPrice = $pltPriceMatch[1] ?? '0';
            $finalPriceDate = $priceDateMatch[1] ?? '';
        }
        
      
        if ($exchange === 'Others') {
           
            preg_match('/"liquidity":\s*([0-9e.-]+)/', $objectString, $liquidityMatch);
            preg_match('/"percentage":\s*([0-9e.-]+)/', $objectString, $percentageMatch);
            preg_match('/"plata":\s*([0-9e.-]+)/', $objectString, $plataMatch);

            $liquidity  = $liquidityMatch[1] ?? '0';
            $percentage = $percentageMatch[1] ?? '0';
            $plata      = $plataMatch[1] ?? '0';

            $currentObjectString = "  {\n";
            $currentObjectString .= "    \"id\": 999,\n"; 
            $currentObjectString .= "    \"exchange\": \"" . addslashes($exchange) . "\",\n";
            $currentObjectString .= "    \"liquidity\": " . $liquidity . ",\n";
            $currentObjectString .= "    \"percentage\": " . $percentage . ",\n";
            $currentObjectString .= "    \"plata\": " . $plata . "\n";
            $currentObjectString .= "  }";
            
            $othersObjectString = $currentObjectString; 
            
        } else {
        
            preg_match('/"id":\s*([0-9.]+)/', $objectString, $idMatch);
            preg_match('/"liquidity":\s*([0-9e.-]+)/', $objectString, $liquidityMatch);
            preg_match('/"percentage":\s*([0-9e.-]+)/', $objectString, $percentageMatch);
            preg_match('/"plata":\s*([0-9e.-]+)/', $objectString, $plataMatch);
            
            $id         = $idMatch[1] ?? 'null';
            $liquidity  = $liquidityMatch[1] ?? '0';
            $percentage = $percentageMatch[1] ?? '0';
            $plata      = $plataMatch[1] ?? '0';

            $currentObjectString = "  {\n";
            $currentObjectString .= "    \"id\": " . $id . ",\n";
            $currentObjectString .= "    \"exchange\": \"" . addslashes($exchange) . "\",\n";
            $currentObjectString .= "    \"liquidity\": " . $liquidity . ",\n";
            $currentObjectString .= "    \"percentage\": " . $percentage . ",\n";
            $currentObjectString .= "    \"plata\": " . $plata . "\n";
            $currentObjectString .= "  }";

            $regularFormattedObjects[] = $currentObjectString; 
        }
    }
}


$finalDataObjects = $regularFormattedObjects;
if ($othersObjectString !== null) {
    $finalDataObjects[] = $othersObjectString;
}


$formattedTimestamp = '';
if ($finalPriceDate && !empty($finalPriceDate)) {
    try {
        $dateObj = new DateTime($finalPriceDate);
        $formattedTimestamp = $dateObj->format('d-m-Y H:i:s');
    } catch (Exception $e) {
        $formattedTimestamp = '';
    }
}

$timestampObjectString = "  {\n";
$timestampObjectString .= "    \"timestamp\": \"" . $formattedTimestamp . " UTC\",\n";
$timestampObjectString .= "    \"pltprice\": " . number_format((float)($finalPltPrice ?? 0), 10, '.', '') . "\n";
$timestampObjectString .= "  }";


array_unshift($finalDataObjects, $timestampObjectString);


$finalOutputContent = "[\n" . implode(",\n", $finalDataObjects) . "\n]";

echo $finalOutputContent;

?>