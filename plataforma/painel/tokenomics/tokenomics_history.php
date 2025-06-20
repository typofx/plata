<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';

include 'conexao.php';

ob_start();
require '/home2/granna80/public_html/en/mobile/price.php'; 
ob_end_clean();
require_once 'get_live_data.php'; 


if (!function_exists('get_dex_base_name_from_string')) {
    function get_dex_base_name_from_string($exchange_name)
    {
        $known_dexes = ['MM Finance', 'CurveFi', 'SushiSwap', 'QuickSwap', 'Uniswap'];
        foreach ($known_dexes as $dex) {
            if (stripos($exchange_name, $dex) === 0) {
                return $dex;
            }
        }
        return explode(' ', $exchange_name)[0];
    }
}

if (!function_exists('get_authoritative_display_name')) {
    function get_authoritative_display_name($group_name_tag, $available_groups_map)
    {
        if (isset($available_groups_map[$group_name_tag])) {
        
            return $available_groups_map[$group_name_tag];
        }
        if ($group_name_tag == 'others') return 'Others';

     
        return ucwords(str_replace('_', ' ', $group_name_tag));
    }
}

$groups_result = $conn->query("SELECT tag, name FROM granna80_bdlinks.finance_tools_groups ORDER BY name ASC");
$available_groups = [];
while ($group_row = $groups_result->fetch_assoc()) {
    $available_groups[] = $group_row;
}

$circulating_supply = 11299000992;
$edit_data = null;
$action = $_REQUEST['action'] ?? '';



$dates_result = $conn->query("SELECT DISTINCT price_date FROM granna80_bdlinks.tokenomics_history WHERE price_date IS NOT NULL ORDER BY price_date DESC");
$available_dates = [];
while ($date_row = $dates_result->fetch_assoc()) {
    $available_dates[] = $date_row['price_date'];
}


$date_filter = $_GET['date_filter'] ?? 'live';
if (empty($date_filter)) {
    $date_filter = 'live';
}

$where_clause = ''; 


if (!empty($date_filter)) {

    $where_clause = " WHERE price_date = '" . $conn->real_escape_string($date_filter) . "'";
}





