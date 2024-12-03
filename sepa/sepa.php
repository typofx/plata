<?php
// Check if data was sent via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    echo "<h1>Data received via POST:</h1>";
    echo "<ul>";
    foreach ($_POST as $key => $value) {
        // Adjust display according to the rules
        if ($key === 'valorpix') {
            echo "<li><strong>eurvalue:</strong> " . htmlspecialchars($value) . "</li>";
        } elseif ($key !== 'identificador' && $key !== 'verify_code') {
            echo "<li><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<h1>No data was sent via POST.</h1>";
}

// Example form for testing
?>