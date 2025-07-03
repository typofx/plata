<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php
include 'conexao.php';


$summary_query = "
    SELECT
        status,
        COUNT(*) AS item_count,
        SUM(CAST(Price AS DECIMAL(10, 2))) AS total_value
    FROM
        granna80_bdlinks.scrapyard
    GROUP BY
        status
";

$summary_result = $conn->query($summary_query);


$status_summary = [
    'Active' => ['count' => 0, 'value' => 0.00],
    'Sold' => ['count' => 0, 'value' => 0.00],
    'Test' => ['count' => 0, 'value' => 0.00]
];


if ($summary_result) {
    while ($summary_row = $summary_result->fetch_assoc()) {
        $status = $summary_row['status'];

        if (isset($status_summary[$status])) {
            $status_summary[$status]['count'] = $summary_row['item_count'];
            $status_summary[$status]['value'] = $summary_row['total_value'];
        }
    }
}



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
    <a href="register_equipament.php">[ Register equipment ]</a>

    <a href="<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/main.php'; ?>">[Back]</a>
    <br><br>

    <div>
        <p style="margin: 5px 0;">
            <strong>Active Products:</strong> <?= $status_summary['Active']['count'] ?> :
            <span style="color: green;"><?= number_format($status_summary['Active']['value'] ?? 0, 2) ?> EUR</span>
        </p>
        <p style="margin: 5px 0;">
            <strong>Sold Products:</strong> <?= $status_summary['Sold']['count'] ?> :
            <span style="color: red;"><?= number_format($status_summary['Sold']['value'] ?? 0, 2) ?> EUR</span>
        </p>
        <p style="margin: 5px 0;">
            <strong>Query products total: </strong>
            <span id="search-total" style="color: blue; font-weight: bold;">0.00 EUR</span>
        </p>
        <p style="margin: 5px 0;">
            <strong>Test Products:</strong> <?= $status_summary['Test']['count'] ?? 0 ?> :
            <span style="color: #cccc00;"><?= number_format($status_summary['Test']['value'] ?? 0, 2) ?> EUR</span>
        </p>
    </div>


    <table id="scrapyardTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Images</th>
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
                <th>Copy</th>
                <th>eBay</th>
                <th>Last Edited By</th>
                <th>Status</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $uploadDir = '/images/uploads-scrapyard/';
            $query = "
    SELECT 
        scrapyard.*,
        scrapyard_brands.brand_image AS brand_logo,
        scrapyard_brands.brand_name, 
        scrapyard_equipment.name AS equipment_name,
        scrapyard_models.model_name 
    FROM granna80_bdlinks.scrapyard
    LEFT JOIN granna80_bdlinks.scrapyard_brands 
        ON scrapyard.brand_id = scrapyard_brands.brand_id 
    LEFT JOIN granna80_bdlinks.scrapyard_equipment
        ON scrapyard.Equipment = scrapyard_equipment.id
    LEFT JOIN granna80_bdlinks.scrapyard_models 
        ON scrapyard.model_id = scrapyard_models.model_id 
    ORDER BY scrapyard.ID ASC
