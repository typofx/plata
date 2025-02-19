<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
// Include the database connection
include 'conexao.php';

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete the item from the database
    $sql = "DELETE FROM granna80_bdlinks.typofx_companies WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Item deleted successfully!";
        } else {
            $message = "Error: Unable to delete item.";
        }
        $stmt->close();
    } else {
        $message = "Error: Unable to prepare the SQL statement.";
    }
}

// Fetch all items from the database
$sql = "SELECT * FROM granna80_bdlinks.typofx_companies";
$result = $conn->query($sql);
$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}
$jsonFilePath = '/home2/granna80/public_html/plataforma/painel/typofx_components/companies/typofx_companies.json';
file_put_contents($jsonFilePath, json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Items</title>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }



        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a {
            text-decoration: none;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .actions .edit {
            background-color: #28a745;
        }

        .actions .delete {
            background-color: #dc3545;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            color: #28a745;
        }

        .add-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .add-button:hover {
            background-color: #0056b3;
        }

        .device-icon {
            font-size: 18px;
        }

        .desktop-icon {
            color: #007bff;
        }

        .mobile-icon {
            color: #28a745;
        }

        /* Custom DataTables styling */
        .dataTables_wrapper {
            margin-top: 20px;
        }

        .dataTables_filter input {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .dataTables_length select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .dataTables_paginate .paginate_button {
            padding: 5px 10px;
            margin: 0 2px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        }

        .dataTables_paginate .paginate_button:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <h1>Manage Items: Companies</h1>
    <a href="https://plata.ie/plataforma/painel/menu.php">[Back]</a>
    <a target="_blank" href="https://plata.ie/plataforma/painel/typofx_components/companies/typofx_companies.json">[JSON]</a>
    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <a href="add_item.php" class="add-button">
        <i class="fas fa-plus"></i> Add New Item
    </a>

    <table id="items-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Logo</th>
                <th>Full Logo</th>
                <th>Device</th>
                <th>Name</th>

                <th>Country</th>
                <th>Last Updated By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $cont = 1;

            if (count($items) > 0): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cont); ?></td>
                        <td>
                            <?php if ($item['logo']): ?>
                                <img src="/images/typofx-uploads/<?php echo htmlspecialchars($item['logo']); ?>" alt="Logo" width="50">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($item['full_logo']): ?>
                                <img src="/images/typofx-uploads/<?php echo htmlspecialchars($item['full_logo']); ?>" alt="Full Logo" width="100">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($item['device'] == 'desktop'): ?>
                                <center><i class="fas fa-desktop device-icon desktop-icon"></i></center>
                            <?php else: ?>
                                <center><i class="fas fa-mobile-alt device-icon mobile-icon"></i></center>
                            <?php endif; ?>
                        </td>
                        <td><a href="<?php echo htmlspecialchars($item['platform_link']); ?>"><?php echo htmlspecialchars($item['name']); ?></a></td>

                        <td>
                            <center><img src="/images/all_flags/<?php echo htmlspecialchars($item['country']); ?>.png" alt="" style="width: 30px; height: auto;"></center>
                        </td>

                        <td><?php echo htmlspecialchars($item['last_update_by']); ?></td>
                        <td class="actions">
                            <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="index.php?action=delete&id=<?php echo $item['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php
                    $cont++;
                endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center;">No items found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('#items-table').DataTable({
                paging: false,
                searching: true,
                ordering: true,
                info: true,
                responsive: true,
                columnDefs: [{
                        width: "6px",
                        targets: 0
                    }, 
                    {
                        width: "2px",
                        targets: 5
                    }, 
                    {
                        width: "6px",
                        targets: 3
                    } 
                ],
                fixedColumns: true
            });
        });
    </script>
</body>

</html>