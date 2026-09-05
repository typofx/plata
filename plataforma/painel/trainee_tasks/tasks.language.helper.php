<?php
/**
 * Helper function to manage programming languages stored as positional binary strings.
 * Uses tasks.programming.languages.json for the master index.
 */


function getLanguagesFromConfig() {
    // Detect module prefix dynamically from this file's own name for maximum component portability.
    $prefix = explode('.', basename(__FILE__))[0];
    $jsonFile = __DIR__ . '/' . $prefix . '.programming.languages.json';
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
        if ($index >= $len || $binaryString[$index] !== '1') {
            continue;
        }
        $cleanName = strtolower($langName);
        $iconClass = $iconMap[$cleanName] ?? 'fa-solid fa-code';
        $html .= '<i class="' . $iconClass . ' lang-active lang-icon-' . $cleanName . '" title="' . strtoupper($langName) . '"></i> ';
    }
    $html .= '</div>';
    
    return $html;
}

/**
 * Generates the Top Navigation Bar used by system admin pages.
 */
function renderTopBar($visible, $canEdit) {
    if (!$visible) {
        return '';
    }
    
    $items = [];
    $items[] = '<a href="https://www.typofx.ie/plataforma/panel/">[Control Panel]</a>';
    $items[] = '<a href="javascript:window.location.reload(true)">[Refresh]</a>';
    
    if ($canEdit) {
        // Direct link creation based on current module prefix.
        $prefix = explode('.', basename(__FILE__))[0];
        $items[] = '<a href="' . $prefix . '.form">[Add New Record]</a>';
    } else {
        $items[] = '<span style="color: gray; cursor: not-allowed; text-decoration: none;" title="Restricted Access">[Add New Record]</span>';
    }
    
    return implode(' ', $items);
}