";

            $result = $conn->query($query);
            $cont = 1;

            while ($row = $result->fetch_assoc()):
                $eshop_data = $row['eshop_data'];
                $eshops = [];


                if (!empty($eshop_data)) {
                    $eshop_entries = explode(',', $eshop_data);
                    foreach ($eshop_entries as $entry) {
                        [$eshop_id, $product_code] = explode(':', $entry);
                        $eshops[] = [
                            'id' => $eshop_id,
                            'product_code' => $product_code,
                        ];
                    }
                }
                $brand_text = (isset($row['brand_name']) && strtolower(trim($row['brand_name'])) !== 'null') ? $row['brand_name'] : '';
                $model_text = (isset($row['model_name']) && strtolower(trim($row['model_name'])) !== 'null') ? $row['model_name'] : '';


                $row_style = '';

          
                if (isset($row['status']) && $row['status'] === 'Test') {
              
                    $row_style = 'style="background-color: #fffacd;"';
                }

            ?>
               <tr <?= $row_style ?>>
                    <td><?= $cont ?></td>
                    <td>

                        <div style="display: flex; gap: 10px;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php
                                $hasImage = !empty($row["image$i"]);
                                $imagePath = $hasImage ? $uploadDir . "/equipaments/" . htmlspecialchars($row["image$i"]) : '';
                                ?>
                                <?php if ($hasImage): ?>

                                    <div style="position: relative; width: 30px; height: 30px;">

                                        <img
                                            src="<?= $imagePath ?>"
                                            alt="Image <?= $i ?>"
                                            title="Image <?= $i ?>"
                                            draggable="true"
                                            ondragstart="event.dataTransfer.setData('text/plain', '<?= $imagePath ?>');"
                                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px; cursor: grab;" />


                                        <div
                                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: white; border-radius: 4px; pointer-events: none;">
                                            <i class="fa-solid fa-image" style="color: green; font-size: 30px;"></i>
                                        </div>
                                    </div>
                                <?php else: ?>

                                    <div
                                        style="width: 30px; height: 30px; background-color: #999999; display: flex; align-items: center; justify-content: center; border-radius: 4px; color: white; cursor: not-allowed;"
                                        title="No Image Available">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </td>
                    <td>
                        <?php

                        $searchable_text = '';
                        ?>
                        <div style="display: flex; gap: 10px;">
                            <?php

                            $eshop_query = $conn->query("SELECT id, name, logo, link FROM granna80_bdlinks.scrapyard_eshops");


                            while ($eshop_data = $eshop_query->fetch_assoc()):
                                $eshop_id = intval($eshop_data['id']);
                                $eshop_name = $eshop_data['name'] ?? 'Unknown eShop';
                                $eshop_logo = $eshop_data['logo'] ?? 'default-logo.png';
                                $base_url = $eshop_data['link'] ?? '';


                                $product_code = '';
                                foreach ($eshops as $eshop) {
                                    if ($eshop['id'] == $eshop_id) {
                                        $product_code = $eshop['product_code'] ?? '';
                                        break;
                                    }
                                }

                                $product_url = '';
                                if (!empty($base_url) && !empty($product_code)) {
                                    $product_url = rtrim($base_url, '/') . '/' . urlencode($product_code);
                                }
                            ?>
                                <div style="display: inline-block; margin: 1px; text-align: center;">
                                    <?php if (!empty($product_code)): ?>

                                        <?php
                                        $searchable_text .= $eshop_name . ' ' . $product_code . ' ';
                                        ?>

                                        <a href="<?= htmlspecialchars($product_url) ?>" target="_blank" title="<?= htmlspecialchars($eshop_name) ?>">
                                            <img
                                                src="<?= htmlspecialchars($uploadDir . $eshop_logo) ?>"
                                                alt="<?= htmlspecialchars($eshop_name) ?>"
                                                style="height: 30px;">
                                        </a>
                                    <?php else: ?>

                                        <img
                                            src="<?= htmlspecialchars($uploadDir . $eshop_logo) ?>"
                                            alt="<?= htmlspecialchars($eshop_name) ?>"
                                            title="Product code unavailable"
                                            style="height: 30px; filter: grayscale(100%) brightness(50%) invert(75%) sepia(20%) saturate(0%); cursor: not-allowed;">
                                    <?php endif; ?>
                                    <div style="font-size: 12px; color: #666;"></div>
                                </div>
                            <?php endwhile; ?>



                        </div>
                        <span style="display: none;"><?= htmlspecialchars($searchable_text) ?></span>
                    </td>
                    <td><?= htmlspecialchars($row['Conditions']) ?></td>
                    <td><?= htmlspecialchars($row['Column_4'] === 'yes' ? 'OEM' : '') ?></td>
                    <td><?= htmlspecialchars($row['equipment_name']) ?></td>
                    <td>
                        <?php

                        if (!empty($row['brand_logo'])) {
                            echo '<img src="/images/uploads-scrapyard/brands/' . htmlspecialchars($row['brand_logo']) . '" alt="' . htmlspecialchars($row['brand_name']) . '" style="height: 30px;">';


                            echo '<span style="display: none;">' . htmlspecialchars($row['brand_name']) . '</span>';
                        }

                        ?>
                    </td>
                    <td>
                        <?php

                        $model_display = $row['model_name'] ?? '';

                        if (strtolower($model_display) == 'null' || strtolower($model_display) == '­null') {
                            echo '';
                        } else {
                            echo htmlspecialchars($model_display);
                        }
                        ?>
                    </td>


                    <td><?= htmlspecialchars($row['Config']) ?></td>
                    <td><?= htmlspecialchars($row['Code']) ?></td>
                    <td><?= htmlspecialchars($row['Description']) ?></td>
                    <td><?= number_format((float)$row['Price'], 2) ?></td>
                    <td><?= htmlspecialchars($row['IRE']) ?></td>
                    <td><?= htmlspecialchars($row['EUR']) ?></td>
                    <td><?= htmlspecialchars($row['Returns']) ?></td>
                    <td>
                        <?php

                        $text_to_copy_parts = [
                            $row['Conditions'],
                            $row['Column_4'] === 'yes' ? 'OEM' : '',
                            $row['equipment_name'],
                            $brand_text,
                            $model_text,
                            $row['Config'],
                            $row['Code'],
                            $row['Description']
                        ];

                        $full_text_to_copy = implode(' ', array_filter($text_to_copy_parts));


                        $char_count = strlen($full_text_to_copy);


                        $counter_color = ($char_count < 80) ? 'green' : 'red';
                        ?>

                        <button
                            class="copy-btn"
                            data-content="<?= htmlspecialchars(json_encode([
                                                'Condition' => $row['Conditions'],
                                                'OEM' => $row['Column_4'] === 'yes' ? 'OEM' : '',
                                                'Equipment' => $row['equipment_name'],
                                                'Brand' => $brand_text,
                                                'Model' =>  $model_text,
                                                'Configuration' => $row['Config'],
                                                'Code' => $row['Code'],
                                                'Description' => $row['Description'],
                                            ])) ?>">
                            Copy
                        </button>

                        <div style="color: <?= $counter_color ?>; font-weight: bold; font-size: 12px; margin-top: 4px;">
                            (<?= $char_count ?>)
                        </div>
                    </td>

                    <?php



                    $price = number_format((float)$row['Price'], 2);

                    $title_parts = [
                        $row['Conditions'],
                        $row['Column_4'] === 'yes' ? 'OEM' : '',
                        $row['equipment_name'],
                        $brand_text,
                        $model_text,
                        $row['Config'],
                        $row['Code'],
                        $row['Description']
                    ];


                    $title = rawurlencode(implode(' ', array_filter($title_parts)));
                    $ebay_url = "https://www.ebay.ie/lstng?mode=AddItem&price={$price}&categoryId=168061&aspects=eJyLjgUAARUAuQ%3D%3D&condition=3000&title={$title}";


                    ?>
                    <td>
                        <!-- Link eBay -->
                        <a href="<?= $ebay_url ?>" target="_blank" title="Add to eBay">
                            <i class="fab fa-ebay" style="font-size: 20px; color: #0064D2;"></i>
                        </a>
                    </td>

                    <td style="white-space: nowrap;">
                        <?= htmlspecialchars($row['last_edited_by']) ?>
                    </td>


                    <td style="white-space: nowrap;">
                        <?= htmlspecialchars($row['status']) ?>
                    </td>

                    <td style="white-space: nowrap;">
                        <?php

                        if ($row['status'] === 'Sold' && !empty($row['sold_date'])) {

                            echo '<span style="color: red;">' . date('d-m-Y', strtotime($row['sold_date'])) . '</span>';
                        } else {

                            echo date('d-m-Y', strtotime($row['last_updated']));
                        }
                        ?>
                    </td>





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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.copy-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const data = JSON.parse(this.dataset.content);




                    let content = `
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span>${data.Condition} ${data.OEM} ${data.Equipment} ${data.Brand} ${data.Model} ${data.Configuration} ${data.Code} ${data.Description}</span>
                </div>
                `;

                    // Create a temporary element to copy the HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = content;
                    document.body.appendChild(tempDiv);

                    // Use Range and Selection API to copy HTML content
                    const range = document.createRange();
                    range.selectNodeContents(tempDiv);
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);

                    try {
                        document.execCommand('copy');
                        alert('Content copied to clipboard!');
                    } catch (err) {
                        alert('Failed to copy content.');
                        console.error(err);
                    }

                    // Cleanup
                    selection.removeAllRanges();
                    document.body.removeChild(tempDiv);
                });
            });
        });
    </script>



    <script>
        $(document).ready(function() {

            var table = $('#scrapyardTable').DataTable({
                pageLength: 250,
                lengthChange: true,
                responsive: true,
                lengthMenu: [100, 250, 500, 1000]
            });


            var searchTotalElement = $('#search-total');


            function updateSearchTotal() {

                var searchTerm = table.search();


                if (searchTerm === '') {
                    searchTotalElement.text('0.00 EUR');
                    return;
                }


                var total = 0;
                const priceColumnIndex = 11;

                table.rows({
                    search: 'applied'
                }).data().each(function(rowData) {
                    var priceString = rowData[priceColumnIndex];
                    var price = parseFloat(priceString) || 0;
                    total += price;
                });


                searchTotalElement.text(total.toFixed(2) + ' EUR');
            }


            table.on('draw.dt', function() {
                updateSearchTotal();
            });
        });
    </script>
</body>

</html>