<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();

$manual_entry = false; // Flag to enable manual entry if no data is found
$next_week = 1;        // Default week number
$new_start_week = "";  // New start week
$new_end_week = "";    // New end week
$current_year = date('Y'); // Current year

if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    // Retrieve the last registered work week
    $sql_last_week = "SELECT work_week, start_week, end_week 
                      FROM granna80_bdlinks.work_weeks 
                      WHERE employee_id = ? 
                      ORDER BY start_week DESC 
                      LIMIT 1";
    $stmt_last_week = $conn->prepare($sql_last_week);
    $stmt_last_week->bind_param("i", $employee_id);
    $stmt_last_week->execute();
    $result_last_week = $stmt_last_week->get_result();

    if ($result_last_week->num_rows > 0) {
        $last_week_data = $result_last_week->fetch_assoc();
        $last_start_week = $last_week_data['start_week'];
        $last_work_week = (int)$last_week_data['work_week'];

        // Determine the year of the last registered week
        $last_week_year = date('Y', strtotime($last_start_week));

        if ($last_work_week < 52) {
            // If the last year has not completed 52 weeks, continue incrementing
            $next_week = $last_work_week + 1;
        } elseif ($last_work_week == 52 && $last_week_year < $current_year) {
            // If last week is 52 and it's a new year, reset to week 1
            $next_week = 1;
        } else {
            // If invalid data (e.g., week > 52), throw an error
            echo "Error: Invalid data. Please check the database.";
            exit();
        }

        // Calculate new start and end dates for the week
        $start_week_date = new DateTime($last_start_week);
        $start_week_date->modify('+7 days'); // Add 7 days to get the next start date
        $new_start_week = $start_week_date->format('Y-m-d');

        $end_week_date = clone $start_week_date;
        $end_week_date->modify('+4 days'); // Assuming end of week is Friday
        $new_end_week = $end_week_date->format('Y-m-d');
    } else {
        // No previous weeks found, allow manual input
        $manual_entry = true;
    }
} else {
    echo "No employee ID provided.";
    exit();
}



// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $week = $_POST['week'];
    $start_week = $_POST['start_date'];
    $end_week = $_POST['end_date'];
    $end_year = date('Y', strtotime($end_week));
    $status = $_POST['status'];

    $sql_insert = "INSERT INTO granna80_bdlinks.work_weeks 
                   (employee_id, work_week, start_week, end_week, status, weekly_value_plteur, weekly_value_pltusd, year_date) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iissssss", $employee_id, $week, $start_week, $end_week, $status, $EURUSD, $PLTUSD, $end_year);

    if ($stmt_insert->execute()) {
        echo "New week added successfully!";
        echo "<script>window.location.href='work_weeks_ireland.php?employee_id=" . $employee_id . "';</script>";
        exit();
    } else {
        echo "Error: " . $stmt_insert->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Week</title>
</head>

<body>
    <h1>Add New Week</h1>
    <form method="POST">
        <label for="week">Week Number:</label>
        <input type="number" id="week" name="week" value="<?php echo $manual_entry ? '' : $next_week; ?>" min="1" max="52" required><br><br>

        <label for="start_date">Start Week:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo $manual_entry ? '' : $new_start_week; ?>" required><br><br>

        <label for="end_date">End Week:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo $manual_entry ? '' : $new_end_week; ?>" required><br><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Paid">Paid</option>
            <option value="Pending">Pending</option>
            <option value="Processing">Processing</option>
            <option value="Holiday">Holiday</option>
            <option value="Holiday (Paid)">Holiday (Paid)</option>
            <option value="Holiday (Off)">Holiday (Off)</option>
            <option value="Running">Running</option>
            <option value="Off">Off</option>
        </select><br><br>


        <label>PLTUSDT: <?php echo htmlspecialchars($PLTUSD); ?></label><br><br>
        <label>EURUSDT: <?php echo htmlspecialchars($EURUSD); ?></label><br><br>

        <input type="submit" value="Add Week">
        <a href='work_weeks_ireland.php?employee_id=<?php echo $employee_id; ?>'>[back]</a>
    </form>
</body>

</html>

<?php
$stmt_last_week->close();
$conn->close();
?>