if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM granna80_bdlinks.tokenomics_history WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {

        $redirect_url = 'tokenomics_history.php?date_filter=' . urlencode($date_filter);
        echo "<script>window.location.href='$redirect_url';</script>";
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $record_year = intval($_POST['record_year']);
    $record_month = intval($_POST['record_month']);
    $exchange = $_POST['exchange'];
    $liquidity = $_POST['liquidity'];
    $percentage = $_POST['percentage'];
    $plata = $_POST['plata'];
    $plt_price = $_POST['plt_price'];
    $price_date = $_POST['price_date'];





    if ($action === 'mass_update_price') {

        $plt_price = $_POST['plt_price'];
        $price_date = $_POST['price_date'];
        $date_to_update = $_POST['date_to_update']; 

     
        $sql_mass_update = "UPDATE granna80_bdlinks.tokenomics_history SET plt_price = ?, price_date = ? WHERE price_date = ?";
        $stmt_mass_update = $conn->prepare($sql_mass_update);
        $stmt_mass_update->bind_param("dss", $plt_price, $price_date, $date_to_update);

        if ($stmt_mass_update->execute()) {
            $stmt_mass_update->close();

            $sql_select_rows = "SELECT id, plata FROM granna80_bdlinks.tokenomics_history WHERE price_date = ?";
            $stmt_select = $conn->prepare($sql_select_rows);
            $stmt_select->bind_param("s", $date_to_update);
            $stmt_select->execute();
            $result_rows = $stmt_select->get_result();


            $sql_update_row = "UPDATE granna80_bdlinks.tokenomics_history SET liquidity = ?, percentage = ? WHERE id = ?";
            $stmt_update_row = $conn->prepare($sql_update_row);

            while ($row_to_update = $result_rows->fetch_assoc()) {
                $new_liquidity = $plt_price * $row_to_update['plata'];
                $new_percentage = $row_to_update['plata'] / $circulating_supply;
                $row_id = $row_to_update['id'];

                $stmt_update_row->bind_param("ddi", $new_liquidity, $new_percentage, $row_id);
                $stmt_update_row->execute();
            }

            $stmt_select->close();
            $stmt_update_row->close();

  
            $redirect_url_after_update = 'tokenomics_history.php?date_filter=' . $date_to_update;
            echo "<script>alert('Success! Price, liquidity and percentage have been updated.'); window.location.href='$redirect_url_after_update';</script>";
            exit();
        } else {
            echo "ERROR UPDATING PRICE: " . $stmt_mass_update->error;
        }
    } elseif ($action === 'create') {

        $record_month = intval($_POST['record_month']);
        $exchange = $_POST['exchange'];
        $plata = $_POST['plata'];
        $group_wallet = $_POST['group_wallet'];
        $walletAddress = $_POST['walletAddress'];

        $price_query = $conn->prepare("SELECT plt_price, price_date FROM granna80_bdlinks.tokenomics_history WHERE record_year = ? AND record_month = ? LIMIT 1");
        $price_query->bind_param("ii", $record_year, $record_month);
        $price_query->execute();
        $price_result = $price_query->get_result()->fetch_assoc();
        $price_query->close();


        $current_price = $price_result ? (float)$price_result['plt_price'] : 0;
        $current_date = $price_result ? $price_result['price_date'] : '';



        $liquidity = $current_price * (float)$plata;
        $percentage = $plata / $circulating_supply;



        $sql = "INSERT INTO granna80_bdlinks.tokenomics_history (record_year, record_month, exchange, liquidity, percentage, plata, plt_price, price_date, group_wallet, walletAddress) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        $stmt->bind_param("iisddddsss", $record_year, $record_month, $exchange, $liquidity, $percentage, $plata, $current_price, $current_date, $group_wallet, $walletAddress);


        if ($stmt->execute()) {
            $redirect_filter = $_POST['current_date_filter'] ?? 'live';
            $correct_redirect_url = 'tokenomics_history.php?date_filter=' . urlencode($redirect_filter);
            echo "<script>window.location.href='$correct_redirect_url';</script>";
            exit();
        } else {
            echo "Error creating record: " . $stmt->error;
        }
        $stmt->close();
    } elseif ($action === 'update' && isset($_POST['id'])) {

        $id = intval($_POST['id']);
        $record_month = intval($_POST['record_month']);
        $exchange = $_POST['exchange'];
        $plata = $_POST['plata'];
        $group_wallet = $_POST['group_wallet']; 
        $walletAddress = $_POST['walletAddress']; 

        $price_query = $conn->prepare("SELECT plt_price FROM granna80_bdlinks.tokenomics_history WHERE record_year = ? AND record_month = ? LIMIT 1");
        $price_query->bind_param("ii", $record_year, $record_month);
        $price_query->execute();
        $price_result = $price_query->get_result()->fetch_assoc();
        $price_query->close();
        $current_price = $price_result ? (float)$price_result['plt_price'] : 0;


        $liquidity = $current_price * (float)$plata;
        $percentage = (float)$plata / $circulating_supply;



        $sql_single = "UPDATE granna80_bdlinks.tokenomics_history SET record_year=?, record_month=?, exchange=?, liquidity=?, percentage=?, plata=?, group_wallet=?, walletAddress=? WHERE id=?";
        $stmt_single = $conn->prepare($sql_single);
        // bind_param atualizado
        $stmt_single->bind_param("iisdddssi", $record_year, $record_month, $exchange, $liquidity, $percentage, $plata, $group_wallet, $walletAddress, $id);

        if ($stmt_single->execute()) {
            $redirect_filter = $_POST['current_date_filter'] ?? 'live';
            $correct_redirect_url = 'tokenomics_history.php?date_filter=' . urlencode($redirect_filter);
            echo "<script>window.location.href='$correct_redirect_url';</script>";
            exit();
        } else {
            echo "Error updating record: " . $stmt_single->error;
        }
        $stmt_single->close();
    }
}

