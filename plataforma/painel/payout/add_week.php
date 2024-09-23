<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
include 'conexao.php';
ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/en/mobile/price.php';
ob_end_clean();
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    $sql_employee = "SELECT employee FROM granna80_bdlinks.payout WHERE id = ?";
    $stmt_employee = $conn->prepare($sql_employee);
    $stmt_employee->bind_param("i", $employee_id);
    $stmt_employee->execute();
    $result_employee = $stmt_employee->get_result();

    if ($result_employee->num_rows > 0) {
        $employee = $result_employee->fetch_assoc()['employee'];
    } else {
        echo "Employee not found.";
        exit();
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
    $stmt_insert->bind_param("iisssss", $employee_id, $week, $start_week, $end_week,  $status, $EURUSD, $PLTUSD);

    if ($stmt_insert->execute()) {
        echo "New week added successfully!";
        echo "<script>window.location.href='work_weeks.php?employee_id=" . $employee_id . "';</script>";
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
        <input type="number" id="week" name="week" min="1" max="99999" required><br><br>

        <label for="start_date">Start Week:</label>
        <input type="date" id="start_date" name="start_date" required><br><br>

        <label for="end_date">End Week:</label>
        <input type="date" id="end_date" name="end_date" required><br><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Paid">Paid</option>
            <option value="Pending">Pending</option>
            <option value="Processing">Processing</option>
        </select><br><br>

        <label for="PLTUSDT">PLTUSDT: <?php echo $PLTUSD ?></label><br><br>
        <label for="PLTUSDT">EURUSDT: <?php echo $EURUSD ?></label><br><br>


        <input type="submit" value="Add Week">
        <a href='work_weeks.php?employee_id=<?php echo $employee_id; ?>'>[back]</a>
    </form>
</body>

</html>

<?php
$stmt_employee->close();
$conn->close();
?>