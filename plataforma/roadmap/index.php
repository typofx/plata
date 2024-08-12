<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';

// Include the file for database connection
include 'conexao.php';

// Checking the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch available languages
function fetchLanguages($conn, $visibleOnly = false) {
    $sql = "SELECT code FROM granna80_bdlinks.languages";
    if ($visibleOnly) {
        $sql .= " WHERE visible = 1";
    }
    $result = $conn->query($sql);

    $languages = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $languages[] = $row['code'];
        }
    }
    return $languages;
}

// Get available languages
$languages = fetchLanguages($conn);

// Fetch all unique uindex values ordered by the earliest task_date
$uindexQuery = "
    SELECT uindex 
    FROM granna80_bdlinks.roadmap 
    GROUP BY uindex
    ORDER BY MIN(task_date) ASC, uindex ASC
";
$uindexResult = $conn->query($uindexQuery);
$uindexes = [];

if ($uindexResult->num_rows > 0) {
    while ($row = $uindexResult->fetch_assoc()) {
        $uindexes[] = $row['uindex'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Roadmap</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .highlighted {
            background-color: yellow;
            display: inline;
            padding: 0;
        }
    </style>
</head>
<body>

<h2>Roadmap</h2>

<a href="add.php">[ Add new ]</a>
<br>
<br>

<table>
    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Done</th>
        <?php foreach ($languages as $language): ?>
            <th>Goal <?= strtoupper($language) ?></th>
            <th>Goal <?= strtoupper($language) ?> MOBILE</th>
        <?php endforeach; ?>
        <th>Semester</th>
        <th>Actions</th>
    </tr>
    <?php
    $index = 1;
    // Loop through each unique uindex
    foreach ($uindexes as $uindex) {
        $rows = [];
        foreach ($languages as $language) {
            // Fetch rows for each language and device type based on uindex
            $rows[$language]['desktop'] = $conn->query("SELECT * FROM granna80_bdlinks.roadmap WHERE language = '$language' AND device_type = 'desktop' AND uindex = '$uindex' ORDER BY task_date ASC")->fetch_assoc();
            $rows[$language]['mobile'] = $conn->query("SELECT * FROM granna80_bdlinks.roadmap WHERE language = '$language' AND device_type = 'mobile' AND uindex = '$uindex' ORDER BY task_date ASC")->fetch_assoc();
       
        }
        
        // If there are no results in any of the tables for this uindex, skip it
        if (empty(array_filter($rows, function ($row) {
            return $row['desktop'] || $row['mobile'];
        }))) {
            continue;
        }

        echo "<tr>";
        // Use uindex directly as the ID to ensure correct positioning
        echo "<td>" . $index . "</td>";

        // Check and display task date in English (assuming English always has a date)
        echo "<td>";
        if ($rows['en']['desktop']) {
            echo date('d/m/Y', strtotime($rows['en']['desktop']["task_date"]));
        }
        echo "</td>";

        // Check and display if task is done
        echo "<td>";
        if ($rows['en']['desktop'] && $rows['en']['desktop']["task_done"] == 1) {
            echo "&#x2713;";
        } else if ($rows['en']['desktop']) {
            echo "&ndash; ";
        }
        echo "</td>";

        // Check and display task goals for each language
        foreach ($languages as $language) {
            // Desktop
            echo "<td>";
            if ($rows[$language]['desktop'] && $rows[$language]['desktop']["task_highlighted"] == 1) {
                echo "<span class='highlighted'>" . $rows[$language]['desktop']["task_goal"] . "</span>";
            } else if ($rows[$language]['desktop']) {
                echo $rows[$language]['desktop']["task_goal"];
            }
            echo "</td>";

            // Mobile
            echo "<td>";
            if ($rows[$language]['mobile'] && $rows[$language]['mobile']["task_highlighted"] == 1) {
                echo "<span class='highlighted'>" . $rows[$language]['mobile']["task_goal"] . "</span>";
            } else if ($rows[$language]['mobile']) {
                echo $rows[$language]['mobile']["task_goal"];
            }
            echo "</td>";
        }

        // Display the semester, fetching from any of the tables
        echo "<td>";
        if (isset($rows['en']['desktop']["task_date"])) {
            $date = strtotime($rows['en']['desktop']["task_date"]);
            $month = date('m', $date);
            $year = date('Y', $date);
            $semester = ($month <= 6) ? '1' : '2';
            echo $semester . "(" . $year . ")";
        }
        echo "</td>";

        // Add an edit button for each row
        echo "<td><a href='edit.php?uindex=" . $uindex . "'>Edit</a> | <a href='delete.php?uindex=" . $uindex . "'>Delete</a></td>";
        echo "</tr>";
        $index++;
    }
    ?>
</table>

</body>
</html>
