<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; ?>
<?php
include 'conexao.php';

function get_default_settings()
{
    return [
        // Style Settings
        'font_style' => 'Roboto, Arial, sans-serif',
        'font_size' => '14px',
        'font_weight' => '400',
        'font_italic' => 0,
        'font_color' => '#333333',
        'background_color' => '#f4f4f4',
        'link_color' => '#1a0dab',
        'link_hover_color' => '#67458b',
        'table_background_color' => '#ffffff',
        'table_border_color' => '#dddddd',
        'header_color' => '#67458b',
        'header_font_color' => '#ffffff',
        'row_even_color' => '#f9f9f9',
        'row_hover_color' => '#f1f1f1',
        'table_font_size' => '13px',
        'show_summary_panel' => 1,

        'show_id' => 1,
        'show_images' => 1,
        'show_eshop' => 1,
        'show_condition' => 1,
        'show_oem' => 1,
        'show_equipment' => 1,
        'show_brand' => 1,
        'show_model' => 1,
        'show_configuration' => 1,
        'show_code' => 1,
        'show_description' => 1,
        'show_price' => 1,
        'show_qtd' => 1,
        'show_total' => 1,
        'show_location' => 1,
        'show_ire' => 1,
        'show_eur' => 1,
        'show_returns' => 1,
        'show_copy' => 1,
        'show_ebay' => 1,
        'show_last_edited_by' => 1,
        'show_status' => 1,
        'show_last_updated' => 1,
        'show_actions' => 1
    ];
}

// Gets settings for the current user, or defaults.
function getUserSettings($conn, $userEmail)
{
    if (empty($userEmail)) return get_default_settings();
    $stmt = $conn->prepare("SELECT * FROM granna80_bdlinks.scrapyard_settings WHERE user_email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Merge saved settings with defaults to ensure new settings are present
        return array_merge(get_default_settings(), $result->fetch_assoc());
    } else {
        return get_default_settings();
    }
}


$settings = getUserSettings($conn, $userEmail);

