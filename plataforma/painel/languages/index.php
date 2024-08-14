<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

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


$visibleLanguages = fetchLanguages($conn, true);


$allLanguages = fetchLanguages($conn);


function organizeDataByDevice($conn, $device) {
    $sql = "SELECT * FROM granna80_bdlinks.plata_texts WHERE device = '$device' ORDER BY uindex";
    $result = $conn->query($sql);

    $organizedData = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $uindex = $row['uindex'];
            $language = $row['language'];

            if (!isset($organizedData[$uindex])) {
                $organizedData[$uindex] = [
                    'name' => $row['name'],
                    'languages' => array_fill_keys($GLOBALS['allLanguages'], null),
                ];
            }

            $organizedData[$uindex]['languages'][$language] = $row['text'];
        }
    }
    return $organizedData;
}


$desktopData = organizeDataByDevice($conn, 'desktop');


$mobileData = organizeDataByDevice($conn, 'mobile');


$desktopJsonData = [];
$mobileJsonData = [];
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
    <a href="data_desktop.json" target="_blank">[ JSON Desktop ]</a>
    <a href="data_mobile.json" target="_blank">[ JSON Mobile ]</a>
    <a href="add_language.php" target="_blank">[ Add idiom ]</a>
    <a href="manage_languages.php" target="_blank">[ Visible Languages ]</a>
    <a href=" " target="_blank">[ Control Panel ]</a>
    <br><br>
    <table>
        <tr>
            <th>#ID</th>
            <?php foreach ($visibleLanguages as $language): ?>
                <th><?php echo strtoupper($language); ?> Desktop Text</th>
                <th><?php echo strtoupper($language); ?> Mobile Text</th>
            <?php endforeach; ?>
            <th>Actions</th>
        </tr>
        <?php foreach ($desktopData as $uindex => $rowData): ?>
            <tr>
                <td><?php echo "#". $uindex; ?></td>
                <?php foreach ($visibleLanguages as $language): ?>
                    <td><?php echo htmlspecialchars($rowData['languages'][$language] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($mobileData[$uindex]['languages'][$language] ?? ''); ?></td>
                <?php endforeach; ?>
                <td><a href="edit.php?uindex=<?php echo $uindex; ?>">Edit</a></td>
            </tr>
            <?php
           
            $row = ["uindex" => $uindex, "name" => trim($rowData['name'])];
            foreach ($allLanguages as $language) {
                $row[$language] = $rowData['languages'][$language] ?? 'NULL';
            }
            $desktopJsonData[] = $row;
            ?>
        <?php endforeach; ?>
    </table>

    <?php
   
    file_put_contents('data_desktop.json', json_encode($desktopJsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

  
    foreach ($mobileData as $uindex => $rowData) {
        $row = ["uindex" => $uindex, "name" => trim($rowData['name'])];
        foreach ($allLanguages as $language) {
            $row[$language] = $rowData['languages'][$language] ?? 'NULL';
        }
        $mobileJsonData[] = $row;
    }
    file_put_contents('data_mobile.json', json_encode($mobileJsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    ?>
</body>
</html>
