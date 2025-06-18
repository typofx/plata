<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';

include 'conexao.php';

$groups_result = $conn->query("SELECT tag, name FROM granna80_bdlinks.finance_tools_groups ORDER BY name ASC");
$available_groups = [];
while ($group_row = $groups_result->fetch_assoc()) {
    $available_groups[] = $group_row;
}

$circulating_supply = 11299000992;
$edit_data = null;
$action = $_REQUEST['action'] ?? '';

// Get distinct years for the filter dropdown
$years_result = $conn->query("SELECT DISTINCT record_year FROM granna80_bdlinks.tokenomics_history ORDER BY record_year DESC");
$available_years = [];
while ($year_row = $years_result->fetch_assoc()) {
    $available_years[] = $year_row['record_year'];
}

// Set default filter if none is provided. If years exist, use the most recent one.
if (isset($_GET['year_filter']) && is_numeric($_GET['year_filter'])) {
    $year_filter = intval($_GET['year_filter']);
} elseif (!empty($available_years)) {
    $year_filter = $available_years[0];
} else {
    $year_filter = date('Y'); // Fallback to current year if no records exist
}


$where_clause = ''; // Initialize the WHERE clause

// First, check if the user wants to see all records.
if (isset($_GET['show_all']) && $_GET['show_all'] == 'true') {
    // If showing all, the where clause remains empty, showing all data from all years.
    $where_clause = '';
    // We'll set a default for the JSON link month just in case
    $latest_month_for_link = date('n');
} else {



    $latest_month_in_db = null;
    $stmt_latest_month = $conn->prepare("SELECT MAX(record_month) as latest_month FROM granna80_bdlinks.tokenomics_history WHERE record_year = ?");
    $stmt_latest_month->bind_param("i", $year_filter);

    if ($stmt_latest_month->execute()) {
        $result_latest_month = $stmt_latest_month->get_result();
        $row_latest_month = $result_latest_month->fetch_assoc();
        if ($row_latest_month && $row_latest_month['latest_month'] !== null) {
            $latest_month_in_db = (int)$row_latest_month['latest_month'];
        }
    }
    $stmt_latest_month->close();


    $latest_month_for_link = $latest_month_in_db ?? date('n'); // Use found month, or current month as fallback


    if ($latest_month_in_db !== null) {
        // Build the clause to filter by year AND the found latest month.
        $where_clause = " WHERE record_year = " . $year_filter . " AND record_month = " . $latest_month_in_db;
    } else {

        $where_clause = " WHERE record_year = " . $year_filter;
    }
}