$summary_query = "
    SELECT
        status,
        COUNT(*) AS item_count,
        SUM(CAST(Price AS DECIMAL(10, 2)) * QTD) AS total_value
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,700;1,400&family=Open+Sans:ital,wght@0,400;0,700;1,400&family=Lato:ital,wght@0,400;0,700;1,400&family=Montserrat:ital,wght@0,400;0,600;0,700;1,400&family=Source+Sans+Pro:ital,wght@0,400;0,700;1,400&family=Nunito+Sans:ital,wght@0,400;0,700;1,400&family=Poppins:ital,wght@0,400;0,700;1,400&family=Raleway:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;700&family=Work+Sans:ital,wght@0,400;0,700;1,400&family=PT+Sans:ital,wght@0,400;0,700;1,400&family=Oswald:wght@400;700&family=Merriweather:ital,wght@0,400;0,700;1,400&family=Lora:ital,wght@0,400;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=EB+Garamond:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:ital,wght@0,400;0,700;1,400&family=Arvo:ital,wght@0,400;0,700;1,400&family=Lobster&family=Pacifico&family=Bebas+Neue&family=Alfa+Slab+One&family=Source+Code+Pro:ital,wght@0,400;0,700;1,400&family=Inconsolata:wght@400;700&family=Fira+Code:wght@400;700&display=swap" rel="stylesheet">
    <style id="user-settings-styles">
        body {
            background-color: <?= htmlspecialchars($settings['background_color']) ?> !important;
        }

        .theme-wrapper {
            font-family: <?= htmlspecialchars($settings['font_style']) ?>;
            font-size: <?= htmlspecialchars($settings['font_size']) ?>;
            font-weight: <?= htmlspecialchars($settings['font_weight']) ?>;
            font-style: <?= $settings['font_italic'] ? 'italic' : 'normal' ?>;
            color: <?= htmlspecialchars($settings['font_color']) ?>;
            background-color: <?= htmlspecialchars($settings['background_color']) ?>;

        }


        .theme-wrapper a {
            color: <?= htmlspecialchars($settings['link_color']) ?>;
        }

        .theme-wrapper a:hover {
            color: <?= htmlspecialchars($settings['link_hover_color']) ?>;
        }


        .theme-wrapper .action-icons a {
            color: inherit;
        }

        .theme-wrapper .edit-icon:hover {
            color: blue;
        }

        .theme-wrapper .delete-icon:hover {
            color: red;
        }



        .theme-wrapper table#scrapyardTable {
            background-color: <?= htmlspecialchars($settings['table_background_color']) ?>;
            border: 1px solid <?= htmlspecialchars($settings['table_border_color']) ?>;
        }

        .theme-wrapper table#scrapyardTable th,
        .theme-wrapper table#scrapyardTable td {
            border: 1px solid <?= htmlspecialchars($settings['table_border_color']) ?>;
            font-size: <?= htmlspecialchars($settings['table_font_size']) ?>;
        }

        .theme-wrapper table#scrapyardTable th {
            background-color: <?= htmlspecialchars($settings['header_color']) ?>;
            color: <?= htmlspecialchars($settings['header_font_color']) ?>;
        }

        .theme-wrapper table#scrapyardTable tr:nth-child(even) {
            background-color: <?= htmlspecialchars($settings['row_even_color']) ?>;
        }

        .theme-wrapper table#scrapyardTable tr:hover {
            background-color: <?= htmlspecialchars($settings['row_hover_color']) ?>;
        }


        .theme-wrapper .dataTables_paginate .paginate_button {
            color: <?= htmlspecialchars($settings['link_color']) ?> !important;
        }

        .theme-wrapper .dataTables_paginate .paginate_button.current,
        .theme-wrapper .dataTables_paginate .paginate_button.current:hover {
            background: <?= htmlspecialchars($settings['header_color']) ?> !important;
            color: <?= htmlspecialchars($settings['header_font_color']) ?> !important;
            border-color: <?= htmlspecialchars($settings['header_color']) ?>;
        }
    </style>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;

            margin: 0;
            padding: 10px;
        }

        h1,
        h4 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            background-color: #fff;

        }

        th,
        td {
            padding: 4px 6px;
            text-align: left;
            border: 1px solid #ddd;
            vertical-align: middle;

        }

        th {
            background-color: #67458b;
            color: white;
            padding: 6px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        a {
            color: #9362C6;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }


        .edit-icon {
            color: blue;
            cursor: pointer;
        }

        .delete-icon {
            color: red;
            cursor: pointer;
        }

        .action-icons {
            display: flex;
            gap: 10px;
            justify-content: center;
            white-space: nowrap;

        }


        .datatable-top-controls {
            display: flex;
            justify-content: space-between;

            align-items: center;
            padding: 10px 0;
        }


        .datatable-bottom-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }


        .dataTables_length label {
            font-weight: normal;

            color: #333;
        }

        .dataTables_length select {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 6px;
            margin: 0 5px;
            background-color: #fff;
        }


        .dataTables_filter {
            flex-grow: 1;

            text-align: center;

        }

        .dataTables_filter label {
            font-weight: bold;
            color: #333;
        }

        .dataTables_filter input[type="search"] {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
            margin-left: 10px;
            width: 40%;

            max-width: 400px;
        }


        .dataTables_info {
            color: #333;
            padding: 8px 0;
        }


        .dataTables_paginate {
            text-align: right;
        }

        .dataTables_paginate .paginate_button {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 2px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #67458b;
            background-color: #fff;
            cursor: pointer;
            text-decoration: none;
        }

        .dataTables_paginate .paginate_button:hover {
            background-color: #f2f2f2;
            border-color: #ccc;
            text-decoration: none;
        }

        .dataTables_paginate .paginate_button.current {
            background-color: #67458b;
            color: white;
            border-color: #67458b;
        }

        .dataTables_paginate .paginate_button.disabled {
            color: #ccc;
            cursor: not-allowed;
            background-color: #f9f9f9;
        }
    </style>

</head>

<body>
    <div class="theme-wrapper">
        <h1>Scrapyard Equipment List</h1>


        <a href="register_brands.php">[ Register Brands ]</a><br>
        <a href="register_models.php">[ Register Models ]</a><br>
        <a href="add_new_equipment.php">[ Add new product ]</a>
        <a href="register_eshop.php">[ Register eShop ]</a>
        <a href="register_equipament.php">[ Register equipment ]</a>
        <a href="settings.php">[ Settings ]</a>

        <a href="<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/main.php'; ?>">[Back]</a>
        <br><br>

        <style>
            .totals-container {
                display: flex;
                justify-content: flex-start;
                flex-wrap: wrap;
                gap: 20px;
                margin: 20px 0;

                font-family: <?= htmlspecialchars($settings['font_style']) ?>;
                font-size: <?= htmlspecialchars($settings['font_size']) ?>;
                font-style: <?= $settings['font_italic'] ? 'italic' : 'normal' ?>;
            }

            .total-box {

                border: 1px solid <?= htmlspecialchars($settings['table_border_color']) ?>;
                background-color: <?= htmlspecialchars($settings['table_background_color']) ?>;
                border-radius: 8px;
                padding: 15px;
                min-width: 400px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .total-box h3 {
                margin-top: 0;

                border-bottom: 1px solid <?= htmlspecialchars($settings['table_border_color']) ?>;
                color: <?= htmlspecialchars($settings['font_color']) ?>;
                padding-bottom: 10px;
            }

            .total-box p {
                margin: 10px 0;
                font-size: 1em;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .total-box p strong {

                color: <?= htmlspecialchars($settings['font_color']) ?>;
                opacity: 0.9;
            }
        </style>
        <?php if ($settings['show_summary_panel']): ?>
            <div class="totals-container">
                <div class="total-box">
                    <h3>Datalist Panel</h3>

                    <p>
                        <strong>
                            <i style="color: #28a745; margin-right: 8px;"></i>
                            Active Products: <?= $status_summary['Active']['count'] ?>
                        </strong>
                        <span style="font-weight: bold; color: #28a745;">
                            <?= number_format($status_summary['Active']['value'] ?? 0, 2) ?> EUR
                        </span>
                    </p>

                    <p>
                        <strong>
                            <i style="color: #dc3545; margin-right: 8px;"></i>
                            Sold Products: <?= $status_summary['Sold']['count'] ?>
                        </strong>
                        <span style="font-weight: bold; color: #dc3545;">
                            <?= number_format($status_summary['Sold']['value'] ?? 0, 2) ?> EUR
                        </span>
                    </p>

                    <?php if ($status_summary['Test']['count'] > 0): ?>
                        <p>
                            <strong>
                                <i style="color: #ffc107; margin-right: 8px;"></i>
                                Test Products: <?= $status_summary['Test']['count'] ?>
                            </strong>
                            <span style="font-weight: bold; color: #ffc107;">
                                <?= number_format($status_summary['Test']['value'] ?? 0, 2) ?> EUR
                            </span>
                        </p>
                    <?php endif; ?>

                    <p id="query-total-container" style="display: none; border-top: 2px dashed #eee; margin-top: 3px; padding-top: 3px;">
                        <strong>
                            <i style="color: #007bff; margin-right: 8px;"></i>
                            Query products total: <span id="search-count" style="font-weight: bold;">0</span>
                        </strong>
                        <span id="search-total" style="font-weight: bold; color: #007bff;">
                            0.00 EUR
                        </span>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <table id="scrapyardTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Images</th>
                    <th>Eshop</th>
                    <th>Condition</th>
                    <th></th> <!--OEM-->
                    <th>                 Equipment                 </th>
                    <th> </th> <!--Brand-->
                    <th>Model                         </th>
                    <th>   Configuration             </th>
                    <th>Code                                          </th>
                    <th>          Description          </th>
                    <th>Price</th>
                    <th>QTD</th>
                    <th>Total</th>
                    <th>Location</th>
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

                                        <div style="position: relative; width: 25px; height: 25px;">

                                            <img
                                                src="<?= $imagePath ?>"
                                                alt="Image <?= $i ?>"
                                                title="Image <?= $i ?>"
                                                draggable="true"
                                                ondragstart="event.dataTransfer.setData('text/plain', '<?= $imagePath ?>');"
                                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px; cursor: grab;" />


                                            <div
                                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: white; border-radius: 4px; pointer-events: none;">
                                                <i class="fa-solid fa-image" style="color: green; font-size: 25px;"></i>
                                            </div>
                                        </div>
                                    <?php else: ?>

                                        <div
                                            style="width: 25px; height: 25px; background-color: #999999; display: flex; align-items: center; justify-content: center; border-radius: 4px; color: white; cursor: not-allowed;"
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
                                                    style="height: 25px;">
                                            </a>
                                        <?php else: ?>

                                            <img
                                                src="<?= htmlspecialchars($uploadDir . $eshop_logo) ?>"
                                                alt="<?= htmlspecialchars($eshop_name) ?>"
                                                title="Product code unavailable"
                                                style="height: 25px; filter: grayscale(100%) brightness(50%) invert(75%) sepia(20%) saturate(0%); cursor: not-allowed;">
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

                            if (strtolower($model_display) == 'null' || strtolower($model_display) == '足null') {
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
                        <?php

                        $qtd = (int)($row['QTD'] ?? 1);
                        $price = (float)($row['Price'] ?? 0);
                        $total = $qtd * $price;
                        ?>
                        <td><?= htmlspecialchars($qtd) ?></td>
                        <td><?= number_format($total, 2) ?></td>
                        <td><?= htmlspecialchars($row['location'] ?? '') ?></td>
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


                            $counter_color = ($char_count <= 80) ? 'green' : 'red';
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
                                &#x2398;
                            </button>
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
                            <div style="color: <?= $counter_color ?>; font-weight: bold; font-size: 12px; margin-top: 4px;">
                                <?= $char_count ?>
                            </div>

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
            <?php

            $column_map = [
                'show_id',
                'show_images',
                'show_eshop',
                'show_condition',
                'show_oem',
                'show_equipment',
                'show_brand',
                'show_model',
                'show_configuration',
                'show_code',
                'show_description',
                'show_price',
                'show_qtd',
                'show_total',
                'show_location',
                'show_ire',
                'show_eur',
                'show_returns',
                'show_copy',
                'show_ebay',
                'show_last_edited_by',
                'show_status',
                'show_last_updated',
                'show_actions'
            ];

            $hiddenColumns = [];
            foreach ($column_map as $index => $setting_key) {
                // Use empty() to check for 0, false, or null, which all mean "hide"
                if (empty($settings[$setting_key])) {
                    $hiddenColumns[] = $index;
                }
            }
            ?>

            // This variable will now contain all columns the user chose to hide
            var columnsToHide = <?= json_encode($hiddenColumns) ?>;

            $(document).ready(function() {
                var table = $('#scrapyardTable').DataTable({
                    pageLength: 250,
                    lengthChange: true,
                    responsive: true,
                    lengthMenu: [100, 250, 500, 1000],
                    dom: '<"datatable-top-controls"lf>t<"datatable-bottom-controls"ip>',
                    "columnDefs": [{
                        "targets": columnsToHide, // Our array of columns to hide
                        "visible": false // The action: make them invisible
                    }]
                });

                // The rest of your search summary script (no changes needed here)
                var queryTotalContainer = $('#query-total-container');
                var searchCountElement = $('#search-count');
                var searchTotalElement = $('#search-total');

                function updateSearchSummary() {
                    var searchTerm = table.search();
                    var filteredRows = table.rows({
                        search: 'applied'
                    });

                    if (searchTerm === '') {
                        queryTotalContainer.hide();
                        return;
                    }

                    var totalValue = 0;
                    var productCount = filteredRows.nodes().length;
                    const priceColumnIndex = 11;

                    filteredRows.data().each(function(rowData) {
                        var priceString = rowData[priceColumnIndex];
                        var cleanedPriceString = String(priceString).replace(/[^0-9.]/g, '');
                        var price = parseFloat(cleanedPriceString) || 0;
                        totalValue += price;
                    });

                    searchCountElement.text(productCount);
                    searchTotalElement.text(totalValue.toFixed(2) + ' EUR');
                    queryTotalContainer.show();
                }

                table.on('search.dt draw.dt', function() {
                    updateSearchSummary();
                });

                updateSearchSummary();
            });
        </script>

    </div>
</body>

</html>