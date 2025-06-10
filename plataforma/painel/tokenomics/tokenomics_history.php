<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php'; 
include 'conexao.php';

$edit_data = null;
$action = $_REQUEST['action'] ?? ''; 

// Get distinct years from the database for the filter
$years_result = $conn->query("SELECT DISTINCT record_year FROM granna80_bdlinks.tokenomics_history ORDER BY record_year DESC");
$available_years = [];
while ($year_row = $years_result->fetch_assoc()) {
    $available_years[] = $year_row['record_year'];
}


// Set current year as default filter
$current_year = date('Y');
$year_filter = $_GET['year_filter'] ?? $current_year;

// If current year doesn't exist in database, show all
if (!in_array($current_year, $available_years)) {
    $year_filter = 'all';
}
// Check if a year filter is applied

$where_clause = '';
if ($year_filter !== '' && $year_filter !== 'all') {
    $year_filter = intval($year_filter);
    $where_clause = " WHERE record_year = $year_filter";
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

  $redirect_url = 'tokenomics_history.php?year_filter=' . $record_year;

    if ($action === 'create') {
        $sql = "INSERT INTO granna80_bdlinks.tokenomics_history (record_year, record_month, exchange, liquidity, percentage, plata) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissss", $record_year, $record_month, $exchange, $liquidity, $percentage, $plata);
        
        if ($stmt->execute()) {
           echo "<script>window.location.href='$redirect_url';</script>";
            exit();
        } else {
            echo "Error creating record: " . $conn->error;
        }
        $stmt->close();
    } elseif ($action === 'update' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $sql = "UPDATE granna80_bdlinks.tokenomics_history SET record_year=?, record_month=?, exchange=?, liquidity=?, percentage=?, plata=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissssi", $record_year, $record_month, $exchange, $liquidity, $percentage, $plata, $id);

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

// Apply the year filter to the main query
$records = [];
$sql = "SELECT * FROM granna80_bdlinks.tokenomics_history $where_clause ORDER BY record_year DESC, record_month DESC, exchange DESC";
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

    <!-- Year Filter -->
<div class="filter-container">
    <form method="get" action="tokenomics_history.php">
        <label for="year_filter">Filter by Year:</label>
        <select id="year_filter" name="year_filter" onchange="this.form.submit()">
            <?php
            // Se nenhum filtro estiver ativo, o primeiro ano da lista (o mais recente) será selecionado por padrão.
            foreach ($available_years as $year): ?>
                <option value="<?php echo $year; ?>" <?php echo ($year_filter == $year) ? 'selected' : ''; ?>>
                    <?php echo $year; ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <?php
        // Mostra o botão "Limpar Filtro" apenas se um ano específico estiver selecionado.
        if (!empty($year_filter)): ?>
            <a href="tokenomics_history.php" class="button">Clear Filter</a>
        <?php endif; ?>
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
            <input type="number" id="record_year" name="record_year" value="<?php echo $edit_data['record_year'] ?? date('Y'); ?>" >

            <label for="record_month">Month:</label>
            <input type="number" id="record_month" name="record_month" min="1" max="12" value="<?php echo $edit_data['record_month'] ?? date('n'); ?>" >

            <label for="exchange">Exchange:</label>
            <input type="text" id="exchange" name="exchange" value="<?php echo htmlspecialchars($edit_data['exchange'] ?? ''); ?>" >
            
            <label for="liquidity">Liquidity:</label>
            <input type="number" id="liquidity" name="liquidity" value="<?php echo $edit_data['liquidity'] ?? ''; ?>" >

            <label for="percentage">Percentage:</label>
            <input type="number" id="percentage" name="percentage" value="<?php echo $edit_data['percentage'] ?? ''; ?>" >

            <label for="plata">Plata:</label>
            <input type="number" id="plata" name="plata" value="<?php echo $edit_data['plata'] ?? ''; ?>" >

            <button type="submit" class="<?php echo $edit_data ? 'update' : ''; ?>">
                <?php echo $edit_data ? 'Update Record' : 'Save Record'; ?>
            </button>
            <?php if ($edit_data): ?>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="cancel">Cancel Edit</a>
            <?php endif; ?>
        </form>
    </div>

    <table id="liquidityTable" class="display">
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
                    <a href="?action=edit&id=<?php echo $row['id']; ?><?php echo $year_filter ? '&year_filter='.$year_filter : ''; ?>">Edit</a> |
                    <a href="?action=delete&id=<?php echo $row['id']; ?><?php echo $year_filter ? '&year_filter='.$year_filter : ''; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
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
                [3, 'desc'], // Coluna 'Liquidity'
                [4, 'desc']  // Coluna 'Percentage'
            ],

     
            "rowCallback": function(row, data, index) {
  
                if (data[2] === "Others") { 
                    $(row).addClass('row-others');
                }
            },

      
            "drawCallback": function(settings) {
                var api = this.api();

          
                var othersRows = api.rows('.row-others').nodes();

         
                if (othersRows.length) {
         
                    var aTbody = $(api.table().body());
                  
                    $(othersRows).detach().appendTo(aTbody);
                }
            }
        });
    });
</script>

</body>
</html>