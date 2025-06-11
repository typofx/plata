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

$where_clause = " WHERE record_year = " . intval($year_filter);
if (isset($_GET['show_all']) && $_GET['show_all'] == 'true') {
    $where_clause = ''; // Clear where clause if showing all
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
        $id = intval($_POST['id']);
      
        $sql = "UPDATE granna80_bdlinks.tokenomics_history SET record_year=?, record_month=?, exchange=?, liquidity=?, percentage=?, plata=?, plt_price=?, price_date=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("iisddddsi", $record_year, $record_month, $exchange, $liquidity, $percentage, $plata, $plt_price, $price_date, $id);

        if ($stmt->execute()) {
            echo "<script>window.location.href='$redirect_url';</script>";
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $stmt->close();
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
$conn->close();
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

<body>
    <div class="container">
        <div class="nav-links">
            <a href="index.php">[Back]</a>
            <a href="javascript:window.location.reload(true)">[Refresh Page]</a>
        </div>

        <h2>Tokenomics History</h2>

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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="action" value="<?php echo $edit_data ? 'update' : 'create'; ?>">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>

                <label for="record_year">Year:</label>
                <input type="number" id="record_year" name="record_year" value="<?php echo $edit_data['record_year'] ?? date('Y'); ?>" required>

                <label for="record_month">Month:</label>
                <input type="number" id="record_month" name="record_month" min="1" max="12" value="<?php echo $edit_data['record_month'] ?? date('n'); ?>" required>

                <div style="margin-bottom: 15px;">
                    <button type="button" id="fetchPriceBtn">Search Historical Price</button>
                </div>

                <label for="exchange">Exchange:</label>
                <input type="text" id="exchange" name="exchange" value="<?php echo htmlspecialchars($edit_data['exchange'] ?? ''); ?>" required>

                <label for="liquidity">Liquidity:</label>
                <input type="number" step="any" id="liquidity" name="liquidity" value="<?php echo $edit_data['liquidity'] ?? '0'; ?>" required>

                <label for="percentage">Percentage:</label>
                <input type="number" step="any" id="percentage" name="percentage" value="<?php echo $edit_data['percentage'] ?? '0'; ?>" required>

                <label for="plata">Plata:</label>
                <input type="number" step="any" id="plata" name="plata" value="<?php echo $edit_data['plata'] ?? '0'; ?>" required>

                <label for="plt_price">PLT Price:</label>
                <input type="number" step="any" id="plt_price" name="plt_price" value="<?php echo $edit_data['plt_price'] ?? '0.00'; ?>">

                <label for="price_date">Price Date:</label>
                <input type="datetime-local" id="price_date" name="price_date" value="<?php echo !empty($edit_data['price_date']) ? (new DateTime($edit_data['price_date']))->format('Y-m-d\TH:i:s') : ''; ?>">

                <button type="submit" class="<?php echo $edit_data ? 'update' : ''; ?>">
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
                    <th>PLT Price</th>
                    <th>Price Date</th>
                    <th>Price Source</th>
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
                        <td><?php echo number_format((float)$row['plt_price'], 10, '.', ''); ?></td>
                        <td><?php echo htmlspecialchars($row['price_date']); ?></td>
                          <td>
                            <?php if (!empty($row['price_date'])): ?>
                                <a href="fetch_json.php?date=<?php echo urlencode($row['price_date']); ?>" target="_blank">
                                    Verify JSON
                                </a>
                            <?php endif; ?>
                        </td>
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




        $('#fetchPriceBtn').on('click', function() {
        var btn = $(this);
        var year = $('#record_year').val();
        var month = $('#record_month').val();

        if (!year || !month) {
            alert('Please fill in the year and month first.');
            return;
        }


        btn.prop('disabled', true).text('Searching...');

        $.ajax({
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

             
                } else {
             
                    alert('Erro: ' + response.message);
                }
            },
            error: function() {
     
                alert('A communication error occurred while trying to retrieve the price.');
            },
            complete: function() {
          
                btn.prop('disabled', false).text('Search Historical Price');
            }
        });
        });
        
    </script>




</body>

</html>