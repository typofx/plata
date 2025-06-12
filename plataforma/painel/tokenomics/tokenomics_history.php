<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
include 'conexao.php';

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

// =========================================================================
// START: New Data Filtering Logic
// =========================================================================

$where_clause = ''; // Initialize the WHERE clause

// First, check if the user wants to see all records.
if (isset($_GET['show_all']) && $_GET['show_all'] == 'true') {
    // If showing all, the where clause remains empty, showing all data from all years.
    $where_clause = '';
    // We'll set a default for the JSON link month just in case
    $latest_month_for_link = date('n');
} else {
    // If not showing all, filter by the selected year and its latest recorded month.

    // 1. Find the latest month for the selected year from the database.
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

    // This variable will also be used by the [JSON] link later in the HTML.
    $latest_month_for_link = $latest_month_in_db ?? date('n'); // Use found month, or current month as fallback

    // 2. Build the WHERE clause for the main table query.
    if ($latest_month_in_db !== null) {
        // Build the clause to filter by year AND the found latest month.
        $where_clause = " WHERE record_year = " . $year_filter . " AND record_month = " . $latest_month_in_db;
    } else {
        // If no records/month were found for that year, create a condition that returns no results.
        $where_clause = " WHERE record_year = " . $year_filter; // Shows an empty table for that year
    }
}
// =========================================================================
// END: New Data Filtering Logic
// =========================================================================





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

    if ($action === 'create') {

        $sql = "INSERT INTO granna80_bdlinks.tokenomics_history (record_year, record_month, exchange, liquidity, percentage, plata, plt_price, price_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("iisdddds", $record_year, $record_month, $exchange, $liquidity, $percentage, $plata, $plt_price, $price_date);

        if ($stmt->execute()) {
            echo "<script>window.location.href='$redirect_url';</script>";
            exit();
        } else {
            echo "Error creating record: " . $conn->error;
        }
        $stmt->close();
    } elseif ($action === 'update' && isset($_POST['id'])) {
        // =========================================================================
        // START OF MODIFIED 'UPDATE' BLOCK
        // =========================================================================
        $id = intval($_POST['id']);

        // First, update the single, specific record the user edited
        $sql_single = "UPDATE granna80_bdlinks.tokenomics_history SET record_year=?, record_month=?, exchange=?, liquidity=?, percentage=?, plata=?, plt_price=?, price_date=? WHERE id=?";
        $stmt_single = $conn->prepare($sql_single);
        $stmt_single->bind_param("iisddddsi", $record_year, $record_month, $exchange, $liquidity, $percentage, $plata, $plt_price, $price_date, $id);

        // Try to execute the update on the single record
        if ($stmt_single->execute()) {
            $stmt_single->close(); // Close the first statement

            // --- NEW LOGIC ADDED HERE ---
            // If the first update was successful, run a second update.
            // This will propagate the price and date to all other records with the same month and year.
            $sql_multiple = "UPDATE granna80_bdlinks.tokenomics_history SET plt_price = ?, price_date = ? WHERE record_year = ? AND record_month = ?";
            $stmt_multiple = $conn->prepare($sql_multiple);
            // Bind the price, date, year, and month for the WHERE clause
            $stmt_multiple->bind_param("dsii", $plt_price, $price_date, $record_year, $record_month);

            if ($stmt_multiple->execute()) {
                // If the second mass-update is also successful, now we can redirect.
                $stmt_multiple->close();
                echo "<script>window.location.href='$redirect_url';</script>";
                exit();
            } else {
                // Handle an error on the second update
                echo "Error updating related records: " . $conn->error;
            }
            // --- END OF NEW LOGIC ---

        } else {
            // Handle an error on the first (single record) update
            echo "Error updating record: " . $conn->error;
            $stmt_single->close();
        }
        // =========================================================================
        // END OF MODIFIED 'UPDATE' BLOCK
        // =========================================================================
    }
}

if ($action === 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM granna80_bdlinks.tokenomics_history WHERE id = $id");
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
    }
}


$records = [];
$sql = "SELECT id, record_year, record_month, exchange, liquidity, percentage, plata, plt_price, price_date FROM granna80_bdlinks.tokenomics_history $where_clause ORDER BY record_year DESC, record_month DESC, exchange ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}



// ==================================================================
// START: JSON File Generation Block
// ==================================================================

// Define the file path. It is RECOMMENDED to use an absolute path to avoid errors.
// Change '/path/on/your/server/' to the correct folder.

// If you're sure, you can use the relative path:
$json_file_path = 'tokenomics_history.json';

// 1. Run the query to fetch ALL records.
$json_query = "SELECT id, record_year, record_month, exchange, liquidity, percentage, plata, plt_price, price_date FROM granna80_bdlinks.tokenomics_history ORDER BY record_year DESC, record_month DESC, exchange ASC";
$json_result = $conn->query($json_query);

// 2. Prepare an array to store each formatted JSON object string.
$formatted_json_objects = [];

