<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
include 'conexao.php';

// Fetch all records from the scrapyard table
$result = $conn->query("SELECT * FROM granna80_bdlinks.scrapyard");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scrapyard Equipment</title>

    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Include FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .edit-icon {
            color: blue;
            /* Green for edit */
            cursor: pointer;
        }

        .delete-icon {
            color: red;
            /* Red for delete */
            cursor: pointer;
        }

        .action-icons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
    </style>
    <style>
        body {
            font-family: Arial, sans-serif;

            background-color: #fff;
        }

        table.dataTable {
            width: auto;
            margin: 0 auto;
            border-collapse: collapse;
        }

        table.dataTable th,
        table.dataTable td {
            padding: 8px 12px;
            text-align: center !important;
        }

        table.dataTable th {
            background-color: #fff;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0;
            margin: 0;
            border: none;
            background: none;
        }
    </style>
</head>

<body>
    <h1>Scrapyard Equipment List</h1>


    <a href="register_brands.php">[ Register Brands ]</a><br>
    <a href="register_models.php">[ Register Models ]</a><br>
    <a href="add_new_equipment.php">[ Add new product ]</a>
    <a href="register_eshop.php">[ Register eShop ]</a>
    <table id="scrapyardTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Eshop</th>
                <th>Condition</th>
                <th>OEM</th>
                <th>Equipment</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Configuration</th>
                <th>Code</th>
                <th>Description</th>
                <th>Price</th>
                <th>IRE</th>
                <th>EUR</th>
                <th>Returns</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $uploadDir = '/images/uploads-scrapyard/';

            $query = "
            SELECT 
                granna80_bdlinks.scrapyard.*,
                granna80_bdlinks.scrapyard_eshops.name AS eshop_name,
                granna80_bdlinks.scrapyard_eshops.logo AS eshop_logo,
                granna80_bdlinks.scrapyard_brands.brand_image AS brand_logo
            FROM granna80_bdlinks.scrapyard
            LEFT JOIN granna80_bdlinks.scrapyard_eshops 
                ON granna80_bdlinks.scrapyard.eshop_id = granna80_bdlinks.scrapyard_eshops.id
            LEFT JOIN granna80_bdlinks.scrapyard_brands 
                ON TRIM(LOWER(granna80_bdlinks.scrapyard.Brand)) = TRIM(LOWER(granna80_bdlinks.scrapyard_brands.brand_name))
            ORDER BY granna80_bdlinks.scrapyard.ID ASC
        ";
        
        

            $result = $conn->query($query);
            $cont = 1;
            while ($row = $result->fetch_assoc()):
                $product_url = "";
                if ($row['eshop_name'] === 'Ebay') {
                    $product_url = "https://www.ebay.ie/itm/" . urlencode($row['product_code']);
                } elseif ($row['eshop_name'] === 'Amazon') {
                    $product_url = "https://www.amazon.com/dp/" . urlencode($row['product_code']);
                }
            ?>
                <tr>
                    <td><?= $cont ?></td>
                    <td>
                        <?php if ($row['eshop_logo'] && $product_url): ?>
                            <a href="<?= htmlspecialchars($product_url) ?>" target="_blank" title="<?= htmlspecialchars($row['eshop_name']) ?>">
                                <img
                                    src="<?= htmlspecialchars($uploadDir . $row['eshop_logo']) ?>"
                                    alt="<?= htmlspecialchars($row['eshop_name']) ?>"
                                    style="height: 30px; filter: <?= empty($row['product_code']) ? 'grayscale(100%)' : 'none' ?>;">

                            </a>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['Conditions']) ?></td>
                    <td><?= htmlspecialchars($row['Column_4']) ?></td>
                    <td><?= htmlspecialchars($row['Equipment']) ?></td>
                    <td>
            <?php if (!empty($row['brand_logo'])): ?>
                <img
                    src="/images/uploads-scrapyard/brands/<?= htmlspecialchars($row['brand_logo']) ?>"
                    alt="<?= htmlspecialchars($row['Brand']) ?>"
                    style="height: 30px;">
            <?php else: ?>
                <?= htmlspecialchars($row['Brand']) ?>
            <?php endif; ?>
        </td>
                    <td><?= htmlspecialchars($row['Model']) ?></td>
                    <td><?= htmlspecialchars($row['Config']) ?></td>
                    <td><?= htmlspecialchars($row['Code']) ?></td>
                    <td><?= htmlspecialchars($row['Description']) ?></td>
                    <td><?= htmlspecialchars($row['Price']) ?></td>
                    <td><?= htmlspecialchars($row['IRE']) ?></td>
                    <td><?= htmlspecialchars($row['EUR']) ?></td>
                    <td><?= htmlspecialchars($row['Returns']) ?></td>
                    <td class="action-icons">
                        <a href="edit_equipment.php?id=<?= $row['ID'] ?>" title="Edit">
                            <i class="fas fa-edit edit-icon"></i>
                        </a>
                        <a href="delete_equipment.php?id=<?= $row['ID'] ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this record?');">
                            <i class="fas fa-trash delete-icon"></i>
                        </a>
                    </td>
                </tr>
            <?php
                $cont++;
            endwhile;
            ?>
        </tbody>
    </table>


    <script>
        $(document).ready(function() {
            $('#scrapyardTable').DataTable({
                pageLength: 100,
                lengthChange: true,
                responsive: true
            });
        });
    </script>
</body>

</html>