<body>






<?php
include 'conexao.php';




// Base URLs for JSON files
$desktopJsonUrl = 'https://plata.ie/plataforma/painel/languages/data_desktop.json';
$mobileJsonUrl = 'https://plata.ie/plataforma/painel/languages/data_mobile.json';

// Function to load translations from the JSON file
if (!function_exists('loadTranslations')) {
    function loadTranslations($jsonFileUrl, $lang)
    {
        // Fetch the JSON data from the URL
        $jsonData = file_get_contents($jsonFileUrl);
        
        if ($jsonData === false) {
            die("Error fetching JSON data from the URL.");
        }
        
        // Decode the JSON data into an associative array
        $translationsArray = json_decode($jsonData, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Error decoding JSON: " . json_last_error_msg());
        }

        // Prepare an array to hold the translations for the specific language
        $translations = [];
        
        // Loop through the translations array
        foreach ($translationsArray as $translation) {
            if (isset($translation[$lang])) {
                $translations[$translation['name']] = $translation[$lang];
            }
        }
        
        return $translations;
    }
}

// Function to fetch available languages from the database
if (!function_exists('fetchLanguages')) {
    function fetchLanguages($conn)
    {
        $sql = "SELECT code FROM granna80_bdlinks.languages";
        $result = $conn->query($sql);

        $languages = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $languages[] = $row['code'];
            }
        }
        return $languages;
    }
}

// Get available languages
$languages = fetchLanguages($conn);

// Determine the language based on the URL
$CurrentPageURL = $_SERVER['REQUEST_URI'];

// Set the JSON URL based on the URL pattern (desktop or mobile)
if (strpos($CurrentPageURL, '/mobile/') !== false) {
    $jsonFileUrl = $mobileJsonUrl;
} else {
    $jsonFileUrl = $desktopJsonUrl;
}

// Extract language code from the URL
$lang = substr($CurrentPageURL, 1, 2);
$lang = in_array($lang, $languages) ? $lang : 'en';

// Load translations for the determined language from JSON
$translations = loadTranslations($jsonFileUrl, $lang);

// Assign translations to variables
foreach ($translations as $varName => $text) {
    $$varName = $text;
}


?>











</body>