if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM granna80_bdlinks.tokenomics_history WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $redirect_url = 'tokenomics_history.php' . ($year_filter ? '?year_filter=' . $year_filter : '');
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


    $redirect_url = 'tokenomics_history.php?year_filter=' . $record_year;
    if ($action === 'mass_update_price') {

        $record_month = intval($_POST['record_month']);
        $plt_price = $_POST['plt_price'];
        $price_date = $_POST['price_date'];


        $sql_mass_update = "UPDATE granna80_bdlinks.tokenomics_history SET plt_price = ?, price_date = ? WHERE record_year = ? AND record_month = ?";
        $stmt_mass_update = $conn->prepare($sql_mass_update);
        $stmt_mass_update->bind_param("dsii", $plt_price, $price_date, $record_year, $record_month);


        if ($stmt_mass_update->execute()) {
            $stmt_mass_update->close();




            $sql_select_rows = "SELECT id, plata FROM granna80_bdlinks.tokenomics_history WHERE record_year = ? AND record_month = ?";
            $stmt_select = $conn->prepare($sql_select_rows);
            $stmt_select->bind_param("ii", $record_year, $record_month);
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


            echo "<script>alert('Success! Price, liquidity and percentage have been updated.'); window.location.href='$redirect_url';</script>";
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
        // bind_param atualizado
        $stmt->bind_param("iisddddsss", $record_year, $record_month, $exchange, $liquidity, $percentage, $plata, $current_price, $current_date, $group_wallet, $walletAddress);


        if ($stmt->execute()) {
            echo "<script>window.location.href='$redirect_url';</script>";
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
        $group_wallet = $_POST['group_wallet']; // Novo campo
        $walletAddress = $_POST['walletAddress']; // Novo campo

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
            echo "<script>window.location.href='$redirect_url';</script>";
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

$records = [];
$sql = "SELECT id, record_year, record_month, exchange, liquidity, percentage, plata, plt_price, price_date, group_wallet, walletAddress FROM granna80_bdlinks.tokenomics_history $where_clause ORDER BY record_year DESC, record_month DESC, exchange ASC";
$result = $conn->query($sql);


$history_total_liquidity = 0;
$history_total_percentage = 0;
$history_total_plata = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;

        $history_total_liquidity += (float)$row['liquidity'];
        $history_total_percentage += (float)$row['percentage'];
        $history_total_plata += (float)$row['plata'];
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

</head>

<?php

$latest_month_for_link = date('n');


$stmt_month = $conn->prepare("SELECT MAX(record_month) as latest_month FROM granna80_bdlinks.tokenomics_history WHERE record_year = ?");
$stmt_month->bind_param("i", $year_filter);

if ($stmt_month->execute()) {
    $result_month = $stmt_month->get_result();
    if ($result_month->num_rows > 0) {
        $row_month = $result_month->fetch_assoc();

        if ($row_month['latest_month'] !== null) {
            $latest_month_for_link = $row_month['latest_month'];
        }
    }
}
$stmt_month->close();


?>


<body>
    <div class="container">
        <div class="nav-links">
            <a href="index.php">[Back]</a>
            <a href="javascript:window.location.reload(true)">[Refresh Page]</a>
            <a href="https://typofx.ie/plataforma/panel/lp-contracts/index.php">[Lp Contracts]</a>
            <?php

            echo "<a href='json.php?year_filter={$year_filter}&month_filter={$latest_month_for_link}' target='_blank'>[JSON]</a>";
            ?>
        </div>

        <?php
        $month_name = date('F', mktime(0, 0, 0, $latest_month_for_link, 1));
        ?>
        <?php



        $sql_price = "SELECT plt_price, price_date FROM granna80_bdlinks.tokenomics_history WHERE record_year = $year_filter AND record_month = $latest_month_for_link ORDER BY id DESC LIMIT 1";

        $result_price = $conn->query($sql_price);
        $row_price = $result_price->fetch_assoc();


        $price_for_heading = $row_price ? number_format((float)$row_price['plt_price'], 10, '.', '') : 'N/A';

        $price_date_view = $row_price['price_date'];
        $month_name = date('F', mktime(0, 0, 0, $latest_month_for_link, 1));







        ?>



        <h2>
            Tokenomics History: <?php echo $month_name; ?> <?php echo $year_filter; ?>

        </h2>


        <p>
            PLTUSD: $<?php echo $price_for_heading; ?><br><br>

            Liquidity: <?php echo number_format($history_total_liquidity, 4, '.', ','); ?><br>
            Percentage: <?php echo number_format(abs(1 - $history_total_percentage) < 0.0001 ? 100 : $history_total_percentage * 100, 2, '.', ','); ?>%<br>
            Plata: <?php echo number_format((float) $history_total_plata, 2, '.', ''); ?>


            <br><br>
            <?php
            if (!empty($price_date_view)) {
                $date = new DateTime($price_date_view);
                $formatted_date = $date->format('d-m-Y H:i:s');
                echo "Last update on: " . $formatted_date . " UTC";
            }
            ?>
        </p>
        <br><br>





        <div class="filter-container">
            <form method="get" action="tokenomics_history.php">
                <label for="year_filter">Filter by Year:</label>
                <select id="year_filter" name="year_filter" onchange="this.form.submit()">
                    <?php foreach ($available_years as $year): ?>
                        <option value="<?php echo $year; ?>" <?php echo ($year_filter == $year) ? 'selected' : ''; ?>>
                            <?php echo $year; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <a href="tokenomics_history.php?show_all=true" class="button">Show All</a>
            </form>
        </div>

        <div class="form-container">


            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="massUpdateForm"> <input type="hidden" name="action" value="mass_update_price">

                <input type="hidden" name="record_year" value="<?php echo $year_filter; ?>">
                <input type="hidden" name="record_month" value="<?php echo $latest_month_for_link; ?>">
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
                    <a href="tokenomics_history.php?year_filter=<?php echo $edit_data['record_year']; ?>" class="cancel">Cancel Edit</a>
                <?php endif; ?>
            </form>
        </div>

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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql_update_on_display = "UPDATE granna80_bdlinks.tokenomics_history SET liquidity = ?, percentage = ? WHERE id = ?";
                $stmt_update = $conn->prepare($sql_update_on_display);


                foreach ($records as $row):

                    $liquidity_plata = (float)$row_price['plt_price'] * (float)$row['plata'];
                    $porcentage_tokens = (float)$row['plata'] / $circulating_supply;


                    $stmt_update->bind_param("ddi", $liquidity_plata, $porcentage_tokens, $row['id']);
                    $stmt_update->execute();

                ?>
                    <tr>
                        <td><?php echo $row['record_year']; ?></td>
                        <td><?php echo $row['record_month']; ?></td>
                        <td><?php echo htmlspecialchars($row['exchange']); ?></td>
                        <td> <b><?php echo $row['group_wallet']; ?></b></td>
                        <td>
                            <?php

                            $wallet_address = $row['walletAddress'] ?? '';
                            if (!empty($wallet_address)) {
                                echo "<a href='https://polygonscan.com/address/{$wallet_address}' target='_blank'>"
                                    . substr($wallet_address, 0, 6) . "..." . substr($wallet_address, -4)
                                    . "</a>";
                            }
                            ?>
                        </td>
                        <td><?php echo number_format($row['plata'], 4, '.', ','); ?></td>
                        <td><?php echo number_format($liquidity_plata, 2, '.', ','); ?></td>
                        <td><?php echo number_format($porcentage_tokens, 4, '.', ','); ?></td>




                        <td>
                            <a href="?action=edit&id=<?php echo $row['id']; ?>&year_filter=<?php echo $row['record_year']; ?>">Edit</a> |
                            <a href="?action=delete&id=<?php echo $row['id']; ?>&year_filter=<?php echo $row['record_year']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                        </td>
                    </tr>

                <?php
                endforeach;


                $stmt_update->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#liquidityTable').DataTable({
                "order": [
                    [6, 'desc'],
                    [7, 'desc']
                ],
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