if ($action === 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM granna80_bdlinks.tokenomics_history WHERE id = $id");
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
    }
}





$total_liquidity = 0;
$total_percentage = 0;
$total_plata = 0;


$table_render_data = [];
$date_filter = $_GET['date_filter'] ?? 'live';
if (empty($date_filter)) {
    $date_filter = 'live';
}


$year_for_json = gmdate('Y');
$month_for_json = gmdate('n');
$price_for_heading = 'N/A';
$price_date_view = '';
$month_name_for_title = '';
$year_for_title = '';
$history_total_liquidity = 0;
$history_total_plata = 0;
$history_total_percentage = 0;


if ($date_filter === 'live') {

    $table_render_data = get_live_tokenomics_data($conn, $PLTUSD);

    foreach ($table_render_data as $item) {
        if ($item['is_group']) {
            $history_total_liquidity += $item['data']['liquidity'];
            $history_total_plata += $item['data']['plata'];
        }
    }
    if ($circulating_supply > 0) {
        $history_total_percentage = $history_total_plata / $circulating_supply;
    }

    $price_for_heading = number_format($PLTUSD, 10, '.', ',');
    $price_date_view = gmdate('Y-m-d H:i:s');
    $month_name_for_title = "LIVE Data";
} else {



    $where_clause = " WHERE price_date = '" . $conn->real_escape_string($date_filter) . "'";
    $sql = "SELECT * FROM granna80_bdlinks.tokenomics_history " . $where_clause;
    $result = $conn->query($sql);

    $records = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
    }


    if (!empty($records)) {
        $first_record = $records[0];
        $price_for_heading = number_format((float)$first_record['plt_price'], 10, '.', ',');
        $price_date_view = $first_record['price_date'];
        $date_obj = new DateTime($first_record['price_date']);
        $year_for_title = $date_obj->format('Y');
        $month_name_for_title = $date_obj->format('F');

        $year_for_json = $date_obj->format('Y');
        $month_for_json = $date_obj->format('n');
    }


    $history_total_liquidity = array_sum(array_column($records, 'liquidity'));
    $history_total_plata = array_sum(array_column($records, 'plata'));
    $history_total_percentage = $history_total_plata > 0 ? $history_total_plata / $circulating_supply : 0;

   
    $groups_map = array_column($available_groups, 'name', 'tag');
    $grouped_individuals = [];
    $group_totals = [];

    foreach ($records as $item) {
        $group_key = ($item['group_wallet'] == 'dex')
            ? get_dex_base_name_from_string($item['exchange'])
            : get_authoritative_display_name($item['group_wallet'], $groups_map);

        if (!isset($group_totals[$group_key])) {
            $group_totals[$group_key] = ['exchange' => $group_key, 'liquidity' => 0, 'percentage' => 0, 'plata' => 0];
            $grouped_individuals[$group_key] = [];
        }

        $group_totals[$group_key]['liquidity'] += (float)$item['liquidity'];
        $group_totals[$group_key]['percentage'] += (float)$item['percentage'];
        $group_totals[$group_key]['plata'] += (float)$item['plata'];
        $grouped_individuals[$group_key][] = $item;
    }

    uasort($group_totals, fn($a, $b) => $b['liquidity'] <=> $a['liquidity']);

    foreach ($group_totals as $group_display_name => $total_data) {
        $table_render_data[] = ['is_group' => true, 'data' => $total_data];
        if (isset($grouped_individuals[$group_display_name])) {
            usort($grouped_individuals[$group_display_name], fn($a, $b) => $b['liquidity'] <=> $a['liquidity']);
            foreach ($grouped_individuals[$group_display_name] as $individual_item) {
                $table_render_data[] = ['is_group' => false, 'data' => $individual_item];
            }
        }
    }
}


$json_file_path = 'tokenomics_history.json';


$json_query = "SELECT id, record_year, record_month, exchange, liquidity, percentage, plata, plt_price, price_date, group_wallet FROM granna80_bdlinks.tokenomics_history ORDER BY record_year DESC, record_month DESC, exchange ASC";
$json_result = $conn->query($json_query);


