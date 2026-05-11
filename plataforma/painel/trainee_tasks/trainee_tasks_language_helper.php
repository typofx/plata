<?php
/**
 * Helper function to manage programming languages stored as positional binary strings.
 * Uses tasks.programming.languages.json for the master index.
 */

function getLanguagesFromConfig() {
    $jsonFile = __DIR__ . '/tasks.programming.languages.json';
    if (!file_exists($jsonFile)) {
        return [];
    }
    $content = file_get_contents($jsonFile);
    $data = json_decode($content, true);
    return $data['programming_languages'] ?? [];
}

/**
 * Converts an array of selected languages into a binary string representation.
 * Example: ['php', 'sql'] -> "1000000010..."
 */
function encodeLanguagesToString(array $selectedLanguages) {
    $masterList = getLanguagesFromConfig();
    $result = '';
    foreach ($masterList as $lang) {
        $result .= in_array($lang, $selectedLanguages) ? '1' : '0';
    }
    return $result;
}

/**
 * Converts a binary string from DB back to an array of language names.
 */
function decodeLanguagesToArray($binaryString) {
    if (empty($binaryString)) {
        return [];
    }
    
    $masterList = getLanguagesFromConfig();
    $active = [];
    
    // Iterate over each character in the string up to the masterList count
    $len = strlen($binaryString);
    foreach ($masterList as $index => $lang) {
        if ($index < $len && $binaryString[$index] === '1') {
            $active[] = $lang;
        }
    }
    return $active;
}

/**
 * Returns a formatted HTML string representation for usage in tables.
 */
function renderLanguagesHtml($binaryString) {
    $masterList = getLanguagesFromConfig();
    if (empty($masterList)) {
        return '<span class="text-muted">-</span>';
    }

    // Setup icon mapping
    $iconMap = [
        'php' => 'fa-brands fa-php',
        'javascript' => 'fa-brands fa-js',
        'json' => 'fa-solid fa-file-code',
        'dotnet' => 'fa-brands fa-microsoft',
        'html' => 'fa-brands fa-html5',
        'css' => 'fa-brands fa-css3-alt',
        'solidity' => 'fa-brands fa-ethereum',
        'xml' => 'fa-solid fa-code',
        'sql' => 'fa-solid fa-database',
        'jquery' => 'fa-solid fa-bolt'
    ];

    $html = '<div class="lang-icons-wrapper">';
    $len = strlen($binaryString);
    
    foreach ($masterList as $index => $langName) {
        $cleanName = strtolower($langName);
        $isActive = ($index < $len && $binaryString[$index] === '1');
        $iconClass = $iconMap[$cleanName] ?? 'fa-solid fa-code';
        $stateClass = $isActive ? 'lang-active' : 'lang-inactive';
        
        $html .= '<i class="' . $iconClass . ' ' . $stateClass . ' lang-icon-' . $cleanName . '" title="' . strtoupper($langName) . '"></i> ';
    }
    $html .= '</div>';
    
    return $html;
}