// 3. Check if the query returned results before proceeding.
if ($json_result && $json_result->num_rows > 0) {
    // 4. Iterate over each row (record) from the database.
    while ($json_row = $json_result->fetch_assoc()) {

        // Extract values for easier usage
        $id           = $json_row['id'];
        $record_year  = $json_row['record_year'];
        $record_month = $json_row['record_month'];
        $exchange     = $json_row['exchange'];
        $liquidity    = $json_row['liquidity'];
        $percentage   = $json_row['percentage'];
        $plata        = $json_row['plata'];
        $plt_price    = $json_row['plt_price'];
        $price_date   = $json_row['price_date'];

        // 5. BUILDING THE TEXT LINE BY LINE (manual style)
        $current_object_string  = "  {\n";
        $current_object_string .= "    \"id\": " . $id . ",\n";
        $current_object_string .= "    \"record_year\": " . $record_year . ",\n";
        $current_object_string .= "    \"record_month\": " . $record_month . ",\n";
        $current_object_string .= "    \"exchange\": \"" . addslashes($exchange) . "\",\n";
        $current_object_string .= "    \"liquidity\": " . $liquidity . ",\n";
        $current_object_string .= "    \"percentage\": " . $percentage . ",\n";
        $current_object_string .= "    \"plata\": " . $plata . ",\n";
        $current_object_string .= "    \"plt_price\": " . $plt_price . ",\n";
        $current_object_string .= "    \"price_date\": \"" . addslashes($price_date) . "\"\n";
        $current_object_string .= "  }";

        // Store the formatted object string in the array
        $formatted_json_objects[] = $current_object_string;
    }
    // Free the result memory as soon as the loop ends
    $json_result->free();
}

// 6. FINAL FILE ASSEMBLY AND WRITING
// This part works even if there are no results (creates a file with an empty array `[]`).
$final_file_content = "[\n";
$final_file_content .= implode(",\n", $formatted_json_objects);
$final_file_content .= "\n]";

// Write the entire content to the file at once, replacing the previous content.
file_put_contents($json_file_path, $final_file_content);

// 7. Close the database connection.


// ==================================================================
// END: JSON File Generation Block
// ==================================================================



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
            <?php

            echo "<a href='json.php?year_filter={$year_filter}&month_filter={$latest_month_for_link}' target='_blank'>[JSON]</a>";
            ?>
        </div>

        <?php
        $month_name = date('F', mktime(0, 0, 0, $latest_month_for_link, 1));
        ?>
        <?php



        $sql_price = "SELECT plt_price FROM granna80_bdlinks.tokenomics_history WHERE record_year = $year_filter AND record_month = $latest_month_for_link ORDER BY id DESC LIMIT 1";

        $result_price = $conn->query($sql_price);
        $row_price = $result_price->fetch_assoc();


        $price_for_heading = $row_price ? number_format((float)$row_price['plt_price'], 10, '.', '') : 'N/A';


        $month_name = date('F', mktime(0, 0, 0, $latest_month_for_link, 1));
        ?>



        <h2>
            Tokenomics History: <?php echo $month_name; ?> <?php echo $year_filter; ?>

        </h2>
        <p> (PLT Price: $<?php echo $price_for_heading; ?>)</p>


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
            <h3><?php echo $edit_data ? 'Edit Record' : 'Add New Record'; ?></h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="formedit">
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

                <label for="liquidity">Liquidity:</label>
                <input type="number" step="any" id="liquidity" name="liquidity" value="<?php echo $edit_data['liquidity'] ?? '0'; ?>">

                <label for="percentage">Percentage:</label>
                <input type="number" step="any" id="percentage" name="percentage" value="<?php echo $edit_data['percentage'] ?? '0'; ?>">

                <label for="plata">Plata:</label>
                <input type="number" step="any" id="plata" name="plata" value="<?php echo $edit_data['plata'] ?? '0'; ?>">

                <label for="plt_price">PLT Price:</label>
                <input type="number" step="any" id="plt_price" name="plt_price" value="<?php echo $edit_data['plt_price'] ?? '0.00'; ?>">

                <label for="price_date">Price Date:</label>
                <input type="datetime-local" id="price_date" name="price_date" value="<?php echo !empty($edit_data['price_date']) ? (new DateTime($edit_data['price_date']))->format('Y-m-d\TH:i:s') : ''; ?>">

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
                    <th>Exchange</th>
                    <th>Liquidity</th>
                    <th>Percentage</th>
                    <th>Plata</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $row): ?>
                    <tr>
                        <td><?php echo $row['record_year']; ?></td>
                        <td><?php echo $row['record_month']; ?></td>
                        <td><?php echo htmlspecialchars($row['exchange']); ?></td>
                        <td><?php echo number_format($row['liquidity'], 2, '.', ','); ?></td>
                        <td><?php echo number_format($row['percentage'], 5, '.', ','); ?></td>
                        <td><?php echo number_format($row['plata'], 4, '.', ','); ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $row['id']; ?>&year_filter=<?php echo $row['record_year']; ?>">Edit</a> |
                            <a href="?action=delete&id=<?php echo $row['id']; ?>&year_filter=<?php echo $row['record_year']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#liquidityTable').DataTable({
                "order": [
                    [3, 'desc'],
                    [4, 'desc']
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