$formatted_json_objects = [];







?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tokenomics History</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <style>
        .group-total-row {
            background-color: #ecf0f1 !important;
            /* Cor de fundo cinza claro */
            font-weight: bold;
        }

        .group-total-row td {
            border-bottom: 1px solid #bdc3c7;
            border-top: 2px solid #95a5a6;
        }
    </style>
    <style>
        .totals-container {
            display: flex;
            justify-content: flex-start; 
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px 0;
            font-family: sans-serif;
        }

        .total-box {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            
            min-width: 350px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        .total-box h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            color: #333;
        }

        .total-box p {
            margin: 8px 0;
            font-size: 1em;
            display: flex;
            justify-content: space-between;
        }

        .total-box p strong {
            color: #555;
        }

        .total-box p small {
            margin-top: 10px;
            color: #777;
            display: block;
            text-align: right;
        }
    </style>
</head>



<body>
    <div class="container">
        <div class="nav-links">
            <a href="index.php">[Back]</a>
            <a href="javascript:window.location.reload(true)">[Refresh Page]</a>
            <a href="https://typofx.ie/plataforma/panel/lp-contracts/index.php">[Lp Contracts]</a>
            <?php

            echo "<a href='json.php?year_filter={$year_for_json}&month_filter={$month_for_json}' target='_blank'>[JSON]</a>";
            ?>
        </div>

        <?php
        $month_name = date('F', mktime(0, 0, 0, $latest_month_for_link, 1));
        ?>

        <div class="totals-container">
            <div class="total-box">
                <h3>
                    <?php
               
                    $card_title = ($date_filter === 'live' || empty($date_filter))
                        ? "LIVE Totals"
                        : "Snapshot: " . $month_name_for_title . ' ' . $year_for_title;
                    echo $card_title;
                    ?>
                </h3>

                <p><strong>PLTUSD:</strong> <span>$<?php echo $price_for_heading; ?></span></p>
                <p><strong>Liquidity:</strong> <span>$<?php echo number_format($history_total_liquidity, 4, '.', ','); ?></span></p>
                <p><strong>Percentage:</strong> <span><?php echo number_format($history_total_percentage * 100, 2, '.', ','); ?>%</span></p>
                <p><strong>Plata:</strong> <span><?php echo number_format((float)$history_total_plata, 4, '.', ','); ?></span></p>

                <?php if (!empty($price_date_view)): ?>
                    <p>
                        <small>
                            Last update on:
                            <?php
                            $date = new DateTime($price_date_view);
                            echo $date->format('d-m-Y H:i:s') . ' UTC';
                            ?>
                        </small>
                    </p>
                <?php endif; ?>
            </div>
        </div>





        <div class="filter-container">
            <form method="get" action="tokenomics_history.php">
                <label for="date_filter">Filter by:</label>
                <select id="date_filter" name="date_filter" onchange="this.form.submit()">
                    <option value="live" <?php if (empty($_GET['date_filter']) || $_GET['date_filter'] == 'live') echo 'selected'; ?>>--- LIVE Data (Now) ---</option>

                    <?php foreach ($available_dates as $date_option): ?>
                        <option value="<?php echo $date_option; ?>" <?php echo ($date_filter == $date_option) ? 'selected' : ''; ?>>
                            <?php
                            $display_date = new DateTime($date_option);
                            echo $display_date->format('d-m-Y H:i:s');
                            ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <?php

        if ($date_filter !== 'live'):
        ?>

            <div class="form-container">


                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="massUpdateForm"> <input type="hidden" name="action" value="mass_update_price">

                    <input type="hidden" name="date_to_update" value="<?php echo $date_filter; ?>">
                    <label for="price_date">Price Date:</label>
                    <input type="datetime-local" value="<?php echo $price_date_view ?>" id="price_date" name="price_date" required>


                    <input type="hidden" id="new_plt_price" name="plt_price">

                    <button type="submit" id="update_price_btn">
                        Update price
                    </button>
                </form>
            </div>

            <div class="form-container">



                <h3><?php echo $edit_data ? 'Edit Record' : 'Add New Record'; ?></h3>




                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="current_date_filter" value="<?php echo htmlspecialchars($date_filter); ?>">
                    <input type="hidden" name="action" value="<?php echo $edit_data ? 'update' : 'create'; ?>">
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                    <?php endif; ?>

                    <label for="record_year">Year:</label>
                    <input type="number" id="record_year" name="record_year" value="<?php echo $edit_data['record_year'] ?? date('Y'); ?>">

                    <label for="record_month">Month:</label>
                    <input type="number" id="record_month" name="record_month" min="1" max="12" value="<?php echo $edit_data['record_month'] ?? date('n'); ?>">

                    <label for="exchange">Exchange:</label>
                    <input type="text" id="exchange" name="exchange" value="<?php echo htmlspecialchars($edit_data['exchange'] ?? ''); ?>">

                    <label for="plata">Plata:</label>
                    <input type="number" step="any" id="plata" name="plata" value="<?php echo $edit_data['plata'] ?? '0'; ?>">

                    <label for="group_wallet">Group:</label>
                    <select id="group_wallet" name="group_wallet" required>
                        <option value="">-- Select a group --</option>
                        <?php foreach ($available_groups as $group): ?>
                            <option value="<?php echo htmlspecialchars($group['tag']); ?>" <?php if (isset($edit_data) && $edit_data['group_wallet'] == $group['tag']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($group['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="walletAddress">Wallet Address:</label>
                    <input type="text" id="walletAddress" name="walletAddress" value="<?php echo htmlspecialchars($edit_data['walletAddress'] ?? ''); ?>" placeholder="0x...">
                    <br><br>

                    <button type="submit" id="update" class="<?php echo $edit_data ? 'update' : ''; ?>">
                        <?php echo $edit_data ? 'Update Record' : 'Save Record'; ?>
                    </button>
                    <?php if ($edit_data): ?>
                        <a href="tokenomics_history.php?date_filter=<?php echo $date_filter; ?>" class="cancel">Cancel Edit</a>
                    <?php endif; ?>
                </form>
            </div>

        <?php
        endif; 
        ?>


        <table id="liquidityTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Tag</th>
                    <th>Group</th>
                    <th>Wallet Adress</th>
                    <th>Plata</th>
                    <th>Liquidity</th>
                    <th>Percentage</th>
                    <?php if ($date_filter !== 'live'): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($table_render_data as $row_item): ?>
                    <?php
                    $row = $row_item['data'];
                    $is_group = $row_item['is_group'];
                    $row_class = $is_group ? 'group-total-row' : 'individual-row';

                   
                    $liquidity_display = $row['liquidity'];
                    $percentage_display = $row['percentage'];
                    if (!$is_group && isset($row_price['plt_price'])) {
                        $liquidity_display = (float)$row_price['plt_price'] * (float)$row['plata'];
                        $percentage_display = (float)$row['plata'] / $circulating_supply;
                    }
                    ?>
                    <tr class="<?php echo $row_class; ?>">
                        <td><?php echo $row['record_year']; ?></td>
                        <td><?php echo $row['record_month']; ?></td>

                        <td><?php echo htmlspecialchars($row['exchange']); ?></td>

                        <td><b><?php echo $is_group ? 'TOTAL GROUP' : htmlspecialchars($row['group_wallet']); ?></b></td>

                        <td>
                            <?php if (!$is_group && !empty($row['walletAddress'])): ?>
                                <a href="https://polygonscan.com/address/<?php echo htmlspecialchars($row['walletAddress']); ?>" target="_blank">
                                    <?php echo substr($row['walletAddress'], 0, 6) . "..." . substr($row['walletAddress'], -4); ?>
                                </a>
                            <?php endif; ?>
                        </td>

                        <td><?php echo number_format((float)$row['plata'], 4, '.', ','); ?></td>
                        <td><?php echo number_format((float)$liquidity_display, 2, '.', ','); ?></td>
                        <td><?php echo number_format((float)$percentage_display * 100, 4, '.', ','); ?>%</td>

                        <?php if ($date_filter !== 'live'): ?>
                            <td>
                                <?php if (!$is_group): ?>
                                    <a href="?action=edit&id=<?php echo $row['id']; ?>&date_filter=<?php echo $date_filter; ?>">Edit</a> |
                                    <a href="?action=delete&id=<?php echo $row['id']; ?>&date_filter=<?php echo $date_filter; ?>" onclick="...">Delete</a>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#liquidityTable').DataTable({
                "order": [],
                "pageLength": 100,
                "lengthMenu": [
                    [100, 200, 500, -1],
                    [100, 200, 500, "All"]
                ],

                rowCallback: function(row, data, index) {
                    if (data[2] === "Others") {
                        $(row).addClass('row-others');
                    }
                },

                drawCallback: function(settings) {
                    var api = this.api();
                    var othersRows = api.rows('.row-others').nodes();

                    if (othersRows.length) {
                        var tbody = $(api.table().body());
                        $(othersRows).detach().appendTo(tbody);
                    }
                }
            });
        });



        $('#massUpdateForm').on('submit', function(event) {
            event.preventDefault();

            var form = this;
            var submitButton = $('#update_price_btn');
            var priceDate = $('#price_date').val();

            if (!priceDate) {
                alert('Please select a date.');
                return;
            }

            submitButton.prop('disabled', true).text('Searching for price...');

            $.ajax({
                url: 'fetch_price.php',
                type: 'GET',
                data: {
                    price_date: priceDate
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {

                        $('#new_plt_price').val(response.price);

                        var formattedDate = response.date.replace(' ', 'T');
                        $('#price_date').val(formattedDate);

                        console.log('Price found.');
                        form.submit();
                    } else {
                        console.log('API returned error: ' + response.message);
                        alert('Warning: Unable to find a historical price. The change has been canceled.');
                        submitButton.prop('disabled', false).text('Search Price and Update Records');
                    }
                },
                error: function() {
                    alert('Communication error while retrieving price. The change has been cancelled.');
                    submitButton.prop('disabled', false).text('Search Price and Update Records');
                }

            });
        });




        $('#formedit').on('submit', function(event) {

            event.preventDefault();

            var form = this;
            var submitButton = $('#update');
            var priceDate = $('#price_date').val();


            if (!priceDate) {
                form.submit();
                return;
            }


            submitButton.prop('disabled', true).text('Searching for price...');


            $.ajax({
                url: 'fetch_price.php',
                type: 'GET',
                data: {
                    price_date: priceDate
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {

                        $('#plt_price').val(response.price);
                        var formattedDate = response.date.replace(' ', 'T');
                        $('#price_date').val(formattedDate);
                        console.log('Price found and updated. Sending form...');
                    } else {

                        console.log('Search API returned error: ' + response.message);
                        alert('Warning: Unable to find a historical price for the date. The current price will not be changed.');
                    }
                },
                error: function() {

                    alert('Communication error while fetching price. Current price will not be changed.');
                },
                complete: function() {

                    form.submit();
                }
            });
        });



        $('#fetchPriceBtn').on('click', function() {
            var btn = $(this);
            var year = $('#record_year').val();
            var month = $('#record_month').val();

            if (!year || !month) {
                alert('Please fill in the year and month first.');
                return;
            }

            btn.prop('disabled', true).text('Searching for data...');


            fetchAndUpdatePrice(year, month).always(function() {
                btn.prop('disabled', false).text('Search Historical Price');
            });
        });



        function fetchAndUpdatePrice(year, month) {

            return $.ajax({
                url: 'fetch_price.php',
                type: 'GET',
                data: {
                    year: year,
                    month: month
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {

                        $('#plt_price').val(response.price);

                        var formattedDate = response.date.replace(' ', 'T');
                        $('#price_date').val(formattedDate);

                        console.log('Price and date updated successfully!');
                    } else {

                        alert('Error fetching price: ' + response.message);
                    }
                },
                error: function() {
                    alert('A communication error occurred while trying to retrieve the price.');
                }
            });
        }
    </script>




</body>

</html>