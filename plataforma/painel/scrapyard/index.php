<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php
include 'conexao.php';


$query = "SELECT SUM(CAST(Price AS DECIMAL(10, 2))) AS total_price FROM granna80_bdlinks.scrapyard";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    // echo "The total sum of all prices is: " . $row['total_price'];
} else {
    echo "Error: " . mysqli_error($conn);
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
    <p>Total of the Products: <?php echo $row['total_price'];  ?></p>
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
                <th>Last Updated</th>
                <th>Last Edited By</th>
                <th>Sold</th>
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
                scrapyard_brands.brand_name AS brand_name,
                scrapyard_equipment.name AS equipment_name
            FROM granna80_bdlinks.scrapyard
            LEFT JOIN granna80_bdlinks.scrapyard_brands 
                ON TRIM(LOWER(scrapyard.Brand)) = TRIM(LOWER(scrapyard_brands.brand_name))
            LEFT JOIN granna80_bdlinks.scrapyard_equipment
                ON scrapyard.Equipment = scrapyard_equipment.id
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
            ?>
                <tr>
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
                        <?php if (!empty($row['brand_logo'])): ?>
                            <img
                                src="/images/uploads-scrapyard/brands/<?= htmlspecialchars($row['brand_logo']) ?>"
                                alt="<?= htmlspecialchars($row['brand_name']) ?>"
                                style="height: 30px;">

                            <span style="display: none;"><?= htmlspecialchars($row['brand_name']) ?></span>

                        <?php else: ?>
                            <?= htmlspecialchars($row['Brand']) ?>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['Model'] === 'Null' ? '' : $row['Model']) ?></td>
                    <td><?= htmlspecialchars($row['Config']) ?></td>
                    <td><?= htmlspecialchars($row['Code']) ?></td>
                    <td><?= htmlspecialchars($row['Description']) ?></td>
                    <td><?= number_format((float)$row['Price'], 2) ?></td>
                    <td><?= htmlspecialchars($row['IRE']) ?></td>
                    <td><?= htmlspecialchars($row['EUR']) ?></td>
                    <td><?= htmlspecialchars($row['Returns']) ?></td>
                    <td>
                        <button
                            class="copy-btn"
                            data-content="<?= htmlspecialchars(json_encode([
                                                'Condition' => $row['Conditions'],
                                                'OEM' => $row['Column_4'] === 'yes' ? 'OEM' : '',
                                                'Equipment' => $row['equipment_name'],
                                                'Brand' => $row['Brand'],
                                                'Model' => $row['Model'] === 'Null' ? '' : $row['Model'],
                                                'Configuration' => $row['Config'],
                                                'Code' => $row['Code'],
                                                'Description' => $row['Description'],
                                            ])) ?>">
                            Copy
                        </button>
                    </td>

                    <?php



                    $price = number_format((float)$row['Price'], 2);

                    $title_parts = [
                        $row['Conditions'],
                        $row['Column_4'] === 'yes' ? 'OEM' : '',
                        $row['equipment_name'],
                        $row['Brand'],
                        $row['Model'] === 'Null' ? '' : $row['Model'],
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
                        <?= date('d-m-Y', strtotime($row['last_updated'])) ?>
                    </td>

                    <td style="white-space: nowrap;">
                        <?= htmlspecialchars($row['last_edited_by']) ?>
                    </td>

                    <td style="white-space: nowrap;">
                        <?= !empty($row['sold_date']) ? date('d-m-Y', strtotime($row['sold_date'])) : ' ' ?>
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
        $('#scrapyardTable').DataTable({
            pageLength: 250,
            lengthChange: true,
            responsive: true,
            lengthMenu: [100, 250, 500, 1000] 
        });
    });
</script>
</body>

</html>