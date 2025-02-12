<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php'; // Include your database connection file

// Fetch all header items
$queryItems = "SELECT * FROM granna80_bdlinks.granna_header_items ORDER BY order_number";
$resultItems = mysqli_query($conn, $queryItems);
$headerItems = [];

while ($row = mysqli_fetch_assoc($resultItems)) {
    $headerItems[] = $row;
}

// Fetch all sub-items
$querySubitems = "SELECT * FROM granna80_bdlinks.granna_header_subitems ORDER BY order_number";
$resultSubitems = mysqli_query($conn, $querySubitems);
$subItems = [];

while ($row = mysqli_fetch_assoc($resultSubitems)) {
    $subItems[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Granna Header Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4bb033;
            color: white;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .column-header {
            font-weight: bold;
        }

        .subitem {
            padding-left: 20px;
            font-size: 14px;
            color: #555;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <h2>Granna Header Items</h2><br>

    <a href="add.php">[Add new item] </a>
    <a href="config.php">[Config header]</a>
    <a href="http://granna.ie/desktop-header-2.php">[Granna header test]</a>
    <a href="https://plata.ie/plataforma/painel/menu.php">[Main menu]</a>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <?php foreach ($headerItems as $index => $item): ?>
                    <th class="column-header">
                        <a href="edit_column.php?id=<?php echo $item['id']; ?>" style="color: white; text-decoration: none;">
                            <i class="fas fa-edit"></i> <?php echo htmlspecialchars($item['name']); ?>
                        </a>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <!-- First Row: Non-dropdown items and first sub-items for dropdowns -->
            <tr>
                <td style="text-align: center; font-weight: bold;">1</td>
                <?php foreach ($headerItems as $item): ?>
                    <td>
                        <?php if (!$item['is_dropdown']): ?>
                            <?php if (!empty($item['url'])): ?>
                                <a href="<?php echo htmlspecialchars($item['url']); ?>">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </a>
                            <?php else: ?>
                                <?php echo htmlspecialchars($item['name']); ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php
                            // Display the first sub-item for dropdowns
                            $firstSubitem = null;
                            foreach ($subItems as $subItem) {
                                if ($subItem['parent_item_id'] == $item['id']) {
                                    $firstSubitem = $subItem;
                                    break;
                                }
                            }
                            if ($firstSubitem): ?>
                                <div class="subitem">
                                    <?php if (!empty($firstSubitem['url'])): ?>
                                        <a href="<?php echo htmlspecialchars($firstSubitem['url']); ?>">
                                            <?php echo htmlspecialchars($firstSubitem['name']); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($firstSubitem['name']); ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>

            <!-- Subsequent Rows: Remaining sub-items for dropdowns -->
            <?php
            // Find the maximum number of sub-items for any header item
            $maxSubitems = 0;
            foreach ($headerItems as $item) {
                if ($item['is_dropdown']) {
                    $count = 0;
                    foreach ($subItems as $subItem) {
                        if ($subItem['parent_item_id'] == $item['id']) {
                            $count++;
                        }
                    }
                    if ($count > $maxSubitems) {
                        $maxSubitems = $count;
                    }
                }
            }

            // Display each remaining sub-item in its own row
            for ($i = 1; $i < $maxSubitems; $i++): ?>
                <tr>
                    <td style="text-align: center; font-weight: bold;"><?php echo $i + 1; ?></td>
                    <?php foreach ($headerItems as $item): ?>
                        <td>
                            <?php if ($item['is_dropdown']): ?>
                                <?php
                                $subitemIndex = 0;
                                foreach ($subItems as $subItem) {
                                    if ($subItem['parent_item_id'] == $item['id']) {
                                        if ($subitemIndex == $i) {
                                            echo '<div class="subitem">';
                                            if (!empty($subItem['url'])) {
                                                echo '<a href="' . htmlspecialchars($subItem['url']) . '">';
                                                echo htmlspecialchars($subItem['name']);
                                                echo '</a>';
                                            } else {
                                                echo htmlspecialchars($subItem['name']);
                                            }
                                            echo '</div>';
                                            break;
                                        }
                                        $subitemIndex++;
                                    }
                                }
                                ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

</body>

</html>