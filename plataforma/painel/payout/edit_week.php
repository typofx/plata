<?php
include 'conexao.php';

if (isset($_GET['week']) && isset($_GET['employee_id'])) {
    $week_id = $_GET['week'];
    $employee_id = $_GET['employee_id'];

    // Fetch current week data
    $sql_week = "SELECT * FROM granna80_bdlinks.work_weeks WHERE work_week = ? AND employee_id = ?";
    $stmt_week = $conn->prepare($sql_week);
    $stmt_week->bind_param("ii", $week_id, $employee_id);
    $stmt_week->execute();
    $result_week = $stmt_week->get_result();

    if ($result_week->num_rows > 0) {
        $week_data = $result_week->fetch_assoc();
    } else {
        echo "Week not found.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $start_week = $_POST['start_week'];
        $end_week = $_POST['end_week'];
        $status = $_POST['status'];

        // Update the week data
        $sql_update = "UPDATE granna80_bdlinks.work_weeks SET start_week = ?, end_week = ?, status = ? WHERE work_week = ? AND employee_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssii", $start_week, $end_week, $status, $week_id, $employee_id);

        if ($stmt_update->execute()) {
            echo "Week updated successfully.";
            echo "<script>window.location.href='work_weeks.php?employee_id=" . $employee_id . "';</script>";
            exit();
        } else {
            echo "Error updating week: " . $conn->error;
        }
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Week</title>
</head>

<body>
    <h1>Edit Week</h1>

    <form method="post">
        <label for="start_week">Start Week:</label>
        <input type="date" id="start_week" name="start_week" value="<?php echo htmlspecialchars($week_data['start_week']); ?>" required>
        <br><br>

        <label for="end_week">End Week:</label>
        <input type="date" id="end_week" name="end_week" value="<?php echo htmlspecialchars($week_data['end_week']); ?>" required>
        <br><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Paid" <?php echo $week_data['status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
            <option value="Pending" <?php echo $week_data['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Processing" <?php echo $week_data['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
        </select>
        <br><br>

        <input type="submit" value="Update Week">
    </form>

    <br>
    <a href="work_weeks.php?employee_id=<?php echo $employee_id; ?>">Back</a>

</body>

</html>

<?php
$stmt_week->close();
$conn->close();
?>
