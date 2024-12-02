<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();

$manual_entry = false; // Flag to control manual entry
$next_week = 1;        // Default week number if no previous week exists
$new_start_week = "";  // Default empty start week
$new_end_week = "";    // Default empty end week

if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    // Retrieve the latest work week for the employee
    $sql_last_week = "SELECT work_week, start_week, end_week FROM granna80_bdlinks.work_weeks WHERE employee_id = ? ORDER BY work_week DESC LIMIT 1";
    $stmt_last_week = $conn->prepare($sql_last_week);
    $stmt_last_week->bind_param("i", $employee_id);
    $stmt_last_week->execute();
    $result_last_week = $stmt_last_week->get_result();

    if ($result_last_week->num_rows > 0) {
        $last_week_data = $result_last_week->fetch_assoc();
        $last_work_week = $last_week_data['work_week'];
        $last_start_week = $last_week_data['start_week'];
        $last_end_week = $last_week_data['end_week'];

        // Calculate the next work week number
        $next_week = $last_work_week + 1;

        // Convert the last start and end week to DateTime objects
        $start_week_date = new DateTime($last_start_week);
        $end_week_date = new DateTime($last_end_week);

        // Add 7 days to get the next week's Monday (Start Week) and Friday (End Week)
        $start_week_date->modify('+7 days');
        $end_week_date->modify('+7 days');

        // Format the new dates
        $new_start_week = $start_week_date->format('Y-m-d'); // Next Monday
        $new_end_week = $end_week_date->format('Y-m-d');     // Next Friday
    } else {
        // No previous work week found, allow manual input
        $manual_entry = true;
    }
} else {
    echo "No employee ID provided.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $week = $_POST['week'];
    $start_week = $_POST['start_date'];
    $end_week = $_POST['end_date'];
    $status = $_POST['status'];

    $sql_insert = "INSERT INTO granna80_bdlinks.work_weeks (employee_id, work_week, start_week, end_week, status, weekly_value_plteur, weekly_value_pltusd) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iisssss", $employee_id, $week, $start_week, $end_week, $status, $EURUSD, $PLTUSD);

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
    <title>Add New Week for <?php echo htmlspecialchars($employee); ?></title>
</head>
<body>
    <h1>Add New Week for <?php echo htmlspecialchars($employee); ?></h1>
    <form method="POST">
        <label for="week">Week Number:</label>
        <input type="number" id="week" name="week" value="<?php echo $manual_entry ? '' : $next_week; ?>" min="1" max="99999" required><br><br>

        <label for="start_date">Start Week:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo $manual_entry ? '' : $new_start_week; ?>" required><br><br>

        <label for="end_date">End Week:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo $manual_entry ? '' : $new_end_week; ?>" required><br><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Paid">Paid</option>
            <option value="Pending">Pending</option>
            <option value="Processing">Processing</option>
        </select><br><br>

        <label for="PLTUSDT">PLTUSDT: <?php echo $PLTUSD ?></label><br><br>
        <label for="EURUSDT">EURUSDT: <?php echo $EURUSD ?></label><br><br>

        <input type="submit" value="Add Week">
        <a href='edit_week_ireland.php?employee_id=<?php echo $employee_id; ?>'>[back]</a>
    </form>
</body>
</html>

<?php
$stmt_last_week->close();
$conn->close();
?>
