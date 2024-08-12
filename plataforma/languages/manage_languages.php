<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function fetchLanguagesWithStatus($conn) {
    $sql = "SELECT * FROM granna80_bdlinks.languages";
    $result = $conn->query($sql);

    $languages = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $languages[] = $row;
        }
    }

    usort($languages, function($a, $b) {
        return $a['code'] === 'en' ? -1 : ($b['code'] === 'en' ? 1 : 0);
    });

    return $languages;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_visibility'])) {

    //echo '<pre>';
   // print_r($_POST['visibility']);
  //  echo '</pre>';

    foreach ($_POST['visibility'] as $code => $visible) {
        
        if ($code === 'en') {
            $visible = 1; 
        } else {
            $visible = ($visible === '1') ? 1 : 0;
        }


        $stmt = $conn->prepare("UPDATE granna80_bdlinks.languages SET visible = ? WHERE code = ?");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("is", $visible, $code);
        if (!$stmt->execute()) {
            echo 'Execute failed: ' . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    }


    echo "<script type='text/javascript'>window.location.href = 'manage_languages.php';</script>";
    exit();
}


$languages = fetchLanguagesWithStatus($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Languages</title>
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
    <h2>Manage Languages Visibility</h2>

    <form method="post" action="">
        <table>
            <tr>
                <th>Language Code</th>
                <th>Visible</th>
            </tr>
            <?php foreach ($languages as $language): ?>
                <tr>
                    <td><?php echo htmlspecialchars($language['code']); ?></td>
                    <td>
                        <input type="hidden" name="visibility[<?php echo htmlspecialchars($language['code']); ?>]" value="0">
                        <input type="checkbox" name="visibility[<?php echo htmlspecialchars($language['code']); ?>]" value="1" <?php echo $language['visible'] ? 'checked' : ''; ?> <?php echo $language['code'] === 'en' ? 'disabled' : ''; ?>>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <input type="submit" name="update_visibility" value="Update Visibility">
    </form>

    <br>
    <a href="index.php">[ Back to Home ]</a>
</body>
</html>
