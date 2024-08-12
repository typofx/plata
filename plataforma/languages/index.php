<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Checking the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch available languages with visibility
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

// Fetch visible languages for table display
$visibleLanguages = fetchLanguages($conn, true);

// Fetch all languages for JSON data
$allLanguages = fetchLanguages($conn);

// Fetch all data grouped by uindex
$sql = "SELECT * FROM granna80_bdlinks.plata_texts WHERE device = 'desktop' ORDER BY uindex";
$result = $conn->query($sql);

// Organize data by uindex
$organizedData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $uindex = $row['uindex'];
        $language = $row['language'];

        if (!isset($organizedData[$uindex])) {
            $organizedData[$uindex] = [
                'name' => $row['name'],
                'languages' => array_fill_keys($allLanguages, null),
            ];
        }

        $organizedData[$uindex]['languages'][$language] = $row['text'];
    }
}

// Array to store JSON data
$data = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plata Languages</title>
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
    </style>
</head>
<body>
    <h2>Plata Languages</h2>
    <a href="add.php">[ Add new ]</a>
    <a href="data.json" target="_blank">[ JSON ]</a>
    <a href="add_language.php" target="_blank">[ Add idiom ]</a>
    <a href="manage_languages.php" target="_blank">[ Visible Languages ]</a>
    <a href=" " target="_blank">[ Control Panel ]</a>
    <br><br>
    <table>
        <tr>
            <th>#ID</th>
            <?php foreach ($visibleLanguages as $language): ?>
                <th><?php echo strtoupper($language); ?> Text</th>
            <?php endforeach; ?>
            <th>Actions</th>
        </tr>
        <?php foreach ($organizedData as $uindex => $rowData): ?>
            <tr>
                <td><?php echo "#". $uindex; ?></td>
                <?php foreach ($visibleLanguages as $language): ?>
                    <td><?php echo htmlspecialchars($rowData['languages'][$language] ?? ''); ?></td>
                <?php endforeach; ?>
                <td><a href="edit.php?uindex=<?php echo $uindex; ?>">Edit</a></td>
            </tr>
            <?php
            // Add row data to JSON array
            $row = ["uindex" => $uindex, "name" => $rowData['name']];
            foreach ($allLanguages as $language) {
                $row[$language] = $rowData['languages'][$language] ?? 'NULL';
            }
            $data[] = $row;
            ?>
        <?php endforeach; ?>
    </table>
    <?php
    // Write JSON data to a file
    file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    ?>
</body>
</html>
